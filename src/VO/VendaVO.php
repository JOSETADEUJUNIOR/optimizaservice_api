<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\ItensVendaVO;

class VendaVO extends ItensVendaVO
{

    private $VendaID;
    private $VendaDT;
    private $VendaValorTotal;
    private $VendaDesconto;
    private $VendaFaturado;
    private $VendaCliID;
    private $VendaLancamentoID;
    private $valorEntrega;
    private $status;
    private $schema;
    
    public function setID($id)
    {

        $this->VendaID = $id;
    }

    public function getID()
    {
        return $this->VendaID;
    }

    public function setDtVenda($p)
    {

        $this->VendaDT = $p;
    }

    public function getDtVenda()
    {
        return $this->VendaDT;
    }

    public function setValorTotal($p)
    {

        $this->VendaValorTotal = Util::TratarDados($p);
    }

    public function getValorTotal()
    {
        return $this->VendaValorTotal;
    }

    public function setDesconto($p)
    {

        $this->VendaDesconto = Util::TratarDados($p);
    }

    public function getDesconto()
    {
        return $this->VendaDesconto;
    }

    public function setFaturado($p)
    {

        $this->VendaFaturado = Util::TratarDados($p);
    }

    public function getFaturado()
    {
        return $this->VendaFaturado;
    }

    public function setCliID($p)
    {

        $this->VendaCliID = Util::TratarDados($p);
    }

    public function getCliID()
    {
        return $this->VendaCliID;
    }

    public function setLancamentoID($p)
    {

        $this->VendaLancamentoID = Util::TratarDados($p);
    }

    public function getLancamentoID()
    {
        return $this->VendaLancamentoID;
    }

    /**
     * Get the value of valorEntrega
     */ 
    public function getValorEntrega()
    {
        return $this->valorEntrega;
    }

    /**
     * Set the value of valorEntrega
     *
     * @return  self
     */ 
    public function setValorEntrega($valorEntrega)
    {
        $this->valorEntrega = $valorEntrega;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of schema
     */ 
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @return  self
     */ 
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}