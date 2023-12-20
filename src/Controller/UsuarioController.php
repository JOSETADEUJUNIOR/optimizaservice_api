<?php

namespace Src\Controller;

use Src\Model\UsuarioDAO;
use Src\_public\Util;
use Src\Model\EmpresaDAO;
use Src\VO\UsuarioVO;
use Src\VO\EmpresaVO;

class UsuarioController
{
    private $dao;
    private $schemaDAO;
    public function __construct()
    {
        $this->dao = new UsuarioDAO;
        $this->schemaDAO = new EmpresaDAO;
    }

   
    public function RetornarDadosCadastraisController($permissao, $tenant_id, $empresa_id)
    {
        
        return $this->dao->DetalharEmpresaDAO($permissao, $tenant_id, $empresa_id);
    }



    public function CadastrarPermissao($telas)
    {
        var_dump($telas);
    }


    public function CadastrarUsuarioController($nome_empresa, $plano, UsuarioVO $vo)
    {

        $vo->setStatus(STATUS_ATIVO);
        $vo->setSenha(Util::CriarSenha($vo->getLogin()));
        
        $vo->setFuncao(CADASTRO_USUARIO);
        $vo->setIdLogado($vo->getId());
        $vo->setEmpID($nome_empresa);
        $ret= $this->dao->CadastrarSchema($vo, $nome_empresa, $plano);
        $schema = explode("_",$ret);#para pegar id da empresa
        
        $tenant_id = $ret;
        $retUserMaster= $this->dao->CadastrarUsuarioMasterDAO(SCHEMA_PRINCIPAL, $schema[1], $vo, $tenant_id);
    
        if ($retUserMaster) {
            $tenant_id = $ret;
           return $this->dao->CadastrarUsuarioAdminDAO($ret, $schema[1], $vo, $tenant_id);
        }else {
            return -1;
        }
  
    }

    public function CadastrarUsuarioAPIController($vo)
    {

        $vo->setStatus(STATUS_ATIVO);
        $vo->setSenha(Util::CriarSenha($vo->getLogin()));
        
        $vo->setFuncao(CADASTRO_USUARIO);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->CadastrarUsuarioAPIDAO($vo);
        
    }





 public function AlterarImagemUsuarioController($vo)
    {
        $vo->setStatus(STATUS_ATIVO);
        $vo->setSenha(Util::CriarSenha($vo->getLogin()));

        $vo->setFuncao(ALTERA_USUARIO);
        $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());

        return $this->dao->AlterarImagemUsuarioDAO($vo);
    }

    public function AlterarImagemEmpresaController($vo)
    {
        $vo->setStatus(STATUS_ATIVO);
        $vo->setFuncao(ALTERA_USUARIO);
        $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());

        return $this->dao->AlterarImagemEmpresaDAO($vo);
    }

public function AlterarEmpresa($vo)
{
    $vo->setFuncao(ALTERA_EMPRESA);
    $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());
    return $this->dao->AlterarEmpresaDAO($vo);
}

    

    public function EditarUsuarioAPIController($vo)
    {
        if (empty($vo->getNome()) || empty($vo->getTelefone()) || empty($vo->getRua()) || empty($vo->getCep()) || empty($vo->getNomeCidade())) {
            return 0;
        }

        $vo->setStatus(STATUS_ATIVO);
        if ($vo->getSenha()!="") {
            $vo->setSenha(Util::RetornarSenhaCriptografada($vo->getSenha()));
        }
        $vo->setFuncao(ALTERA_USUARIO);
        $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());
        return $this->dao->AlterarUsuarioDAO($vo);
    }
    public function AlterarUsuarioController($vo)
    {
        if (empty($vo->getNome()) || empty($vo->getTelefone()) || empty($vo->getRua()) || empty($vo->getCep()) || empty($vo->getNomeCidade())) {
            return 0;
        }

        $vo->setStatus(STATUS_ATIVO);
        if ($vo->getSenha()!="") {
            $vo->setSenha(Util::RetornarSenhaCriptografada($vo->getSenha()));
        }
        $vo->setFuncao(ALTERA_USUARIO);
        $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());
        return $this->dao->AlterarUsuarioDAO($vo);
    }

    

    public function FiltrarPessoaController($nome, $filtro)
    {
        return $this->dao->FiltrarPessoaDAO($nome, $filtro);
    }
    public function FiltrarUsuariosController()
    {
        return $this->dao->RetornarUsuariosDAO();
    }

    public function RetornarUsuarios($tenant_id)
    {
        return $this->dao->RetornarUsuariosDAO($tenant_id);
    }
    public function DetalharUsuarioController($vo)
    {
        return $this->dao->DetalharUsuarioDAO($vo);
    }
    public function DetalharEmpresaController($usuario_id, $schema)
    {
        return $this->dao->DetalharEmpresaDAO($usuario_id, $schema);
    }



    
    public function RetornarEmpresasCTRL($tenant_id)
    {
        return $this->dao->RetornarEmpresasDAO($tenant_id);
    }

    
    



    public function ValidarSenhaAdmin($senha)
    {
            $id = Util::CodigoLogado();
            if (empty($id) || empty($senha)) {
                return 0;
            }
            $user_senha = $this->dao->RecuperarSenhaAtual($id);

            if (password_verify($senha, $user_senha['senha'])) {
                return 1;
            } else {
                return -1;
            }
       
    }

    public function ValidarSenhaAtual($id, $senha)
    {
        if (Util::AuthenticationTokenAccess()) {
            if (empty($id) || empty($senha)) {
                return 0;
            }
            $user_senha = $this->dao->RecuperarSenhaAtual($id);

            if (password_verify($senha, $user_senha['senha'])) {
                return 1;
            } else {
                return -1;
            }
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function ValidarLoginController($login, $senha)
    {
        $usuario = '';
        if (empty($login) || empty($senha)) {
            return 0;
        }

        $usuario = $this->dao->ValidarLoginDAO($login, STATUS_ATIVO);

        if (empty($usuario)) {
            return -4;
        }
        if ($usuario['tipo'] == PERFIL_FUNCIONARIO || $usuario['tipo'] == PERFIL_TECNICO) {

            return -10;
        }

        # testar variavel para ver se encontrou o usuario com login digitado.
        #testando a senha digitada/criptografada
        if (!Util::ValidarSenhaBanco($senha, $usuario['senha'])) {
            return -3;
        } else {
            Util::CriarSessao($usuario['id'], $usuario['nome'], $usuario['UserEmpID']);
            $this->dao->CriarLogUsuario();
            Util::chamarPagina('dashboard.php');exit;
        }
    }

    public function ValidarAcessoFuncionarioAPI($email, $senha)
    {
        if (empty($email) || empty($senha)) {
            return 0;
        }

        $usuario = $this->dao->ValidarAcesso($email, STATUS_ATIVO, PERFIL_FUNCIONARIO);
        if ($usuario == '') {
            return -4;
        } else {
            if (password_verify($senha, $usuario['senha'])) {

                $dados_usuario = [
                    'funcionario_id'    => $usuario['id'],
                    'nome'              => $usuario['nome'],
                    'setor_id'          => $usuario['setor_id'],
                    'tipo'              => $usuario['tipo'],
                    'empresa_id'        => $usuario['UserEmpID'],
                    'empresa_nome'      => $usuario['EmpNome'],
                    'empresa_endereco'  => $usuario['EmpEnd'],
                    'empresa_cnpj'      => $usuario['EmpCNPJ'],
                    'EmpLogoPath'       => $usuario['EmpLogoPath']
                ];

                $token = Util::CreateTokenAuthentication($dados_usuario);
                return $token;
            } else {
                return -3;
            }
        }
    }

    public function ValidarAcessoTecnicoAPI($email, $senha)
    {
        if (empty($email) || empty($senha)) {
            return 0;
        }
        $usuario = $this->dao->ValidarAcesso($email, STATUS_ATIVO);
        if ($usuario == '') {
            return -4;
        } else {
            if (password_verify($senha, $usuario['senha'])) {

                $dados_usuario = [
                    'tecnico_id'        => $usuario['id'],
                    'nome'              => $usuario['nome'],
                    'empresa_id'        => $usuario['UserEmpID'],
                    'tenant_id'         => $usuario['tenant_id'],
                    'permissao_user'    => $usuario['tipo'],
                    'empresa_id'        => $usuario['UserEmpID'],
                    'empresa_nome'      => $usuario['EmpNome'],
                    'empresa_endereco'  => $usuario['EmpEnd'],
                    'empresa_cnpj'      => $usuario['EmpCNPJ'],
                    'EmpLogoPath'       => $usuario['EmpLogoPath']
                ];

                $token = Util::CreateTokenAuthentication($dados_usuario);
                return $token;
            } else {
                return -3;
            }
        }
    }

    public function AtualizarSenhaAtual(UsuarioVO $vo, $repetir_senha)
    {
        if (empty($vo->getSenha()) || empty($vo->getId())) {
            return 0;
        }
        if (strlen($vo->getSenha()) < 6) {
            return -2;
        }
        if ($vo->getSenha() != $repetir_senha) {
            return -4;
        }

        $vo->setSenha(Util::RetornarSenhaCriptografada($vo->getSenha()));

        $vo->setFuncao(MUDAR_SENHA);
        $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());


        return $this->dao->AtualizarSenhaAtual($vo);
    }

    public function DetalharMeusDadosController(){
        return $this->dao->DetalharMeusDadosDAO(Util::CodigoLogado());
    }
























}
