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

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function random_bytes;

/**
 * Description of UsersController
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class UsersController extends Controller
{

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
    public function newUserAction(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer)
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

            $message = (new Swift_Message('Your user account for the CMS Garden CMS Compass'))
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
       
        $formBuilder->add('update_user', SubmitType::class, array('label' => 'Save'));
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

    private function getRoles()
    {
        return array(
            'ROLE_ADMIN',
            'ROLE_CMSEDITOR'
        );
    }

}
