<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\TpServicoVO;
use Src\Model\SQL\TpServicoSQL;


class TpServicoDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarTpServicoDAO(TpServicoVO $vo): int
    {

            $sql = $this->conexao->prepare(TpServicoSQL::InserirTpServicoSQL($vo->getTpServSchema()));
            $sql->bindValue(1, $vo->getTpServNome());
            $sql->bindValue(2, $vo->getTpServStatus());
            
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function EditarTpServicoDAO(TpServicoVO $vo): int
    {
            $sql = $this->conexao->prepare(TpServicoSQL::EditarTpServicoSQL($vo->getTpServSchema()));
            $sql->bindValue(1, $vo->getTpServNome());
            $sql->bindValue(4, $vo->getTpServStatus());
            $sql->bindValue(5, $vo->getTpServID());
            
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }





      public function DetalharTpServicoDAO(TpServicoVO $vo)
    {
        $sql = $this->conexao->prepare(TpServicoSQL::DetalharTpServicoSQL($vo->getTpServSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getTpServID());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }


      public function FiltrarTpServicoDAO($filtrar_nome,$tenant_id)
    {
        $sql = $this->conexao->prepare(TpServicoSQL::FiltrarTpServicoSQL($filtrar_nome, $tenant_id));
        $i = 1;
        if (!empty($filtrar_nome)) {
            $sql->bindValue($i++, '%'.$filtrar_nome.'%');
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

  
   
    public function RetornarTpServicosDAO(TpServicoVO $vo)
    {
        $sql = $this->conexao->prepare(TpServicoSQL::RetornarTpServicoSQL($vo->getTpServSchema()));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

}
