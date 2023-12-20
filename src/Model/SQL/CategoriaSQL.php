<?php

namespace Src\Model\SQL;

class CategoriaSQL{

    public static function INSERT_CATEGORIA_SQL($schema)
    {
        $sql = "INSERT INTO `$schema`.tb_categoria (nome_categoria, cod, descricao_categoria) VALUES (?,?,?)";
        return $sql;
    }

    public static function EDITAR_CATEGORIA_SQL($tenant_id)
    {
        $sql = "UPDATE `$tenant_id`.tb_categoria SET nome_categoria = ?, cod = ?, descricao_categoria = ?  WHERE id = ?";
        return $sql;
    }

    public static function UPDATE_STATUS_CLIENTE_SQL()
    {
        $sql = 'UPDATE tb_cliente SET CliStatus = ? WHERE CliID = ? AND CliEmpID = ?';
        return $sql;
    }

    public static function RETORNAR_CATEGORIAS_SQL($schema)
    {
        
        $sql = "SELECT Distinct * FROM `$schema`.tb_categoria left join `$schema`.tb_imagem On imagemEntidadeID = id And imagemEntidadeTipo = 'categoria' ";
        return $sql;
    }
    public static function DETALHAR_CATEGORIA_SQL($schema)
    {
        $sql = "SELECT *, imagemID FROM `$schema`.tb_categoria Left Join `$schema`.tb_imagem On imagemEntidadeID = id And imagemEntidadeTipo = 'categoria' WHERE id = ?";
        return $sql;
    }


    public static function FILTRAR_CATEGORIA_SQL($nome_categorias, $nome_cod, $tenant_id)
    {
        
        $sql = "SELECT * FROM `$tenant_id`.tb_categoria Inner Join `$tenant_id`.tb_imagem On imagemEntidadeID = id where 1=1";

        if (!empty($nome_categorias))
            $sql.=" And nome_categoria like ?";
        
            if (!empty($nome_cod))
            $sql.=" And cod like ?";
        

      

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