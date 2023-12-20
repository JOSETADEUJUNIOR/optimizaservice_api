<?php

namespace Src\model;

use Exception;
use Src\_public\Util;
use Src\Model\Conexao;
use Src\Model\SQL\UsuarioSQL;
use Src\Model\SQL\EnderecoSQL;
use Src\VO\EmpresaVO;
use Src\VO\UsuarioVO;

class EmpresaDAO extends Conexao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CriarSchemaNovo($vo)
    {
        $schemaNome = 'schema_' . uniqid();
        Conexao::criarSchema($schemaNome);
        Conexao::selecionaSchema($schemaNome);

        $conexao = Conexao::retornaConexao();
        $conexao->beginTransaction();

        try {
            // Criação das tabelas
            $this->criarTabelaEmpresa();
            $this->criarTabelaUsuario();
            $this->criarTabelaCidade();
            $this->criarTabelaEstado();
            $this->criarTabelaEndereco();

            // ... (restante do código para cadastrar o usuário dentro do schema selecionado)

            $this->conexao->commit();
            return 1;
        } catch (Exception $ex) {
            $this->conexao->rollBack();
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    private function criarTabelaUsuario()
    {
        $sql = "CREATE TABLE tb_usuario (
            id int(11) NOT NULL,
            tipo smallint(6) NOT NULL COMMENT '1 - Adm\n2 - funcionario\n3 - tecnico',
            nome varchar(50) NOT NULL,
            login varchar(45) NOT NULL,
            senha varchar(60) NOT NULL,
            status tinyint(1) NOT NULL,
            telefone varchar(20) NOT NULL,
            UserEmpID int(11) NOT NULL,
            UserLogo varchar(100) DEFAULT NULL,
            UserLogoPath varchar(100) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->conexao->exec($sql);
    }

private function criarTabelaEmpresa()
{
   $sql= "CREATE TABLE tb_empresa (
        EmpID int(11) NOT NULL,
        EmpNome varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpEnd varchar(150) COLLATE utf8_bin DEFAULT NULL,
        EmpCNPJ varchar(18) COLLATE utf8_bin DEFAULT NULL,
        EmpPlano char(1) COLLATE utf8_bin DEFAULT NULL,
        EmpStatus char(1) COLLATE utf8_bin DEFAULT 'A',
        EmpDtCadastro varchar(45) COLLATE utf8_bin NOT NULL,
        EmpDtRenovacao datetime DEFAULT NULL,
        EmpDtVencimento datetime DEFAULT NULL,
        EmpLogo varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpLogoPath varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpCep varchar(45) COLLATE utf8_bin DEFAULT NULL,
        EmpNumero varchar(45) COLLATE utf8_bin DEFAULT NULL,
        EmpCidade varchar(100) COLLATE utf8_bin DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $this->conexao->exec($sql);
   }


   private function criarTabelaCidade()
{
   $sql= "CREATE TABLE tb_cidade (
        id int(11) NOT NULL,
        nome_cidade varchar(45) NOT NULL,
        estado_id int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $this->conexao->exec($sql);
}


private function criarTabelaEndereco()
{
   $sql= "CREATE TABLE tb_endereco` (
    `id` int(11) NOT NULL,
    `rua` varchar(45) NOT NULL,
    `bairro` varchar(50) NOT NULL,
    `cep` varchar(8) NOT NULL,
    `cidade_id` int(11) NOT NULL,
    `usuario_id` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
   $this->conexao->exec($sql);
}


private function criarTabelaEstado()
{
   $sql= "CREATE TABLE tb_estado (
    id int(11) NOT NULL,
    nome_estado varchar(45) NOT NULL,
    sigla_estado varchar(2) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
   $this->conexao->exec($sql);
}











}