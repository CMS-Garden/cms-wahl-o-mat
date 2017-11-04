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

use AppBundle\Entity\PropertyDefinition;

class FeaturePropertyRepository extends PropertyRepository
{

    public function findCmsWithPropertyValue(PropertyDefinition $definition,
                                               $value)
    {

        $propQueryBuilder = $this->createQueryBuilder('p');
        $propQueryBuilder
                ->select('p, c')
                ->join('p.cms', 'c')
                ->join('p.propertyDefinition', 'd');
        $whereDefName = $propQueryBuilder->expr()->eq('d.name', ':name');
        $whereValue = $propQueryBuilder->expr()->eq('p.value', ':value');
        $propQueryBuilder->where($propQueryBuilder->expr()->andX($whereDefName,
                                                                 $whereValue))
                ->setParameter('name', $definition->getName())
                ->setParameter('value', $value);

//        return $propQueryBuilder->getQuery()->getResult();
        $result = array();
        foreach($propQueryBuilder->getQuery()->getResult() as $row) {
            $cms = $row['c'];
            array_push($result, $cms);
        }
        
    }

}
