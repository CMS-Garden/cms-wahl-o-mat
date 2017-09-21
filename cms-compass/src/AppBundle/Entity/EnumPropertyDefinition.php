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
 * 
 * @ORM\Table(name = "enum_property_definitions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyDefinitionRepository")
 */
class EnumPropertyDefinition extends PropertyDefinition
{

    /**
     * @ORM\Column(name="permitted_values", type="array")
     */
    private $permittedValues;

    /**
     * @ORM\Column(name = "multiple_values", type="boolean")
     */
    private $multipleValues;

    public function __construct()
    {
        parent::__construct();
        $this->permittedValues = array();
    }

    public function getTypeName() {
        return "Enum";
    }


    public function getPermittedValues()
    {
        return $this->permittedValues;
    }

    public function setPermittedValues($permittedValues)
    {
        $this->permittedValues = $permittedValues;
    }

    public function addPermittedValue($permittedValue)
    {
        array_push($this->permittedValues, $permittedValue);
    }

    public function removePermittedValue($permittedValue)
    {
        foreach ($this->permittedValues as $key => $value) {
            if ($value === $permittedValue) {
                unset($this->permittedValues[$key]);
            }
        }
    }

}
