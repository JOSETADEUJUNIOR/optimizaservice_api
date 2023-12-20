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

class CategoriaApi extends ApiRequest
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


    public function RetornarCategorias()
    {
        return (new CategoriaController)->RetornarCategoriaController($this->params['tenant_id']);
    }

    public function FiltrarCategoria()
    {
        return (new CategoriaController)->FiltrarCategoriaController($this->params['busca_nome'], $this->params['busca_cod'], $this->params['tenant_id']);
    }

    public function CadastrarCategoria()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new CategoriaVO;

            $vo->setNomeCategoria($this->params['nome_categoria']);
            $vo->setDescricaoCategoria($this->params['descricao_categoria']);
            $vo->setCod($this->params['cod_categoria']);
            $vo->setSchema($this->params['tenant_id']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_CAT . $vo->getSchema() . '/';
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setImagemLogo($nomeDoArquivo);
                            $vo->setImagemPath($path);
                            return (new CategoriaController)->CadastrarCategoriaController($vo);
                        }
                    }
                }
            }
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function EditarCategoria()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new CategoriaVO();
            $vo->setNomeCategoria($this->params['nome_categoria']);
            $vo->setCod($this->params['cod_categoria']);
            $vo->setDescricaoCategoria($this->params['descricao_categoria']);
            $vo->setID($this->params['categoria_id']);
            $vo->setSchema($this->params['tenant_id']);
            $vo->setImagemId($this->params['imagem_id']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_CAT . $vo->getSchema() . '/';
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setImagemEntidadeID($this->params['categoria_id']);
                            $vo->setImagemLogo($nomeDoArquivo);
                            $vo->setImagemPath($path);
                        }
                    }
                }
            } 
            return (new CategoriaController)->EditarCategoriaController($vo);


        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function DetalharCategoria()
    {
        return (new CategoriaController)->DetalharCategoriaController($this->params['tenant_id'], $this->params['id_categoria']);
    }

}
