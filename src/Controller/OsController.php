<?php

namespace Src\Controller;

use Src\Model\OsDAO;
use Src\VO\OsVO;
use Src\_public\Util;
use Src\VO\ProdutoOSVO;
use Src\VO\ServicoOSVO;
use Src\VO\AnxOSVO;
use Src\VO\ImagemVO;
use Src\VO\LancamentoVO;
use Src\VO\SendMailVO;

class OsController
{

    private $dao;

    public function __construct()
    {
        $this->dao = new OsDAO;
    }

    public function CadastrarOsController(OsVO $vo): int
    {
        if (empty($vo->getDtInicial()))
            return 0;

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarOsDAO($vo);
    }
    public function EditarOsController(OsVO $vo)
    {
        if (empty($vo->getID()))
            return 0;

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->EditarOsDAO($vo);
    }

    
    public function RealizarPagamentoOsController(LancamentoVO $vo): int
    {
        if ($vo->getLancQtdParcela()) {
            if ($vo->getLancQtdParcela() >10  || $vo->getLancQtdParcela() < 0  ) {
                return -14;
            }
            
        }
        if (empty($vo->getValor()) || empty($vo->getLancValorTotal())) {
            return -5;
        }
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RealizarPagamentoOsDAO($vo);
    }
    public function FiltrarOSController($filtrar_nome, $filtrar_data_os, $tenant_id, $OsID, $tipo_servico)
    {
        $dados = $this->dao->FiltrarOSDAO($filtrar_nome, $filtrar_data_os, $tenant_id, $OsID, $tipo_servico);
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }
    public function FiltrarOSDetalhadaController($tenant_id, $OsID)
    {
        $dados = $this->dao->FiltrarOsDetalhadaSQL($tenant_id, $OsID);
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }



    
    public function GravarDadosEmailController(SendMailVO $vo){
        
        if (empty($vo->getDestinatario()))
        return 0;

        $vo->setfuncao(CADASTRAR_EMAIL);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->GravarDadosEmailDAO($vo);
    }
    public function RetornarDadosEmailController($dataInicial='',$datafinal=''): array
    {

        return $this->dao->RetornarDadosEmailDAO($dataInicial,$datafinal);
    }

    public function InserirItemOrdemController(ProdutoOSVO $vo): int
    {
        if (empty($vo->getProdQtd()))
            return 0;
        
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->InserirItemOsDAO($vo);
    }

    public function InserirServicoOrdemController(ServicoOSVO $vo): int
    {
        if (empty($vo->getServQtd()))
            return 0;
        
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->InserirServOsDAO($vo);
    }
    
    public function CarregarProdutosOsController(OsVO $vo)
    {
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CarregarProdutosOsDAO($vo);
    }

    public function CarregarServicosOsController(OsVO $vo)
    {
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CarregarServicosOsDAO($vo);
    }


    

    
    public function InserirAnxOrdemController(ImagemVO $vo): int
    {
        
        $vo->setfuncao(CADASTRO_ANX_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->InserirAnxOsDAO($vo);
    }
    public function InserirServOrdemController(ServicoOSVO $vo): int
    {
        if (empty($vo->getServQtd()))
            return 0;

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->InserirServOsDAO($vo);
    }

    public function RetornaOrdem(OsVO $vo): array
    {

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RetornarOrdemDAO($vo);
    }

    public function RetornarDadosOsController(): array
    {
        return $this->dao->RetornarDadosOsDAO();
    }
    public function RetornaProdOrdem(OsVO $vo): array
    {
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RetornarProdOrdemDAO($vo);
    }


    public function RetornarChamadoController($tenant_id): array
    {
        return $this->dao->RetornarChamadoDAO($tenant_id);
    }




    
    public function RetornaServOrdem(OsVO $vo): array
    {

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RetornarServOrdemDAO($vo);
    }
    public function RetornaAnxOS(OsVO $vo): array
    {

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RetornarAnxOSDAO($vo);
    }

    public function ExcluirItemOSController(ProdutoOSVO $vo)
    {
        $vo->setfuncao(EXCLUI_ITEM_OS);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->ExcluirItemOSDAO($vo);
    }
    public function ExcluirOSController(OSVO $vo)
    {
        $vo->setfuncao(EXCLUI_OS);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->ExcluirOSDAO($vo);
    }
    public function ExcluirAnxOSController(AnxOSVO $vo)
    {
        $vo->setfuncao(EXCLUIR_ANX);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->ExcluirAnxOSDAO($vo);
    }

    public function ExcluirServOSController(ServicoOSVO $vo)
    {
        $vo->setfuncao(EXCLUI_ITEM_OS);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->ExcluirServOSDAO($vo);
    }


    public function AlterarOsController(OsVO $vo): int
    {
        if (empty($vo->getID()))
            return 0;

        $vo->setfuncao(ALTERA_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->AlterarOsDAO($vo);
    }
    public function FaturarOsController(OsVO $vo): int
    {
        if (empty($vo->getID()))
            return 0;

        $vo->setfuncao(FATURA_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->FaturarOsDAO($vo);
    }


    public function RetornarOsController(OsVO $vo): array
    {
        return $this->dao->RetornarOsDAO($vo);
    }
    public function RetornarPagamentosOSController(OsVO $vo): array
    {
        return $this->dao->RetornarPagamentosOSDAO($vo);
    }


    
    public function DetalharOsController(OsVO $vo): array
    {
        return $this->dao->DetalharOsDAO($vo);
    }


    
  
    public function FiltrarStatusController($status_filtro, $filtroDe, $filtroAte)
    {
        return $this->dao->FiltrarStatusDAO($status_filtro, $filtroDe, $filtroAte);
    }
    public function RetornarOsMesController(): array
    {
        return $this->dao->RetornarOsMesDAO();
    }
    public function RetornarOsClienteController($CliID, $tipo): array
    {
        return $this->dao->RetornarOsClienteDAO($CliID, $tipo);
    }
    public function RetornarOsServController(): array
    {
        return $this->dao->RetornarOsDAO();
    }
}
