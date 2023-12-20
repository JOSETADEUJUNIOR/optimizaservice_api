<?php

namespace Src\Service;

use Src\Model\ClienteDAO;
use Src\VO\ClienteVO;
use Src\_public\Util;
use Src\Model\CategoriaDAO;
use Src\VO\CategoriaVO;

class CategoriaService
{
    private $dao;

    public function __construct()
    {
        $this->dao = new CategoriaDAO;
    }

    public function CadastrarCategoriaService($vo)
    {
        if (empty($vo->getNomeCategoria()) || empty($vo->getImagemLogo())){
            return 0;
        }
            
        $vo->setfuncao(CADASTRO_CATEGORIA);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->CadastrarCategoriaDAO($vo);
    }

    public function EditarCategoriaService($vo)
    {
        if (empty($vo->getNomeCategoria())){
            return 0;
        }
            
        $vo->setfuncao(CADASTRO_CATEGORIA);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->EditarCategoriaDAO($vo);
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

    public function RetornarCategoriaService($schema){
        
        $dados = $this->dao->RetornarCategoriaDAO($schema); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
      
        return $dados;
    }

    public function DetalharCategoriaService($schema, $id)
    {
        $dados = $this->dao->DetalharCategoriaDAO($schema, $id); 
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }



    

    public function FiltrarCategoriaService($filtrar_nome, $filtrar_cod, $tenant_id)
    {
        $dados = $this->dao->FiltrarCategoriaDAO($filtrar_nome, $filtrar_cod, $tenant_id);
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
