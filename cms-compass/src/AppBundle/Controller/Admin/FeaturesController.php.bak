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

use AppBundle\Entity\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of FeaturesController
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class FeaturesController extends Controller
{
    
        /**
     * @Route("/admin/features", name="admin_list_features")
     */
    public function listFeatures(Request $request)
    {
        $featureFilter = $request->query->get('filter', '');

        $featureRepo = $this->getDoctrine()->getRepository(Feature::class);
        $features = $featureRepo->filterFeaturesByTitle($featureFilter);

        return $this->render('admin/features.html.twig', array(
                    'featureFilter' => $featureFilter,
                    'features' => $features
        ));
    }

    /**
     * 
     * @Route("/admin/features/new", name="admin_create_new_feature")
     */
    public function newFeatureAction(Request $request)
    {

        $form = $this->createFormBuilder()
                ->add('featurename', TextType::class, array('label' => 'Name'))
                ->add('title', TextType::class, array('label' => 'Title'))
                ->add('description', TextareaType::class, array('label' => 'Description'))
                ->add('create-feature', SubmitType::class, array('label' => 'Create new feature'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $feature = new Feature();
            $feature->setName($data['featurename']);
            $feature->addTitleForLanguage('en', $data['title']);
            $feature->addDescriptionForLanguage('en', $data['description']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feature);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_features');
        }

        return $this->render('admin/feature-form.html.twig', array(
                    'form' => $form->createView(),
                    'newFeature' => true
        ));
    }

    /**
     * 
     * @Route("/admin/features/{featurename}", name="admin_edit_feature")
     */
    public function editFeature(Request $request, $featurename)
    {
        $featureRepo = $this->getDoctrine()->getRepository(Feature::class);
        $feature = $featureRepo->findFeatureByName($featurename);

        if (!$feature) {
            throw $this->createNotFoundException('No feature with name ' . $featurename);
        }
        $form = $this->createFormBuilder()
                ->add('featurename', TextType::class, array(
                    'label' => 'Name',
                    'data' => $feature->getName()))
                ->add('title', TextType::class, array(
                    'label' => 'Title',
                    'data' => $feature->getTitleForLanguage('en')))
                ->add('description', TextareaType::class, array(
                    'label' => 'Description',
                    'data' => $feature->getDescriptionForLanguage('en')))
                ->add('update-feature', SubmitType::class, array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $feature->setName($data['featurename']);
            $feature->addTitleForLanguage('en', $data['title']);
            $feature->addDescriptionForLanguage('en', $data['description']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($feature);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_features');
        }

        return $this->render('admin/feature-form.html.twig', array(
                    'form' => $form->createView(),
                    'feature' => $feature,
                    'newFeature' => false
        ));
    }

    /**
     * 
     * @Route("/admin/features/{featurename}/delete", name="admin_delete_feature")
     */
    public function deleteFeature($featurename)
    {
        $featureRepo = $this->getDoctrine()->getRepository(Feature::class);
        $feature = $featureRepo->findFeatureByName($featurename);

        if (!$feature) {
            throw $this->createNotFoundException('No feature with name ' . $featurename);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($feature);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_features');
    }

    
}
