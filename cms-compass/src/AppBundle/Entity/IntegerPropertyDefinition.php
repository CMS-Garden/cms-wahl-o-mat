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

/**
 * 
 * @ORM\Table(name = "integer_property_definitions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyDefinitionRepository")
 */
class IntegerPropertyDefinition extends PropertyDefinition
{
    /**
     *
     * @Column(type="string")
     */
    private $unit;
    
    /**
     *
     * @Column(type="integer")
     */
    private $maxium;
    
    /**
     *
     * @Column(type="integer")
     */
    private $mininum;
    
    public function getUnit()
    {
        return $this->unit;
    }

    public function getMaxium()
    {
        return $this->maxium;
    }

    public function getMininum()
    {
        return $this->mininum;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function setMaxium($maxium)
    {
        $this->maxium = $maxium;
    }

    public function setMininum($mininum)
    {
        $this->mininum = $mininum;
    }


}
