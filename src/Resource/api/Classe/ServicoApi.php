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
use Src\Controller\ServicoController;
use Src\VO\CategoriaVO;
use Src\VO\ClienteVO;
use Src\VO\EmpresaVO;
use Src\VO\LoteVO;
use Src\VO\ProdutoVO;
use Src\VO\ServicoVO;
use Src\VO\TecnicoVO;

class ServicoApi extends ApiRequest
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

 
    public function RetornarServicos()
    {
        return (new ServicoController)->RetornarServicosController($this->params['tenant_id']);
    }

    public function DetalharServico()

    {
        return (new ServicoController)->DetalharServicoController($this->params['tenant_id'], $this->params['servico_id']);
    }
    public function CadastrarServico()
    {
        
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ServicoVO;
            $vo->setServNome($this->params['nome_servico']);
            $vo->setServValor($this->params['valor_servico']);
            $vo->setServDescricao($this->params['descricao_servico']);
            $vo->setServSchema($this->params['tenant_id']);
            return (new ServicoController)->CadastrarServicoController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function EditarServico()
    {
        if (Util::AuthenticationTokenAccess()) {
            
            $vo = new ServicoVO;
            $vo->setServNome($this->params['nome_servico']);
            $vo->setServValor($this->params['valor_servico']);
            $vo->setServDescricao($this->params['descricao_servico']);
            $vo->setServSchema($this->params['tenant_id']);
            $vo->setServStatus($this->params['status_servico']);
            $vo->setServID($this->params['servico_id']);
            $vo->setServUserID($this->params['user_id']);
            return (new ServicoController)->EditarServicoController($vo);

        } else {
            return NAO_AUTORIZADO;
        }
    }


  
    public function FiltrarServico()
    {
        return (new ServicoController)->FiltrarServicoController($this->params['busca_nome'], $this->params['busca_valor'], $this->params['tenant_id']);
    }

   

}
