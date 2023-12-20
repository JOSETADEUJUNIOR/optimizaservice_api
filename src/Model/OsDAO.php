<?php

namespace Src\Model;

use DateTime;
use Src\Model\Conexao;
use Src\VO\OsVO;
use Src\Model\SQL\Os;
use Src\_public\Util;
use Src\Model\SQL\ChamadoSQL;
use Src\Model\SQL\Financeiro;
use Src\Model\SQL\ImagemSQL;
use Src\Model\SQL\LancamentoSQL;
use Src\VO\ProdutoOSVO;
use Src\VO\ServicoOSVO;
use Src\VO\AnxOSVO;
use Src\VO\ImagemVO;
use Src\VO\LancamentoVO;
use Src\VO\SendMailVO;

class OsDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function RetornarOsDAO(OsVO $vo)
    {
        $sql = $this->conexao->prepare(Os::RetornarOsSQL($vo->getOsSchema()));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RetornarPagamentosOSDAO(OsVO $vo)
    {
        $sql = $this->conexao->prepare(Os::RetornarPagamentosOsSQL($vo->getOsSchema()));
        $sql->bindValue(1, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    
    public function DetalharOsDAO(OsVO $vo)
    {
        $sql = $this->conexao->prepare(Os::DetalharOsSQL($vo->getOsSchema()));
        $sql->bindValue(1, $vo->getID());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function RealizarPagamentoOsDAO(LancamentoVO $vo)
    {

        $valorPago =(float) $vo->getValor();
        $valorTotal = (float)$vo->getLancValorTotal();
        $valorDesconto = (float)$vo->getDesconto();
        $valorDevido = max(0, $valorTotal - $valorDesconto - $valorPago);
        
        $sql = $this->conexao->prepare(LancamentoSQL::INSERIR_LANCAMENTO_SQL($vo->getLancSchema()));
        $i = 1;
        $sql->bindValue($i++, $vo->getDescricao());
        $sql->bindValue($i++, $valorPago);
        $sql->bindValue($i++, $valorTotal);
        $sql->bindValue($i++, $valorDevido);
        $sql->bindValue($i++, Util::formatarDataParaBanco($vo->getDataVencimento()));
        $sql->bindValue($i++, Util::formatarDataParaBanco($vo->getDataPagamento()));
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getLancEntidadeID());
        $sql->bindValue($i++, 'ordem');
        try {
            $sql->execute();
            $UltimoLancID = $this->conexao->lastInsertId();
            $dataVencimento = new DateTime($vo->getDataVencimento());
            
            if ($vo->getLancQtdParcela()>0) {
                $valorParcela = ($vo->getLancValorTotal() / $vo->getLancQtdParcela());
                $dataVencimento->modify('+30 days');
                for ($p=1; $p <=$vo->getLancQtdParcela() ; $p++) { 
                    $sql = $this->conexao->prepare(LancamentoSQL::INSERIR_PARCELA_SQL($vo->getLancSchema()));
                    $e = 1;
                    $sql->bindValue($e++, $UltimoLancID);
                    $sql->bindValue($e++, $p);
                    $sql->bindValue($e++, $valorParcela);
                    $sql->bindValue($e++, $dataVencimento->format('Y-m-d'));
                    $sql->bindValue($e++, Util::formatarDataParaBanco($vo->getDataPagamento()));
                    $sql->bindValue($e++, 'N');
                    $sql->execute();

                    $dataVencimento->modify('+1 month');
                }
            }
           
            $OsFaturado = 'N';
            
            if ($valorDevido <= 0) {
                $OsFaturado = 'S';
            }
            $sql = $this->conexao->prepare(Os::AtualizaPagamentoOsSQL($vo->getLancSchema()));
            $sql->bindValue(1, $OsFaturado);
            $sql->bindValue(2, $valorDesconto);
            $sql->bindValue(3, $valorPago);
            $sql->bindValue(4, $valorDevido);
            $sql->bindValue(5, $vo->getLancEntidadeID());
            $sql->execute();
            return 1;

        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    


    public function CadastrarOsDAO(OsVO $vo)
    {
        
        $sql = $this->conexao->prepare(Os::InserirOsSQL($vo->getOsSchema()));
        $i = 1;
        $sql->bindValue($i++, Util::formatarDataHoraParaBanco($vo->getDtInicial()));
        $sql->bindValue($i++, Util::formatarDataHoraParaBanco($vo->getOsDtFinal()));
        $sql->bindValue($i++, $vo->getOsGarantia());
        $sql->bindValue($i++, $vo->getOsDescProdServ());
        $sql->bindValue($i++, $vo->getOsDefeito());
        $sql->bindValue($i++, $vo->getOsObs());
        $sql->bindValue($i++, $vo->getOsCliID());
        $sql->bindValue($i++, $vo->getOsTecID());
        $sql->bindValue($i++, $vo->getOsStatus());
        $sql->bindValue($i++, $vo->getOsLaudoTec());
        $sql->bindValue($i++, $vo->getOsTpServico());
        try {
            $sql->execute();
            $UltimoLancID = $this->conexao->lastInsertId();

            if ($vo->getOsAgendamento()=="S") {
                $sql = $this->conexao->prepare(ChamadoSQL::AbrirChamadoSQL($vo->getOsSchema()));
                $i = 1;
                $dataAbertura = Util::formatarDataHoraParaBanco($vo->getDtInicial());
                
                $sql->bindValue($i++, $dataAbertura);
                $sql->bindValue($i++, $vo->getOsDescProdServ());
                $sql->bindValue($i++, $vo->getOsObs());
                $sql->bindValue($i++, $vo->getOsCliID());
                $sql->execute();
            }
            return $UltimoLancID ;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function EditarOsDAO(OsVO $vo)
    {
        
        $sql = $this->conexao->prepare(Os::EditarOsSQL($vo->getOsSchema()));
        $i = 1;
        $sql->bindValue($i++, Util::formatarDataHoraParaBanco($vo->getDtInicial()));
        $sql->bindValue($i++, Util::formatarDataHoraParaBanco($vo->getOsDtFinal()));
        $sql->bindValue($i++, $vo->getOsGarantia());
        $sql->bindValue($i++, $vo->getOsDescProdServ());
        $sql->bindValue($i++, $vo->getOsDefeito());
        $sql->bindValue($i++, $vo->getOsObs());
        $sql->bindValue($i++, $vo->getOsCliID());
        $sql->bindValue($i++, $vo->getOsTecID());
        $sql->bindValue($i++, $vo->getOsStatus());
        $sql->bindValue($i++, $vo->getOsLaudoTec());
        $sql->bindValue($i++, $vo->getOsTpServico());
        $sql->bindValue($i++, $vo->getID());
        try {
            $sql->execute();
            return 1 ;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function FiltrarOsDAO($filtrar_nome, $filtar_data_os, $tenant_id, $OsID, $tipo_servico)
    {
        $sql = $this->conexao->prepare(Os::FiltrarOsSQL($filtrar_nome, $filtar_data_os, $tenant_id, $OsID, $tipo_servico));
        $i = 1;
        if (!empty($filtrar_nome)) {
            $sql->bindValue($i++, '%' . $filtrar_nome . '%');
        }
        if (!empty($filtar_data_os)) {
            $sql->bindValue($i++, Util::formatarDataHoraParaBanco($filtar_data_os . ' 00:00:00'));
            $sql->bindValue($i++, Util::formatarDataHoraParaBanco($filtar_data_os . ' 23:59:59'));
            
        }
        if (!empty($OsID)) {
            $sql->bindValue($i++, $OsID);
        }
        if (!empty($tipo_servico)) {
            $sql->bindValue($i++, $tipo_servico);
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function FiltrarOsDetalhadaSQL($tenant_id, $OsID)
    {
        $sql = $this->conexao->prepare(Os::FiltrarOsDetalhadaSQL($tenant_id, $OsID));
        $i = 1;
        $sql->bindValue($i++, $OsID);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    
    
    public function GravarDadosEmailDAO(SendMailVO $vo){
        $sql = $this->conexao->prepare(Os::GravarDadosEmail());

        $conteudoArquivo = file_get_contents($vo->getAnexo());

        $sql->bindValue(1, $vo->getDestinatario());
        $sql->bindValue(2, $vo->getAssunto());
        $sql->bindValue(3, $vo->getMensagem());
        $sql->bindValue(4, Util::EmpresaLogado());
        $sql->bindValue(5, Util::DataAtualBd());
        $sql->bindValue(6, $vo->getAnexo());
        $sql->bindValue(7, $vo->getNome_anexo());
        $sql->bindValue(8, $vo->getTamanho_arquivo());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        } 
    }
    public function RetornarDadosEmailDAO($dataInicial, $datafinal): array
    {
        
        $sql = $this->conexao->prepare(Os::RetornarDadosEmail($dataInicial, $datafinal));
        $sql->bindValue(1, Util::EmpresaLogado());
        
        // Converte as datas iniciais e finais em timestamps UNIX
        $timestampInicial = strtotime($dataInicial);
        $timestampFinal = strtotime($datafinal);
    
        // Formata os timestamps como datas no formato "YYYY-MM-DD"
        $dataFormatadaInicial = date("Y-m-d", $timestampInicial);
        $dataFormatadaFinal = date("Y-m-d", $timestampFinal);
        if ($dataInicial!="" && $datafinal!="") {
            $sql->bindValue(2, $dataFormatadaInicial);
            $sql->bindValue(3, $dataFormatadaFinal);
        
            
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    
  
    public function AlterarOsDAO(OsVO $vo): int
    {
        $sql = $this->conexao->prepare(Os::AlterarOsSQL());
        $sql->bindValue(1, $vo->getDtInicial());
        $sql->bindValue(2, $vo->getOsDtFinal());
        $sql->bindValue(3, $vo->getOsGarantia());
        $sql->bindValue(4, $vo->getOsDescProdServ());
        $sql->bindValue(5, $vo->getOsDefeito());
        $sql->bindValue(6, $vo->getOsObs());
        $sql->bindValue(7, $vo->getOsCliID());
        $sql->bindValue(8, $vo->getOsTecID());
        $sql->bindValue(9, $vo->getOsStatus());
        $sql->bindValue(10, $vo->getOsLaudoTec());
        $sql->bindValue(11, Util::EmpresaLogado());
        $sql->bindValue(12, $vo->getID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }
    public function FaturarOsDAO(OsVO $vo): int
    {

        $sql = $this->conexao->prepare(Financeiro::InserirLancamentoSQL());
        $sql->bindValue(1, 'Receita da OS:' . $vo->getID() . '');
        $sql->bindValue(2, $vo->getOsValorTotal());
        $sql->bindValue(3, date('Y-m-d'));
        $sql->bindValue(4, date('Y-m-d'));
        $sql->bindValue(5, 'N');
        $sql->bindValue(6, 'D');
        $sql->bindValue(7, 1);
        $sql->bindValue(8, $vo->getOsCliID());
        $sql->bindValue(9, Util::EmpresaLogado());
        $sql->bindValue(10, Util::CodigoLogado());
        $sql->execute();

        $UltimoLancID = $this->conexao->lastInsertId();

        $sql = $this->conexao->prepare(Os::RetornarOrdemFaturadoSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, $vo->getID());
        $sql->execute();

        $dadosOS = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $statusFatura = $dadosOS[0]['OsFaturado'];

        if ($statusFatura == 'N') {
            $sql = $this->conexao->prepare(Os::FaturarOsSQL());
            $sql->bindValue(1, 'S');
            $sql->bindValue(2, $UltimoLancID);
            $sql->bindValue(3, Util::EmpresaLogado());
            $sql->bindValue(4, $vo->getID());
        } else {
            $sql = $this->conexao->prepare(Os::FaturarOsSQL());
            $sql->bindValue(1, 'N');
            $sql->bindValue(2, Util::EmpresaLogado());
            $sql->bindValue(3, $vo->getID());
        }
        try {
            $sql->execute();

            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function InserirItemOsDAO(ProdutoOSVO $vo): int
    {
        $sql = $this->conexao->prepare(Os::BuscarItemSQL($vo->getProdOsSchema()));
        $sql->bindValue(1, $vo->getOsProdID());
        $sql->execute();
        
        $dadosItem = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $estoque = $dadosItem[0]['ProdEstoque'];
        $valor = $dadosItem[0]['ProdValorVenda'];
        $qtd = $vo->getProdQtd();
        if ($estoque < $qtd) {
            return -13;
        }
        
        $sql = $this->conexao->prepare(Os::AtualizaItemSQL($vo->getProdOsSchema()));

        $sql->bindValue(1, $qtd);
        $sql->bindValue(2, $vo->getOsProdID());
        $sql->execute();

        $SubTotal = $valor * $qtd;

        $sql = $this->conexao->prepare(Os::InserirItemOsSQL($vo->getProdOsSchema()));
        $sql->bindValue(1, $vo->getProdQtd());
        $sql->bindValue(2, $vo->getOsID());
        $sql->bindValue(3, $vo->getOsProdID());
        $sql->bindValue(4, $SubTotal);
        
        try {
            $sql->execute();

            $sql = $this->conexao->prepare(Os::AtualizaTotalOsSQL($vo->getProdOsSchema()));
            $sql->bindValue(1, $SubTotal);
            $sql->bindValue(2, $vo->getOsID());
            $sql->execute();

            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function InserirAnxOsDAO(ImagemVO $vo): int
    {
        $sql = $this->conexao->prepare(ImagemSQL::INSERT_IMAGEM_SQL($vo->getImagemSchema()));
        
            $i = 1;
            $sql->bindValue($i++, $vo->getImagemLogo());
            $sql->bindValue($i++, $vo->getImagemPath());
            $sql->bindValue($i++, $vo->getImagemEntidadeID());
            $sql->bindValue($i++, 'os');
            $sql->execute();


        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }
    public function InserirServOsDAO(ServicoOSVO $vo): int
    {
        
        $sql = $this->conexao->prepare(Os::BuscarServSQL($vo->getServOsSchema()));
        $sql->bindValue(1, $vo->getOsServID());
        $sql->execute();

        $dadosServ = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $valor = $dadosServ[0]['ServValor'];
        $qtd = $vo->getServQtd();
        $SubTotal = $valor * $qtd;



        $sql = $this->conexao->prepare(Os::InserirServOsSQL($vo->getServOsSchema()));
        $sql->bindValue(1, $vo->getServQtd());
        $sql->bindValue(2, $vo->getOsID());
        
        $sql->bindValue(3, $vo->getOsServID());
        $sql->bindValue(4, $SubTotal);
        $sql->execute();
        
        try {
            $sql = $this->conexao->prepare(Os::AtualizaTotalOsSQL($vo->getServOsSchema()));
            $sql->bindValue(1, $SubTotal);
            $sql->bindValue(2, $vo->getOsID());
            $sql->execute();

            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function RetornarOrdemDAO(OsVO $vo): array
    {
        $sql = $this->conexao->prepare(Os::RetornarOrdemSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function CarregarProdutosOsDAO(OsVO $vo)
    {
        $sql = $this->conexao->prepare(Os::RetornarProdOrdemSQL($vo->getOsSchema()));
        $sql->bindValue(1, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function CarregarServicosOsDAO(OsVO $vo)
    {
        $sql = $this->conexao->prepare(Os::RetornarServOrdemSQL($vo->getOsSchema()));
        $sql->bindValue(1, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    

    
    public function RetornarProdOrdemDAO(OsVO $vo): array
    {
        $sql = $this->conexao->prepare(Os::RetornarProdOrdemSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RetornarChamadoDAO($tenant_id): array
    {
        $sql = $this->conexao->prepare(Os::RetornarChamadoSQL($tenant_id));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    
    public function RetornarServOrdemDAO(OsVO $vo): array
    {
        $sql = $this->conexao->prepare(Os::RetornarServOrdemSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function RetornarAnxOSDAO(OsVO $vo): array
    {
        $sql = $this->conexao->prepare(Os::RetornarAnxOSSQL($vo->getOsSchema()));
        $sql->bindValue(1, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RetornarOrdemServDAO(OsVO $vo): array
    {
        $sql = $this->conexao->prepare(Os::RetornarOrdemServSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, $vo->getID());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function ExcluirItemOSDAO(ProdutoOSVO $vo)
    {
        $sql = $this->conexao->prepare(Os::BuscarItemSQL($vo->getProdOsSchema()));
        $sql->bindValue(1, $vo->getOsProdID());
        $sql->execute();

        $dadosItem = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $valor = $dadosItem[0]['ProdValorVenda'];
        $qtd = $vo->getProdQtd();

        $sql = $this->conexao->prepare(Os::AtualizaExcluiItemSQL($vo->getProdOsSchema()));
        $sql->bindValue(1, $qtd);
        $sql->bindValue(2, $vo->getOsProdID());
        $sql->execute();

        $sql = $this->conexao->prepare(Os::ExcluirItemOS($vo->getProdOsSchema()));
        $sql->bindValue(1, $vo->getProdOsID());

        $sql->execute();


        $SubTotal = $valor * $qtd;

        try {
            $sql->execute();

            $sql = $this->conexao->prepare(Os::AtualizaExclusaoValorOsSQL());
            $sql->bindValue(1, $SubTotal);
            $sql->bindValue(2, $vo->getOsID());
            $sql->bindValue(3, Util::EmpresaLogado());
            $sql->execute();


            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }

    public function ExcluirOSDAO(OSVO $vo)
    {

        $sql = $this->conexao->prepare(Os::ExcluirOS());
        $sql->bindValue(1, $vo->getID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }

    public function RetornarDadosOsDAO()
    {
        $sql = $this->conexao->prepare(Os::RetornarDadosOS());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ExcluirAnxOSDAO(AnxOSVO $vo)
    {

        $sql = $this->conexao->prepare(Os::ExcluirAnxOS());
        $sql->bindValue(1, $vo->getAnxID());
        $sql->bindValue(2, Util::EmpresaLogado());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }


    public function ExcluirServOSDAO(ServicoOSVO $vo)
    {

        $sql = $this->conexao->prepare(Os::BuscarServSQL($vo->getServOsSchema()));
        $sql->bindValue(1, $vo->getOsServID());
      
        $sql->execute();

        $dadosItem = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $valor = $dadosItem[0]['ServValor'];
        $qtd = $vo->getServQtd();

        $sql = $this->conexao->prepare(Os::ExcluirServOS($vo->getServOsSchema()));
        $sql->bindValue(1, $vo->getID());

        $sql->execute();


        $SubTotal = $valor * $qtd;

        try {

            $sql = $this->conexao->prepare(Os::AtualizaExclusaoValorOsSQL($vo->getServOsSchema()));
            $sql->bindValue(1, $SubTotal);
            $sql->bindValue(2, $vo->getOsID());
            $sql->bindValue(3, Util::EmpresaLogado());
            $sql->execute();


            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }
   
    public function FiltrarStatusDAO($status_filtro, $filtroDe, $filtroAte)
    {
        $sql = $this->conexao->prepare(Os::FiltrarStatusSQL($status_filtro, $filtroDe, $filtroAte));
        $sql->bindValue(1, Util::EmpresaLogado());
        if (!empty($filtroDe) && !empty($filtroAte)) {
            $sql->bindValue(2, $filtroDe);
            $sql->bindValue(3, $filtroAte);
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

   
    public function RetornarOsMesDAO()
    {
        $sql = $this->conexao->prepare(Os::RetornarOsMesSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->bindValue(2, date('Y-m-01'));
        $sql->bindValue(3, date('Y-m-t'));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function RetornarOsClienteDAO($CliID, $tipo)
    {
        $sql = $this->conexao->prepare(Os::RetornarOsClienteSQL($CliID, $tipo));
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
}
