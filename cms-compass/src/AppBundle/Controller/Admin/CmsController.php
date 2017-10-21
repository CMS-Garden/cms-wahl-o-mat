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

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\CMS;
use AppBundle\Entity\DateProperty;
use AppBundle\Entity\EnumProperty;
use AppBundle\Entity\FeatureProperty;
use AppBundle\Entity\IntegerProperty;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyDefinition;
use AppBundle\Entity\StringProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CmsController
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class CmsController extends Controller
{

    /**
     * @Route("/admin/cms", name="admin_list_cms")
     */
    public function listCms(Request $request)
    {
        $cmsFilter = $request->query->get('filter', '');

        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $cmsList = $cmsRepo->filterCmsByName($cmsFilter);

        return $this->render('admin/cms-list.html.twig',
                             array(
                    'cmsFilter' => $cmsFilter,
                    'cmsList' => $cmsList
        ));
    }

    /**
     * @Route("/admin/cms/new", name="admin_create_new_cms")
     */
    public function createNewCms(Request $request)
    {

        $form = $this->createFormBuilder()
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('homepage', TextType::class, array('label' => 'Homepage'))
                ->add('description_de', TextareaType::class,
                      array(
                    'label' => 'Description (de)'))
                ->add('description_en', TextareaType::class,
                      array(
                    'label' => 'Description (en)'))
                ->add('create-cms', SubmitType::class,
                      array(
                    'label' => 'Create new CMS'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $cms = new CMS();
            $cms->setName($data['name']);
            $cms->setHomepage($data['homepage']);
            $cms->addDescriptionForLanguage('de', $data['description_de']);
            $cms->addDescriptionForLanguage('en', $data['description_en']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cms);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cms');
        }

        return $this->render('admin/cms-form.html.twig',
                             array(
                    'form' => $form->createView(),
                    'newCms' => true
        ));
    }

    /**
     * @Route("/admin/cms/{cmsId}/edit", name="admin_edit_cms")
     */
    public function editCms(Request $request, $cmsId)
    {
        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $form = $this->createFormBuilder()
                ->add('name', TextType::class,
                      array(
                    'label' => 'Name',
                    'data' => $cms->getName()))
                ->add('homepage', TextType::class,
                      array(
                    'label' => 'Homepage',
                    'data' => $cms->getHomepage()))
                ->add('description_de', TextareaType::class,
                      array(
                    'label' => 'Description (de)',
                    'data' => $cms->getDescriptionForLanguage('de')))
                ->add('description_en', TextareaType::class,
                      array(
                    'label' => 'Description (en)',
                    'data' => $cms->getDescriptionForLanguage('en')))
                ->add('update-cms', SubmitType::class, array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $cms->setName($data['name']);
            $cms->setHomepage($data['homepage']);
            $cms->addDescriptionForLanguage('de', $data['description_de']);
            $cms->addDescriptionForLanguage('en', $data['description_en']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($cms);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cms');
        }

        return $this->render('admin/cms-form.html.twig',
                             array(
                    'form' => $form->createView(),
                    'cms' => $cms,
                    'newCms' => false
        ));
    }

    /**
     * @Route("/admin/cms/{cmsId}/delete", name="admin_delete_cms")
     * 
     */
    public function deleteCMS($cmsId)
    {

        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cms);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_cms');
    }

    /**
     * @Route("/admin/cms/{cmsId}", name="admin_show_cms_details")
     */
    public function showCmsDetails($cmsId)
    {
        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
//        $cmsFeaturesRepo = $this->getDoctrine()->getRepository(CmsFeature::class);
        $propertiesRepo = $this->getDoctrine()->getRepository(Property::class);
        $propertiesDefRepo = $this
                ->getDoctrine()
                ->getRepository(PropertyDefinition::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $properties = $propertiesRepo->findPropertiesForCms($cms);
        $propertyDefs = array();
        foreach ($properties as $property) {
            array_push($propertyDefs, $property->getPropertyDefinition());
        }

        $defsForRequiredProperties = $propertiesDefRepo
                ->findDefinitionsForRequiredProperties();

        $allRequiredPropertiesSet = true;
        $defsForMissingProperies = array();
        foreach ($defsForRequiredProperties as $defForRequiredProperty) {

            if (!in_array($defForRequiredProperty, $propertyDefs)) {
                $allRequiredPropertiesSet = $allRequiredPropertiesSet && false;
                array_push($defsForMissingProperies, $defForRequiredProperty);
            }
        }

        $availablePropertyDefs = $propertiesDefRepo->findAll();

        $unusedPropertyDefs = array();
        foreach ($availablePropertyDefs as $propertyDef) {

            if (!in_array($propertyDef, $propertyDefs)) {
                array_push($unusedPropertyDefs, $propertyDef);
            }
        }

        uasort($unusedPropertyDefs,
               function($a, $b) {
            return strcmp($a->getTitle()['en'], $b->getTitle()['en']);
        });

        return $this->render('admin/cms-details.html.twig',
                             array(
                    'cmsId' => $cmsId,
                    'name' => $cms->getName(),
                    'homepage' => $cms->getHomepage(),
                    'description' => $cms->getDescriptionForLanguage('en'),
                    'properties' => $properties,
                    'allRequiredPropertiesSet' => $allRequiredPropertiesSet,
                    'defsForMissingProperties' => $defsForMissingProperies,
                    'unusedPropertyDefs' => $unusedPropertyDefs
        ));
    }

    /**
     * @Route("/admin/cms/{cmsId}/properties/", name="admin_add_new_cms_property")
     * 
     * @param Request $request
     * @param type $cmsId
     */
    public function addProperty(Request $request, $cmsId)
    {
        $propertyToAdd = $request->get('property_to_add');

        echo $propertyToAdd;

        return $this->redirectToRoute('admin_edit_cms_property',
                                      array(
                    'cmsId' => $cmsId,
                    'propertyDefName' => $propertyToAdd
        ));
    }

    /**
     * @Route("/admin/cms/{cmsId}/properties/{propertyDefName}", name="admin_edit_cms_property") 
     * 
     * @param Request $request
     * @param type $cmsId
     */
    public function editProperty(Request $request, $cmsId, $propertyDefName)
    {

        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $propertiesRepo = $this->getDoctrine()->getRepository(Property::class);
        $propertiesDefRepo = $this
                ->getDoctrine()
                ->getRepository(PropertyDefinition::class);

        $cms = $cmsRepo->find($cmsId);
        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $propertyDef = $propertiesDefRepo
                ->findPropertyDefinitionByName($propertyDefName);
        if (!$propertyDef) {
            throw $this->createNotFoundException(
                    'No PropertyDefinition with name ' . $propertyDefName);
        }

        $property = $propertiesRepo->findPropertyByPropertyDefinitionName(
                $cms, $propertyDefName);
        $createProperty = false;
        if (!$property) {

            $createProperty = true;

            switch ($propertyDef->getTypeName()) {
                case 'Date':
                    $property = new DateProperty();
                    break;
                case 'Enum':
                    $property = new EnumProperty();
                    break;
                case 'Feature':
                    $property = new FeatureProperty();
                    break;
                case 'Integer':
                    $property = new IntegerProperty();
                    break;
                case 'String':
                    $property = new StringProperty();
                    break;
                default:
                    throw new Exception('Unknown property type '
                    . $propertyDef->getTypeName());
            }

            $property->setPropertyDefinition($propertyDef);
            $property->setCms($cms);
            $cms->addProperty($property);
        }

        $formBuilder = $this->createFormBuilder();

        switch ($propertyDef->getTypeName()) {
            case 'Date':
                $formBuilder->add('value', DateType::class,
                                  array(
                    'label' => 'Value',
                    'data' => $property->getValue()));
                break;
            case 'Enum':
                $permittedValues = array();
                foreach ($propertyDef->getPermittedValues() as $value) {
                    $permittedValues[$value] = $value;
                }
                $values = array();
                if ($property->getValues() instanceof ArrayCollection) {
                    $values = $property->getValues()->toArray();
                }
                else if (is_array($property->getValues())) {
                    $values = $property->getValues();
                }
                else {
                    array_push($values, $property->getValues());
                }
                if(!$propertyDef->getMultipleValues()
                        && count($values) >= 1) {
                    $values = $values[0];
                }
                $formBuilder->add('values', ChoiceType::class,
                                  array(
                    'label' => 'Values(s)',
                    'choices' => $permittedValues,
                    'multiple' => $propertyDef->getMultipleValues(),
                    'data' => $values));
                break;
            case 'Feature':
                $permittedValues = array();
                foreach (FeatureProperty::PERMITTED_VALUES as $value) {
                    $permittedValues[$value] = $value;
                }
                $formBuilder->add('value', ChoiceType::class,
                                  array(
                    'label' => 'Value',
                    'choices' => $permittedValues,
                    'multiple' => false,
                    'data' => $property->getValue()));
                break;
            case 'Integer':
                $formBuilder->add('value', IntegerType::class,
                                  array(
                    'label' => 'Value',
                    'data' => $property->getValue()));
                break;
            case 'String':
                $formBuilder->add('value', TextareaType::class,
                                  array(
                    'label' => 'Value',
                    'data' => $property->getValue()));
                break;
            default:
                throw new Exception('Unknown property type '
                . $propertyDef->getTypeName());
        }

        if ($createProperty) {
            $formBuilder->add('add_property', SubmitType::class,
                              array(
                'label' => 'Add property'));
        }
        else {
            $formBuilder->add('add_property', SubmitType::class,
                              array(
                'label' => 'Save property'));
        }

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            switch ($propertyDef->getTypeName()) {
                case 'Date':
                    $property->setValue($data['value']);
                    break;
                case 'Enum':
                    $property->setValues($data['values']);
                    break;
                case 'Feature':
                    $property->setValue($data['value']);
                    break;
                case 'Integer':
                    $property->setValue($data['value']);
                    break;
                case 'String':
                    $property->setValue($data['value']);
                    break;
            }

            $entityManager = $this->getDoctrine()->getManager();
            if ($createProperty) {
                $entityManager->persist($property);
                $entityManager->merge($cms);
            }
            else {
                $entityManager->merge($property);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_cms_details',
                                          array(
                        'cmsId' => $cmsId));
        }

        return $this->render('admin/cms_edit_property.html.twig',
                             array(
                    'form' => $form->createView(),
                    'cms' => $cms,
                    'propertyDef' => $propertyDef,
                    'property' => $property,
                    'createProperty' => $createProperty));
    }

//    /**
//     * @Route("/admin/cms/{cmsId}/features/new", name="admin_add_new_cms_feature")
//     */
//    public function addCmsFeature(Request $request, $cmsId)
//    {
//
//
//        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
//        $featuresRepo = $this->getDoctrine()->getRepository(Feature::class);
//        $cms = $cmsRepo->find($cmsId);
//
//        if (!$cms) {
//            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
//        }
//
//        $features = $featuresRepo->findUnusedFeatures($cms);
//
//        $featureChoices = array();
//        foreach ($features as $feature) {
//            $featureChoices[$feature->getName()] = $feature->getFeatureId();
//        }
//
//        $form = $this->createFormBuilder()
//                ->add('feature', ChoiceType::class, array(
//                    'label' => 'Feature',
//                    'expanded' => false,
//                    'multiple' => false,
//                    'choices' => $featureChoices))
//                ->add('value', ChoiceType::class, array(
//                    'label' => 'Value',
//                    'expanded' => true,
//                    'multiple' => false,
//                    'choices' => array(
//                        'yes' => 'Yes',
//                        'no' => 'No',
//                        'plugin' => 'Plugin',
//                        'commerical' => 'Commerical Plugin',
//                        'n/a' => 'N/A')))
//                ->add('save', SubmitType::class, array('label' => 'Save'))
//                ->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $data = $form->getData();
//
//            $feature = $featuresRepo->find($data['feature']);
//            if (!$feature) {
//                throw $this->createNotFoundException('No CmsFeature with ID '
//                        . $data['feature']);
//            }
//
//            $cmsFeature = new CmsFeature();
//            $cmsFeature->setCms($cms);
//            $cmsFeature->setFeature($feature);
//            $cmsFeature->setValue($data['value']);
//
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($cmsFeature);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin_show_cms_details', array('cmsId' => $cms->getCmsId()));
//        }
//
//        return $this->render('admin/cms-add-feature-form.html.twig', array(
//                    'form' => $form->createView(),
//                    'cms' => $cms,
//                    'feature' => $feature
//        ));
//    }
//    /**
//     * 
//     * @Route("/admin/cms/{cmsId}/features/{featureId}", name="admin_edit_cms_feature")
//     * 
//     * @param type $featureId
//     */
//    public function editCmsFeature(Request $request, $cmsId, $featureId)
//    {
//
//        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
//        $cmsFeaturesRepo = $this->getDoctrine()->getRepository(CmsFeature::class);
//        $cms = $cmsRepo->find($cmsId);
//
//        if (!$cms) {
//            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
//        }
//
//        $feature = $cmsFeaturesRepo->find($featureId);
//
//        if (!$feature) {
//            throw $this->createNotFoundException('No CmsFeature with ID '
//                    . $featureId);
//        }
//
//        $form = $this->createFormBuilder()
//                ->add('value', ChoiceType::class, array(
//                    'label' => 'Value',
//                    'expanded' => true,
//                    'multiple' => false,
//                    'data' => $feature->getValue(),
//                    'choices' => array(
//                        'yes' => 'Yes',
//                        'no' => 'No',
//                        'plugin' => 'Plugin',
//                        'commerical' => 'Commerical Plugin',
//                        'n/a' => 'N/A')))
//                ->add('save', SubmitType::class, array('label' => 'Save'))
//                ->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $data = $form->getData();
//
//            $feature->setValue($data['value']);
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->merge($feature);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin_show_cms_details', array('cmsId' => $cms->getCmsId()));
//        }
//
//        return $this->render('admin/cms-feature-form.html.twig', array(
//                    'form' => $form->createView(),
//                    'cms' => $cms,
//                    'feature' => $feature
//        ));
//    }
}
