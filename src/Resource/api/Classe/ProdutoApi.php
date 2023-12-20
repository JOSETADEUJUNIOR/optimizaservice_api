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
use Src\Controller\ProdutoController;
use Src\VO\CategoriaVO;
use Src\VO\ClienteVO;
use Src\VO\EmpresaVO;
use Src\VO\LoteVO;
use Src\VO\ProdutoVO;
use Src\VO\TecnicoVO;

class ProdutoApi extends ApiRequest
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

 
    public function RetornarProdutos()
    {
        return (new ProdutoController)->RetornarProdutosController($this->params['tenant_id']);
    }

    public function BuscarProdutoVenda()
    {
        return (new ProdutoController)->BuscarProdutoVendaController($this->params['tenant_id']);
    }

    public function RetornarProdutosPorCategoria()
    {
        return (new ProdutoController)->RetornarProdutosPorCategoriaController($this->params['tenant_id'], $this->params['categoria_id']);
    }
    public function DetalharProduto()
    {
        return (new ProdutoController)->DetalharProdutoController($this->params['tenant_id'], $this->params['id_produto']);
    }
    public function CadastrarProduto()
    {
        
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ProdutoVO;

            $vo->setProdDescricao($this->params['nome_produto']);
            $vo->setCategoria_id($this->params['categoria_id']);
            $vo->setSku($this->params['SKU']);
            $vo->setProdCodBarra($this->params['cod_barra']);
            $vo->setProdEstoqueMin($this->params['qtd_minima']);
            $vo->setProdEstoque($this->params['qtd']);
            $vo->setProdValorCompra($this->params['valor_compra']);
            $vo->setProdValorVenda($this->params['valor_venda']);
            $vo->setProdSchema($this->params['tenant_id']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_PRODUTO . $vo->getProdSchema() . '/';
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
                            return (new ProdutoController)->CadastrarProdutoController($vo);
                        }
                    }
                }
            }
        } else {
            return NAO_AUTORIZADO;
        }
    }






    public function EditarProduto()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ProdutoVO;
            $vo->setProdDescricao($this->params['nome_produto']);
            $vo->setCategoria_id($this->params['categoria_id']);
            $vo->setSku($this->params['SKU']);
            $vo->setProdCodBarra($this->params['cod_barra']);
            $vo->setProdEstoqueMin($this->params['qtd_minima']);
            $vo->setProdEstoque($this->params['qtd']);
            $vo->setProdValorCompra($this->params['valor_compra']);
            $vo->setProdValorVenda($this->params['valor_venda']);
            $vo->setProdSchema($this->params['tenant_id']);
            $vo->setProdID($this->params['produto_id']);
            
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_PRODUTO . $vo->getProdSchema() . '/';
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setImagemEntidadeID($this->params['produto_id']);
                            $vo->setImagemLogo($nomeDoArquivo);
                            $vo->setImagemPath($path);
                            
                        }
                    }
                }
            } 
            return (new ProdutoController)->EditarProdutoController($vo);


        } else {
            return NAO_AUTORIZADO;
        }
    }


    public function RetornarUsuarios()
    {
        return (new UsuarioController)->RetornarUsuarios($this->params['tenant_id']);
    }

   
    public function FiltrarClientes()
    {
        return (new ClienteController)->FiltrarClienteCTRL($this->params['busca_nome'], $this->params['busca_cidade'], $this->params['tenant_id']);
    }

    public function DetalharMeusDados()
    {
        if (Util::AuthenticationTokenAccess()) {
            if (empty($this->params['id_user'])) {
                return 0;
            }
            return $this->ctrl_user->DetalharUsuarioController($this->params['id_user'], $this->params['tenant_id']);
        } else {
            return NAO_AUTORIZADO;
        }
    }


}
