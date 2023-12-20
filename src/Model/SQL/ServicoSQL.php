<?php

namespace Src\Model\SQL;


class ServicoSQL
{

    public static function InserirServicoSQL($schema)
    {
        $sql = "INSERT into `$schema`.tb_servico (ServNome, ServValor, ServDescricao, ServStatus) VALUES (?,?,?,?)";
        return $sql;
    }

    public static function DetalharServicoSQL($schema)
    {
        $sql = "SELECT *
                    FROM `$schema`.tb_servico WHERE ServID = ?";
        return $sql;
    }

    public static function RetornarServicoSQL($schema)
    {
        $sql = "SELECT * FROM `$schema`.tb_servico";
        return $sql;
    }
    public static function ConsultarServicoSQL($filtro_palavra)
    {
        $sql = 'SELECT ServID, ServNome, ServValor, ServDescricao, ServEmpID, ServUserID
        FROM tb_servico';

        if (!empty($filtro_palavra))
            $sql = $sql . ' WHERE ServNome LIKE ?';

        return $sql;
    }

    public static function EditarServicoSQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_servico set ServNome = ?, ServValor = ?, ServDescricao = ?, ServStatus = ? where ServID = ?";
        return $sql;
    }


 
public static function FiltrarServicoSQL($filtrar_nome, $filtrar_valor, $schema)
    {
        
        $sql = "SELECT * FROM `$schema`.tb_servico where 1=1";

        if (!empty($filtrar_nome))
            $sql.=" And ServNome like ?";
        
            if (!empty($filtrar_valor))
            $sql.=" And ServValor like ?";
        

      

        return $sql;
    }




    public static function ExcluirServico()
    {
        $sql = 'DELETE FROM tb_servico where ServID = ?';
        return $sql;
    }

    public static function DADOS_EMPRESA_SQL()
    {
        $sql = 'SELECT EmpNome, EmpCNPJ, EmpLogoPath, EmpEnd, EmpCidade, EmpNumero  FROM tb_empresa WHERE EmpID = ?';
        return $sql;
    }
}
