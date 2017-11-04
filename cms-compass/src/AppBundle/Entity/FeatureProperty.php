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
 * Description of FeatureProperty
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name="feature_properties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeaturePropertyRepository")
 */
class FeatureProperty extends Property
{
    const PERMITTED_VALUES = ["yes", "no", "plugin", "commerical", "n/a"];
    
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
        if (!in_array($value, FeatureProperty::PERMITTED_VALUES)) {
            throw new InvalidArgumentException(
                    "The value of a FeatureProperty can only one these: " 
                    . implode(", ", PERMITTED_VALUES));
        }
        
        $this->value = $value;
    }


}
