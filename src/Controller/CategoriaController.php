<?php

namespace Src\Controller;

use Src\Model\ClienteDAO;
use Src\VO\ClienteVO;
use Src\_public\Util;
use Src\Service\CategoriaService;
use Src\Service\ClienteService;
use Src\VO\CategoriaVO;

class CategoriaController
{
    private $service;

    public function __construct()
    {
        $this->service = new CategoriaService;
    }

    public function CadastrarCategoriaController($vo): int
    {
        return $this->service->CadastrarCategoriaService($vo);
    }

    public function EditarCategoriaController($vo): int
    {
        return $this->service->EditarCategoriaService($vo);
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

    public function RetornarCategoriaController($schema)
    {
        
        $dados = $this->service->RetornarCategoriaService($schema); 
        return $dados;
    }


    

    public function DetalharCategoriaController($schema, $id)
    {
        $dados = $this->service->DetalharCategoriaService($schema, $id); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }



    

    public function FiltrarCategoriaController($filtrar_nome, $filtrar_cod, $tenant_id)
    {
        $dados = $this->service->FiltrarCategoriaService($filtrar_nome, $filtrar_cod, $tenant_id);
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
