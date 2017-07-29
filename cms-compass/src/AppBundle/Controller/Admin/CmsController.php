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
use AppBundle\Entity\CmsFeature;
use AppBundle\Entity\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

        return $this->render('admin/cms-list.html.twig', array(
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
                ->add('description', TextareaType::class, array(
                    'label' => 'Description'))
                ->add('create-cms', SubmitType::class, array(
                    'label' => 'Create new CMS'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $cms = new CMS();
            $cms->setName($data['name']);
            $cms->setHomepage($data['homepage']);
            $cms->addDescriptionForLanguage('en', $data['description']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cms);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cms');
        }

        return $this->render('admin/cms-form.html.twig', array(
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
                ->add('name', TextType::class, array(
                    'label' => 'Name',
                    'data' => $cms->getName()))
                ->add('homepage', TextType::class, array(
                    'label' => 'Homepage',
                    'data' => $cms->getHomepage()))
                ->add('description', TextareaType::class, array(
                    'label' => 'Description',
                    'data' => $cms->getDescriptionForLanguage('en')))
                ->add('update-cms', SubmitType::class, array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $cms->setName($data['name']);
            $cms->setHomepage($data['homepage']);
            $cms->addDescriptionForLanguage('en', $data['description']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($cms);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cms');
        }

        return $this->render('admin/cms-form.html.twig', array(
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
        $cmsFeaturesRepo = $this->getDoctrine()->getRepository(CmsFeature::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $features = $cmsFeaturesRepo->findFeaturesForCms($cms);

        return $this->render('admin/cms-details.html.twig', array(
                    'cmsId' => $cmsId,
                    'name' => $cms->getName(),
                    'homepage' => $cms->getHomepage(),
                    'description' => $cms->getDescriptionForLanguage('en'),
                    'features' => $features
        ));
    }

    /**
     * @Route("/admin/cms/{cmsId}/features/new", name="admin_add_new_cms_feature")
     */
    public function addCmsFeature(Request $request, $cmsId)
    {


        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $featuresRepo = $this->getDoctrine()->getRepository(Feature::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $features = $featuresRepo->findUnusedFeatures($cms);

        $featureChoices = array();
        foreach ($features as $feature) {
            $featureChoices[$feature->getName()] = $feature->getFeatureId();
        }

        $form = $this->createFormBuilder()
                ->add('feature', ChoiceType::class, array(
                    'label' => 'Feature',
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => $featureChoices))
                ->add('value', ChoiceType::class, array(
                    'label' => 'Value',
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => array(
                        'yes' => 'Yes',
                        'no' => 'No',
                        'plugin' => 'Plugin',
                        'commerical' => 'Commerical Plugin',
                        'n/a' => 'N/A')))
                ->add('save', SubmitType::class, array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $feature = $featuresRepo->find($data['feature']);
            if (!$feature) {
                throw $this->createNotFoundException('No CmsFeature with ID '
                        . $data['feature']);
            }

            $cmsFeature = new CmsFeature();
            $cmsFeature->setCms($cms);
            $cmsFeature->setFeature($feature);
            $cmsFeature->setValue($data['value']);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsFeature);
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_cms_details', array('cmsId' => $cms->getCmsId()));
        }

        return $this->render('admin/cms-add-feature-form.html.twig', array(
                    'form' => $form->createView(),
                    'cms' => $cms,
                    'feature' => $feature
        ));
    }

    /**
     * 
     * @Route("/admin/cms/{cmsId}/features/{featureId}", name="admin_edit_cms_feature")
     * 
     * @param type $featureId
     */
    public function editCmsFeature(Request $request, $cmsId, $featureId)
    {

        $cmsRepo = $this->getDoctrine()->getRepository(CMS::class);
        $cmsFeaturesRepo = $this->getDoctrine()->getRepository(CmsFeature::class);
        $cms = $cmsRepo->find($cmsId);

        if (!$cms) {
            throw $this->createNotFoundException('No CMS with ID ' . $cmsId);
        }

        $feature = $cmsFeaturesRepo->find($featureId);

        if (!$feature) {
            throw $this->createNotFoundException('No CmsFeature with ID '
                    . $featureId);
        }

        $form = $this->createFormBuilder()
                ->add('value', ChoiceType::class, array(
                    'label' => 'Value',
                    'expanded' => true,
                    'multiple' => false,
                    'data' => $feature->getValue(),
                    'choices' => array(
                        'yes' => 'Yes',
                        'no' => 'No',
                        'plugin' => 'Plugin',
                        'commerical' => 'Commerical Plugin',
                        'n/a' => 'N/A')))
                ->add('save', SubmitType::class, array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $feature->setValue($data['value']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($feature);
            $entityManager->flush();

            return $this->redirectToRoute('admin_show_cms_details', array('cmsId' => $cms->getCmsId()));
        }

        return $this->render('admin/cms-feature-form.html.twig', array(
                    'form' => $form->createView(),
                    'cms' => $cms,
                    'feature' => $feature
        ));
    }

}
