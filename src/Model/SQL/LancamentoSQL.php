<?php

namespace Src\Model\SQL;

class LancamentoSQL{

    public static function INSERIR_LANCAMENTO_SQL($schema)
    {
        $sql = "INSERT INTO `$schema`.tb_lancamento (LancDescricao, LancValorPago, LancValorTotal, LancValorDevido, LancDtVencimento, LancDtPagamento, Tipo, LancEntidadeID, LancEntidadeTipo) VALUES (?,?,?,?,?,?,?,?,?)";
        return $sql;
    }

    public static function INSERIR_PARCELA_SQL($schema)
    {
        $sql = "INSERT INTO `$schema`.tb_pagamentos_parcelados (LancID, NumeroParcela, ValorParcela, DataVencimento, DataPagamento, Baixado) VALUES (?,?,?,?,?,?)";
        return $sql;
    }
   
}