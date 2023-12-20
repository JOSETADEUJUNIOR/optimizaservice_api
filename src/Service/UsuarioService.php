<?php

namespace Src\Service;

use Src\Model\UsuarioDAO;
use Src\_public\Util;
use Src\VO\UsuarioVO;
use Src\VO\EmpresaVO;

class UsuarioService
{
    private $dao;

    public function __construct()
    {
        $this->dao = new UsuarioDAO;
    }

    public function cadastrarUsuario(UsuarioVO $vo)
    {
        // Lógica para cadastrar um usuário
    }

    public function alterarUsuario(UsuarioVO $vo)
    {
        if ($this->validarDadosUsuario($vo)) {
            $vo->setStatus(STATUS_ATIVO);

            if (!empty($vo->getSenha())) {
                $vo->setSenha(Util::RetornarSenhaCriptografada($vo->getSenha()));
            }

            $vo->setFuncao(ALTERA_USUARIO);
            $vo->setIdLogado(Util::CodigoLogado() == 0 ? $vo->getId() : Util::CodigoLogado());

            return $this->dao->AlterarUsuarioDAO($vo);
        } else {
            return 0; // ou algum código de erro para dados inválidos
        }
    }

    public function validarLogin($login, $senha)
    {
        // Lógica para validar o login do usuário
    }

    // ... outras regras de negócio ...
}
