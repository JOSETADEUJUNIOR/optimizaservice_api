<?php

namespace Src\Controller;

use Src\Model\VendaDAO;
use Src\VO\VendaVO;
use Src\VO\ItensVendaVO;
use Src\_public\Util;

class VendaController
{

    private $dao;

    public function __construct()
    {
        $this->dao = new VendaDAO;
    }

    public function CadastrarVendaController(VendaVO $vo, $produtosVenda): int
    {
        if (empty($vo->getCliID()))
            return 0;

       /*  $vo->setfuncao(CADASTRO_VENDA);
        $vo->setIdLogado(Util::CodigoLogado()); */

        return $this->dao->CadastrarVendaDAO($vo, $produtosVenda);
    }

    public function EditarVendaController(VendaVO $vo, $produtosVenda, $itensExcluidos)
    {
        if (empty($vo->getCliID()))
            return 0;

       /*  $vo->setfuncao(CADASTRO_VENDA);
        $vo->setIdLogado(Util::CodigoLogado()); */

        return $this->dao->EditarVendaDAO($vo, $produtosVenda, $itensExcluidos);
    }

    public function FiltrarVendaDetalhadaController($tenant_id, $VendaID)
    {
        $dados = $this->dao->FiltrarVendaDetalhadaDAO($tenant_id, $VendaID);
        // for ($i = 0; $i < count($dados); $i++){
        //     $dados[$i]['CliDtNasc'] = Util::ExibirDataBr($dados[$i]['CliDtNasc']);
        // }
        return $dados;
    }





    
    
    public function AlterarVendaController(VendaVO $vo): int
    {
        if (empty($vo->getCliID()))
            return 0;

        $vo->setfuncao(CADASTRO_VENDA);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->AlterarVendaDAO($vo);
    }

    public function ExcluirItemVendaController(ItensVendaVO $vo)
    {
        $vo->setfuncao(EXCLUI_ITEM_VENDA);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->ExcluirItemVendaDAO($vo);
    }

    public function RetornarVendaController($tenant_id): array # Retorna todas as vendas
    {

        return $this->dao->RetornarVendaDAO($tenant_id);
    }

    public function DetalharVendaController(VendaVO $vo): array#para retornar os detalhes da venda
    {
        return $this->dao->DetalharVendaDAO($vo);
    }

    public function DetalharItensVendaController(VendaVO $vo): array # retornar os itens de uma venda específica
    {
        return $this->dao->DetalharItensVendaDAO($vo);
    }
    

    public function RetornarDadosVendaController(): array
    {
        return $this->dao->RetornaDadosVendaDAO();
    }

    public function FaturarVendaController(VendaVO $vo): int
    {
        if (empty($vo->getID()))
            return 0;


        return $this->dao->FaturarVendaDAO($vo);
    }


    public function RetornarTodasVendaController(): array
    {

        return $this->dao->RetornarTodasVendaDAO();
    }

    public function InserirItemVendaController(ItensVendaVO $vo): int
    {
        if (empty($vo->getProdQtd()))
            return 0;

        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->InserirItemVendaDAO($vo);
    }
    public function RetornaProdVendaController(VendaVO $vo): array
    {
        $vo->setfuncao(CADASTRO_OS);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RetornarProdVendaDAO($vo);
    }
    public function ExcluirVendaController(VendaVO $vo)
    {
        $vo->setfuncao(EXCLUI_VENDA);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->ExcluirVendaDAO($vo);
    }
}