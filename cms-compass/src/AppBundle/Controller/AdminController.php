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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Feature;
use AppBundle\Entity\CMS;

/**
 * Controller for the Admin UI.
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class AdminController extends Controller
{

    /**
     * @Route("/admin")
     */
    public function showAdmin()
    {
        return $this->render('admin/admin.html.twig');
    }

    /**
     *
     * @Route("/admin/users", name="admin_list_users")
     * 
     */
    public function listUsers(Request $request)
    {
        $userFilter = $request->query->get('filter', "");

        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepo->filterUsersByUsernameOrEmail($userFilter);

        return $this->render('admin/users.html.twig', array(
                    'userFilter' => $userFilter,
                    'users' => $users
        ));
    }

    /**
     * @Route("/admin/users/new", name="admin_create_new_user")
     */
    public function newUserAction(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {

        $form = $this->createFormBuilder()
                ->add('username', TextType::class, array('label' => 'User name'))
                ->add('email', EmailType::class, array('label' => 'E-Mail'))
                ->add('create-user', SubmitType::class, array('label' => 'Create new user'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user = new User();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setIsActive(true);

            $initialPassword = base64_encode(random_bytes(12));
            $user->setPassword($encoder->encodePassword($user, $initialPassword));

//            $user = $form->getData();
//            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->beginTransaction();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Your user account for the CMS Garden CMS Compass'))
                    ->setFrom('tech@cms-garden.org')
                    ->setTo($user->getEmail())
                    ->setBody(
                    $this->renderView(
                            'mails/user-info.txt.twig', array(
                        'username' => $user->getUsername(),
                        'password' => $initialPassword)
                    ), 'text/plain'
            );
            $mailer->send($message);

            $entityManager->commit();

            return $this->redirectToRoute('admin_list_users');
        }

        return $this->render('admin/user-form.html.twig', array(
                    'form' => $form->createView(),
                    'newUser' => true
        ));
    }

    /**
     * 
     * @Route("/admin/users/{username}", name="admin_edit_user")
     */
    public function editUser(Request $request, $username)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->findByUsername($username);

        if (!$user) {
            throw $this->createNotFoundException('No user with username ' . $username);
        }

        $formBuilder = $this->createFormBuilder()
                ->add('username', TextType::class, array(
                    'label' => 'User name',
                    'data' => $user->getUsername()))
                ->add('email', EmailType::class, array(
                    'label' => 'E-Mail',
                    'data' => $user->getEmail()))
                ->add('isactive', CheckboxType::class, array(
            'label' => 'Is active?',
            'required' => false,
            'data' => $user->getIsActive()));

        $roles = $this->getRoles();
        foreach ($roles as $role) {
            $formBuilder->add($role, CheckboxType::class, array(
                'label' => $role,
                'required' => false,
                'data' => false !== array_search($role, $user->getRoles())
            ));
        }

        $formBuilder->add('update-user', SubmitType::class, array('label' => 'Save'));
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setIsActive($data['isactive']);

            foreach ($roles as $role) {
                if ($data[$role]) {
                    $user->addRole($role);
                } else {
                    $user->removeRole($role);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->beginTransaction();
            $entityManager->merge($user);
            $entityManager->flush();
            $entityManager->commit();

            return $this->redirectToRoute('admin_list_users');
        }

        return $this->render('admin/user-form.html.twig', array(
                    'form' => $form->createView(),
                    'newUser' => false,
                    'user' => $user
        ));
    }

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
    public function createNewCms(Request $request) {
        
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array('label' => 'Name'))
            ->add('homepage', TextType::class, array('label' => 'Homepage'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('create-cms', SubmitType::class, array('label' => 'Create new CMS'))
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
    public function editCms(Request $request, $cmsId) {
        
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
    public function deleteCMS($cmsId) {
        
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
        return $this->render('admin/cms-details.html.twig', array(
                    'cms' => $cmsId
        ));
    }

    private function getRoles()
    {
        return array(
            'ROLE_ADMIN',
            'ROLE_CMSEDITOR'
        );
    }

}
