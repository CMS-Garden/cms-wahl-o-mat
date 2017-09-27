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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

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
                        'date' => 'Date',
                        'enum' => 'Enum',
                        'feature' => 'Feature',
                        'integer' => 'Integer',
                        'string' => 'Text')))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('title_de', TextType::class, array('label' => 'Title (de)'))
                ->add('title_en', TextType::class, array('label' => 'Title (en)'))
                ->add('description_de', TextareaType::class, array('label' => 'Description (de)'))
                ->add('description_en', TextareaType::class, array('label' => 'Description (en)'))
                ->add('required', CheckboxType::class, array('label' => 'Required?'))
                ->add('createPropertyDef', SubmitType::class, array('label' => 'Create'))
                ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->valid()) {

            $data = $form->getData();

            $propertyDef = null;
            switch ($data['type']) {
                case 'date':
                    $propertyDef = new DatePropertyDefinition();
                    break;
                case 'enum':
                    $propertyDef = new EnumPropertyDefinition();
                    break;
                case 'feature':
                    $propertyDef = new FeaturePropertyDefinition();
                    break;
                case 'integer':
                    $propertyDef = new IntegerPropertyDefinition();
                    break;
                case 'string':
                    $propertyDef = new StringPropertyDefinition();
                    break;
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

            return $this->redirectToRoute('admin_property_definition_details', array('definitionName' => $propertyDef->getName()));
        }

        return $this->render('admin/property-definition-createform.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/admin/property-definitions/{propertyDefName}/delete
     * 
     * @param Request $request
     * @param type $propertyDefName
     */
    public function deletePropertyDefinition(Request $request, $propertyDefName)
    {
        //ToDo
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
     * @Route("/admin/property-definitions/{propertyDefName}", name="admin_edit_property_definition")
     * 
     * @param Request $request
     * @param type $propertyDefName
     */
    public function editPropertyDefinition(Request $request, $propertyDefName)
    {
        $propertyDefRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
        $propertyDef = $propertyDefRepo->find($propertyDefName);

        if (!$propertyDef) {
            throw $this->createNotFoundException(
                    'No property definition with name ' . $propertyDefName);
        }

        $form = $this->createFormBuilder()
                ->add('name', TextType::class, array(
                    'label' => 'Name',
                    'data' => $propertyDef->getName()))
                ->add('title_de', TextType::class, array(
                    'label' => 'Title (de)',
                    'data' => $propertyDef->getLabelForLanguage('de')))
                ->add('title_en', TextType::class, array(
                    'label' => 'Title (en)',
                    'data' => $propertyDef->getLabelForLanguage('en')))
                ->add('description_de', TextareaType::class, array(
                    'label' => 'Description (de)',
                    'data' => $propertyDef->getDescriptionForLanguage('de')))
                ->add('description_en', TextareaType::class, array(
                    'label' => 'Description (en)',
                    'data' => $propertyDef->getDescriptionForLanguage('en')))
                ->add('required', CheckboxType::class, array(
                    'label' => 'Required?',
                    'data' => $propertyDef->isRequired()))
                ->add('createPropertyDef', SubmitType::class, array(
                    'label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->valid()) {

            $data = $form->getData();

            $propertyDef->setName($data['name']);
            $propertyDef->addTitleForLanguage('de', $data['title_de']);
            $propertyDef->addTitleForLanguage('en', $data['title_en']);
            $propertyDef->addDescriptionForLanguage('de', $data['description_de']);
            $propertyDef->addDescriptionForLanguage('en', $data['description_en']);
            $propertyDef->setRequired($data['required']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($propertyDef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_property_definition_details', array('definitionName' => $propertyDef->getName()));
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
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->valid()) {
            $propertyDefRepo = $this->getDoctrine()->getRepository(PropertyDefinition::class);
            $propertyDef = $propertyDefRepo->find($propertyDefName);

            if (!$propertyDef) {
                throw $this->createNotFoundException(
                        'No property definition with name ' . $propertyDefName);
            }

            $data = $form->getData();

            $propertyDef->addPermittedValue($data['value']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($propertyDef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_property_definition_details', array('definitionName' => $propertyDef->getName()));
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
        $propertyDef = $propertyDefRepo->find($propertyDefName);

        if (!$propertyDef) {
            throw $this->createNotFoundException(
                    'No property definition with name ' . $propertyDefName);
        }

        $propertyDef->removePermittedValue($value);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($propertyDef);
        $entityManager->flush();

        return $this->redirectToRoute('admin_property_definition_details', array('definitionName' => $propertyDef->getName()));
    }

}
