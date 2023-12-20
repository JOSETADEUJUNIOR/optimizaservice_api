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

class EmpresaApi extends ApiRequest
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

    public function RetornarEmpresas()
    {
        return (new UsuarioController)->RetornarEmpresasCTRL($this->params['tenant_id']);
    }
    public function DetalharEmpresa()
    {
        return (new UsuarioController)->RetornarDadosCadastraisController($this->params['permission_user'], $this->params['tenant_id'], $this->params['empresa_id']);
    }
    public function AlterarEmpresa()
    {

        $vo = new EmpresaVO;
        $vo->setNomeEmpresa($this->params['EmpRazao']);
        $vo->setCNPJ($this->params['EmpCnpj']);
        $vo->setID($this->params['EmpID']);
        $vo->setDtCadastro($this->params['EmpDtCadastro']);
        $vo->setEmpDtVencimento($this->params['EmpDtVencimento']);
        $vo->setCep($this->params['cep']);
        $vo->setRua($this->params['rua']);
        $vo->setNomeCidade($this->params['cidade']);
        $vo->setNumero($this->params['numero']);
        $vo->setPlano($this->params['EmpPlano']);
        $vo->setStatus($this->params['EmpStatus']);
        $vo->setUserSchema($this->params['tenant_id']);
        return (new UsuarioController)->AlterarEmpresa($vo);
    }

    public function AlterarImagemEmp()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new EmpresaVO;

            $vo->setID($this->params['id_emp']);
            $vo->setSchema($this->params['tenant_id']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_EMP . $vo->getSchema() . '/';
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setEmpLogo($nomeDoArquivo);
                            $vo->setLogoPath($path);
                            return (new UsuarioController)->AlterarImagemEmpresaController($vo);
                        }
                    }
                }
            }
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function CadastrarUsuario()
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

        return (new UsuarioController)->CadastrarUsuarioController($this->params['nome_empresa'], $this->params['plano'], $vo);
    }

}
