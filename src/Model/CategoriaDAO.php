<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\CategoriaVO;
use Src\Model\SQL\CategoriaSQL;
use Src\Model\SQL\ImagemSQL;

class CategoriaDAO extends Conexao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarCategoriaDAO($vo)
    {
        try {
            $sql = $this->conexao->prepare(CategoriaSQL::INSERT_CATEGORIA_SQL($vo->getSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getNomeCategoria());
            $sql->bindValue($i++, $vo->getCod());
            $sql->bindValue($i++, $vo->getDescricaoCategoria());
            $sql->execute();
            $categoria_id = $this->conexao->lastInsertId();
            $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getImagemLogo());
            $sql->bindValue($i++, $vo->getImagemPath());
            $sql->bindValue($i++, $categoria_id);
            $sql->bindValue($i++, 'categoria');
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            #parent::GravarLogErro($vo);
            return -1;
        }
    }   

    public function EditarCategoriaDAO(CategoriaVO $vo)
    {
        try {
            $sql = $this->conexao->prepare(CategoriaSQL::EDITAR_CATEGORIA_SQL($vo->getSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getNomeCategoria());
            $sql->bindValue($i++, $vo->getCod());
            $sql->bindValue($i++, $vo->getDescricaoCategoria());
            $sql->bindValue($i++, $vo->getID());
            $sql->execute(); 
            if ($vo->getImagemLogo() != "") {
                if ($vo->getImagemId()!="") {
                    $sql = $this->conexao->prepare(ImagemSQL::UPDATE_IMAGEM_SQL($vo->getSchema()));
                    $i = 1;
                    $sql->bindValue($i++, $vo->getImagemLogo());
                    $sql->bindValue($i++, $vo->getImagemPath());
                    $sql->bindValue($i++, $vo->getImagemId());
                    $sql->execute();
                    
                }else{
                    $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getSchema()));
                    $i = 1;
                    $sql->bindValue($i++, $vo->getImagemLogo());
                    $sql->bindValue($i++, $vo->getImagemPath());
                    $sql->bindValue($i++, $vo->getImagemEntidadeID());
                    $sql->bindValue($i++, 'categoria');
                    $sql->execute(); 
                }
            }
            return 1;
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            #parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function RetornarCategoriaDAO($schema)
    {

        $sql = $this->conexao->prepare(CategoriaSQL::RETORNAR_CATEGORIAS_SQL($schema));
        $sql->execute();

        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function DetalharCategoriaDAO($schema, $id)
    {
        $sql = $this->conexao->prepare(CategoriaSQL::DETALHAR_CATEGORIA_SQL($schema));
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function FiltrarCategoriaDAO($filtrar_nome, $filtrar_cod, $tenant_id)
    {
        
        $sql = $this->conexao->prepare(CategoriaSQL::FILTRAR_CATEGORIA_SQL($filtrar_nome, $filtrar_cod, $tenant_id));
        $i = 1;
        if (!empty($filtrar_nome)) {
            $sql->bindValue($i++, '%'.$filtrar_nome.'%');
        }
        if (!empty($filtrar_cod)) {
            $sql->bindValue($i++, '%'.$filtrar_cod.'%');
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

}
