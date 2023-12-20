<?php

namespace Src\Controller;

use Src\Model\ServicoDAO;
use Src\VO\ServicoVO;
use Src\_public\Util;

class ServicoController
{

    private $dao;

    public function __construct()
    {
        $this->dao = new ServicoDAO;
    }

    public function CadastrarServicoController(ServicoVO $vo): int
    {

        $vo->setfuncao(CADASTRO_SERVICO);
        $vo->setIdLogado(Util::CodigoLogado());
        $vo->setServStatus(STATUS_ATIVO);
        return $this->dao->CadastrarServicoDAO($vo);
    }

    public function ConsultarServicoController($filtro_palavra): array
    {

        return $this->dao->ConsultarServicoDAO($filtro_palavra);
    }

    public function ConsultarServicoAllController(): array
    {
        return $this->dao->ConsultarServicoAllDAO();
    }


    public function RetornarServicosController($schema): array
    {
        return $this->dao->RetornarServicosDAO($schema);
    }
    

    public function DetalharServicoController($tenant_id, $id_servico)
    {
        if (empty(trim($id_servico))) {
            return 0;
        }
        return $this->dao->DetalharServicoDAO($tenant_id, $id_servico);
    }

    public function FiltrarServicoController($filtro_nome, $filtro_valor, $tenant_id)
    {

        return $this->dao->FiltrarServicoDAO($filtro_nome, $filtro_valor, $tenant_id);
    }

    public function EditarServicoController(ServicoVO $vo): int
    {
        if (empty($vo->getServNome()))
            return 0;

        $vo->setfuncao(ALTERA_SERVICO);
        $vo->setIdLogado($vo->getServUserID());

        return $this->dao->EditarServicoDAO($vo);
    }

    public function ExcluirServicoController(ServicoVO $vo): int
    {
        if (empty($vo->getServID()))
            return 0;

        $vo->setfuncao(EXCLUI_SERVICO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->ExcluirServicoDAO($vo);
    }

    public function DadosEmpresaCTRL()
    {
        return $this->dao->DadosEmpresaDAO();
    }
}
