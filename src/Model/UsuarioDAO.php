<?php

namespace Src\model;

use Exception;
use Src\_public\Util;
use Src\Model\Conexao;
use Src\Model\SQL\UsuarioSQL;
use Src\Model\SQL\EnderecoSQL;
use Src\Model\SQL\ImagemSQL;
use Src\VO\EmpresaVO;
use Src\VO\UsuarioVO;

class usuarioDAO extends Conexao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

   
    public function AlterarEmpresaDAO(EmpresaVO $vo): int
    {
            $sql = $this->conexao->prepare(UsuarioSQL::AlterarEmpresaSQL());
            $i = 1;
            $sql->bindValue($i++, $vo->getNomeEmpresa());
            $sql->bindValue($i++, $vo->getCNPJ());
            $sql->bindValue($i++, Util::formatarDataParaBancoSql($vo->getDtCadastro()));
            $sql->bindValue($i++, Util::formatarDataParaBancoSql($vo->getEmpDtVencimento()));
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $vo->getEndereco());
            $sql->bindValue($i++, $vo->getCidade());
            $sql->bindValue($i++, $vo->getNumero());
            $sql->bindValue($i++, $vo->getPlano());
            $sql->bindValue($i++, $vo->getStatus());
            $sql->bindValue($i++, $vo->getID());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }



    public function VerificarEmailDuplicadoDAO($id, $email)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::SelecionarEmailDuplicado($id));
        $i = 1;
        $sql->bindValue($i++, $email);
        if (!empty($id)) {
            $sql->bindValue($i++, $id);
        }
        $sql->execute();
        return  $sql->fetch(\PDO::FETCH_ASSOC)['login'] == '' ? true : false;
    }

    public function FiltrarPessoaDAO($nome, $filtro)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::FILTRAR_USUARIO($nome, $filtro));
        $sql->bindValue(1, Util::EmpresaLogado());
        if (!empty($nome)) {
            $sql->bindValue(2, '%' . $nome . '%');
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    /* public function CadastrarPermissaoDAO(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::CadastrarPermissaoSQL());
        $sql->bindValue(1, $vo->getAdmin());
        $sql->bindValue(2, $vo->get());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }






 */







    public function RetornarUsuariosDAO(UsuarioVO $vo)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::RETORNAR_USUARIOS($vo->getUserSchema()));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RecuperarSenhaAtual($id)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::RECUPERARSENHAATUAL());
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function AtualizarSenhaAtual(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::ATUALIZAR_SENHA());
        $sql->bindValue(1, $vo->getSenha());
        $sql->bindValue(2, $vo->getId());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function ValidarLoginDAO($login, $status)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::BUSCAR_DADOS_ACESSO());
        $sql->bindValue(1, $login);
        $sql->bindValue(2, $status);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function CriarLogUsuario()
    {
        $sql = $this->conexao->prepare(UsuarioSQL::CRIAR_LOG_USUARIO_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::CodigoLogado());
        $sql->bindValue($i++, Util::DataAtualBd());
        $sql->bindValue($i++, Util::HoraAtual());
        $sql->bindValue($i++, Util::LogIPUsuario());
        $sql->execute();
        return 1;
    }

    public function ValidarAcesso($login, $status)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::VALIDAR_ACESSO(SCHEMA_PRINCIPAL));
        $sql->bindValue(1, $login);
        $sql->bindValue(2, $status);
        $sql->execute();
        $usuario = $sql->fetch(\PDO::FETCH_ASSOC);

        if ($usuario) {
            $schema = $usuario['tenant_id'];
            $sql =  $this->conexao->prepare(UsuarioSQL::VALIDAR_ACESSO_LOCAL($schema));
            $sql->bindValue(1, $login);
            $sql->bindValue(2, $status);
            $sql->execute();
            return $sql->fetch(\PDO::FETCH_ASSOC);
        }
    }




    public function MudarStatusDAO(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::MUDAR_STATUS());
        $sql->bindValue(1, $vo->getStatus());
        $sql->bindValue(2, $vo->getId());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function CadastrarSchema($vo, $nome_empresa, $plano)
    {

        try {
            $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_EMPRESA(SCHEMA_PRINCIPAL));
            $sql->bindValue(1, $nome_empresa);
            $dataVencimento = date("Y-m-d");
            if ($plano == "1") {
                // Se o plano for 1 (um mês)
                $dataVencimento = date('Y-m-d', strtotime($dataVencimento . ' +1 month'));
            } elseif ($plano == "2") {
                // Se o plano for 2 (três meses)
                $dataVencimento = date('Y-m-d', strtotime($dataVencimento . ' +3 months'));
            } elseif ($plano == "3") {
                // Se o plano for 3 (um ano)
                $dataVencimento = date('Y-m-d', strtotime($dataVencimento . ' +1 year'));
            }
            
            $sql->bindValue(2, $plano);
            $sql->bindValue(3, Util::DataAtualBd());
            $sql->bindValue(4, $dataVencimento);
            $sql->execute();
            $idEmpresa = $this->conexao->lastInsertId();
            $schemaNome = 'pizzar15_schema_' . $idEmpresa;

            // Executar a criação do schema e tabelas dentro da mesma transação
            $sqlCriarSchema = $this->conexao->prepare(UsuarioSQL::CRIAR_SCHEMA($schemaNome));
            $sqlCriarSchema->execute();
            $sqlCriarSchema->closeCursor();

            $sqlCriarTabelas = $this->conexao->prepare(UsuarioSQL::CRIAR_TABELA_USUARIO($schemaNome));
            $sqlCriarTabelas->execute();
            $sqlCriarTabelas->closeCursor();

            $resultados = $sqlCriarTabelas->fetchAll();
            return $schemaNome;
        } catch (\PDOException $e) {
            // Se ocorrer um erro, desfazer a transação
            error_log('Erro de banco de dados: ' . $e->getMessage());
            // Trate o erro aqui, log ou retorne uma mensagem de erro
            // ...
            return 0;
        }
    }




    public function CadastrarUsuarioMasterDAO($schema, $idEmpresa, $vo, $tenant_id)
    {

        $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_USUARIO_MASTER($schema));
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        $sql->bindValue($i++, $vo->getSenha());
        $sql->bindValue($i++, $vo->getStatus());
        $sql->bindValue($i++, $vo->getTelefone());
        if ($vo->getEmpID() > 0) {
            $sql->bindValue($i++, $vo->getEmpID());
        } else {
            $sql->bindValue($i++, $idEmpresa);
        }
        $sql->bindValue($i++, $tenant_id);

        try {
            $sql->execute();
            # Recupera o ID recem cadastrado
            $idUser = $this->conexao->lastInsertId();
            # Processo de cadastrar a cidade e estado
            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE($schema));
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade
                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO($schema));
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO($schema));
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE($schema));
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ENDERECO($schema));
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $idUser);
            $sql->execute();
            # Verificar o tipo de usuario 1-Administrador, 2-Funcionário ou 3-Tecnico
            return 1;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return -1;
        }
    }

    public function CadastrarUsuarioAdminDAO($schema, $idEmpresa, $vo, $tenant_id)
    {

        $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_USUARIO_MASTER($schema));
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        $sql->bindValue($i++, $vo->getSenha());
        $sql->bindValue($i++, $vo->getStatus());
        $sql->bindValue($i++, $vo->getTelefone());
        if ($vo->getEmpID() > 0) {
            $sql->bindValue($i++, $vo->getEmpID());
        } else {
            $sql->bindValue($i++, $idEmpresa);
        }
        $sql->bindValue($i++, $tenant_id);

        try {
            $sql->execute();
            # Recupera o ID recem cadastrado
            $idUser = $this->conexao->lastInsertId();
            # Processo de cadastrar a cidade e estado
            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE($schema));
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade
                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO($schema));
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO($schema));
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE($schema));
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ENDERECO($schema));
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $idUser);
            $sql->execute();
          
            # Verificar o tipo de permissao 
          
            $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_PERMISSAO($schema));
            $i = 1;
            $sql->bindValue($i++, $idUser);
            $sql->bindValue($i++, $vo->getTipo());
            $sql->execute();
                
            

            return 1;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return -1;
        }
    }


    public function CadastrarUsuarioAPIDAO(UsuarioVO $vo)
    {

        $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_USUARIO_MASTER($vo->getUserSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        $sql->bindValue($i++, $vo->getSenha());
        $sql->bindValue($i++, $vo->getStatus());
        $sql->bindValue($i++, $vo->getTelefone());
        $sql->bindValue($i++, $vo->getEmpID());
        $sql->bindValue($i++, $vo->getUserSchema());

        try {
            $sql->execute();
            # Recupera o ID recem cadastrado
            $idUser = $this->conexao->lastInsertId();
            # Processo de cadastrar a cidade e estado
            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE($vo->getUserSchema()));
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade
                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO($vo->getUserSchema()));
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO($vo->getUserSchema()));
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE($vo->getUserSchema()));
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ENDERECO($vo->getUserSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $idUser);
            $sql->execute();
          
            # Verificar o tipo de permissao 
          
            $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_PERMISSAO($vo->getUserSchema()));
            $i = 1;
            $sql->bindValue($i++, $idUser);
            $sql->bindValue($i++, $vo->getTipo());
            $sql->execute();
                
            

            return 1;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return -1;
        }
    }

    public function AlterarImagemUsuarioDAO(UsuarioVO $vo)
    {

        $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getUserSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getImagemLogo());
        $sql->bindValue($i++, $vo->getImagemPath());
        $sql->bindValue($i++, $vo->getId());
        $sql->bindValue($i++, 'user');
        $sql->execute();
        return 1;
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function AlterarImagemEmpresaDAO(EmpresaVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_IMAGEM_EMPRESA($vo->getSchema()));
        $sql->bindValue(1, $vo->getEmpLogo());
        $sql->bindValue(2, $vo->getLogoPath());
        $sql->bindValue(3, $vo->getID());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function AlterarUsuarioDAO(UsuarioVO $vo)
    {
        # Cadastra usuario
        $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_USUARIO($vo->getUserSchema(),$vo->getSenha()));
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        if ($vo->getSenha() != "") {
            $sql->bindValue($i++, $vo->getSenha());
        }
        $sql->bindValue($i++, $vo->getTelefone());
        $sql->bindValue($i++, $vo->getId());

        
        try {
            $sql->execute();

            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE($vo->getUserSchema()));
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade

                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO($vo->getUserSchema()));
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO($vo->getUserSchema()));
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE($vo->getUserSchema()));
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::ALTERAR_ENDERECO($vo->getUserSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $vo->getidEndereco());
            $sql->execute();
            # Verificar o tipo de usuario 1-Administrador, 2-Funcionário ou 3-Tecnico
            
             # Verificar o tipo de permissao 
          
             $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_PERMISSAO($vo->getUserSchema()));
             $i = 1;
             $sql->bindValue($i++, $vo->getId());
             $sql->bindValue($i++, $vo->getTipo());
             $sql->execute();

            
            return 1;
        } catch (Exception $ex) {
           
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function DetalharUsuarioDAO(UsuarioVO $vo)
    {   $sql = $this->conexao->prepare(UsuarioSQL::DETALHAR_USUARIO($vo->getUserSchema()));
        $sql->bindValue(1, $vo->getId());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

 

    public function DetalharEmpresaDAO($permissao, $tenant_id, $empresa_id)
    {
        
        
            $sql = $this->conexao->prepare(UsuarioSQL::RetornarDadosCadastrais($tenant_id));
            $sql->bindValue(1, $empresa_id);
            $sql->execute();
            return $sql->fetch(\PDO::FETCH_ASSOC);
      
    }

    public function RetornarEmpresasDAO($tenant_id)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::RETORNAR_EMPRESAS($tenant_id));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }



    

    public function DetalharMeusDadosDAO($idUser)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::DETALHAR_MEUS_DADOS_SQL());
        $i = 1;
        $sql->bindValue($i++, $idUser);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}
