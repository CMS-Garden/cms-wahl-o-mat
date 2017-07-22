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

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entity for users
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{

    /**
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * 
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     */
    private $email;

    /**
     *
     * @ORM\Column(type="array",nullable=true)
     */
    private $roles;

    /**
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->roles = array();
        $this->isActive = true;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function addRole($role)
    {
        if (false === array_search($role, $this->roles)) {
            $this->roles[] = $role;
            $this->roles = array_values($this->roles);
        }
    }

    public function removeRole($role)
    {
        $key = array_search($role, $this->roles);
        if (false !== $key) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        } 
    }

    public function eraseCredentials()
    {
        
    }

    public function serialize()
    {
        return serialize(array(
            $this->userId,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
                $this->userId,
                $this->username,
                $this->password) = unserialize($serialized);
    }

}
