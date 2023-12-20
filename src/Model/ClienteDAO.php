<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\ClienteVO;
use Src\Model\SQL\ClienteSQL;


class ClienteDAO extends Conexao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarClienteDAO($tenant_id, ClienteVO $vo): int
    {
        $sql = $this->conexao->prepare(ClienteSQL::INSERT_CLIENTE_SQL($tenant_id));
        $i = 1;
        $sql->bindValue($i++, $vo->getCliNome());
        $sql->bindValue($i++, $vo->getCliDtNasc());
        $sql->bindValue($i++, $vo->getCliTelefone());
        $sql->bindValue($i++, $vo->getCliEmail());
        $sql->bindValue($i++, $vo->getCliCep());
        $sql->bindValue($i++, $vo->getCliEndereco());
        $sql->bindValue($i++, $vo->getCliNumero());
        $sql->bindValue($i++, $vo->getCliBairro());
        $sql->bindValue($i++, $vo->getCliCidade());
        $sql->bindValue($i++, $vo->getCliEstado());
        $sql->bindValue($i++, $vo->getCliDescricao());
        $sql->bindValue($i++, $vo->getCliStatus());
        $sql->bindValue($i++, $vo->getCliUserID());
        $sql->bindValue($i++, $vo->getCliCpfCnpj());
       
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function AlterarClienteDAO($tenant_id, ClienteVO $vo): int
    {
        $sql = $this->conexao->prepare(ClienteSQL::UPDATE_CLIENTE_SQL($tenant_id));
        $i = 1;
        $sql->bindValue($i++, $vo->getCliNome());
        $sql->bindValue($i++, $vo->getCliDtNasc());
        $sql->bindValue($i++, $vo->getCliTelefone());
        $sql->bindValue($i++, $vo->getCliEmail());
        $sql->bindValue($i++, $vo->getCliCep());
        $sql->bindValue($i++, $vo->getCliEndereco());
        $sql->bindValue($i++, $vo->getCliNumero());
        $sql->bindValue($i++, $vo->getCliBairro());
        $sql->bindValue($i++, $vo->getCliCidade());
        $sql->bindValue($i++, $vo->getCliEstado());
        $sql->bindValue($i++, $vo->getCliDescricao());
        $sql->bindValue($i++, $vo->getCliEmpID());
        $sql->bindValue($i++, $vo->getCliStatus());
        $sql->bindValue($i++, $vo->getCliUserID());
        $sql->bindValue($i++, $vo->getCliCpfCnpj());
        $sql->bindValue($i++, $vo->getCliID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function AlterarStatusClienteDAO(ClienteVO $vo): int
    {
        $sql = $this->conexao->prepare(ClienteSQL::UPDATE_STATUS_CLIENTE_SQL());
        $i = 1;
        $sql->bindValue($i++, $vo->getCliStatus());
        $sql->bindValue($i++, $vo->getCliID());
        $sql->bindValue($i++, $vo->getCliEmpID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function SelecionarClienteDAO($schema)
    { 
       
        $sql = $this->conexao->prepare(ClienteSQL::SELECT_CLIENTE_SQL($schema));
        $sql->execute();
        
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function DetalharClienteDAO($schema, $id)
    {
        $sql = $this->conexao->prepare(ClienteSQL::DETALHAR_CLIENTE_SQL($schema));
        $i = 1;
         $sql->bindValue($i++, $id);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }




    

    public function FiltrarClienteDAO($filtrar_nome, $filtrar_cidade, $tenant_id)
    {
        
        $sql = $this->conexao->prepare(ClienteSQL::FILTER_CLIENTE_SQL($filtrar_nome, $filtrar_cidade, $tenant_id));
        $i = 1;
        if (!empty($filtrar_nome)) {
            $sql->bindValue($i++, '%'.$filtrar_nome.'%');
        }
        if (!empty($filtrar_cidade)) {
            $sql->bindValue($i++, '%'.$filtrar_cidade.'%');
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

 
    public function EmailDuplicadoClienteDAO()
    {
        $sql = $this->conexao->prepare(ClienteSQL::EMAIL_DUPLICADO_CLIENTE_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function EmailDuplicadoUsuarioDAO()
    {
        $sql = $this->conexao->prepare(ClienteSQL::EMAIL_DUPLICADO_USUARIO_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
}
