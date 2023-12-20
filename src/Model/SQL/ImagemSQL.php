<?php

namespace Src\Model\SQL;

class ImagemSQL{

    public static function INSERT_IMAGEM_SQL($schema)
    {
        $sql = "INSERT INTO `$schema`.tb_imagem (imagemLogo, imagemPath, imagemEntidadeID, imagemEntidadeTipo) VALUES (?,?,?,?)";
        return $sql;
    }

    public static function UPDATE_IMAGEM_SQL($schema)
    {
        $sql = "UPDATE `$schema`.tb_imagem set imagemLogo = ?, imagemPath = ?, imagemEntidadeID = ?, imagemEntidadeTipo = ?  WHERE imagemID = ?";
        return $sql;
    }

    public static function RETORNA_IMAGEM_ENTIDADE($schema)
    {
        $sql = "SELECT imagemID From `$schema`.tb_imagem Where imagemEntidadeID = ? AND imagemEntidadeTipo = ?";
        return $sql;
    }

   
}