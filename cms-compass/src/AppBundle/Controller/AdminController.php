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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;

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
        $userFilter = $request->query->get("filter", "");

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
    public function newUserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createFormBuilder($user)
                ->add('username', TextType::class, array('label' => 'User name'))
                ->add('email', EmailType::class, array('label' => 'E-Mail'))
                ->add('password', PasswordType::class, array('label' => 'Initial password'))
                ->add('create-user', SubmitType::class, array('label' => 'Create new user'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_users');
        }

        return $this->render('admin/user-form.html.twig', array(
                    'form' => $form->createView(),
                    'newUser' => true
        ));
    }

    /**
     * 
     * @Route("/admin/users/{user}")
     */
    public function showUserDetails($user)
    {
        return $this->render('admin/user-details.html.twig', array(
                    'user' => $user
        ));
    }

    /**
     * @Route("/admin/features")
     */
    public function listFeatures()
    {
        return $this->render('admin/features.html.twig');
    }

    /**
     * 
     * @Route("/admin/features/{feature}")
     */
    public function showFeatureDetails($feature)
    {
        return $this->render('admin/feature-details.html.twig', array(
                    'feature' => $feature
        ));
    }

    /**
     * @Route("/admin/cms")
     */
    public function listCms()
    {
        return $this->render('admin/cms-list.html.twig');
    }

    /**
     * @Route("/admin/cms/{cms}")
     */
    public function showCmsDetails($cms)
    {
        return $this->render('admin/cms-details.html.twig', array(
                    'cms' => $cms
        ));
    }

}
