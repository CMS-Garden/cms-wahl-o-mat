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
 * Description of CmsFeatureRepository
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class CmsFeatureRepository extends EntityRepository
{

    public function findFeaturesForCms(CMS $cms)
    {
        $queryBuilder = $this->createQueryBuilder('f');
        
        return $queryBuilder
//                ->select('f')
//                ->from('CmsFeature f')
                ->join('f.feature', 'c')
                ->where($queryBuilder->expr()->eq('f.cms', ':cms'))
                ->orderBy('c.name')
                ->setParameter('cms', $cms)
                ->getQuery()
                ->getResult();
    }

}
