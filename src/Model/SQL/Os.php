<?php

namespace Src\Model\SQL;


class Os
{

    public static function InserirOsSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_os (OsDtInicial, OsDtFinal, OsGarantia, OsDescProdServ, OsDefeito, OsObs, OsCliID, OsTecID, OsStatus, OsLaudoTec, OsTpServico) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        return $sql;
    }

    public static function EditarOsSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_os set OsDtInicial = ?, OsDtFinal = ?, OsGarantia = ?, OsDescProdServ = ?, OsDefeito = ?, OsObs = ?, OsCliID = ?, OsTecID = ?, OsStatus = ?, OsLaudoTec = ?, OsTpServico = ? WHERE OsID = ?";
        return $sql;
    }



    public static function RetornarOsSQL($schema)
    {
        $sql = "SELECT os.OsID, os.OsDtInicial, os.OsDtFinal, os.OsGarantia, os.OsDescProdServ, os.OsDefeito, os.OsObs, os.OsCliID, cl.CliNome as nomeCliente, cl.CliTelefone as CliTelefone, os.OsTecID, os.OsStatus, os.OsLaudoTec, os.OsValorTotal, os.OsDesconto, os.OsFaturado, os.OsValorPago, os.OsValorDevido, os.OsTpServico FROM `$schema`.tb_os os
        Inner Join `$schema`.tb_cliente as cl on cl.CliID = os.OsCliID";
        return $sql;
    }

    public static function RetornarPagamentosOsSQL($schema)
    {
        $sql = "SELECT lanc.LancDtVencimento, LancValorPago, LancValorTotal, LancValorDevido, LancBaixado, lancparc.* 
            from `$schema`.tb_lancamento as lanc 
             Left Join`$schema`.tb_pagamentos_parcelados as lancparc on lanc.LancID = lancparc.LancID Where lanc.LancEntidadeID = ?";
        return $sql;
    }
    public static function FiltrarOsSQL($filtrar_nome, $filtar_data_os, $tenant_id, $OsID, $tipo_servico)
    {
        $sql = "SELECT os.OsID, os.OsDtInicial, os.OsDtFinal, os.OsGarantia, os.OsDescProdServ, os.OsDefeito, os.OsObs, os.OsCliID, cl.CliNome as nomeCliente, cl.CliTelefone as CliTelefone, os.OsTecID, os.OsStatus, os.OsLaudoTec, os.OsValorTotal, os.OsDesconto, os.OsFaturado, os.OsValorPago, os.OsValorDevido, os.OsTpServico FROM `$tenant_id`.tb_os os
    Inner Join `$tenant_id`.tb_cliente as cl on cl.CliID = os.OsCliID";
        if (!empty($filtrar_nome)) {
            // str_replace("'", "", $nome_filtro);
            $sql = $sql . ' And CliNome Like ?';
        }
        if (!empty($filtar_data_os)) {
            $sql = $sql . ' AND OsDtInicial >= ? AND OsDtInicial < ?';
        }
        if (!empty($OsID)) {
            $sql = $sql . ' And OsID = ?';
        }
        if (!empty($$tipo_servico)) {
            $sql = $sql . ' And OsTpServico = ?';
        }
        return $sql;
    }

    public static function FiltrarOsDetalhadaSQL($tenant_id)
    {
        $sql = "SELECT 
        os.*,
        cl.*,
        (
            SELECT JSON_ARRAYAGG(JSON_OBJECT('ServID', s.ServID, 'Descricao', s.ServDescricao, 'Qtd', serv.ServOsQtd, 'SubTotal', serv.ServOsSubTotal))
            FROM `$tenant_id`.tb_servico_os as serv
            INNER JOIN `$tenant_id`.tb_servico as s ON serv.ServOsServID = s.ServID
            WHERE serv.ServOs_osID = os.OsID
        ) AS servicos,
        (
            SELECT JSON_ARRAYAGG(JSON_OBJECT('ProdID', p.ProdID, 'Descricao', p.ProdDescricao, 'Qtd', prod.ProdOsQtd, 'SubTotal', prod.ProdOsSubTotal))
            FROM `$tenant_id`.tb_produto_os as prod
            INNER JOIN `$tenant_id`.tb_produto as p ON prod.ProdOsProdID = p.ProdID
            WHERE prod.ProdOs_osID = os.OsID
        ) AS produtos
    FROM 
        `$tenant_id`.tb_os os
    INNER JOIN 
        `$tenant_id`.tb_cliente as cl ON cl.CliID = os.OsCliID
    WHERE 
        os.OsID = ?";
    return $sql;
    }
    public static function FiltrarStatusSQL($status_filtro, $filtroDe, $filtroAte)
    {
        $sql = "SELECT OsID, OsDtInicial, OsDtFinal, OsGarantia, OsDescProdServ, OsDefeito, OsObs, OsCliID,tb_cliente.CliNome as nomeCliente, tb_cliente.CliTelefone as CliTelefone, OsTecID, OsStatus, OsLaudoTec, OsValorTotal, OsDesconto, OsFaturado FROM tb_os
                Inner Join tb_cliente on tb_cliente.CliID = tb_os.OsCliID

             WHERE OsEmpID = ? ";
        if (!empty($status_filtro)) {
            $sql = $sql .= " And OsStatus IN($status_filtro)";
        }
        if (!empty($filtroDe) && !empty($filtroAte)) {
            $sql = $sql .= " And OsDtInicial Between ? and ?";
        }
        return $sql;
    }
    public static function RetornarOsMesSQL()
    {
        $sql = 'SELECT OsID, OsDtInicial, OsDtFinal, OsGarantia, OsDescProdServ, OsDefeito, OsObs, OsCliID,tb_cliente.CliNome as nomeCliente, tb_cliente.CliTelefone as CliTelefone, OsTecID, OsStatus, OsLaudoTec, OsValorTotal, OsDesconto, OsFaturado FROM tb_os
                Inner Join tb_cliente on tb_cliente.CliID = tb_os.OsCliID

             WHERE OsEmpID = ? AND OsDtInicial Between ? AND ? ';
        return $sql;
    }
    public static function RetornarOsClienteSQL($CliID, $tipo)
    {
        $sql = 'SELECT OsID, OsDtInicial, OsDtFinal, OsGarantia, OsDescProdServ, OsDefeito, OsObs, OsCliID,tb_cliente.CliNome as nomeCliente, tb_cliente.CliTelefone as CliTelefone, OsTecID, OsStatus, OsLaudoTec, OsValorTotal, OsDesconto, OsFaturado FROM tb_os
                Inner Join tb_cliente on tb_cliente.CliID = tb_os.OsCliID WHERE OsEmpID = ?';

        if (!empty($CliID)) {
            $sql .= " AND OsCliID = $CliID";
        }
        if ($tipo != "") {
            $sql .= " AND OsStatus IN($tipo)";
        }

        return $sql;
    }
    public static function DetalharOsSQL($schema)
    {
        $sql = "SELECT os.OsID, os.OsDtInicial, os.OsDtFinal, os.OsGarantia, os.OsDescProdServ, os.OsDefeito, os.OsObs, os.OsCliID, cl.CliNome as nomeCliente, cl.CliTelefone as CliTelefone, os.OsTecID, os.OsStatus, os.OsLaudoTec, os.OsValorTotal, os.OsDesconto, os.OsFaturado, os.OsValorPago, os.OsValorDevido, os.OsTpServico  
        FROM `$schema`.tb_os as os
        Inner Join `$schema`.tb_cliente as cl on cl.CliID = os.OsCliID
    
         WHERE os.OsID = ?";
        return $sql;
    }

    public static function GravarDadosEmail()
    {
        $sql = 'Insert Into tb_emails (destinatario, assunto, mensagem, empresa_id, data_envio, anexo, nome_anexo, tamanho_anexo) Values (?,?,?,?,?,?,?,?)';
        return $sql;
    }

    public static function RetornarDadosEmail($dataInicial, $datafinal)
    {
        $sql = 'SELECT * from tb_emails Where empresa_id = ?';

        if ($dataInicial != "" && $datafinal != "") {
            # code...
            $sql .= " AND DATE(data_envio) BETWEEN ? AND ?";
        }



        return $sql;
    }


    public static function RetornarOrdemFaturadoSQL()
    {
        $sql = 'SELECT OsID, OsFaturado  
                    FROM tb_os
                        WHERE OsEmpID = ? And OsID = ?';
        return $sql;
    }

    public static function RetornarProdOrdemSQL($schema)
    {
        $sql = "SELECT prod_os.*, prod.*
        FROM `$schema`.tb_produto_os as prod_os
            INNER JOIN `$schema`.tb_produto as prod On prod.ProdID = prod_os.ProdOsProdID 
        WHERE ProdOs_osID = ?";
        return $sql;
    }

    public static function RetornarChamadoSQL($schema)
    {
        $sql = "SELECT ch.*, cli.*
        
        FROM
        `$schema`.tb_chamado as ch
        INNER JOIN `$schema`.tb_cliente as cli
        on ch.cliente_CliID = cli.CliID
       ";

        return $sql;
    }
    public static function RetornarServOrdemSQL($schema)
    {
        $sql = "SELECT serv_os.*, serv.*
        FROM `$schema`.tb_servico_os as serv_os
            INNER JOIN `$schema`.tb_servico as serv On serv.ServID = serv_os.ServOsServID 
        WHERE ServOs_osID = ?";
        return $sql;
    }

    public static function RetornarDadosOS()
    {
        $sql = 'SELECT OsID, ProdOs_osID, ServOs_osID, AnxOsID

        FROM tb_os
            Left JOIN tb_produto_os on tb_os.OsID = tb_produto_os.ProdOs_osID 
            Left JOIN tb_servico_os on tb_os.OsID = tb_servico_os.ServOs_osID
            Left JOIN tb_anexo on tb_os.OsID = tb_anexo.AnxOsID WHERE OsEmpID = ?';
        return $sql;
    }

    public static function RetornarAnxOSSQL($schema)
    {
        $sql = "SELECT OsID, img.* FROM `$schema`.tb_os as os Left Join `$schema`.tb_imagem as img On imagemEntidadeID = OsID And imagemEntidadeTipo = 'os' WHERE OsID = ?";
        return $sql;
    }


    public static function RetornarOrdemServSQL()
    {
        $sql = 'SELECT OsID, OsDtInicial, OsDtFinal, OsGarantia, OsDescProdServ, OsDefeito, OsObs, OsCliID,tb_cliente.CliNome as nomeCliente, OsTecID, OsStatus, OsLaudoTec, OsValorTotal, OsFaturado, ServOsQtd, ServOsProdID, ServNome, ServOsSubTotal  
        FROM tb_os
        Inner Join tb_cliente on tb_cliente.CliID = tb_os.OsCliID
        left Join tb_servico_os on tb_servico_os.ServOs_osID = tb_os.OsID
        left Join tb_servico on tb_servico.ServID = tb_servico_os.ServOsServID
      
   
     WHERE OsEmpID = ? And OsID = ?';
        return $sql;
    }

    public static function ExcluirItemOS($schema)
    {
        $sql = "DELETE FROM `$schema`.tb_produto_os WHERE ProdOsID = ?";
        return $sql;
    }
    public static function ExcluirOS()
    {
        $sql = 'DELETE FROM tb_os WHERE OsID = ?';
        return $sql;
    }
    public static function ExcluirAnxOS()
    {
        $sql = 'DELETE FROM tb_anexo WHERE AnxID = ? AND AnxEmpID = ?';
        return $sql;
    }
    public static function ExcluirServOS()
    {
        $sql = 'DELETE FROM tb_servico_os WHERE ServOsID = ?';
        return $sql;
    }

    public static function BuscarItemSQL($schema)
    {
        $sql = "SELECT ProdEstoque, ProdValorVenda
                     FROM `$schema`.tb_produto
                        WHERE ProdID = ?";
        return $sql;
    }
    public static function BuscarServSQL($schema)
    {
        $sql = "SELECT ServValor
                     FROM `$schema`.tb_servico
                        WHERE ServID = ?";
        return $sql;
    }

    public static function AtualizaItemSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_produto set ProdEstoque = ProdEstoque - ?
                        WHERE ProdID = ?";
        return $sql;
    }
    public static function AtualizaExcluiItemSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_produto set ProdEstoque = ProdEstoque + ?
                        WHERE ProdID = ?";
        return $sql;
    }


    public static function FaturarOsSQL()
    {
        $sql = 'UPDATE tb_os set OsFaturado = ?, OsLancamentoID= ? WHERE OsEmpID = ? AND OsID = ?';
        return $sql;
    }
    public static function ExcluiFaturarOsSQL()
    {
        $sql = 'UPDATE tb_os set OsFaturado = ?, OsLancamentoID= ?, OsDesconto =? WHERE OsEmpID = ? AND OsID = ?';
        return $sql;
    }

    public static function InserirItemOsSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_produto_os (ProdOsQtd, ProdOs_osID, ProdOsProdID, ProdOsSubTotal) VALUES (?,?,?,?)";
        return $sql;
    }

    public static function InserirServOsSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_servico_os (ServOsQtd, ServOs_osID, ServOsServID, ServOsSubTotal) VALUES (?,?,?,?)";
        return $sql;
    }

    public static function InserirAnxOsSQL()
    {
        $sql = "INSERT into tb_anexo (AnxNome, AnxUrl, AnxPath, AnxOsID, AnxUserID, AnxEmpID) VALUES (?,?,?,?,?,?)";
        return $sql;
    }

    public static function AtualizaTotalOsSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_os set OsValorTotal = OsValorTotal + ? WHERE OsID = ?";
        return $sql;
    }

    public static function AtualizaPagamentoOsSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_os set OsFaturado = ?, OsDesconto = ?, OsValorPago = OsValorPago + ?, OsValorDevido = ? WHERE OsID = ?";
        return $sql;
    }

    public static function AtualizaExclusaoValorOsSQL()
    {
        $sql = 'UPDATE tb_os set OsValorTotal = OsValorTotal - ? WHERE OsID = ? and OsEmpID = ?';
        return $sql;
    }
}
