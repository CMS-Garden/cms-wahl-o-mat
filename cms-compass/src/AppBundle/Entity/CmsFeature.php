<?php

/*
 * Copyright (C) 2017 CMS Garden e.V.
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
 * Description of CmsFeature
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 * 
 * @ORM\Table(name="cms_features")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CmsFeatureRepository")
 */
class CmsFeature
{

    const FEATURE_VALUES = array("yes", "no", "plugin", "commerical", "n/a");

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cmsFeatureId;
    
    /**
     * @ORM\ManyToOne(targetEntity="CMS")
     * @ORM\JoinColumn(name="cms_id", referencedColumnName="cms_id")
     * 
     * @var type 
     */
    private $cms;
    
     /**
     * @ORM\ManyToOne(targetEntity="Feature")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="feature_id")
     * 
     * @var type 
     */
    private $feature;
    
    /**
     * @ORM\Column(type="string", length=256)
     *
     */
    private $value;

    public function getCmsFeatureId()
    {
        return $this->cmsFeatureId;
    }

    public function getCms()
    {
        return $this->cms;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setCmsFeatureId($cmsFeatureId)
    {
        $this->cmsFeatureId = $cmsFeatureId;
    }

    public function setCms($cms)
    {
        $this->cms = $cms;
    }

    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

}
