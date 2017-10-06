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
 * Description of EnumProperty
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name="enum_properties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyRepository")
 */
class EnumProperty extends Property
{

    /**
     * @ORM\Column(type="array")
     */
    private $values;

    public function getValues()
    {
        return $this->value;
    }

    public function setValues($value)
    {
        $this->value = $value;
    }

    public function addValue($value)
    {
        $permittedValues = $this->getPropertyDefinition()->getPermittedValues();
        if (!in_array($value, $permittedValues)) {
            throw new InvalidArgumentException('The value ' . $value . 'is not '
            . 'a permitted value. Permitted values are: '
            . implode(', ', $permittedValues));
        }
        
        array_push($this->values, $value);
    }
    
    public function removeValue($value) {
        unset($this->values[$value]);
    }

}
