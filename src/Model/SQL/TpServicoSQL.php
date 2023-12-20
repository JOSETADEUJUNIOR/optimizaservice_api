<?php

namespace Src\Model\SQL;


class TpServicoSQL
{

    public static function InserirTpServicoSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_tipo_servico (nome,status) VALUES (?,?)";
        return $sql;
    }

    public static function DetalharTpServicoSQL($schema)
    {
        $sql = "SELECT *
                    FROM `$schema`.tb_tipo_servico WHERE id = ?";
        return $sql;
    }

    public static function RetornarTpServicoSQL($schema)
    {
        $sql = "SELECT * FROM `$schema`.tb_tipo_servico";
        return $sql;
    }
   
    public static function EditarTpServicoSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_tipo_servico set nome = ?, status = ?, where id = ?";
        return $sql;
    }


 
public static function FiltrarTpServicoSQL($filtrar_nome, $schema)
    {
        
        $sql = "SELECT * FROM `$schema`.tb_tipo_servico where 1=1";

        if (!empty($filtrar_nome))
            $sql.=" And ServNome like ?";
        return $sql;
    }
}
