<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class ImagemVO extends LogErro
{

    private $id;
    private $imagemLogo;
    private $imagemPath;
    private $imagemEntidadeID;
    private $imagemEntidadeTipo;
    private $imagemSchema;

   

    /**
     * Get the value of id
     */ 
    public function getImagemId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setImagemId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of imagemLogo
     */ 
    public function getImagemLogo()
    {
        return $this->imagemLogo;
    }

    /**
     * Set the value of imagemLogo
     *
     * @return  self
     */ 
    public function setImagemLogo($imagemLogo)
    {
        $this->imagemLogo = $imagemLogo;

        return $this;
    }

    /**
     * Get the value of imagemPath
     */ 
    public function getImagemPath()
    {
        return $this->imagemPath;
    }

    /**
     * Set the value of imagemPath
     *
     * @return  self
     */ 
    public function setImagemPath($imagemPath)
    {
        $this->imagemPath = $imagemPath;

        return $this;
    }

    /**
     * Get the value of imagemEntidadeID
     */ 
    public function getImagemEntidadeID()
    {
        return $this->imagemEntidadeID;
    }

    /**
     * Set the value of imagemEntidadeID
     *
     * @return  self
     */ 
    public function setImagemEntidadeID($imagemEntidadeID)
    {
        $this->imagemEntidadeID = $imagemEntidadeID;

        return $this;
    }

    /**
     * Get the value of imagemEntidadeTipo
     */ 
    public function getImagemEntidadeTipo()
    {
        return $this->imagemEntidadeTipo;
    }

    /**
     * Set the value of imagemEntidadeTipo
     *
     * @return  self
     */ 
    public function setImagemEntidadeTipo($imagemEntidadeTipo)
    {
        $this->imagemEntidadeTipo = $imagemEntidadeTipo;

        return $this;
    }

    /**
     * Get the value of imagemSchema
     */ 
    public function getImagemSchema()
    {
        return $this->imagemSchema;
    }

    /**
     * Set the value of imagemSchema
     *
     * @return  self
     */ 
    public function setImagemSchema($imagemSchema)
    {
        $this->imagemSchema = $imagemSchema;

        return $this;
    }
}
