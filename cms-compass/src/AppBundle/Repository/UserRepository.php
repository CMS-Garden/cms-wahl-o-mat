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

namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository and implementation of the UserLoaderInterface to allow
 * login via username or e-mail.
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
                        ->where('u.username = :username OR u.email = :email')
                        ->setParameter('username', $username)
                        ->setParameter('email', $username)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

    public function filterUsersByUsernameOrEmail($filter)
    {
        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder
                        ->where($queryBuilder->expr()->orX(
                                        $queryBuilder->expr()->like('u.username', ':username'), $queryBuilder->expr()->like('u.email', ':email')
                        ))
                        ->orderBy('u.username', 'ASC')
                        ->setParameter('username', '%'. $filter . '%')
                        ->setParameter('email', '%'. $filter. '%')
                        ->getQuery()
                        ->getResult();
    }

}
