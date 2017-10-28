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

use AppBundle\Entity\PropertyDefinition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompassController extends Controller
{

    /**
     * @Route("/compass/property-definitions", name="public_all_property_definitions_as_json")
     */
    public function getPropertyDefinitions()
    {
        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definitions = $repository->findAll();

        return $this->json($definitions, 200, array(),
                           array('groups' => array('definition')));
    }

    /**
     * @Route("/compass/property-definitions/{propertyDefName}", name="public_property_definition_as_json")
     */
    public function getPropertyDefinition($propertyDefName)
    {
        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definition = $repository->findPropertyDefinitionByName($propertyDefName);

        if (!$definition) {
            throw $this->createNotFoundException('No property definition with name ' . $propertyDefName);
        }

        return $this->json($definition, 200, array(),
                           array('groups' => array('definition')));
    }

    /**
     * @Route("/compass")
     */
    public function showCompass()
    {
        return $this->render('compass/compass.html.twig',
                             array(
                    'placeholder' => 'CMS Compass placeholder',
        ));
    }

    /**
     * @Route("/compass/cms")
     */
    public function listCms()
    {
        return $this->render('compass/cms-list.html.twig');
    }

    /**
     *
     * @Route("/compass/cms/{cms}")
     * 
     */
    public function showCmsDetails($cms)
    {
        return $this->render('compass/cms-details.html.twig',
                             array(
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
    public function showFeature($feature)
    {
        return $this->render('compass/feature-details.html.twig',
                             array(
                    'feature' => $feature
        ));
    }

}
