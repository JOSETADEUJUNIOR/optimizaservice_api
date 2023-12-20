<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class ItensVendaVO extends LogErro
{

    private $ItensID;
    private $ItensQtd;
    private $ItensSubTotal;
    private $ItensVendaID;
    private $ItensProdID;
   
    public function setProdVendaID($id)
    {

        $this->ItensID = $id;
    }

    public function getProdVendaID()
    {
        return $this->ItensID;
    }

    public function setProdQtd($p)
    {

        $this->ItensQtd = $p;
    }

    public function getProdQtd()
    {
        return $this->ItensQtd;
    }
    public function setVendaSubTotal($p)
    {

        $this->ItensSubTotal = $p;
    }

    public function getVendaSubTotal()
    {
        return $this->ItensSubTotal;
    }
    public function setVendaID($p)
    {

        $this->ItensVendaID = $p;
    }

    public function getVendaID()
    {
        return $this->ItensVendaID;
    }

    public function setProdID($p)
    {

        $this->ItensProdID = $p;
    }

    public function getProdID()
    {
        return $this->ItensProdID;
    }

    
}