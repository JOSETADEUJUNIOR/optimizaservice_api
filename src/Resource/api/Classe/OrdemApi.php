<?php

namespace Src\Resource\api\Classe;

use Src\_public\Util;
use Src\Controller\ChamadoController;
use Src\Resource\api\Classe\ApiRequest;
use Src\Controller\UsuarioController;
use Src\Controller\EquipamentoController;
use Src\Controller\ServicoController;
use Src\Controller\ProdutoController;
use Src\Controller\LoteController;
use Src\Controller\CategoriaController;
use Src\VO\UsuarioVO;
use Src\VO\ChamadoVO;
use Src\VO\Lote_insumoVO;
use Src\Controller\ClienteController;
use Src\Controller\OsController;
use Src\Controller\TpServicoController;
use Src\VO\AnxOSVO;
use Src\VO\CategoriaVO;
use Src\VO\ClienteVO;
use Src\VO\EmpresaVO;
use Src\VO\ImagemVO;
use Src\VO\LancamentoVO;
use Src\VO\LancamentoParceladoVO;
use Src\VO\LoteVO;
use Src\VO\OsVO;
use Src\VO\ProdutoOSVO;
use Src\VO\ServicoOSVO;
use Src\VO\ServicoVO;
use Src\VO\TecnicoVO;
use Src\VO\TpServicoVO;

class OrdemApi extends ApiRequest
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

    public function RetornarClientesOs()
    {
        return (new ClienteController)->SelecioneClienteCTRL($this->params['tenant_id']);
    }

    public function RetornarChamados()
    {
        return (new OsController)->RetornarChamadoController($this->params['tenant_id']);
    }

    public function CriarChamados()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ChamadoVO;
            $vo->setCliente_id($this->params['cliente_id']);
            $vo->setDescrciaoProblema($this->params['titulo']);
            $vo->setDataAbertura($this->params['data_evento']);
            $vo->setHora_abertura($this->params['hora_evento']);
            $vo->setObservacao($this->params['texto']);
            $vo->setSchema($this->params['tenant_id']);
            return (new ChamadoController)->AbrirChamadoController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
       
    }




    
    public function RetornarProdutosOs()
    {
        return (new ProdutoController)->RetornarProdutosController($this->params['tenant_id']);
    }

    public function RetornarServicosOs()
    {
        return (new ServicoController)->RetornarServicosController($this->params['tenant_id']);
    }

    public function RetornaAnxOS()
    {
        $vo = new OsVO;
        $vo->setOsSchema($this->params['tenant_id']);
        $vo->setID($this->params['OsID']);
        return (new OsController)->RetornaAnxOS($vo);
    }
    

    public function InserirItemOrdemController(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ProdutoOSVO;
            $vo->setOsID($this->params['OsID']);
            $vo->setOsProdID($this->params['produto_os']);
            $vo->setProdQtd($this->params['qtd_produto_os']);
            $vo->setProdOsSchema($this->params['tenant_id']);
            
            return (new OsController)->InserirItemOrdemController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function InserirServicoOrdemController(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ServicoOSVO;
            $vo->setOsID($this->params['OsID']);
            $vo->setOsServID($this->params['servicos_os']);
            $vo->setServQtd($this->params['qtd_servico_os']);
            $vo->setServOsSchema($this->params['tenant_id']);
            
            return (new OsController)->InserirServicoOrdemController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function InserirAnexoOs(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ImagemVO;
            
            $vo->setImagemEntidadeID($this->params['OsID']);
            $vo->setImagemSchema($this->params['tenant_id']);
            
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivos = $_FILES['imagem'];
                if ($arquivos['name'] != "") {
                    if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                        $ret = 10;
                    } else {
                        $pasta = CAMINHO_PARA_SALVAR_IMG_OS . $vo->getImagemSchema() . '/';
                        @mkdir($pasta);
                        $nomeDoArquivo = $arquivos['name'];
                        $novoNomeDoArquivo = uniqid();
                        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != "pdf" && $extensao != "txt" && $extensao != '') {
                            $ret = 11;
                        } else {
                            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                            $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                            $vo->setImagemLogo($nomeDoArquivo);
                            $vo->setImagemPath($path);
                        }
                    }
                }
            }
            return (new OsController)->InserirAnxOrdemController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
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
                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != "pdf" && $extensao != "txt" && $extensao != '') {
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

    
    
    public function DeletarProdutoOs(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ProdutoOSVO;
            $vo->setProdOsID($this->params['ProdOsID']);
            $vo->setOsProdID($this->params['ProdID']);
            $vo->setProdQtd($this->params['ProdOsQtd']);
            $vo->setProdOsSchema($this->params['tenant_id']);
            
            return (new OsController)->ExcluirItemOSController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    
    public function DeletarServicoOs(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ServicoOSVO;
            $vo->setID($this->params['ServOsID']);
            $vo->setOsServID($this->params['ServID']);
            $vo->setServQtd($this->params['ServOsQtd']);
            $vo->setServOsSchema($this->params['tenant_id']);
            
            return (new OsController)->ExcluirServOSController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function RetornarOS(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setOsSchema($this->params['tenant_id']);
            
            return (new OsController)->RetornarOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function RetornarTpServicoOS(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new TpServicoVO;
            $vo->setTpServSchema($this->params['tenant_id']);
            
            return (new TpServicoController)->RetornarTpServicosController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    

    public function RetornarPagamentosOS(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setOsSchema($this->params['tenant_id']);
            $vo->setID($this->params['OsID']);
            
            return (new OsController)->RetornarPagamentosOSController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    
    public function DetalharOS(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setOsSchema($this->params['tenant_id']);
            $vo->setID($this->params['id_ordem']);
            
            return (new OsController)->DetalharOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function CadastrarOS()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setOsCliID($this->params['cliente_id']);
            $vo->setOsTecID($this->params['os_tecnico']);
            $vo->setDtInicial($this->params['os_data_inicio']);
            $vo->setOsDtFinal($this->params['os_data_fim']);
            $vo->setOsGarantia($this->params['os_garantia']);
            $vo->setOsDescProdServ($this->params['os_descricao']);
            $vo->setOsDefeito($this->params['os_defeito']);
            $vo->setOsObs($this->params['os_obs']);
            $vo->setOsLaudoTec($this->params['os_laudo']);
            $vo->setOsStatus($this->params['os_status']);
            $vo->setOsAgendamento($this->params['os_agenda']);
            $vo->setOsTpServico($this->params['os_tp_servico']);
            $vo->setOsSchema($this->params['tenant_id']);
            
            return (new OsController)->CadastrarOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function EditarOS()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setOsCliID($this->params['cliente_id']);
            $vo->setOsTecID($this->params['os_tecnico']);
            $vo->setDtInicial($this->params['os_data_inicio']);
            $vo->setOsDtFinal($this->params['os_data_fim']);
            $vo->setOsGarantia($this->params['os_garantia']);
            $vo->setOsDescProdServ($this->params['os_descricao']);
            $vo->setOsDefeito($this->params['os_defeito']);
            $vo->setOsObs($this->params['os_obs']);
            $vo->setOsLaudoTec($this->params['os_laudo']);
            $vo->setOsStatus($this->params['os_status']);
            $vo->setOsTpServico($this->params['os_tp_servico']);
            $vo->setID($this->params['OsID']);
            $vo->setOsSchema($this->params['tenant_id']);
            
            return (new OsController)->EditarOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    
    public function realizarPagamentoOS()
    {
        
        if (Util::AuthenticationTokenAccess()) {
            $vo = new LancamentoVO;
           
            $vo->setDataPagamento($this->params['data_pagamento']);
            $vo->setDataVencimento($this->params['data_vencimento']);
            $vo->setValor($this->params['valor_pago']);
            $vo->setDesconto($this->params['valor_desconto']);
            $vo->setLancValorTotal($this->params['valor_total']);
            $vo->setTipo($this->params['tipo_pagamento']);
            $vo->setLancQtdParcela($this->params['qtd_parcela']);
            $vo->setDescricao($this->params['observacao']);
            $vo->setLancSchema($this->params['tenant_id']);
            $vo->setLancEntidadeID($this->params['OsID']);
            
            return (new OsController)->RealizarPagamentoOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    

    

    public function RetornarUsuarios()
    {
        return (new UsuarioController)->RetornarUsuarios($this->params['tenant_id']);
    }

   
    public function FiltrarOS()
    {
        return (new OsController)->FiltrarOSController($this->params['busca_nome'], $this->params['busca_data_os'], $this->params['tenant_id'], $this->params['OsID'], $this->params['busca_tipo_servico']);
    }
    public function FiltrarOSDetalhada()
    {
        return (new OsController)->FiltrarOSDetalhadaController($this->params['tenant_id'], $this->params['OsID']);
    }


    

    

    public function DetalharMeusDados()
    {
        if (Util::AuthenticationTokenAccess()) {
            if (empty($this->params['id_user'])) {
                return 0;
            }
            $vo = new UsuarioVO;
            $vo->setId($this->params['id_user']);
            $vo->setUserSchema($this->params['tenant_id']);
           
            return $this->ctrl_user->DetalharUsuarioController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    
    public function CarregarProdutosOs(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setID($this->params['OsID']);
            $vo->setOsSchema($this->params['tenant_id']);
            
            return (new OsController)->CarregarProdutosOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    
    public function CarregarServicosOs(){
        if (Util::AuthenticationTokenAccess()) {
            $vo = new OsVO;
            $vo->setID($this->params['OsID']);
            $vo->setOsSchema($this->params['tenant_id']);
            
            return (new OsController)->CarregarServicosOsController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function AtenderChamado()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ChamadoVO;

            $vo->setId($this->params['id_chamado']);
            $vo->setTecnico_atendimento($this->params['id_tec']);

            return (new ChamadoController)->AtenderChamadoController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }


}
