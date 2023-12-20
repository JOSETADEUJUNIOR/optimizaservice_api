<?php

namespace Src\Service;

use Src\Model\ClienteDAO;
use Src\VO\ClienteVO;
use Src\_public\Util;


class ClienteService
{
    private $dao;

    public function __construct()
    {
        $this->dao = new ClienteDAO;
    }

    public function CadastrarClienteService($tenant_id, ClienteVO $vo): int
    {
        if (empty($vo->getCliNome()) || empty($vo->getCliTelefone()) || empty($vo->getCliEmail()) || empty($vo->getCliCep()) || empty($vo->getCliEndereco()) || empty($vo->getCliNumero()) || empty($vo->getCliBairro()) || empty($vo->getCliCidade()) || empty($vo->getCliEstado()))
            return 0;
        
        $vo->setfuncao(CADASTRO_CLIENTE);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarClienteDAO($tenant_id, $vo);
    }

    public function AlterarClienteService($tenant_id, ClienteVO $vo): int
    {
        if (empty($vo->getCliNome()) || empty($vo->getCliTelefone()) || empty($vo->getCliEmail()) || empty($vo->getCliCep()) || empty($vo->getCliEndereco()) || empty($vo->getCliNumero()) || empty($vo->getCliBairro()) || empty($vo->getCliCidade()) || empty($vo->getCliEstado()))
            return 0;
        $vo->setfuncao(ALTERA_CLIENTE);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->AlterarClienteDAO($tenant_id, $vo);
    }

    public function AlterarStatusClienteService(ClienteVO $vo): int
    {
        $vo->setCliEmpID(Util::EmpresaLogado());
        $vo->setfuncao(STATUS_CLIENTE);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->AlterarStatusClienteDAO($vo);
    }

    public function SelecioneClienteService($schema){
        
        $dados = $this->dao->SelecionarClienteDAO($schema); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
      
        return $dados;
    }

    public function DetalharClienteService($schema, $id)
    {
        $dados = $this->dao->DetalharClienteDAO($schema, $id); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }



    

    public function FiltrarClienteService($filtrar_nome, $filtrar_cidade, $tenant_id)
    {
        $dados = $this->dao->FiltrarClienteDAO($filtrar_nome, $filtrar_cidade, $tenant_id);
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }


    public function EmailDuplicadoService($email)
    {  
        $dados_cliente = $this->dao->EmailDuplicadoClienteDAO();
        for ($i = 0; $i < count($dados_cliente); $i++){
            if ($dados_cliente[$i]['CliEmail'] == $email){
                return -105; # Caso tenha email duplicado
            }
        }
        $dados_usuario = $this->dao->EmailDuplicadoUsuarioDAO();
        for ($i = 0; $i < count($dados_usuario); $i++){
            if ($dados_usuario[$i]['login'] == $email){
                return -105; # Caso tenha email duplicado
            } 
        }
        return 1;
    }
}
