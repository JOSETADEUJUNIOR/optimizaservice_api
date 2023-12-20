<?php

namespace Src\Model\SQL;


class Venda
{

    public static function InserirVendaSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_vendas (VendaDT, VendaDesconto, VendaCliID, VendaUserID, VendaStatus, VendaValorEntrega) VALUES (?,?,?,?,?,?)";
        return $sql;
    }

    public static function EditarVendaSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_vendas set VendaDT = ?, VendaDesconto = ?, VendaCliID = ?, VendaUserID = ?, VendaStatus = ?, VendaValorEntrega = ? WHERE VendaID = ? ";
        return $sql;
    }

    public static function AlterarVendaSQL()
    {
        $sql = 'UPDATE tb_vendas set VendaDT = ?, VendaCliID = ?, VendaEmpID = ?, VendaUserID = ?
                 WHERE VendaID = ?';
        return $sql;
    }

    public static function FaturarVendaSQL()
    {
        $sql = 'UPDATE tb_vendas set VendaFaturado = ?, VendaLancamentoID= ? WHERE VendaEmpID = ? AND VendaID = ?';
        return $sql;
    }
    public static function ExcluirFaturarVendaSQL()
    {
        $sql = 'UPDATE tb_vendas set VendaDesconto = ?, VendaFaturado = ?, VendaLancamentoID= ? WHERE VendaEmpID = ? AND VendaID = ?';
        return $sql;
    }

    public static function RetornarVendaFaturadoSQL()
    {
        $sql = 'SELECT VendaID, VendaFaturado  
                    FROM tb_vendas
                        WHERE VendaEmpID = ? And VendaID = ?';
        return $sql;
    }
    public static function RetornarVendaSQL($schema)
    {
        $sql = "SELECT vd.*, cli.*
                   FROM `$schema`.tb_vendas as vd
                         INNER JOIN `$schema`.tb_cliente as cli on vd.VendaCliID = cli.CliID";
        return $sql;
    }
    public static function DetalharVendaSQL($schema)
    {
        $sql = "SELECT vd.*, cli.*
                   FROM `$schema`.tb_vendas as vd
                         INNER JOIN `$schema`.tb_cliente as cli on vd.VendaCliID = cli.CliID Where vd.VendaID = ?";
        return $sql;
    }
    public static function DetalharItensVendaSQL($schema)
    {
        $sql = "SELECT tb_itens_venda.*, tb_produto.* From `$schema`.tb_itens_venda
            Inner Join `$schema`.tb_produto on tb_itens_venda.itensProdID = tb_produto.ProdID
         Where itensVendaID = ?";
        
        return $sql;
    }

    public static function DeleteItensVendaSQL($schema)
    {
        $sql = "DELETE From `$schema`.tb_itens_venda
         Where itensVendaID = ?";
        
        return $sql;
    }


    public static function RetornarDadosVenda()
    {
        $sql = 'SELECT VendaID, ItensVendaID

        FROM tb_vendas
            Left JOIN tb_Itens_venda on tb_vendas.VendaID = tb_Itens_venda.ItensVendaID 
                 WHERE VendaEmpID = ?';
        return $sql;
    }

    public static function ExcluirVenda()
    {
        $sql = 'DELETE FROM tb_vendas WHERE VendaID = ?';
        return $sql;
    }



    public static function RetornarProdVendaSQL()
    {
        $sql = 'SELECT VendaID, ItensID, ProdValorVenda, ItensQtd, ItensProdID, ProdDescricao, ItensSubTotal  
	                FROM tb_vendas
                        Inner Join tb_cliente on tb_cliente.CliID = tb_vendas.VendaCliID
                        left Join tb_Itens_venda on tb_Itens_venda.ItensVendaID = tb_vendas.VendaID
                        left Join tb_produto on tb_produto.ProdID = tb_Itens_venda.ItensProdID
                          WHERE VendaEmpID = ? And VendaID = ?';
        return $sql;
    }

    public static function BuscarItemVendaSQL($schema)
    {
        $sql = "SELECT ProdEstoque, ProdValorVenda
                        from `$schema`.tb_produto WHERE ProdID = ?";
        return $sql;
    }
    public static function AtualizaTotalVendaSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_vendas set VendaValorTotal = ? WHERE VendaID = ?";
        return $sql;
    }
    public static function AtualizaItemVendaSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_produto set ProdEstoque = ProdEstoque - ?
                        WHERE ProdID = ?";
        return $sql;
    }

    public static function AtualizaExcluiItemVendaSQL()
    {
        $sql = 'UPDATE `$schema`.tb_produto set ProdEstoque = ProdEstoque + ?
                        WHERE ProdID = ? And ProdEmpID = ?';
        return $sql;
    }
    public static function ExcluirItemVenda()
    {
        $sql = 'DELETE FROM tb_itens_venda WHERE ItensID = ?';
        return $sql;
    }

    public static function AtualizaExclusaoValorVendaSQL()
    {
        $sql = 'UPDATE tb_vendas set VendaValorTotal = VendaValorTotal - ? WHERE VendaID = ? and VendaEmpID = ?';
        return $sql;
    }


    public static function InserirItemVendaSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_itens_venda (ItensSubTotal, ItensQtd, ItensVendaID, ItensProdID) VALUES (?,?,?,?)";
        return $sql;
    }

    public static function ExcluirItemVendaSQL($schema)
    {
        $sql = "DELETE from `$schema`.tb_itens_venda Where ItensID = ?";
        return $sql;
    }


    

    public static function EditarItemVendaSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_itens_venda set ItensSubTotal = ?, ItensQtd = ?, ItensVendaID = ?, ItensProdID = ? WHERE ItensID = ?";
        return $sql;
    }

    public static function FiltrarVendaDetalhadaSQL($tenant_id)
    {
        $sql = "SELECT 
        vd.*,
        cl.*,
        (
            SELECT JSON_ARRAYAGG(JSON_OBJECT('ProdID', p.ProdID, 'Descricao', p.ProdDescricao, 'Qtd', iv.ItensQtd, 'SubTotal', iv.ItensSubTotal))
            FROM `$tenant_id`.tb_itens_venda as iv
            INNER JOIN `$tenant_id`.tb_produto as p ON iv.ItensProdID = p.ProdID
            WHERE iv.itensVendaID = vd.VendaID
        ) AS produtos
    FROM 
        `$tenant_id`.tb_vendas vd
    INNER JOIN 
        `$tenant_id`.tb_cliente as cl ON cl.CliID = vd.VendaCliID
    WHERE 
        vd.VendaID = ?";
    return $sql;
    }





    
}