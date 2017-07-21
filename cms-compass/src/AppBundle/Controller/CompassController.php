<?php

/* 
 * Copyright (C) 2017 CMS Garden e.V.
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

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompassController extends Controller
{

    /**
     * @Route("/compass")
     */
    public function showCompass()
    {
        return $this->render('compass/compass.html.twig', array(
                    'placeholder' => 'CMS Compass placeholder',
        ));
    }

    /**
     * @Route("/compass/cms")
     */
    public function listCms() {
        return $this->render('compass/cms-list.html.twig');
    }


    /**
     *
     * @Route("/compass/cms/{cms}")
     * 
     */
    public function showCmsDetails($cms)
    {
        return $this->render('compass/cms-details.html.twig', array(
                    'cms' => $cms
        ));
    }

    /**
     * @Route("/compass/features")
     */
    public function showFeatures()
    {
        return $this->render('compass/features.html.twig');
    }
    
    /**
     * @Route("/compass/features/{feature}")
     * 
     */
    public function showFeature($feature) {
        return $this->render('compass/feature-details.html.twig', array(
                    'feature' => $feature
        ));
    }

}
