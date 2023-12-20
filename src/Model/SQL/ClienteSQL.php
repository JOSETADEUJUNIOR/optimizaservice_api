<?php

namespace Src\Model\SQL;

class ClienteSQL{

    public static function INSERT_CLIENTE_SQL($tenant_id)
    {
        $sql = "INSERT INTO `$tenant_id`.tb_cliente (CliNome, CliDtNasc, CliTelefone, CliEmail, CliCep, CliEndereco, CliNumero, CliBairro, CliCidade, CliEstado, CliDescricao, CliStatus, CliUserID, CliCpfCnpj) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        return $sql;
    }

    public static function UPDATE_CLIENTE_SQL($tenant_id)
    {
        $sql = "UPDATE `$tenant_id`.tb_cliente SET CliNome = ?, CliDtNasc = ?, CliTelefone = ?, CliEmail = ?, CliCep = ?, CliEndereco = ?, CliNumero = ?, CliBairro = ?, CliCidade = ?, CliEstado = ?, CliDescricao = ?, CliEmpID = ?, CliStatus = ?, CliUserID = ?, CliCpfCnpj = ? WHERE CliID=?";
        return $sql;
    }

    public static function UPDATE_STATUS_CLIENTE_SQL()
    {
        $sql = 'UPDATE tb_cliente SET CliStatus = ? WHERE CliID = ? AND CliEmpID = ?';
        return $sql;
    }

    public static function SELECT_CLIENTE_SQL($schema)
    {
        
        $sql = "SELECT * FROM `$schema`.tb_cliente";
        return $sql;
    }
    public static function DETALHAR_CLIENTE_SQL($schema)
    {
        $sql = "SELECT * FROM `$schema`.tb_cliente WHERE CliID = ?";
        return $sql;
    }


    public static function FILTER_CLIENTE_SQL($nome_filtro, $nome_cidade, $tenant_id)
    {
        
        $sql = "SELECT * FROM `$tenant_id`.tb_cliente where 1=1";

        if (!empty($nome_filtro))
            $sql.=" And CliNome like ?";
        
            if (!empty($nome_cidade))
            $sql.=" And CliCidade like ?";
        

      

        return $sql;
    }

    public static function RETORNA_CLIENTE_OS_SQL()
    {
        $sql = 'SELECT * FROM tb_cliente WHERE CliID = ? AND CliEmpID = ?';
        return $sql;
    }

    public static function EMAIL_DUPLICADO_CLIENTE_SQL(){
        $sql = 'SELECT CliEmail FROM tb_cliente WHERE CliEmpID = ?';
        return $sql;
    }

    public static function EMAIL_DUPLICADO_USUARIO_SQL(){
        $sql = 'SELECT login FROM tb_usuario WHERE UserEmpID = ?';
        return $sql;
    }
}