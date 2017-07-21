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
     * @Route("/admin/users")
     * 
     */
    public function listUsers()
    {
        return $this->render('admin/users.html.twig');
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
