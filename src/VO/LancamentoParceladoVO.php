<?php

namespace Src\VO;
use Src\_public\Util;
use Src\VO\LogErro;


class LancamentoParceladoVO extends LogErro{

private $ParcelaID;
private $LancID;
private $NumeroParcela;
private $ValorParcela;
private $DataVencimento;
private $DataPagamento;
private $Baixado;
private $LancSchema;

/**
 * Get the value of ParcelaID
 */ 
public function getParcelaID()
{
return $this->ParcelaID;
}

/**
 * Set the value of ParcelaID
 *
 * @return  self
 */ 
public function setParcelaID($ParcelaID)
{
$this->ParcelaID = $ParcelaID;

return $this;
}

/**
 * Get the value of LancID
 */ 
public function getLancID()
{
return $this->LancID;
}

/**
 * Set the value of LancID
 *
 * @return  self
 */ 
public function setLancID($LancID)
{
$this->LancID = $LancID;

return $this;
}

/**
 * Get the value of NumeroParcela
 */ 
public function getNumeroParcela()
{
return $this->NumeroParcela;
}

/**
 * Set the value of NumeroParcela
 *
 * @return  self
 */ 
public function setNumeroParcela($NumeroParcela)
{
$this->NumeroParcela = $NumeroParcela;

return $this;
}

/**
 * Get the value of ValorParcela
 */ 
public function getValorParcela()
{
return $this->ValorParcela;
}

/**
 * Set the value of ValorParcela
 *
 * @return  self
 */ 
public function setValorParcela($ValorParcela)
{
$this->ValorParcela = $ValorParcela;

return $this;
}

/**
 * Get the value of DataVencimento
 */ 
public function getDataVencimento()
{
return $this->DataVencimento;
}

/**
 * Set the value of DataVencimento
 *
 * @return  self
 */ 
public function setDataVencimento($DataVencimento)
{
$this->DataVencimento = $DataVencimento;

return $this;
}

/**
 * Get the value of DataPagamento
 */ 
public function getDataPagamento()
{
return $this->DataPagamento;
}

/**
 * Set the value of DataPagamento
 *
 * @return  self
 */ 
public function setDataPagamento($DataPagamento)
{
$this->DataPagamento = $DataPagamento;

return $this;
}

/**
 * Get the value of Baixado
 */ 
public function getBaixado()
{
return $this->Baixado;
}

/**
 * Set the value of Baixado
 *
 * @return  self
 */ 
public function setBaixado($Baixado)
{
$this->Baixado = $Baixado;

return $this;
}

/**
 * Get the value of LancSchema
 */ 
public function getLancSchema()
{
return $this->LancSchema;
}

/**
 * Set the value of LancSchema
 *
 * @return  self
 */ 
public function setLancSchema($LancSchema)
{
$this->LancSchema = $LancSchema;

return $this;
}
}