<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class TpServicoVO extends LogErro{

private $TpServID;
private $TpServNome;
private $TpServSchema;
private $TpServStatus;


/**
 * Get the value of TpServID
 */ 
public function getTpServID()
{
return $this->TpServID;
}

/**
 * Set the value of TpServID
 *
 * @return  self
 */ 
public function setTpServID($TpServID)
{
$this->TpServID = $TpServID;

return $this;
}

/**
 * Get the value of TpServNome
 */ 
public function getTpServNome()
{
return $this->TpServNome;
}

/**
 * Set the value of TpServNome
 *
 * @return  self
 */ 
public function setTpServNome($TpServNome)
{
$this->TpServNome = $TpServNome;

return $this;
}

/**
 * Get the value of TpServSchema
 */ 
public function getTpServSchema()
{
return $this->TpServSchema;
}

/**
 * Set the value of TpServSchema
 *
 * @return  self
 */ 
public function setTpServSchema($TpServSchema)
{
$this->TpServSchema = $TpServSchema;

return $this;
}

/**
 * Get the value of TpServStatus
 */ 
public function getTpServStatus()
{
return $this->TpServStatus;
}

/**
 * Set the value of TpServStatus
 *
 * @return  self
 */ 
public function setTpServStatus($TpServStatus)
{
$this->TpServStatus = $TpServStatus;

return $this;
}
}