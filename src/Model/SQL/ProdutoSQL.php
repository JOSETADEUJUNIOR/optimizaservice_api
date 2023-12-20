<?php

namespace Src\Model\SQL;

class ProdutoSQL{

    public static function INSERT_PRODUTO_SQL($schema)
    {
        $sql = "INSERT INTO `$schema`.tb_produto (ProdDescricao, ProdDtCriacao, ProdCodBarra, ProdValorCompra, ProdValorVenda, ProdEstoqueMin, ProdEstoque, ProdStatus, CatID) VALUES (?,?,?,?,?,?,?,?,?)";
        return $sql;
    }

    public static function UPDATE_PRODUTO_SQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_produto SET ProdCodBarra = ?, ProdDescricao = ?, CatID = ?, SKU = ?, ProdEstoqueMin = ?, ProdEstoque = ?, ProdValorCompra = ?, ProdValorVenda = ?, ProdStatus = ? WHERE ProdID = ?";
        return $sql;
    }
    public static function DETALHAR_PRODUTO_SQL($schema)
    {
        $sql = "SELECT *, nome_categoria FROM `$schema`.tb_produto left join `$schema`.tb_imagem On imagemEntidadeID = ProdID And imagemEntidadeTipo = 'produto' Inner Join `$schema`.tb_categoria On CatID = id Where ProdID = ? ";
        return $sql;
    }
    

    public static function UPDATE_STATUS_PRODUTO_SQL($schema)
    {
        $sql = 'UPDATE tb_produto SET ProdStatus = ? WHERE ProdID = ? AND ProdEmpID = ?';
        return $sql;
    }

    public static function RETORNAR_PRODUTOS_SQL($schema)
    {
        $sql = "SELECT * FROM `$schema`.tb_produto left join `$schema`.tb_imagem On imagemEntidadeID = ProdID And imagemEntidadeTipo = 'produto'";
        return $sql;
    }

    public static function RETORNAR_PRODUTOS_POR_CATEGORIA_SQL($schema)
    {
        $sql = "SELECT * FROM `$schema`.tb_produto left join `$schema`.tb_imagem On imagemEntidadeID = ProdID And imagemEntidadeTipo = 'produto' WHERE CatID = ? ";
        return $sql;
    }



    
 
    public static function SELECT_SERVICO_SQL($schema)
    {
        $sql = 'SELECT * FROM tb_servico WHERE ServEmpID = ?';
        return $sql;
    }

    public static function FILTER_PRODUTO_SQL($schema)
    {
        $sql = "SELECT ProdID, ProdDescricao, ProdCodBarra, ProdValorCompra, ProdValorVenda, ProdEstoqueMin, ProdEstoque, ProdStatus, ProdImagem, ProdImagemPath FROM `$schema`.tb_produto";

      

        return $sql;
    }

    public static function DADOS_EMPRESA_SQL()
    {
        $sql = 'SELECT EmpNome, EmpCNPJ, EmpLogoPath, EmpEnd, EmpCidade, EmpNumero  FROM tb_empresa WHERE EmpID = ?';
        return $sql;
    }
}