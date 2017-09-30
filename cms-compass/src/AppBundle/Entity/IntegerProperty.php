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
 * Description of IntegerProperty
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name = "integer_properties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyRepository")
 */
class IntegerProperty extends Property
{
    
    private $value;
    
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $minValue = $this->getPropertyDefinition()->getMinimum();
        $maxValue = $this->getPropertyDefinition()->getMaximum();
        
        
        if($minValue !== null && $value < $minValue) {
            throw new InvalidArgumentException("The provided value " 
                    . $value . "is smaller than the minimum value " 
                    . $minValue . "set in the definition of this property.");
        } 
        
        if($maxValue !== null && $value > $maxValue) {
            throw new InvalidArgumentException("The provided value " 
                    . $value . "is larger than the maximum value " 
                    . $maxValue . "set in the definition of this property.");
        }
        
        $this->value = $value;
    }


    
}
