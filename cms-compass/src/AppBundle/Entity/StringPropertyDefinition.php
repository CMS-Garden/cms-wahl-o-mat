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

use AppBundle\Repository\PropertyDefinitionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Table(name = "string_property_definitions")
 * @ORM\Entity(repositoryClass="PropertyDefinitionRepository")
 */
class StringPropertyDefinition extends PropertyDefinition
{

    public function getTypeName()
    {
        return "String";
    }
    
    /**
     * @ORM\Column(type="integer", name="max_length", nullable=true)
     */
    private $maxLength;

    /**
      @ORM\Column(type="boolean", name="html_permitted")
     */
    private $htmlPermitted;

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function getHtmlPermitted()
    {
        return $this->htmlPermitted;
    }

    public function setHtmlPermitted($htmlPermitted)
    {
        $this->htmlPermitted = $htmlPermitted;
    }

}
