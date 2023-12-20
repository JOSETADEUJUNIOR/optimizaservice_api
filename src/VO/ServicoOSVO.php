<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class ServicoOSVO extends OsVO
{

    private $ServOSID;
    private $ServOsQtd;
    private $ServOs_osID;
    private $ServOsServID;
    private $ServOsSubTotal;
    private $ServOsSchema;
   
    public function setID($id)
    {

        $this->ServOSID = $id;
    }

    public function getID()
    {
        return $this->ServOSID;
    }

    public function setServQtd($p)
    {

        $this->ServOsQtd = $p;
    }

    public function getServQtd()
    {
        return $this->ServOsQtd;
    }
    public function setOsID($p)
    {

        $this->ServOs_osID = $p;
    }

    public function getOsID()
    {
        return $this->ServOs_osID;
    }
    public function setOsServID($p)
    {

        $this->ServOsServID = $p;
    }

    public function getOsServID()
    {
        return $this->ServOsServID;
    }
    public function setOsSubTotal($p)
    {

        $this->ServOsSubTotal = $p;
    }

    public function getOsSubTotal()
    {
        return $this->ServOsSubTotal;
    }

    

    /**
     * Get the value of ServOsSchema
     */ 
    public function getServOsSchema()
    {
        return $this->ServOsSchema;
    }

    /**
     * Set the value of ServOsSchema
     *
     * @return  self
     */ 
    public function setServOsSchema($ServOsSchema)
    {
        $this->ServOsSchema = $ServOsSchema;

        return $this;
    }
}
