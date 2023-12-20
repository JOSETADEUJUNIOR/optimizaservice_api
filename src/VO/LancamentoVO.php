<?php

namespace Src\VO;
use Src\_public\Util;
use Src\VO\LancamentoParceladoVO;

class LancamentoVO extends LancamentoParceladoVO{

private $LancID;
private $LancDescricao;
private $LancValor;
private $LancValorTotal;
private $LancValorDevido;
private $LancDesconto;
private $LancDtVencimento;
private $LancDtPagamento;
private $LancQtdParcela;
private $LancBaixado;
private $LancFormPgto;
private $Tipo;
private $LancClienteID;
private $LancEntidadeID;
private $LancEntidadeTipo;

public function setID($p){

    $this->LancID = $p;
}

public function getID(){
    return $this->LancID;
}

public function setDescricao($p){

    $this->LancDescricao = Util::TratarDados($p);
}

public function getDescricao(){
    return $this->LancDescricao;
}

public function setValor($p){

    $this->LancValor = str_replace(',', '.', $p);;
}

public function getValor(){
    return $this->LancValor;
}

public function setDesconto($p){

    $this->LancDesconto = Util::TratarDados($p);
}

public function getDesconto(){
    return $this->LancDesconto;
}

public function setDtVencimento($p){

    $this->LancDtVencimento = Util::TratarDados($p);
}

public function getDtVencimento(){
    return $this->LancDtVencimento;
}

public function setDtPagamento($p){

    $this->LancDtPagamento = Util::TratarDados($p);
}

public function getDtPagamento(){
    return $this->LancDtPagamento;
}

public function setBaixado($p){

    $this->LancBaixado = Util::TratarDados($p);
}

public function getBaixado(){
    return $this->LancBaixado;
}

public function setFormPgto($p){

    $this->LancFormPgto = Util::TratarDados($p);
}

public function getFormPgto(){
    return $this->LancFormPgto;
}

public function setTipo($p){

    $this->Tipo = Util::TratarDados($p);
}

public function getTipo(){
    return $this->Tipo;
}

public function setClienteID($p){

    $this->LancClienteID = Util::TratarDados($p);
}

public function getClienteID(){
    return $this->LancClienteID;
}



/**
 * Get the value of LancValorTotal
 */ 
public function getLancValorTotal()
{
return $this->LancValorTotal;
}

/**
 * Set the value of LancValorTotal
 *
 * @return  self
 */ 
public function setLancValorTotal($LancValorTotal)
{
$this->LancValorTotal = $LancValorTotal;

return $this;
}

/**
 * Get the value of LancValorDevido
 */ 
public function getLancValorDevido()
{
return $this->LancValorDevido;
}

/**
 * Set the value of LancValorDevido
 *
 * @return  self
 */ 
public function setLancValorDevido($LancValorDevido)
{
$this->LancValorDevido = $LancValorDevido;

return $this;
}

/**
 * Get the value of LancEntidadeID
 */ 
public function getLancEntidadeID()
{
return $this->LancEntidadeID;
}

/**
 * Set the value of LancEntidadeID
 *
 * @return  self
 */ 
public function setLancEntidadeID($LancEntidadeID)
{
$this->LancEntidadeID = $LancEntidadeID;

return $this;
}

/**
 * Get the value of LancEntidadeTipo
 */ 
public function getLancEntidadeTipo()
{
return $this->LancEntidadeTipo;
}

/**
 * Set the value of LancEntidadeTipo
 *
 * @return  self
 */ 
public function setLancEntidadeTipo($LancEntidadeTipo)
{
$this->LancEntidadeTipo = $LancEntidadeTipo;

return $this;
}

/**
 * Get the value of LancQtdParcela
 */ 
public function getLancQtdParcela()
{
return $this->LancQtdParcela;
}

/**
 * Set the value of LancQtdParcela
 *
 * @return  self
 */ 
public function setLancQtdParcela($LancQtdParcela)
{
$this->LancQtdParcela = $LancQtdParcela;

return $this;
}
}