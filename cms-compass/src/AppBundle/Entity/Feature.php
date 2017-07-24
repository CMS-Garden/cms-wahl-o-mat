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

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class for the available Feature.s
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */

/**
 * @ORM\Table(name="features")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $featureId;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     *
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     *
     */
    private $title;

    /**
     * @ORM\Column(type="array")
     *
     */
    private $description;

    public function __construct()
    {
        $this->title = array();
        $this->description = array();
    }

    public function getFeatureId()
    {
        return $this->featureId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setFeatureId($featureId)
    {
        $this->featureId = $featureId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getTitleForLanguage($language)
    {
        return $this->title[$language];
    }

    public function hasTitleForLanguage($language)
    {
        return array_key_exists($language, $this->title);
    }

    public function addTitleForLanguage($language, $title)
    {
        $this->title[$language] = $title;
    }

    public function removeTitleForLanguage($language)
    {
        unset($this->title[$language]);
    }

    public function getDescriptionForLanguage($language)
    {
        return $this->title[$language];
    }

    public function hasDescriptionForLanguage($language)
    {
        return array_key_exists($language, $this->title);
    }

    public function addDescriptionForLanguage($language, $title)
    {
        $this->title[$language] = $title;
    }

    public function removeDescriptionForLanguage($language)
    {
        unset($this->title[$language]);
    }

}
