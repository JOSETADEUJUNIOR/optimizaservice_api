<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\ProdutoVO;
use Src\Model\SQL\ProdutoSQL;
use Src\Model\SQL\ImagemSQL;

class ProdutoDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarProdutoDAO(ProdutoVO $vo): int
    {
        $sql = $this->conexao->prepare(ProdutoSQL::INSERT_PRODUTO_SQL($vo->getProdSchema()));
        $i = 1;

        $sql->bindValue($i++, $vo->getProdDescricao());
        $sql->bindValue($i++, $vo->getProdDtCriacao());
        $sql->bindValue($i++, $vo->getProdCodBarra());
        $sql->bindValue($i++, $vo->getProdValorCompra());
        $sql->bindValue($i++, $vo->getProdValorVenda());
        $sql->bindValue($i++, $vo->getProdEstoqueMin());
        $sql->bindValue($i++, $vo->getProdEstoque());
        $sql->bindValue($i++, $vo->getProdStatus());
        $sql->bindValue($i++, $vo->getCategoria_id());

        $sql->execute();
        $produto_id = $this->conexao->lastInsertId();
        $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getProdSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getImagemLogo());
        $sql->bindValue($i++, $vo->getImagemPath());
        $sql->bindValue($i++, $produto_id);
        $sql->bindValue($i++, 'produto');
       
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function EditarProdutoDAO(ProdutoVO $vo)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::UPDATE_PRODUTO_SQL($vo->getProdSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getProdCodBarra());
        $sql->bindValue($i++, $vo->getProdDescricao());
        $sql->bindValue($i++, $vo->getCategoria_id());
        $sql->bindValue($i++, $vo->getSku());
        $sql->bindValue($i++, $vo->getProdEstoqueMin());
        $sql->bindValue($i++, $vo->getProdEstoque());
        $sql->bindValue($i++, $vo->getProdValorCompra());
        $sql->bindValue($i++, $vo->getProdValorVenda());
        $sql->bindValue($i++, $vo->getProdStatus());
        $sql->bindValue($i++, $vo->getProdID());
        
        $sql->execute();
        $sql = $this->conexao->prepare(ImagemSQL::RETORNA_IMAGEM_ENTIDADE($vo->getProdSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getProdID());
        $sql->bindValue($i++, 'produto');
        $sql->execute();
        $imagemProd = $sql->fetch(\PDO::FETCH_ASSOC);
        if ($imagemProd['imagemID']>0 && $vo->getImagemLogo()!="") {
            $i = 1;
            $sql = $this->conexao->prepare(ImagemSQL::UPDATE_IMAGEM_SQL($vo->getProdSchema()));
            $sql->bindValue($i++, $vo->getImagemLogo());
            $sql->bindValue($i++, $vo->getImagemPath());
            $sql->bindValue($i++, $vo->getProdID());
            $sql->bindValue($i++, 'produto');
            $sql->bindValue($i++, $imagemProd['imagemID']);
            $sql->execute();
           
        }else{
            $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getProdSchema()));
            $i = 1;
            $sql->bindValue($i++, $vo->getImagemLogo());
            $sql->bindValue($i++, $vo->getImagemPath());
            $sql->bindValue($i++, $vo->getProdID());
            $sql->bindValue($i++, 'produto');
            $sql->execute();
        }
        
        try {
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }




    
    public function AlterarProdutoDAO(ProdutoVO $vo): int
    {
        $sql = $this->conexao->prepare(ProdutoSQL::UPDATE_PRODUTO_SQL());
        $i = 1;
        $sql->bindValue($i++, $vo->getProdDescricao());
        $sql->bindValue($i++, $vo->getProdCodBarra());
        $sql->bindValue($i++, $vo->getProdValorCompra());
        $sql->bindValue($i++, $vo->getProdValorVenda());
        $sql->bindValue($i++, $vo->getProdEstoqueMin());
        $sql->bindValue($i++, $vo->getProdEstoque());
        $sql->bindValue($i++, $vo->getProdImagem());
        $sql->bindValue($i++, $vo->getProdImagemPath());
        $sql->bindValue($i++, $vo->getProdID());
        $sql->bindValue($i++, Util::EmpresaLogado());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function AlterarStatusProdutoDAO(ProdutoVO $vo): int
    {
        $sql = $this->conexao->prepare(ProdutoSQL::UPDATE_STATUS_PRODUTO_SQL());
        $i = 1;
        $sql->bindValue($i++, $vo->getProdStatus());
        $sql->bindValue($i++, $vo->getProdID());
        $sql->bindValue($i++, Util::EmpresaLogado());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function RetornarProdutosDAO($schema)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::RETORNAR_PRODUTOS_SQL($schema));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function BuscarProdutoVendaDAO($schema)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::FILTER_PRODUTO_SQL($schema));
        $i = 1;
       
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }



    
    public function RetornarProdutosPorCategoriaDAO($tenant_id, $categoria_id)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::RETORNAR_PRODUTOS_POR_CATEGORIA_SQL($tenant_id));
        $i = 1;
        $sql->bindValue($i++, $categoria_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    
    public function SelecionarProdutoAPIDAO($tenant_id, $id_produto)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::DETALHAR_PRODUTO_SQL($tenant_id));
        $i = 1;
        $sql->bindValue($i++, $id_produto);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function SelecionarServicoAPIDAO($empresa_id)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::SELECT_SERVICO_SQL());
        $i = 1;
        $sql->bindValue($i++, $empresa_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function FiltrarProdutoDAO($nome_filtro)
    {
        $sql = $this->conexao->prepare(ProdutoSQL::FILTER_PRODUTO_SQL($nome_filtro));
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        if (!empty($nome_filtro)) {
            $sql->bindValue($i++, "%" . $nome_filtro . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function DadosEmpresaDAO()
    {
        $sql = $this->conexao->prepare(ProdutoSQL::DADOS_EMPRESA_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}
