<?php

namespace Src\Controller;

use Src\Model\ClienteDAO;
use Src\VO\ClienteVO;
use Src\_public\Util;
use Src\Service\ClienteService;

class ClienteController
{
    private $service;

    public function __construct()
    {
        $this->service = new ClienteService;
    }

    public function CadastrarClienteCTRL($tenant_id, ClienteVO $vo): int
    {
        return $this->service->CadastrarClienteService($tenant_id, $vo);
    }

    public function AlterarClienteCTRL($tenant_id, ClienteVO $vo): int
    {
        return $this->service->AlterarClienteService($tenant_id, $vo);
    }

    public function AlterarStatusClienteCTRL(ClienteVO $vo): int
    {
        $vo->setCliStatus($vo->getCliStatus() == STATUS_ATIVO ? STATUS_INATIVO : STATUS_ATIVO);
        $vo->setCliEmpID(Util::EmpresaLogado());
        $vo->setfuncao(STATUS_CLIENTE);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->service->AlterarStatusClienteService($vo);
    }

    public function SelecioneClienteCTRL($schema)
    {
        
        $dados = $this->service->SelecioneClienteService($schema); 
        
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }

    public function DetalharClienteController($schema, $id)
    {
        $dados = $this->service->DetalharClienteService($schema, $id); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }



    

    public function FiltrarClienteCTRL($filtrar_nome, $filtrar_cidade, $tenant_id)
    {
        $dados = $this->service->FiltrarClienteService($filtrar_nome, $filtrar_cidade, $tenant_id);
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        
        return $dados;
    }

/* 
    public function EmailDuplicadoCTRL($email)
    {  
        $dados_cliente = $this->service->EmailDuplicadoService($Schema);
        for ($i = 0; $i < count($dados_cliente); $i++){
            if ($dados_cliente[$i]['CliEmail'] == $email){
                return -105; # Caso tenha email duplicado
            }
        }
        $dados_usuario = $this->service->EmailDuplicadoService();
        for ($i = 0; $i < count($dados_usuario); $i++){
            if ($dados_usuario[$i]['login'] == $email){
                return -105; # Caso tenha email duplicado
            } 
        }
        return 1;
    } */
}
