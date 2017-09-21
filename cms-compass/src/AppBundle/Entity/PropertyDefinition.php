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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Property
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name = "property_definitions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyDefinitionRepository")
 */
abstract class PropertyDefinition
{

    /**
     * @ORM\Column(type="integer", name="property_definition_id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $propertyId;

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

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $required;

    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="propertyDefinition")
     */
    private $properties;

    public function __construct()
    {
        $this->title = array();
        $this->description = array();
        $this->properties = new ArrayCollection();
    }

    abstract public function getTypeName();
    
    public function getPropertyId()
    {
        return $this->propertyId;
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

    public function getRequired()
    {
        return $this->required;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitleForLanguage($language)
    {
        if ($this->hasTitleForLanguage($language)) {
            return $this->title[$language];
        } else {
            return null;
        }
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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }

    public function getDescriptionForLanguage($language)
    {
        if ($this->hasDescriptionForLanguage($language)) {
            return $this->title[$language];
        } else {
            return null;
        }
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

    public function removeProperty(Property $property)
    {
        $this->properties->removeElement($property);
    }

}
