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

use AppBundle\Entity\CMS;
use Doctrine\ORM\EntityRepository;

/**
 * Description of PropertyRepository
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class PropertyRepository extends EntityRepository
{

    public function findPropertiesForCms(CMS $cms)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->join('p.propertyDefinition', 'd')
                        ->where($queryBuilder->expr()->eq('p.cms', ':cms'))
                        ->setParameter('cms', $cms)
                        ->orderBy('d.name')
                        ->getQuery()
                        ->getResult();
    }

    public function findPropertyByPropertyDefinitionName(CMS $cms, $name)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->join('p.propertyDefinition', 'd')
                        ->where($queryBuilder
                                ->expr()
                                ->andX($queryBuilder->expr()->eq('p.cms', ':cms')),
                                                                 $queryBuilder->expr()->eq('d.name',
                                                                                           ':name'))
                        ->setParameter('cms', $cms)
                        ->setParameter('name', $name)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

}
