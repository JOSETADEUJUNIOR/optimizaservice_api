<?php
namespace Src\Model\SQL;

class EnderecoSQL
{

    public static function ALTERAR_ENDERECO($schema)
    {
       $sql = "UPDATE `$schema`.tb_endereco set rua = ?, bairro = ?, cep = ?, cidade_id = ? WHERE id = ?";
       
       return $sql;
    }

    public static function INSERIR_ENDERECO($schema)
    {
       $sql = "INSERT INTO `$schema`.tb_endereco (rua, bairro, cep, cidade_id, usuario_id) VALUES (?,?,?,?,?)";
       
       return $sql;
    }


    public static function SELECIONAR_ESTADO($schema){
        $sql = "SELECT id FROM `$schema`.tb_estado WHERE sigla_estado LIKE ?";
        return $sql;  
  }

  public static function SELECIONAR_CIDADE($schema){
      $sql = "SELECT id FROM `$schema`.tb_cidade WHERE nome_cidade LIKE ?";
      return $sql;  
}

public static function INSERIR_CIDADE($schema){

    $sql = "INSERT INTO `$schema`.tb_cidade (nome_cidade, estado_id) VALUES (?,?)";

    return $sql;
}
public static function INSERIR_ESTADO($schema){

    $sql = "INSERT INTO `$schema`.tb_estado (sigla_estado) VALUES (?)";

    return $sql;
}

}
