<?php

/*
 * Copyright (C) 2017 <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\EnumPropertyDefinition;
use AppBundle\Entity\FeaturePropertyDefinition;
use AppBundle\Entity\PropertyDefinition;
use Doctrine\ORM\EntityRepository;

class CmsRepository extends EntityRepository
{

    public function filterCmsByName($filter)
    {

        $queryBuilder = $this->createQueryBuilder("c");

        return $queryBuilder
                        ->where($queryBuilder->expr()->like('c.name', ':name'))
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('name', '%' . $filter . '%')
                        ->getQuery()
                        ->getResult();
    }

    public function filterCmsByProperties($values)
    {

        $this->getEntityManager()->getRepository(PropertyDefinition::class);

        $filters = $this->buildPropertyFilters($values);

        $queryBuilder = $this->createQueryBuilder('c');

        foreach ($filters as $filter) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('c.cmsId', $filter));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    private function buildPropertyFilters($values)
    {
        $propertyDefinitionsRepo = $this->getEntityManager()->getRepository(PropertyDefinition::class);
        $propertyDefinitions = $propertyDefinitionsRepo->findAll();

        $filters = array();
        foreach ($propertyDefinitions as $definition) {

            if ($definition instanceof FeaturePropertyDefinition &&
                    array_key_exists($definition->getName(), $values)) {

                $filter = $this->buildFeaturePropertyFilter($definition,
                                                            $values[$definition->getName()]);
                array_push($filters, $filter);
            }

            if ($definition instanceof EnumPropertyDefinition &&
                    array_key_exists($definition->getName(), $values) &&
                    count($values[$definition->getName()]) > 0) {

                $filter = $this->buildEnumPropertyFilter($definition,
                                                         $values[$definition->getName()]);
                array_push($filters, $filter);
            }

        }

        return $filters;
    }

    private function buildFeaturePropertyFilter(PropertyDefinition $definition,
                                                $value)
    {
        $queryBuilder = $this->createQueryBuilder('cms');
        $queryBuilder->select('cms.cmsId')
                ->join('cms.properties', 'p')
                ->leftJoin('AppBundle:FeatureProperty', 'fp', 'WITH',
                           'p.propertyId = fp.propertyId')
                ->join('p.propertyDefinition', 'd')
                ->where($queryBuilder->expr()->eq('d.name', ':name'));

        if ($value === 'yes') {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('fp.value',
                                                              '\'yes\''));
        }
        else if ($value === 'free_plugin') {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('fp.value',
                                                              '\'plugin\''));
        }
        else if ($value === 'available') {
            $whereYes = $queryBuilder->expr()->eq('fp.value', '\'yes\'');
            $wherePlugin = $queryBuilder->expr()->eq('fp.value', '\'plugin\'');
            $queryBuilder->andWhere($queryBuilder->expr()->orX($whereYes,
                                                               $wherePlugin));
        }
        else if ($value === 'commercial') {
            $whereYes = $queryBuilder->expr()->eq('fp.value', '\'yes\'');
            $wherePlugin = $queryBuilder->expr()->eq('fp.value', '\'plugin\'');
            $whereCommerical = $queryBuilder->expr()->eq('fp.value',
                                                         '\'commercial\'');
            $queryBuilder->andWhere($queryBuilder->expr()->orX($whereYes,
                                                               $wherePlugin,
                                                               $whereCommerical));
        }

        $queryBuilder->setParameter('name', $definition->getName());

        return $this->queryResultsToString($queryBuilder->getQuery()->getResult());
    }

    private function buildEnumPropertyFilter(PropertyDefinition $definition,
                                             $values)
    {
        $queryBuilder = $this->createQueryBuilder('cms');
        $queryBuilder->select('cms.cmsId')
                ->join('cms.properties', 'p')
                ->leftJoin('AppBundle:EnumProperty', 'ep', 'WITH',
                           'p.propertyId = ep.propertyId')
                ->join('p.propertyDefinition', 'd')
                ->where($queryBuilder->expr()->eq('d.name', ':name'));

        $whereValues = $queryBuilder->expr()->orX();
        foreach ($values as $value) {
            $whereValue = $queryBuilder->expr()->like('ep.values',
                                                 $queryBuilder->expr()->literal('%' . $value . '%'));
            $whereValues->add($whereValue);
        }
        $queryBuilder->andWhere($whereValues);

        $queryBuilder->setParameter('name', $definition->getName());

        return $this->queryResultsToString($queryBuilder->getQuery()->getResult());
    }

    private function queryResultsToString($queryResults)
    {
        $result = '';
        foreach ($queryResults as $queryResult) {
            if (strlen($result) > 0) {
                $result .= ",";
            }
            $result .= $queryResult['cmsId'];
        }

        return $result;
    }
}
