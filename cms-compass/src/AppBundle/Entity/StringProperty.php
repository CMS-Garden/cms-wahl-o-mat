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

use AppBundle\Repository\PropertyRepository;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Description of StringProperty
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name = "string_properties")
 * @ORM\Entity(repositoryClass="PropertyRepository")
 */
class StringProperty extends Property
{

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"details"})
     */
    private $value;

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        if ($this->getPropertyDefinition()->getMaxLength() !== null 
                && strlen($value) > $this->getPropertyDefinition()->getMaxLength()) {
            throw new InvalidArgumentException("The provided string is longer "
            . "than" . $this->getPropertyDefinition()->getMaxLength()
            . " characters.");
        }

        $this->value = $value;
    }

}
