<?php

namespace Src\Model\SQL;



class UsuarioSQL
{

  public static function RetornarDadosCadastrais($schema)
  {
    $sql =  "SELECT EmpID, id, EmpNome, EmpDtCadastro,EmpDtVencimento, EmpPlano, EmpEnd, EmpCNPJ, EmpEnd, EmpCep, EmpNumero, EmpCidade, EmpStatus, EmpLogo, 
	                    EmpLogoPath,
                        case tipo
                            When 1 Then 'Administrador'
                            When 2 Then 'funcionario'
                            When 3 Then 'Tecnico'
                            End as tipo,
                        nome, login, senha, telefone
                 FROM `$schema`.tb_empresa
		         INNER JOIN `$schema`.tb_usuario on
			            tb_empresa.EmpID = tb_usuario.UserEmpID WHERE EmpID = ?";

    return $sql;
  }

  public static function AlterarEmpresaSQL()
  {
    $sql = 'UPDATE tb_empresa set EmpNome = ?, EmpCNPJ = ?, EmpDtCadastro = ?, EmpDtVencimento = ?, EmpCep = ?, EmpEnd = ?, EmpCidade = ?, EmpNumero = ?,  EmpPlano = ?, EmpStatus = ? WHERE EmpID = ? ';
    return $sql;
  }

  public static function AlterarEmpresaSLSQL()
  {
    $sql = 'UPDATE tb_empresa set EmpNome = ?, EmpCNPJ = ?, EmpEnd = ?,
                                      EmpCep = ?, EmpNumero = ?, EmpCidade = ? WHERE EmpID = ? ';
    return $sql;
  }
  public static function INSERIR_USUARIO_MASTER($schema)
  {

    $sql = "INSERT INTO `$schema`.tb_usuario (tipo, nome, login, senha, status, telefone, UserEmpID, tenant_id) Values (?,?,?,?,?,?,?,?)";
    return $sql;
  }
  public static function INSERIR_PERMISSAO($schema)
  {
    $sql = " INSERT INTO `$schema`.tb_permissao (user_id, permission_key) Values (?,?)";
    return $sql;
  }

  public static function INSERIR_EMPRESA($schema)
  {
    $sql = "INSERT INTO `$schema`.tb_empresa (EmpNome, EmpPlano, EmpDtCadastro, EmpDtVencimento) VALUES (?,?,?,?)";
    return $sql;
  }


  public static function CRIAR_SCHEMA($schemaNome)
  {
    $sql = "CREATE SCHEMA IF NOT EXISTS `$schemaNome`";
    $sql .= "; GRANT ALL PRIVILEGES ON `$schemaNome`.* TO 'root'@'localhost';";
    return $sql;
  }


  public static function CRIAR_TABELA_USUARIO($schemaNome)
  {
    $sql = "CREATE TABLE IF NOT EXISTS `$schemaNome`.tb_usuario (
            id int(11) NOT NULL AUTO_INCREMENT,
            tipo smallint(6) NOT NULL COMMENT '1 - Adm\n2 - funcionario\n3 - tecnico',
            nome varchar(50) NOT NULL,
            login varchar(45) NOT NULL,
            senha varchar(60) NOT NULL,
            status tinyint(1) NOT NULL,
            telefone varchar(20) NOT NULL,
            UserEmpID int(11) NOT NULL,
            UserLogo varchar(100) DEFAULT NULL,
            UserLogoPath varchar(100) DEFAULT NULL,
            tenant_id varchar(45),
            PRIMARY KEY (id),
  KEY fk_tb_usuario_Empresa1_idx (UserEmpID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        CREATE TABLE `$schemaNome`.tb_empresa (
        EmpID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        EmpNome varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpEnd varchar(150) COLLATE utf8_bin DEFAULT NULL,
        EmpCNPJ varchar(18) COLLATE utf8_bin DEFAULT NULL,
        EmpPlano char(1) COLLATE utf8_bin DEFAULT NULL,
        EmpStatus char(1) COLLATE utf8_bin DEFAULT 'A',
        EmpDtCadastro varchar(45) COLLATE utf8_bin NOT NULL,
        EmpDtRenovacao datetime DEFAULT NULL,
        EmpDtVencimento datetime DEFAULT NULL,
        EmpLogo varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpLogoPath varchar(100) COLLATE utf8_bin DEFAULT NULL,
        EmpCep varchar(45) COLLATE utf8_bin DEFAULT NULL,
        EmpNumero varchar(45) COLLATE utf8_bin DEFAULT NULL,
        EmpCidade varchar(100) COLLATE utf8_bin DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
      CREATE TABLE `$schemaNome`.tb_cidade (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome_cidade varchar(45) NOT NULL,
        estado_id int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
      CREATE TABLE `$schemaNome`.tb_endereco (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    rua varchar(45) NOT NULL,
    bairro varchar(50) NOT NULL,
    cep varchar(8) NOT NULL,
    cidade_id int(11) NOT NULL,
    usuario_id int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  CREATE TABLE `$schemaNome`.tb_estado (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_estado varchar(45) NOT NULL,
    sigla_estado varchar(2) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  CREATE TABLE `$schemaNome`.tb_alocar (
  id int(11) NOT NULL PRIMARY KEY,
  situacao smallint(6) NOT NULL,
  data_alocacao date NOT NULL,
  data_remocao date DEFAULT NULL,
  equipamento_id int(11) NOT NULL,
  setor_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_anexo (
  AnxID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  AnxNome varchar(45) DEFAULT NULL,
  AnxUrl varchar(100) DEFAULT NULL,
  AnxPath varchar(100) DEFAULT NULL,
  AnxOsID int(11) NOT NULL,
  AnxUserID int(11) NOT NULL,
  AnxEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_chamado (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  data_abertura datetime NOT NULL,
  descricao_problema varchar(500) NOT NULL,
  data_atendimento datetime DEFAULT NULL,
  data_encerramento datetime DEFAULT NULL,
  laudo_tecnico varchar(500) DEFAULT NULL,
  funcionario_id int(11) NOT NULL,
  tecnico_atendimento int(11) DEFAULT NULL,
  tecnico_encerramento int(11) DEFAULT NULL,
  alocar_id int(11) DEFAULT NULL,
  numero_nf int(11) NOT NULL,
  defeito text DEFAULT NULL,
  observacao text DEFAULT NULL,
  status char(2) DEFAULT NULL,
  cliente_CliID int(11) NOT NULL,
  empresa_EmpID int(11) NOT NULL,
  lote_id int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_cliente (
  CliID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  CliNome varchar(150) NOT NULL,
  CliDtNasc date DEFAULT NULL,
  CliTelefone varchar(45) NOT NULL,
  CliEmail varchar(100) NOT NULL,
  CliCep varchar(45) NOT NULL,
  CliEndereco varchar(150) NOT NULL,
  CliNumero varchar(20) NOT NULL,
  CliBairro varchar(100) NOT NULL,
  CliCidade varchar(100) NOT NULL,
  CliEstado varchar(100) NOT NULL,
  CliDescricao text DEFAULT NULL,
  CliEmpID int(11) NOT NULL,
  CliStatus char(1) NOT NULL DEFAULT 'A',
  CliUserID int(11) NOT NULL,
  CliCpfCnpj varchar(45) DEFAULT NULL,
  CliTipo char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_equipamento (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  identificacao varchar(20) NOT NULL,
  descricao varchar(90) NOT NULL,
  tipoequip_id int(11) NOT NULL,
  modeloequip_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_equipamento_insumo (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  produto_ProdID int(11) NOT NULL,
  equipamento_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
CREATE TABLE `$schemaNome`.tb_equipamento_servico (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  equipamento_id int(11) NOT NULL,
  servico_ServID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
CREATE TABLE `$schemaNome`.tb_funcionario (
  funcionario_id int(11) NOT NULL,
  setor_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_garantia_os (
  grtID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  grtNome varchar(45) NOT NULL,
  grtText text NOT NULL,
  grtOsID int(11) DEFAULT NULL,
  grtEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_itens_venda (
  ItensID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ItensSubTotal decimal(10,2) NOT NULL,
  ItensQtd decimal(10,2) NOT NULL,
  ItensVendaID int(11) NOT NULL,
  ItensProdID int(11) NOT NULL,
  ItensEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_lancamentos (
  LancID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  LancDescricao varchar(255) DEFAULT NULL,
  LancValor decimal(10,2) NOT NULL,
  LancDtVencimento date NOT NULL,
  LancDtPagamento date DEFAULT NULL,
  LancBaixado char(1) DEFAULT 'N',
  LancFormPgto char(2) DEFAULT NULL,
  Tipo char(1) NOT NULL,
  LancClienteID int(11) DEFAULT NULL,
  LancEmpID int(11) NOT NULL,
  LancUserID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_log (
  usuario_id int(11) NOT NULL PRIMARY KEY,
  data_log date NOT NULL,
  hora time NOT NULL,
  ip varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_modeloequip (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nome varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_categoria (
  CatID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  CatNome varchar(100) NOT NULL,
  CatCod varchar(100),
  CatDescricao varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `$schemaNome`.tb_marca (
  MarcaID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  MarcaNome varchar(100) NOT NULL,
  MarcaDescricao varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_subcategoria (
  SubCatID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  SubCatNome varchar(100) NOT NULL,
  CatID int(11) NOT NULL,
  FOREIGN KEY (CatID) REFERENCES `$schemaNome`.tb_categoria(CatID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `$schemaNome`.tb_produto (
  ProdID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ProdDescricao varchar(100) NOT NULL,
  ProdDtCriacao date NOT NULL,
  ProdCodBarra varchar(100) NOT NULL,
  ProdValorCompra decimal(10,2) NOT NULL,
  ProdValorVenda decimal(10,2) NOT NULL,
  ProdEstoqueMin int(11) NOT NULL,
  ProdEstoque int(11) NOT NULL,
  ProdImagem varchar(100) DEFAULT NULL,
  ProdImagemPath varchar(100) DEFAULT NULL,
  ProdEmpID int(11) NOT NULL,
  ProdUserID int(11) NOT NULL,
  ProdStatus smallint(1) NOT NULL COMMENT '1 - ativo 2 - inativo',
  CatID int(11),
  SubCatID int(11),
  MarcaID int(11),
  FOREIGN KEY (CatID) REFERENCES `$schemaNome`.tb_categoria(CatID),
  FOREIGN KEY (SubCatID) REFERENCES `$schemaNome`.tb_subcategoria(SubCatID),
  FOREIGN KEY (MarcaID) REFERENCES `$schemaNome`.tb_marca(MarcaID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_permissao (
        id INT PRIMARY KEY AUTO_INCREMENT,
          user_id INT,
          permission_key VARCHAR(255),
          FOREIGN KEY (user_id) REFERENCES `$schemaNome`.tb_usuario(id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_produto_os (
  ProdOsID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ProdOsQtd int(11) NOT NULL,
  ProdOs_osID int(11) NOT NULL,
  ProdOsProdID int(11) NOT NULL,
  ProdOsSubTotal decimal(10,2) NOT NULL,
  ProdOsEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_referencia (
  referencia_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  chamado_id int(11) NOT NULL,
  produto_ProdID int(11) DEFAULT NULL,
  servico_ServID int(11) DEFAULT NULL,
  empresa_EmpID int(11) NOT NULL,
  quantidade int(11) NOT NULL,
  valor decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 
CREATE TABLE `$schemaNome`.tb_servico (
  ServID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ServNome varchar(100) NOT NULL,
  ServValor decimal(10,2) NOT NULL,
  ServDescricao text DEFAULT NULL,
  ServEmpID int(11) NOT NULL,
  ServUserID int(11) NOT NULL,
  ServStatus char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
CREATE TABLE `$schemaNome`.tb_servico_os (
  ServOsID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ServOsQtd int(11) NOT NULL,
  ServOs_osID int(11) NOT NULL,
  ServOsServID int(11) NOT NULL,
  ServOsSubTotal decimal(10,2) NOT NULL,
  ServOsEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_setor (
  id int(11) NOT NULL PRIMARY KEY,
  nome_setor varchar(45) NOT NULL,
  status char(1) NOT NULL DEFAULT '1',
  SetorEmpID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_tecnico (
  tecnico_id int(11) NOT NULL,
  empresa_tecnico varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
CREATE TABLE `$schemaNome`.tb_tipoequip (
  id int(11) NOT NULL PRIMARY KEY,
  nome varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_vendas (
  VendaID int(11) NOT NULL PRIMARY KEY,
  VendaDT date NOT NULL,
  VendaValorTotal decimal(10,2) DEFAULT 0.00,
  VendaDesconto decimal(10,2) DEFAULT 0.00,
  VendaFaturado char(1) DEFAULT 'N',
  VendaCliID int(11) NOT NULL,
  VendaEmpID int(11) NOT NULL,
  VendaUserID int(11) NOT NULL,
  VendaLancamentoID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE `$schemaNome`.tb_imagem (
  imagemID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  imagemLogo VARCHAR(45) NULL,
  imagemPath VARCHAR(45) NULL,
  imagemEntidadeID INT(11) NOT NULL,
  imagemEntidadeTipo VARCHAR(50) NOT NULL,
  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
  CREATE TABLE `$schemaNome`.tb_categoria (
  id int NOT NULL AUTO_INCREMENT,
  nome_categoria varchar(100) NOT NULL,
  descricao_categoria varchar(150) DEFAULT NULL,
  cod varchar(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_general_ci";
    return $sql;
  }

  public static function ALTERAR_USUARIO($schema, $senha)
  {
    $sql = "UPDATE `$schema`.tb_usuario set tipo = ?, nome = ?, login = ?, " . ($senha != "" ? "senha = ?," : "") . " telefone = ?
                    WHERE id = ?";

    return $sql;
  }


  public static function ALTERAR_IMAGEM()
  {
    $sql = 'UPDATE tb_usuario set UserLogo = ?, UserLogoPath = ? WHERE id = ?';

    return $sql;
  }

  public static function ALTERAR_IMAGEM_EMPRESA($schema)
  {
    $sql = "UPDATE `$schema`.tb_empresa set EmpLogo = ?, EmpLogoPath = ? WHERE EmpID = ?";

    return $sql;
  }

  public static function CRIAR_LOG_USUARIO_SQL()
  {
    $sql = 'INSERT INTO tb_log (usuario_id, data_log, hora, ip) VALUES (?,?,?,?)';
    return $sql;
  }
  public static function ALTERAR_TECNICO()
  {
    $sql = 'UPDATE tb_tecnico set empresa_tecnico  = ? WHERE tecnico_id = ?';
    return $sql;
  }
  public static function ALTERAR_FUNCIONARIO()
  {
    $sql = 'UPDATE tb_funcionario set setor_id = ? WHERE funcionario_id = ?';
    return $sql;
  }

  public function CadastrarPermissaoSQL()
  {
    $sql = 'INSERT INTO permissoes (admin, gerente, vendedor) VALUES (?,?,?)';
    return $sql;
  }


  public static function SelecionarEmailDuplicado($id)
  {
    $sql = 'SELECT login from tb_usuario WHERE login = ?';

    if (!empty($id)) {
      $sql .= " AND id != ?";
    }

    return $sql;
  }

  public static function FILTRAR_USUARIO($nome, $tipo)
  {
    $sql =  'SELECT usu.id as id,
        usu.tipo as tipo,
        usu.nome as nome,
        usu.login,
        usu.telefone,
        usu.status as status,
        end.rua,
        end.bairro,
        end.cep,
        end.id as id_end,
        cid.nome_cidade as cidade,
        est.sigla_estado,
        tec.empresa_tecnico,
        fun.setor_id,
        st.nome_setor
    FROM tb_usuario as usu
    INNER JOIN tb_endereco as end
        ON usu.id = end.usuario_id
    INNER JOIN tb_cidade as cid
        ON end.cidade_id = cid.id
    INNER JOIN tb_estado as est
        ON cid.estado_id = est.id
    LEFT JOIN tb_funcionario as fun
        ON usu.id = fun.funcionario_id
    LEFT JOIN tb_tecnico as tec
        ON usu.id = tec.tecnico_id
        LEFT JOIN tb_setor as st
		ON fun.setor_id = st.id
            WHERE UserEmpID = ?';

    if (!empty($nome)) {
      $sql .= ' AND nome LIKE ?';
    }
    if (!empty($tipo)) {
      if ($tipo == '2') {
        $sql .= ' AND fun.setor_id IS NOT NULL';
      } else if ($tipo == '3') {
        $sql .= ' AND tec.tecnico_id IS NOT NULL';
      }
    }

    return $sql;
  }
  public static function RETORNAR_USUARIOS($schema)
  {
    $sql =  "SELECT usu.id as id,
        usu.tipo as tipo,
        usu.nome as nome,
        usu.login,
        usu.telefone,
        usu.status as status,
        end.rua,
        end.bairro,
        end.cep,
        end.id as id_end,
        cid.nome_cidade as cidade,
        est.sigla_estado,
        tec.empresa_tecnico,
        fun.setor_id,
        st.nome_setor
    FROM `$schema`.tb_usuario as usu
INNER JOIN `$schema`.tb_endereco as end
        ON usu.id = end.usuario_id
INNER JOIN `$schema`.tb_cidade as cid
        ON end.cidade_id = cid.id
INNER JOIN `$schema`.tb_estado as est
        ON cid.estado_id = est.id
LEFT JOIN `$schema`.tb_funcionario as fun
        ON usu.id = fun.funcionario_id
LEFT JOIN `$schema`.tb_tecnico as tec
        ON usu.id = tec.tecnico_id
LEFT JOIN `$schema`.tb_setor as st
		ON fun.setor_id = st.id
      ";
    return $sql;
  }

  public static function MUDAR_STATUS()
  {
    $sql = 'UPDATE tb_usuario
                    SET status = ?
                        WHERE id = ?';
    return $sql;
  }

  public static function RETORNAR_EMPRESAS($schema)
  {
    $sql = "SELECT * From `$schema`.tb_empresa";
    return $sql;
  }

  public static function DETALHAR_USUARIO($schema)
  {
    $sql = "SELECT usu.id as id_user,
                usu.tipo,
                usu.nome,
                usu.login,
                usu.telefone,
                usu.UserLogo,
                usu.UserLogoPath,
                end.rua,
                end.bairro,
                end.cep,
                img.imagemLogo,
                img.imagemPath,
                end.id as id_end,
                cid.nome_cidade as cidade,
                est.sigla_estado
                FROM `$schema`.tb_usuario as usu
                LEFT JOIN `$schema`.tb_permissao as permissao
                ON usu.id = permissao.user_id
        INNER JOIN `$schema`.tb_endereco as end
                ON usu.id = end.usuario_id
        INNER JOIN `$schema`.tb_cidade as cid
                ON end.cidade_id = cid.id
        INNER JOIN `$schema`.tb_estado as est
                ON cid.estado_id = est.id
        LEFT JOIN  `$schema`.tb_imagem as img
                ON usu.id = img.imagemEntidadeID And img.ImagemEntidadeTipo = 'user'
            WHERE usu.id = ?";

    return $sql;
  }


  public static function DETALHAR_EMPRESA($idEmpresa, $schema)
  {

    $sql = "SELECT *
                FROM `$schema`.tb_usuario
                    WHERE login = ? AND status = ?";

    return $sql;
  }




  public static function BUSCAR_DADOS_ACESSO()
  {

    $sql = 'SELECT id, nome, senha, tipo, UserEmpID
                FROM tb_usuario
                    WHERE login = ? AND status = ?';

    return $sql;
  }

  public static function RECUPERARSENHAATUAL()
  {
    $sql = 'SELECT senha from tb_usuario Where id = ?';
    return $sql;
  }

  public static function ATUALIZAR_SENHA()
  {
    $sql = 'UPDATE tb_usuario
                    SET senha = ? WHERE id = ?';

    return $sql;
  }

  public static function VALIDAR_ACESSO($schema)
  {
    $sql = "SELECT  id,
                        nome,
                        senha,
                        status,
                        UserEmpID,
                        tenant_id,
                        tipo
                FROM `$schema`.tb_usuario
                    WHERE login = ?
                    AND status = ?";
    return $sql;
  }
  public static function VALIDAR_ACESSO_LOCAL($schema)
  {
    $sql = "SELECT us.*, emp.*
      FROM `$schema`.tb_usuario as us
      INNER JOIN `$schema`.tb_empresa as emp on UserEmpID = EmpID
          WHERE login = ?
          AND status = ?";
    return $sql;
  }

  public static function DETALHAR_MEUS_DADOS_SQL()
  {
    $sql = 'SELECT * FROM tb_usuario WHERE id = ?';
    return $sql;
  }
}
