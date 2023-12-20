<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\ImagemVO;

class CategoriaVO extends ImagemVO
{

    private $id;
    private $nome_categoria;
    private $cod;
    private $descricao_categoria;
    private $schema;

    public function setID($id)
    {

        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setSchema($schema)
    {

        $this->schema = $schema;
    }

    public function getSchema()
    {
        return $this->schema;
    }


    
    public function setCod($cod)
    {

        $this->cod = $cod;
    }

    public function getCod()
    {
        return $this->cod;
    }

    public function setNomeCategoria($nome_categoria)
    {

        $this->nome_categoria = Util::TratarDados($nome_categoria);
    }

    public function getNomeCategoria()
    {
        return $this->nome_categoria;
    }


    public function setDescricaoCategoria($descricao_categoria)
    {

        $this->descricao_categoria = Util::TratarDados($descricao_categoria);
    }

    public function getDescricaoCategoria()
    {
        return $this->descricao_categoria;
    }
}
