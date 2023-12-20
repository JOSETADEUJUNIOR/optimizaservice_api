<?php

namespace Src\Controller;

use Src\Model\TpServicoDAO;
use Src\VO\TpServicoVO;
use Src\_public\Util;

class TpServicoController
{

    private $dao;

    public function __construct()
    {
        $this->dao = new TpServicoDAO;
    }

    public function CadastrarTpServicoController(TpServicoVO $vo): int
    {

        $vo->setfuncao(CADASTRO_SERVICO);
        $vo->setIdLogado(Util::CodigoLogado());
        $vo->setTpServStatus(STATUS_ATIVO);
        return $this->dao->CadastrarTpServicoDAO($vo);
    }

    public function RetornarTpServicosController(TpServicoVO $vo): array
    {
        return $this->dao->RetornarTpServicosDAO($vo);
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
