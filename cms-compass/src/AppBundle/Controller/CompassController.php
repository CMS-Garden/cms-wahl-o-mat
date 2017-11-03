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

use AppBundle\Entity\CMS;
use AppBundle\Entity\EnumPropertyDefinition;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyDefinition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CompassController extends Controller
{

    /**
     * @Route("/compass/property-definitions.json", name="public_all_property_definitions_as_json")
     */
    public function getPropertyDefinitions()
    {
        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definitions = $repository->findAll();

        return $this->json($definitions, 200, array(),
                           array('groups' => array('definition')));
    }

    /**
     * @Route("/compass/property-definitions/{propertyDefName}.json", name="public_property_definition_as_json")
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
     * @Route("/compass/cms.json", name="public_all_cms_as_json")
     */
    public function getAllCms()
    {

        $repository = $this->getDoctrine()->getRepository(CMS::class);
        $allCms = $repository->findAll();

        return $this->json($allCms, 200, array(),
                           array('groups' => array('cms')));
    }

    /**
     * @Route("/compass/cms/{cms}.json", name="public_get_cms_as_json")
     */
    public function getCms($cms)
    {

        $repository = $this->getDoctrine()->getRepository(CMS::class);
        $cmsData = $repository->find($cms);

        return $this->json($cmsData, 200, array(),
                           array('groups' => array('cms')));
    }

    /**
     * @Route("/compass/cms/{cms}/details.json", name="public_get_cms_details_as_json")
     */
    public function getCmsDetails($cms)
    {

        $repository = $this->getDoctrine()->getRepository(CMS::class);
        $cmsData = $repository->find($cms);

        return $this->json($cmsData, 200, array(),
                           array('groups' => array('cms', 'details')));
    }

    /**
     * @Route("/", name="compass")
     */
    public function showCompass(Request $request)
    {
        $propertyDefinitionsRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $propertyDefinitions = $propertyDefinitionsRepo->findAll();

        $filterValues = array();
        foreach ($propertyDefinitions as $propertyDefinition) {

            if ($request->get($propertyDefinition->getName()) !== null) {

                $filterValues[$propertyDefinition->getName()] = $request->get($propertyDefinition->getName());
            }
            else {
                $filterValues[$propertyDefinition->getName()] = "";
            }
        }

        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $allCms = $cmsRepo->findBy(array(), array('name' => 'asc'));

        $propertyRepo = $this->getDoctrine()->getRepository(Property::class);
        $properties = $propertyRepo->findAll();

        //Prepare and fill propertyValues array. 
        //Structure: properties[$cms.name[$propertyDefinition.name][$value]
        $propertyValues = array();
        foreach ($properties as $property) {

            $propertyValues[$property->getCms()->getName()][$property->getPropertyDefinition()->getName()]
                    = $property->getValue();
        }

        // Ensure that there is at least an empty entry in the propertyValues 
        // array for each propertyDefinition because Twig does not like not
        // existing array keys...
        foreach ($allCms as $cms) {

            if (!array_key_exists($cms->getName(), $propertyValues)) {
                $propertyValues[$cms->getName()] = array();
            }

            foreach ($propertyDefinitions as $propertyDefinition) {

                if (!array_key_exists($propertyDefinition->getName(),
                                      $propertyValues[$cms->getName()])) {
                    $propertyValues[$cms->getName()][$propertyDefinition->getName()] = "";
                }
            }
        }

        return $this->render('compass/compass.html.twig',
                             array(
                    'propertyDefinitions' => $propertyDefinitions,
                    'filterValues' => $filterValues,
                    'allCms' => $allCms,
                    'propertyValues' => $propertyValues
        ));
    }

    private function createEnumPropertyChoices(
    EnumPropertyDefinition $propertyDefinition)
    {
        $choices = array();
        foreach ($propertyDefinition->getPermittedValues() as $value) {
            //array_push($choices, $value);
            $choices[$value] = $value;
        }

        return $choices;
    }

    /**
     * @Route("/compass/cms.html")
     */
    public function listCms()
    {
        return $this->render('compass/cms-list.html.twig');
    }

    /**
     *
     * @Route("/compass/cms/{cms}.html")
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
     * @Route("/compass/properties.html")
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
