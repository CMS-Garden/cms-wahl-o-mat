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

/**
 * Description of Property
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name="properties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyRepository")
 */
class Property
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $propertyId;

    /**
     * @ORM\ManyToOn(targetEntity="PropertyDefinition")
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="property_definition_id")
     * 
     */
    private $propertyDefinition;

    /**
     * @ORM\ManyToOne(targetEntity="CMS")
     * @ORM\JoinColumn(name="cms_id", referencedColumnName="cms_id")
     * 
     */
    private $cms;

    public function getPropertyId()
    {
        return $this->propertyId;
    }

    public function getPropertyDefinition()
    {
        return $this->propertyDefinition;
    }

    public function getCms()
    {
        return $this->cms;
    }

    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;
    }

    public function setPropertyDefinition($propertyDefinition)
    {
        $this->propertyDefinition = $propertyDefinition;
    }

    public function setCms($cms)
    {
        $this->cms = $cms;
    }

}
