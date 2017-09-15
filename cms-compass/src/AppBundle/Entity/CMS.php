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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity class for representing a CMS.
 * 
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */

/**
 * @ORM\Table(name="cms")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CmsRepository")
 */
class CMS
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cmsId;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=2048, unique=true)
     *
     */
    private $homepage;

    /**
     * @ORM\Column(type="array")
     *
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="cms")
     */
    private $properties;

    public function __construct()
    {

        $this->description = array();
        $this->features = new ArrayCollection();
        $this->properties = new ArrayCollection();
    }

    /**
     * @return the $cmsId
     */
    public function getCmsId()
    {
        return $this->cmsId;
    }

    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return the $homepage
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param field_type $cmsId
     */
    public function setCmsId($cmsId)
    {
        $this->cmsId = $cmsId;
    }

    /**
     * @param field_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param field_type $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @param multitype: $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescriptionForLanguage($language)
    {
        if ($this->hasDescriptionForLanguage($language)) {
            return $this->description[$language];
        } else {
            return null;
        }
    }

    public function hasDescriptionForLanguage($language)
    {
        return array_key_exists($language, $this->description);
    }

    public function addDescriptionForLanguage($language, $description)
    {
        $this->description[$language] = $description;
    }

    public function removeDescriptionForLanguage($language)
    {
        unset($this->description[$language]);
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function addProperty(Property $property)
    {
        $this->properties->add($property);
    }

    public function removeProperty(Property $property) {
        $this->properties->remove($property);
    }
}
