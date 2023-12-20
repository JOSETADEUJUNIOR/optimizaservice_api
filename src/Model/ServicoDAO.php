<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\ServicoVO;
use Src\Model\SQL\ServicoSQL;


class ServicoDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarServicoDAO(ServicoVO $vo): int
    {

            $sql = $this->conexao->prepare(ServicoSQL::InserirServicoSQL($vo->getServSchema()));
            $sql->bindValue(1, $vo->getServNome());

            $sql->bindValue(2, $vo->getServValor());
            $sql->bindValue(3, $vo->getServDescricao());
            $sql->bindValue(4, $vo->getServStatus());
            
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function EditarServicoDAO(ServicoVO $vo): int
    {
            $sql = $this->conexao->prepare(ServicoSQL::EditarServicoSQL($vo->getServSchema()));
            $sql->bindValue(1, $vo->getServNome());
            $sql->bindValue(2, $vo->getServValor());
            $sql->bindValue(3, $vo->getServDescricao());
            $sql->bindValue(4, $vo->getServStatus());
            $sql->bindValue(5, $vo->getServID());
            
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }





      public function DetalharServicoDAO($tenant_id, $id_servico)
    {
        $sql = $this->conexao->prepare(ServicoSQL::DetalharServicoSQL($tenant_id));
        $i = 1;
        $sql->bindValue($i++, $id_servico);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }


      public function FiltrarServicoDAO($filtrar_nome, $filtrar_valor, $tenant_id)
    {
        $sql = $this->conexao->prepare(ServicoSQL::FiltrarServicoSQL($filtrar_nome, $filtrar_valor, $tenant_id));
        $i = 1;
        if (!empty($filtrar_nome)) {
            $sql->bindValue($i++, '%'.$filtrar_nome.'%');
        }
        if (!empty($filtrar_valor)) {
            $sql->bindValue($i++, '%'.$filtrar_valor.'%');
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }



    public function ConsultarServicoDAO($filtro_palavra): array
    {

        $sql = $this->conexao->prepare(ServicoSQL::ConsultarServicoSQL($filtro_palavra));
        if (!empty($filtro_palavra)) {

            $sql->bindValue(1, "%" . $filtro_palavra . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

  
   
    public function RetornarServicosDAO($schema)
    {
        $sql = $this->conexao->prepare(ServicoSQL::RetornarServicoSQL($schema));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

}
