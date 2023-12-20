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

class ClienteApi extends ApiRequest
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

    public function RetornarClientes()
    {
        if (Util::AuthenticationTokenAccess()) {
        return (new ClienteController)->SelecioneClienteCTRL($this->params['tenant_id']);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function FiltrarClientes()
    {
         if (Util::AuthenticationTokenAccess()) {
        return (new ClienteController)->FiltrarClienteCTRL($this->params['busca_nome'], $this->params['busca_cidade'], $this->params['tenant_id']);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function DetalharCliente()
    {
         if (Util::AuthenticationTokenAccess()) {
        return (new ClienteController)->DetalharClienteController($this->params['tenant_id'], $this->params['id_cliente']);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function EditarCliente()
    {
         if (Util::AuthenticationTokenAccess()) {
        $vo = new ClienteVO();
        $vo->setCliNome($this->params['CliNome']);
        $vo->setCliCpfCnpj($this->params['CliCpfCnpj']);
        $vo->setCliCep($this->params['CliCep']);
        $vo->setCliCidade($this->params['CliCidade']);
        $vo->setCliEmail($this->params['CliEmail']);
        $vo->setCliEndereco($this->params['CliEndereco']);
        $vo->setCliEstado($this->params['CliEstado']);
        $vo->setCliBairro($this->params['CliBairro']);
        $vo->setCliDescricao($this->params['CliDescricao']);
        $vo->setCliNumero($this->params['CliNumero']);
        $vo->setCliID($this->params['CliID']);
        $vo->setCliTelefone($this->params['CliTelefone']);
        $vo->setCliDtNasc(Util::formatarDataParaBanco($this->params['CliDtNasc']));
        $vo->setCliStatus($this->params['CliTipo']);
        $vo->setCliUserID($this->params['id_user']);
        $vo->setCliEmpID($this->params['empresa_id']);
        $tenant_id = $this->params['tenant_id'];
        return (new ClienteController)->AlterarClienteCTRL($tenant_id, $vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function CadastrarCliente()
    {
         if (Util::AuthenticationTokenAccess()) {
        $vo = new ClienteVO();
        $vo->setCliNome($this->params['CliNome']);
        $vo->setCliCpfCnpj($this->params['CliCpfCnpj']);
        $vo->setCliCep($this->params['CliCep']);
        $vo->setCliCidade($this->params['CliCidade']);
        $vo->setCliEmail($this->params['CliEmail']);
        $vo->setCliEndereco($this->params['CliEndereco']);
        $vo->setCliEstado($this->params['CliEstado']);
        $vo->setCliBairro($this->params['CliBairro']);
        $vo->setCliDescricao($this->params['CliDescricao']);
        $vo->setCliNumero($this->params['CliNumero']);
        $vo->setCliID($this->params['CliID']);
        $vo->setCliTelefone($this->params['CliTelefone']);
        $vo->setCliDtNasc(Util::formatarDataParaBanco($this->params['CliDtNasc']));
        $vo->setCliStatus($this->params['CliTipo']);
        $vo->setCliUserID($this->params['userEmpID']);
        $tenant_id = $this->params['tenant_id'];
        return (new ClienteController)->CadastrarClienteCTRL($tenant_id, $vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
}
