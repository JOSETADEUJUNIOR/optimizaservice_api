<?php

namespace Src\Resource\api\Classe;

use Src\_public\Util;
use Src\Controller\ChamadoController;
use Src\Resource\api\Classe\ApiRequest;
use Src\Controller\UsuarioController;
use Src\Controller\EquipamentoController;
use Src\Controller\LoteController;
use Src\Controller\CategoriaController;
use Src\VO\UsuarioVO;
use Src\VO\ChamadoVO;
use Src\VO\Lote_insumoVO;
use Src\Controller\ClienteController;
use Src\VO\CategoriaVO;
use Src\VO\ClienteVO;
use Src\VO\EmpresaVO;
use Src\VO\LoteVO;
use Src\VO\TecnicoVO;

class UsuarioApi extends ApiRequest
{

    private $ctrl_user;
    private $params;

    public function AddParameters($p)
    {
        $this->params = $p;
    }

    public function CheckEndPoint($endpoint)
    {
        return method_exists($this, $endpoint);
    }

    public function __construct()
    {
        $this->ctrl_user = new UsuarioController;
    }

    public function Autenticar()
    {
        return (new UsuarioController)->ValidarAcessoTecnicoAPI($this->params['email'], $this->params['senha']);
    }

   
    public function RetornarUsuarios()
    {
        return (new UsuarioController)->RetornarUsuarios($this->params['tenant_id']);
    }

    public function DetalharMeusDados()
    {
        
        if (Util::AuthenticationTokenAccess()) {
            if (empty($this->params['id_user'])) {
                return 0;
            }
            $vo = new UsuarioVO;
            $vo->setUserSchema($this->params['tenant_id']);
            $vo->setId($this->params['id_user']);
            return $this->ctrl_user->DetalharUsuarioController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function AlterarMeusDados()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new TecnicoVO;

            $vo->setId($this->params['id_user']);
            $vo->setNome($this->params['nome']);
            $vo->setLogin($this->params['login']);
            $vo->setTelefone($this->params['telefone']);
            $vo->setIdEndereco($this->params['id_end']);
            $vo->setRua($this->params['rua']);
            $vo->setBairro($this->params['bairro']);
            $vo->setCep($this->params['cep']);
            $vo->setEstado($this->params['estado']);
            $vo->setSenha($this->params['senha']);
            $vo->setNomeCidade($this->params['cidade']);
            $vo->setTipo(PERFIL_TECNICO);
            $vo->setNomeEmpresa($this->params['empresa']);

            return (new UsuarioController)->EditarUsuarioAPIController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }


    public function AlterarImagemUser()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new TecnicoVO;

            $vo->setId($this->params['id_user']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_USER;
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setUserLogo($nomeDoArquivo);
                            $vo->setUserLogoPath($path);
                            return (new UsuarioController)->AlterarImagemUsuarioController($vo);
                        }
                    }
                }
            }
        } else {
            return NAO_AUTORIZADO;
        }
    }

   
    public function CadastrarUsuarioAPI()
    {


        $vo = new UsuarioVO;
        $vo->setTipo($this->params['tipo']);
        $vo->setNome($this->params['nome']);
        $vo->setLogin($this->params['email']);
        $vo->setTelefone($this->params['telefone']);
        $vo->setCep($this->params['cep']);
        $vo->setRua($this->params['endereco']);
        $vo->setBairro($this->params['bairro']);
        $vo->setNomeCidade($this->params['cidade']);
        $vo->setEstado($this->params['estado']);
        $vo->setEmpID($this->params['empresa_id']);
        $vo->setUserSchema($this->params['tenant_id']);
        return (new UsuarioController)->CadastrarUsuarioAPIController($vo);
    }

    public function EditarUsuarioAPI()
    {


        $vo = new UsuarioVO;
        $vo->setTipo($this->params['tipo']);
        $vo->setNome($this->params['nome']);
        $vo->setLogin($this->params['email']);
        $vo->setTelefone($this->params['telefone']);
        $vo->setCep($this->params['cep']);
        $vo->setIdEndereco($this->params['end_id']);
        $vo->setId($this->params['user_id']);
        $vo->setRua($this->params['endereco']);
        $vo->setBairro($this->params['bairro']);
        $vo->setNomeCidade($this->params['cidade']);
        $vo->setEstado($this->params['estado']);
        $vo->setEmpID($this->params['empresa_id']);
        $vo->setUserSchema($this->params['tenant_id']);
        return (new UsuarioController)->EditarUsuarioAPIController($vo);
    }

   
    public function VerificarSenhaAtual()
    {

        return (new UsuarioController)->ValidarSenhaAtual($this->params['id'], $this->params['senha']);
    }

 
    public function DetalharUsuario()
    {
        $vo = new UsuarioVO();
        $vo->setId($this->params['id_usuario']);
        $vo->setUserSchema($this->params['tenant_id']);
        return (new UsuarioController)->DetalharUsuarioController($vo);
    }
    public function AtualizarSenha()
    {
        $vo = new UsuarioVO;

        $vo->setId($this->params['id']);
        $vo->setSenha($this->params['senha']);
        return (new UsuarioController)->AtualizarSenhaAtual($vo, $this->params['repetir_senha']);
    }
}
