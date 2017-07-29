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

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\CMS;

/**
 * Description of FeatureRepository
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class FeatureRepository extends EntityRepository
{

    public function findUnusedFeatures(CMS $cms)
    {
        $usedFeaturesQueryBuilder = $this->createQueryBuilder('f');

        $usedFeaturesQuery = $usedFeaturesQueryBuilder
                ->join('f.cmsWithFeature', 'c')
                ->where($usedFeaturesQueryBuilder->expr()->eq('c.cms', ':cms'))
                ->setParameter('cms', $cms)
                ->getQuery();

        $queryBuilder = $this->createQueryBuilder('f');

        return $queryBuilder
                        ->where(('f.cmsWithFeature IS EMPTY'))
                        ->orderBy('f.title', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    public function filterFeaturesByTitle($filter)
    {
        $queryBuilder = $this->createQueryBuilder('f');

        return $queryBuilder
                        ->where($queryBuilder->expr()->orX(
                                        $queryBuilder->expr()->like('f.name', ':name'), $queryBuilder->expr()->like('f.title', ':title')
                        ))
                        ->orderBy('f.title', 'ASC')
                        ->setParameter('name', '%' . $filter . '%')
                        ->setParameter('title', '%' . $filter . '%')
                        ->getQuery()
                        ->getResult();
    }

    public function findFeatureByName($name)
    {

        $queryBuilder = $this->createQueryBuilder('f');

        return $queryBuilder
                        ->where($queryBuilder->expr()->eq('f.name', ':name'))
                        ->setParameter('name', $name)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

}
