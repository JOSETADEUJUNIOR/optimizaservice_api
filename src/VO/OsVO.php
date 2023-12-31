<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class OsVO extends LogErro
{

    private $OsID;
    private $OsDtInicial;
    private $OsDtFinal;
    private $OsGarantia;
    private $OsDescProdServ;
    private $OsDefeito;
    private $OsObs;
    private $OsCliID;
    private $OsTecID;
    private $OsStatus;
    private $OsLaudoTec;
    private $OsNumeroNF;
    private $OsValorTotal;
    private $OsFaturado;
    private $OsAgendamento;
    private $OsTpServico;
    private $OsSchema;

    public function setID($id)
    {

        $this->OsID = $id;
    }

    public function getID()
    {
        return $this->OsID;
    }

    public function setDtInicial($p)
    {

        $this->OsDtInicial = $p;
    }

    public function getDtInicial()
    {
        return $this->OsDtInicial;
    }

    public function setOsDtFinal($p)
    {

        $this->OsDtFinal = $p;
    }

    public function getOsDtFinal()
    {
        return $this->OsDtFinal;
    }

    public function setOsDescProdServ($p)
    {

        $this->OsDescProdServ = Util::TratarDados($p);
    }

    public function getOsDescProdServ()
    {
        return $this->OsDescProdServ;
    }

    public function setOsGarantia($p)
    {

        $this->OsGarantia = Util::TratarDados($p);
    }

    public function getOsGarantia()
    {
        return $this->OsGarantia;
    }

    public function setOsDefeito($p)
    {

        $this->OsDefeito = Util::TratarDados($p);
    }

    public function getOsDefeito()
    {
        return $this->OsDefeito;
    }

    public function setOsObs($p)
    {

        $this->OsObs = Util::TratarDados($p);
    }

    public function getOsObs()
    {
        return $this->OsObs;
    }

    public function setOsCliID($p)
    {

        $this->OsCliID = Util::TratarDados($p);
    }

    public function getOsCliID()
    {
        return $this->OsCliID;
    }

    public function setOsTecID($p)
    {

        $this->OsTecID = Util::TratarDados($p);
    }

    public function getOsTecID()
    {
        return $this->OsTecID;
    }

    public function setOsStatus($p)
    {

        $this->OsStatus = Util::TratarDados($p);
    }

    public function getOsStatus()
    {
        return $this->OsStatus;
    }

    public function setOsLaudoTec($p)
    {

        $this->OsLaudoTec = Util::TratarDados($p);
    }

    public function getOsLaudoTec()
    {
        return $this->OsLaudoTec;
    }

    public function setOsValorTotal($p)
    {

        $this->OsValorTotal = Util::TratarDados($p);
    }

    public function getOsValorTotal()
    {
        return $this->OsValorTotal;
    }

    public function setOsFaturado($p)
    {

        $this->OsFaturado = Util::TratarDados($p);
    }

    public function getOsFaturado()
    {
        return $this->OsFaturado;
    }
    public function setOsNumeroNF($p)
    {

        $this->OsNumeroNF = $p;
    }

    public function getOsNumeroNF()
    {
        return $this->OsNumeroNF;
    }

    /**
     * Get the value of OsSchema
     */ 
    public function getOsSchema()
    {
        return $this->OsSchema;
    }

    /**
     * Set the value of OsSchema
     *
     * @return  self
     */ 
    public function setOsSchema($OsSchema)
    {
        $this->OsSchema = $OsSchema;

        return $this;
    }

    /**
     * Get the value of OsAgendamento
     */ 
    public function getOsAgendamento()
    {
        return $this->OsAgendamento;
    }

    /**
     * Set the value of OsAgendamento
     *
     * @return  self
     */ 
    public function setOsAgendamento($OsAgendamento)
    {
        $this->OsAgendamento = $OsAgendamento;

        return $this;
    }

    /**
     * Get the value of OsTpServico
     */ 
    public function getOsTpServico()
    {
        return $this->OsTpServico;
    }

    /**
     * Set the value of OsTpServico
     *
     * @return  self
     */ 
    public function setOsTpServico($OsTpServico)
    {
        $this->OsTpServico = $OsTpServico;

        return $this;
    }
}
