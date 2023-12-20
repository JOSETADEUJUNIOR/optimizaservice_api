<?php

namespace Src\_public;

class Util
{

    public static function IniciarSessao()
    {
        if (empty($_SESSION)) {
            session_start();
        }
    }

    public static function CriarSessao($id, $nome, $empresa)
    {
        self::IniciarSessao();
        $_SESSION['id']   = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['UserEmpID'] = $empresa;
    }

    public static function CriarSession()
    {
        self::IniciarSessao();
        if (isset($_POST['token_data'])) {
    
        $tokenData = json_decode($_POST['token_data'], true);
    
            // Adapte isso conforme necessário para mapear os dados que você deseja armazenar na sessão
            $_SESSION['tecnico_id'] = $tokenData['tecnico_id'];
            $_SESSION['nome'] = $tokenData['nome'];
            $_SESSION['empresa_id'] = $tokenData['empresa_id'];
            $_SESSION['tenant_id'] = $tokenData['tenant_id'];
            $_SESSION['permissao_user'] = $tokenData['permissao_user'];
            $_SESSION['empresa_nome'] = $tokenData['empresa_nome'];
            $_SESSION['empresa_endereco'] = $tokenData['empresa_endereco'];
            $_SESSION['empresa_cnpj'] = $tokenData['empresa_cnpj'];
            // Pode retornar uma confirmação se necessário
            echo "Dados armazenados na sessão com sucesso!";
        } else {
            echo "Erro: Dados do token não recebidos.";
        }
    }
    public static function CodigoLogado()
    {
        self::IniciarSessao();
        return isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    }
    public static function NomeLogado()
    {
        self::IniciarSessao();
        return $_SESSION['nome'];
    }

    public static function EmpresaLogado()
    {
        self::IniciarSessao();
        return $_SESSION['UserEmpID'];
    }
    public static function Deslogar()
    {
        self::IniciarSessao();
        unset($_SESSION['id']);
        unset($_SESSION['nome']);
        unset($_SESSION['UserEmpID']);
        self::IrParaLogin();
    }

    public static function IrParaLogin()
    {
        header('location: login.php');
        exit;

    }


    public static function LogHoraUsuario(){
        // Obtém o horário atual e o endereço IP do usuário
        $hora = date('H:i:s');
        return $hora;
    }
    public static function LogIPUsuario(){
        // Obtém o horário atual e o endereço IP do usuário
        $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }
    
    public static function VerificarLogado()
    {
       self::IniciarSessao();
        if (!isset($_SESSION['id'])) {
            self::IrParaLogin();
        }
    }

    public static function TratarDados($palavra)
    {

        return strip_tags(trim($palavra));
    }

    public static function DataHoraAtualPDF()
    {
        self::SetarFusoHorario();
        return date('d-m-Y_H-i-s');
    }

    public static function Status($status){
        $tipo = "";
        if ($status == 1){
            $tipo = "Ativo";
        } else {
            $tipo = "Inativo";
        }
        return $tipo;
    }

    private static function SetarFusoHorario()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    public static function HoraAtual()
    {
        self::SetarFusoHorario();
        return date('H:i:s');
    }

    
    public static function debug($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    public static function chamarPagina($pag)
    {

        header("location: $pag");
        exit;
    }
    public static function DataAtual()
    {
        self::SetarFusoHorario();
        return date('d/m/Y');
    }
    public static function DataAtualBd()
    {
        self::SetarFusoHorario();
        return date('Y-m-d H:i:s');
    }
    public static function obterDiaDaSemana($data) {
        // Converte a data em um carimbo de data e hora Unix
        $timestamp = strtotime($data);
    
        // Array com os nomes dos dias da semana
        $diasDaSemana = array(
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            7 => 'Domingo'
        );
    
        // Obtém o número do dia da semana (1 a 7)
        $numeroDiaDaSemana = date('N', $timestamp);
    
        // Retorna o nome do dia da semana
        return $diasDaSemana[$numeroDiaDaSemana];
    }
    

    public static function bytesToMB($bytes) {
        if ($bytes <= 0) {
            return '0 MB';
        }
    
        $tamanhoEmMB = $bytes / (1024 * 1024);
        return round($tamanhoEmMB, 2) . ' MB';
    }
    

    //Remove pontuação
    public static function remove_especial_char($string)
    {
        $especiais = array(".", ",", ";", "!", "@", "#", "%", "¨", "*", "(", ")", "+", "-", "=", "§", "$", "|", "\\", ":", "/", "<", ">", "?", "{", "}", "[", "]", "&", "'", '"', "´", "`", "?", '“', '”', '$', "'", "'");
        $string = str_replace($especiais, "", strip_tags(trim($string)));
        return $string;
    }
    public static function RetornarSenhaCriptografada($palavra)
    {
        return password_hash($palavra, PASSWORD_DEFAULT);
    }

    public static function ExibirDataBr($data, $formato = 'data')
    {
        if ($data == "") {
            return "";
        } else {
            $data_array = explode(' ', $data);
            $dataPart = $dataHoraPart = "";
    
            if ($formato == 'data' || $formato == 'datahora') {
                $dataPart = $data_array[0];
                // Converte a data para o formato desejado (dd/mm/yyyy)
                $dataPart = date('d/m/Y', strtotime($dataPart));
            }
    
            if ($formato == 'hora' || $formato == 'datahora') {
                $dataHoraPart = isset($data_array[1]) ? ' ' . $data_array[1] : '';
                // Converte a hora para o formato desejado (hh:mm:ss)
                $dataHoraPart = date('H:i:s', strtotime($dataHoraPart));
            }
    
            if ($formato == 'datahora') {
                return $dataPart .' às '. $dataHoraPart;
            } else {
                return $dataPart;
            }
        }
    }
    
    public static function formatarDataParaBanco($data) {
        // Converte o formato da data de dd-mm-yyyy para yyyy-mm-dd
        $dataFormatada = date('Y-m-d', strtotime($data));
        return $dataFormatada;
    }

    public static function formatarDataHoraParaBanco($dataHora)
    {
        // Converte a data e hora para o formato do banco de dados (YYYY-MM-DD HH:mm:ss)
        $dataFormatada = date('Y-m-d H:i:s', strtotime($dataHora));
        return $dataFormatada;
    }
    

    public static function formatarDataParaBancoSql($data) {
        // Converte a data do formato 'dd-mm-yyyy' para 'yyyy-mm-dd'
        $dataFormatada = date('Y-m-d', strtotime(str_replace('-', '/', $data)));
        return $dataFormatada;
    }

    public static function FormatarValor($number)
    {
        return  number_format($number, 2, ',', '.');
    }

    public static function CriarSenha($palavra)
    {

        $senha_array = explode('@', $palavra);

        return password_hash($senha_array[0], PASSWORD_DEFAULT);
    }

    public static function ValidarSenhaBanco($senha, $senha_hash)
    {
        return password_verify($senha, $senha_hash);
    }


    public static function Criptografar($palavra)
    {

        $senha_array = ($palavra);

        return password_hash($senha_array, PASSWORD_DEFAULT);
    }

    public static function Descriptografar($senha, $senha_hash)
    {
        return password_verify($senha, $senha_hash);
    }

    public static function DescricaoTipo($tipo): string
    {
        $nome = '';
        switch ($tipo) {
            case PERFIL_ADM:
                $nome = "ADMINISTRADOR";
                break;
            case PERFIL_TECNICO:
                $nome = "TÉCNICO";
                break;
            case PERFIL_FUNCIONARIO:
                $nome = "FUNCIONÁRIO";
                break;
        }
        return $nome;
    }

    public static function DescricaoStatus($status): string
    {
        $nome = '';
        switch ($status) {
            case STATUS_ATIVO:
                $nome = "ATIVO";
                break;
            case STATUS_INATIVO:
                $nome = "INATIVO";
                break;
        }
        return $nome;
    }

    public static function CreateTokenAuthentication(array $dados_user)
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = $dados_user;
        $header = json_encode($header);
        $payload = json_encode($payload);
        $header = base64_encode($header);
        $payload = base64_encode($payload);
        $sign = hash_hmac(
            "sha256",
            $header . '.' . $payload,
            SECRET_JWT,
            true
        );
        $sign = base64_encode($sign);
        $token = $header . '.' . $payload . '.' . $sign;
        return $token;
    }



    public static function AuthenticationTokenAccess()
    {
        
        //Recupera todo o cabeçalho da requisição
        $http_header = apache_request_headers();
        //Se n for nulo
        if (
            $http_header['Authorization'] != null && str_contains($http_header['Authorization'], '.')
            ) :
            //quebra o bearer(autenticação de token)
            $bearer = explode(' ', $http_header['Authorization']);
            $token = explode('.', $bearer[1]);
            //quebrando os valores e jogango em seus significados
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];
            //Faz a criptografia novamente para ver se bate com a chave
            $valid = hash_hmac(
                'sha256',
                $header . '.' . $payload,
                SECRET_JWT,
                true
            );
            $valid = base64_encode($valid);
            
            if ($valid === $sign)
                return true;
            else
                return false;
        endif;
        return false;
    }

    public static function FormatarValorMoedaExibir($val) {
        $valor = strval($val);
        // Adiciona vírgula e zeros no final se não houver casas decimais
        if (strpos($valor, '.') === false) {
            $valor = str_replace('.', ',', $valor) . ',00';
        } else {
            $partes = explode('.', $valor);
            if (strlen($partes[1]) === 1) {
                $valor .= '0';
            }
        }
        // Formata o valor como um valor monetário no formato brasileiro
        $valorFinal = number_format(floatval(str_replace(',', '.', $valor)), 2, ',', '.');
        return 'R$ ' . $valorFinal;
    }
}
