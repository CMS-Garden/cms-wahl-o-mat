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

use AppBundle\Entity\DatePropertyDefinition;
use AppBundle\Entity\EnumPropertyDefinition;
use AppBundle\Entity\FeaturePropertyDefinition;
use AppBundle\Entity\IntegerPropertyDefinition;
use AppBundle\Entity\PropertyDefinition;
use AppBundle\Entity\StringPropertyDefinition;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the Admin UI for managing property definitions.
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class PropertyDefinitionController extends Controller
{

    /**
     * @Route("/admin/property-definitions", name = "admin_list_property_definitions")
     * 
     * @param Request $request
     */
    public function listPropertyDefinitions(Request $request)
    {

        $filter = $request->query->get('filter', '');

        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definitions = $repository->filterPropertyDefinitionsByName($filter);

        return $this->render('admin/property-definitions.html.twig', array(
                    'filter' => $filter,
                    'definitions' => $definitions
        ));
    }

    /**
     * @Route("/admin/property-definitions/new", name="admin_create_new_property_definition")
     * 
     * @param Request $request
     */
    public function createNewPropertyDefinition(Request $request)
    {

        $form = $this->createFormBuilder()
                ->add('type', ChoiceType::class, array('choices' => array(
                        'Date' => 'date',
                        'Enum' => 'enum',
                        'Feature' => 'feature',
                        'Integer' => 'integer',
                        'String' => 'string')))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('title_de', TextType::class, array('label' => 'Title (de)'))
                ->add('title_en', TextType::class, array('label' => 'Title (en)'))
                ->add('description_de', TextareaType::class, array('label' => 'Description (de)'))
                ->add('description_en', TextareaType::class, array('label' => 'Description (en)'))
                ->add('required', CheckboxType::class, array('label' => 'Required?', 'required' => false))
                ->add('createPropertyDef', SubmitType::class, array('label' => 'Create'))
                ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $propertyDef = null;
            switch ($data['type']) {
                case 'date':
                    $propertyDef = new DatePropertyDefinition();
                    break;
                case 'enum':
                    $propertyDef = new EnumPropertyDefinition();
                    $propertyDef->setMultipleValues(false);
                    break;
                case 'feature':
                    $propertyDef = new FeaturePropertyDefinition();
                    break;
                case 'integer':
                    $propertyDef = new IntegerPropertyDefinition();
                    break;
                case 'string':
                    $propertyDef = new StringPropertyDefinition();
                    $propertyDef->setHtmlPermitted(false);
                    break;
                default:
                    throw new Exception('Unknown property type ' . $data['type']);
            }

            $propertyDef->setName($data['name']);
            $propertyDef->addTitleForLanguage('de', $data['title_de']);
            $propertyDef->addTitleForLanguage('en', $data['title_en']);
            $propertyDef->addDescriptionForLanguage('de', $data['description_de']);
            $propertyDef->addDescriptionForLanguage('en', $data['description_en']);
            $propertyDef->setRequired($data['required']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($propertyDef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_property_definition', array('propertyDefName' => $propertyDef->getName()));
        }

        return $this->render('admin/property-definition-createform.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/admin/property-definitions/{propertyDefName}/delete", name="admin_delete_property_definition")
     * 
     * @param Request $request
     * @param type $propertyDefName
     */
    public function deletePropertyDefinition($propertyDefName)
    {
        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definition = $repository->findPropertyDefinitionByName($propertyDefName);

        if (!$definition) {
            throw $this->createNotFoundException('No property definition with name ' . $propertyDefName);
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($definition);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_property_definitions');
    }

    /**
     * @Route("/admin/property-definitions/{propertyDefName}", name="admin_show_property_definition")
     * 
     * @param Request $request
     * @param type $propertyDefName
     * 
     */
    public function showPropertyDefinition(Request $request, $propertyDefName)
    {
        $repository = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $definition = $repository->findPropertyDefinitionByName($propertyDefName);

        if (!$definition) {
            throw $this->createNotFoundException('No property definition with name ' . $propertyDefName);
        }

        return $this->render('admin/property-definition-details.html.twig', array('definition' => $definition));
    }

    /**
     * @Route("/admin/property-definitions/{propertyDefName}/edit", name="admin_edit_property_definition")
     * 
     * @param Request $request
     * @param type $propertyDefName
     */
    public function editPropertyDefinition(Request $request, $propertyDefName)
    {
        $propertyDefRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $propertyDef = $propertyDefRepo->findPropertyDefinitionByName($propertyDefName);

        if (!$propertyDef) {
            throw $this->createNotFoundException(
                    'No property definition with name ' . $propertyDefName);
        }

        $formBuilder = $this->createFormBuilder()
                ->add('name', TextType::class, array(
                    'label' => 'Name',
                    'data' => $propertyDef->getName()))
                ->add('title_de', TextType::class, array(
                    'label' => 'Title (de)',
                    'data' => $propertyDef->getTitleForLanguage('de')))
                ->add('title_en', TextType::class, array(
                    'label' => 'Title (en)',
                    'data' => $propertyDef->getTitleForLanguage('en')))
                ->add('description_de', TextareaType::class, array(
                    'label' => 'Description (de)',
                    'data' => $propertyDef->getDescriptionForLanguage('de')))
                ->add('description_en', TextareaType::class, array(
                    'label' => 'Description (en)',
                    'data' => $propertyDef->getDescriptionForLanguage('en')))
                ->add('required', CheckboxType::class, array(
            'label' => 'Required?',
            'data' => $propertyDef->getRequired()));

        if ($propertyDef->getTypeName() === 'Integer') {
            $formBuilder->add('unit', TextType::class, array(
                        'label' => 'Unit',
                        'data' => $propertyDef->getUnit()))
                    ->add('minimum', IntegerType::class, array(
                        'label' => 'Minimum',
                        'data' => $propertyDef->getMinimum()))
                    ->add('maximum', IntegerType::class, array(
                        'label' => 'Maximum',
                        'data' => $propertyDef->getMaximum()));
        }

        if ($propertyDef->getTypeName() === 'String') {
            $formBuilder->add('maxLength', IntegerType::class, array(
                'label' => 'Maximum length',
                'data' => $propertyDef->getMaxLength()));
            $formBuilder->add('htmlPermitted', CheckboxType::class, array(
                'label' => 'HTML permitted',
                'data' => $propertyDef->getHtmlPermitted()));
        }

        $formBuilder->add('savePropertyDef', SubmitType::class, array(
            'label' => 'Save'));
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $propertyDef->setName($data['name']);
            $propertyDef->addTitleForLanguage('de', $data['title_de']);
            $propertyDef->addTitleForLanguage('en', $data['title_en']);
            $propertyDef->addDescriptionForLanguage('de', $data['description_de']);
            $propertyDef->addDescriptionForLanguage('en', $data['description_en']);
            $propertyDef->setRequired($data['required']);

            if ($propertyDef->getTypeName() === 'Integer') {
                $propertyDef->setUnit($data['unit']);
                $propertyDef->setMinimum($data['minimum']);
                $propertyDef->setMaximum($data['maximum']);
            }

            if ($propertyDef->getTypeName() === 'String') {
                $propertyDef->setMaxLength($data['maxLength']);
                $propertyDef->setHtmlPermitted($data['htmlPermitted']);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($propertyDef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_property_definition', array('propertyDefName' => $propertyDef->getName()));
        }

        return $this->render('admin/property-definition-editform.html.twig', array(
                    'form' => $form->createView(),
                    'propertyDef' => $propertyDef
        ));
    }

    /**
     * 
     * @Route("/admin/property-definitions/{propertyDefName}/enumvalues/add", name="admin_edit_property_definition_add_enumvalue")
     * 
     * @param Request $request
     * @param type $propertyDefName
     * 
     */
    public function addEnumPropertyValue(Request $request, $propertyDefName)
    {
        $form = $this->createFormBuilder()
                ->add('value', TextType::class, array('label' => 'New value'))
                ->add('addValue', SubmitType::class, array(
                    'label' => 'Add'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyDefRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
            $propertyDef = $propertyDefRepo->findPropertyDefinitionByName($propertyDefName);

            if (!$propertyDef) {
                throw $this->createNotFoundException(
                        'No property definition with name ' . $propertyDefName);
            }

            $data = $form->getData();

            $propertyDef->addPermittedValue($data['value']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($propertyDef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_property_definition', array('propertyDefName' => $propertyDef->getName()));
        }

        return $this->render('admin/enumproperty-definition-add-value-form.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/admin/property-definitions/{propertyDefName}/enumvalues/remove/{value}", name="admin_edit_property_definition_remove_enumvalue")
     * 
     * @param Request $request
     * @param type $propertyDefName
     * 
     */
    public function removeEnumPropertyValue(Request $request, $propertyDefName, $value)
    {
        $propertyDefRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $propertyDef = $propertyDefRepo->findPropertyDefinitionByName($propertyDefName);

        if (!$propertyDef) {
            throw $this->createNotFoundException(
                    'No property definition with name ' . $propertyDefName);
        }

        $propertyDef->removePermittedValue($value);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($propertyDef);
        $entityManager->flush();

        return $this->redirectToRoute('admin_show_property_definition', array('propertyDefName' => $propertyDef->getName()));
    }

}
