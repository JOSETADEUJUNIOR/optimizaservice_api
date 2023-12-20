<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class ProdutoOSVO extends OsVO
{

    private $ProdOSID;//id do produtos da os
    private $ProdOsQtd;//quantidade dos produtos da os
    private $ProdOs_osID;//id da os no produto os
    private $ProdOsProdID;//id do produto no produtoOs
    private $ProdOsSubTotal;
    private $prodOsSchema;
   
    public function setProdOsID($id)
    {

        $this->ProdOSID = $id;
    }

    public function getProdOsID()
    {
        return $this->ProdOSID;
    }

    public function setProdQtd($p)
    {

        $this->ProdOsQtd = $p;
    }

    public function getProdQtd()
    {
        return $this->ProdOsQtd;
    }
    public function setOsID($p)
    {

        $this->ProdOs_osID = $p;
    }

    public function getOsID()
    {
        return $this->ProdOs_osID;
    }
    public function setOsProdID($p)
    {

        $this->ProdOsProdID = $p;
    }

    public function getOsProdID()
    {
        return $this->ProdOsProdID;
    }
    public function setOsSubTotal($p)
    {

        $this->ProdOsSubTotal = $p;
    }

    public function getOsSubTotal()
    {
        return $this->ProdOsSubTotal;
    }

    

    /**
     * Get the value of prodOsSchema
     */ 
    public function getProdOsSchema()
    {
        return $this->prodOsSchema;
    }

    /**
     * Set the value of prodOsSchema
     *
     * @return  self
     */ 
    public function setProdOsSchema($prodOsSchema)
    {
        $this->prodOsSchema = $prodOsSchema;

        return $this;
    }
}
