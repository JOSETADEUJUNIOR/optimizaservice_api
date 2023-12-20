<?php

namespace Src\Controller;

use Src\Model\ProdutoDAO;
use Src\VO\ProdutoVO;
use Src\_public\Util;


class ProdutoController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new ProdutoDAO;
    }

    public function CadastrarProdutoController(ProdutoVO $vo): int
    {
        
        $vo->setProdDtCriacao(date('Y-m-d'));
        $vo->setProdStatus(STATUS_ATIVO);
        $vo->setfuncao(CADASTRO_PRODUTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarProdutoDAO($vo);
    }

    public function EditarProdutoController(ProdutoVO $vo): int
    {
        
        $vo->setProdDtCriacao(date('Y-m-d'));
        $vo->setProdStatus(STATUS_ATIVO);
        $vo->setfuncao(ALTERA_PRODUTO);
        $vo->setIdLogado(Util::CodigoLogado());
        
        return $this->dao->EditarProdutoDAO($vo);
    }

    


    public function AlterarProdutoCTRL(ProdutoVO $vo): int
    {
        if (empty($vo->getProdDescricao()) || empty($vo->getProdCodBarra()) || empty($vo->getProdValorCompra()) || empty($vo->getProdValorVenda()) || $vo->getProdEstoqueMin() == "" || $vo->getProdEstoque() == "")
            return 0;
    
        $vo->setfuncao(ALTERA_PRODUTO);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->AlterarProdutoDAO($vo);
    }

    public function AlterarStatusProdutoCTRL(ProdutoVO $vo): int
    {
        
        $vo->setProdStatus($vo->getProdStatus() == STATUS_ATIVO ? STATUS_INATIVO : STATUS_ATIVO);
        $vo->setfuncao(STATUS_PRODUTO);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->AlterarStatusProdutoDAO($vo);
    }

    public function RetornarProdutosController($schema): array
    {
        return $this->dao->RetornarProdutosDAO($schema);
    }

    public function BuscarProdutoVendaController($schema): array
    {
        return $this->dao->BuscarProdutoVendaDAO($schema);
    }

    
    public function RetornarProdutosPorCategoriaController($tenant_id, $categoria_id): array
    {
        return $this->dao->RetornarProdutosPorCategoriaDAO($tenant_id, $categoria_id);
    }


    
    public function DetalharProdutoController($tenant_id, $id_produto)
    {
        $dados = $this->dao->SelecionarProdutoAPIDAO($tenant_id, $id_produto); 
        return $dados;
    }
    public function SelecioneServicoAPICTRL($empresa_id)
    {
        
        return $this->dao->SelecionarServicoAPIDAO($empresa_id);
    }

    public function FiltrarProdutoCTRL($nome_filtro)
    {   
        return $this->dao->FiltrarProdutoDAO($nome_filtro);
    }

    public function DadosEmpresaCTRL()
    {   
        return $this->dao->DadosEmpresaDAO();
    }
}
