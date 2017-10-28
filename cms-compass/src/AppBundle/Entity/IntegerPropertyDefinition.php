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
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * 
 * @ORM\Table(name = "integer_property_definitions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyDefinitionRepository")
 */
class IntegerPropertyDefinition extends PropertyDefinition
{
     /**
     *
     * @Serializer\Groups("definition")
     */
    private $typeName = 'Integer';
    

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups("definition")
     */
    private $unit;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups("definition")
     */
    private $minimum;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups("definition")
     */
    private $maximum;

    public function getTypeName()
    {
        return $this->typeName;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getMaximum()
    {
        return $this->maximum;
    }

    public function getMinimum()
    {
        return $this->minimum;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function setMaximum($maxium)
    {
        $this->maximum = $maxium;
    }

    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;
    }

}
