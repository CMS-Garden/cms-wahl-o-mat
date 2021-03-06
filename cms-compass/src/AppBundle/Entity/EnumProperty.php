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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

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
     * @Serializer\Groups({"details"})
     */
    private $values;

    public function getValue()
    {
        if ($this->values === null) {
            return '';
        } else if($this->values instanceof ArrayCollection) {
            return implode(', ', $this->values->toArray());
        } else if(is_array($this->values)) {
            return implode(', ', $this->values);
        } else if(is_string($this->values)) {
            return $this->values;
        } else {
            strval($this->values);
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValues($value)
    {
        $this->values = $value;
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

    public function removeValue($value)
    {
        unset($this->values[$value]);
    }

}
