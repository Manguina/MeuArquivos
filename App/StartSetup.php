<?php

namespace App;


use Source\Setup\Model\DataBase;
use Source\Core\View;
use Source\Support\Message;

class StartSetup extends DataBase
{

  protected $view;
  protected $message;
	
	function __construct()
	{
		 parent:: __construct();
	}
      
  public function database()
 {
  $mensagem="";
  $conn=(new DataBase());
    $conn->createDataBase();
  if ($conn->executa()) {
   $mensagem="banco e tabelas criados";

   $this->table();
     
    }else{
      
      $mensagem="erro ao criar";
    }
    
    return $mensagem;

 }



public function table()
 {

  $conn=(new DataBase());
   $conn->datas("DROP TABLE IF EXISTS `armazem`;
   
CREATE TABLE `armazem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `des_armazem` varchar(300) DEFAULT NULL,
  `localizacao` varchar(300) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `baixa_produtos`;
CREATE TABLE `baixa_produtos` (
  `id_baixa` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `data` date NOT NULL,
  PRIMARY KEY (`id_baixa`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");



$conn->datas("DROP TABLE IF EXISTS `caixa`;
CREATE TABLE `caixa` (
  `id_caixa` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `val_entra` decimal(10,0) NOT NULL,
  `entrada` decimal(10,0) NOT NULL DEFAULT 0,
  `saida` decimal(10,0) NOT NULL DEFAULT 0,
  `val_info` int(11) NOT NULL DEFAULT 0,
  `saldo` int(11) NOT NULL DEFAULT 0,
  `hora_entrada` time NOT NULL,
  `data_entrada` date NOT NULL,
  `hora_saida` time DEFAULT NULL,
  `data_saida` date DEFAULT NULL,
  `estado` varchar(40) NOT NULL DEFAULT 'activo',
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_caixa`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");


$conn->datas("DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nome` varchar(200) NOT NULL,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `conta_corrente`;
CREATE TABLE `conta_corrente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_asociado` int(11) DEFAULT NULL ,
  `tipo` varchar(20) NOT NULL,
  `descricao` varchar(300) DEFAULT NULL,
  `saldo` double NOT NULL DEFAULT 0,
  `doc` varchar(50) NOT NULL DEFAULT 'FR',
  `data_executada` date DEFAULT NULL,
  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");


$conn->datas("DROP TABLE IF EXISTS `document_key`;
CREATE TABLE `document_key` (
  `id_key_doc` int(11) NOT NULL AUTO_INCREMENT,
  `number_doc` varchar(50) NOT NULL,
  `key_generation` varchar(172) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_key_doc`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");


$conn->datas("DROP TABLE IF EXISTS `empresa`;
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `nif` varchar(50) NOT NULL DEFAULT '',
  `registo_civil` varchar(255) DEFAULT NULL,
  `razao_social` text DEFAULT NULL,
  `cod_fatura` varchar(100) NOT NULL,
  `regime` varchar(200) NOT NULL,
  `tel_um` varchar(40) NOT NULL,
  `tel_dois` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `site` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL DEFAULT 'AO',
  `cidade` varchar(200) NOT NULL,
  `rua` text NOT NULL,
  `edificio` varchar(100) NOT NULL,
  `foto` mediumblob DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `fina_banco`;
CREATE TABLE `fina_banco` (
  `id_banco` int(11) NOT NULL AUTO_INCREMENT,
  `ds_banco` varchar(200) NOT NULL,
  PRIMARY KEY (`id_banco`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `fin_conta`;
CREATE TABLE `fin_conta` (
  `id_conta` int(11) NOT NULL AUTO_INCREMENT,
  `id_banco` int(11) NOT NULL,
  `num_conta` varchar(150) NOT NULL,
  `ibam` varchar(150) NOT NULL,
  `valor` decimal(10,0) NOT NULL DEFAULT 0,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_conta`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
;");

$conn->datas("DROP TABLE IF EXISTS `fluxo_caixa`;
CREATE TABLE `fluxo_caixa` (
  `id_fluxo` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(250) NOT NULL,
  `tipo_operacao` varchar(50) NOT NULL,
  `entrada` double NOT NULL DEFAULT 0,
  `saida` double NOT NULL DEFAULT 0,
  `saldo` double NOT NULL DEFAULT 0,
  `id_asociado` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `data_creat` date NOT NULL,
  PRIMARY KEY (`id_fluxo`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `generation_key`;
CREATE TABLE `generation_key` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `key_new` varchar(172) DEFAULT NULL,
  PRIMARY KEY (`key_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `gr_fatura`;
CREATE TABLE `gr_fatura` (
  `fat_cod` int(11) NOT NULL AUTO_INCREMENT,
  `nun_fatura` int(11) NOT NULL,
  `data_fatura` year(4) NOT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`fat_cod`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");


$conn->datas("DROP TABLE IF EXISTS `imposto`;
CREATE TABLE `imposto` (
  `id_imposto` int(11) NOT NULL AUTO_INCREMENT,
  `ds_imposto` varchar(300) NOT NULL,
  `ab_imposto` varchar(300) NOT NULL,
  `percentagem` int(11) NOT NULL,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_imposto`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `iva`;
CREATE TABLE `iva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(300) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `razao` text DEFAULT NULL,
  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `marca`;
CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL AUTO_INCREMENT,
  `ds_marca` varchar(300) NOT NULL,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_marca`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `modelo`;
CREATE TABLE `modelo` (
  `id_modelo` int(11) NOT NULL AUTO_INCREMENT,
  `marca` int(11) NOT NULL,
  `ds_modelo` varchar(300) NOT NULL,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_modelo`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `nota_credito`;

CREATE TABLE `nota_credito` (
  `codNota` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `codigo_nota` varchar(50) NOT NULL,
  `motivo` text DEFAULT NULL,
  `data_essimao` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`codNota`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `pagamento`;
CREATE TABLE `pagamento` (
  `id_forma` int(11) NOT NULL AUTO_INCREMENT,
  `ds_forma` varchar(150) NOT NULL,
  `tipo` varchar(150) NOT NULL DEFAULT 'caixa',
  `conta` int(11) NOT NULL DEFAULT 0,
  `dispesa` int(11) NOT NULL DEFAULT 0,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_forma`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("CREATE TABLE IF NOT EXISTS `pessoa` (
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nome_compl` varchar(300) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `bi` varchar(100) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `data_nascimento` varchar(200) NOT NULL,
  `nacionalidade` varchar(200) DEFAULT NULL,
  `pais` varchar(100) NOT NULL DEFAULT 'Angola',
  `provincia` varchar(500) NOT NULL,
  `cidade` varchar(500) NOT NULL,
  `rua` varchar(500) NOT NULL,
  `edificio` varchar(200) NOT NULL,
  `tipo` varchar(100) NOT NULL DEFAULT 'user',
  `status` varchar(100) NOT NULL DEFAULT 'Activo',
  `entidade` varchar(50) DEFAULT NULL,
  `habilitacao` int(11) DEFAULT NULL,
  `funcao` int(11) DEFAULT NULL,
  `data_creat` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pessoa`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
");

$conn->datas("DROP TABLE IF EXISTS `pessoa_funcao`;
CREATE TABLE `pessoa_funcao` (
  `id_funcao` int(11) NOT NULL AUTO_INCREMENT,
  `ds_funcao` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_funcao`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `pessoa_habilitcao`;
CREATE TABLE `pessoa_habilitcao` (
  `id_habi` int(11) NOT NULL AUTO_INCREMENT,
  `ds_habi` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_habi`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `produto`;
CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(300) NOT NULL,
  `codigo_barra` varchar(300) DEFAULT NULL,
  `codigo_ucf` varchar(100) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `perce_iva` varchar(100) DEFAULT NULL,
  `codigo_iva` varchar(100) DEFAULT NULL,
  `motiv_izecao` varchar(300) DEFAULT NULL,
  `custo` decimal(10,0) NOT NULL,
  `preco_sem_iva` decimal(10,0) NOT NULL,
  `valor_iva` decimal(10,0) NOT NULL,
  `min` int(11) NOT NULL DEFAULT 0,
  `mix` int(11) NOT NULL DEFAULT 0,
  `lucros` decimal(10,0) NOT NULL,
  `preco_venda` decimal(10,0) NOT NULL DEFAULT 0,
  `categoria` varchar(300) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `metro` varchar(10) DEFAULT NULL,
  `balanca` varchar(10) DEFAULT NULL,
  `confencionavel` varchar(10) DEFAULT NULL,
  `expira` varchar(10) DEFAULT NULL,
  `retalhar` varchar(10) DEFAULT NULL,
  `p_venda` varchar(10) DEFAULT NULL,
  `n_venda` varchar(10) DEFAULT 's',
  `status` varchar(50) DEFAULT 's',
  `data_creat` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_produto`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `p_acesso`;
CREATE TABLE `p_acesso` (
  `id_acesso` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(300) NOT NULL,
  `grupo` varchar(200) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL COMMENT 'Activo/Inativo',
  `acesso` int(11) DEFAULT NULL,
  `pos` int(11) DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_acesso`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `saidas_produtos`;
CREATE TABLE `saidas_produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL ,
  `data_hora` datetime NOT NULL,
  `tipo_evento` varchar(50) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `id_armazem` varchar(100) DEFAULT NULL,
  `usuario_responsavel` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `stoque`;

CREATE TABLE `stoque` (
  `id_stoque` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_armazem` int(11) NOT NULL,
  `qtd` int(11) NOT NULL,
  `custo` decimal(10,0) NOT NULL,
  `preco` decimal(10,0) NOT NULL,
  `val_iva` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `n_lote` varchar(100) DEFAULT NULL,
  `data_fabrico` date DEFAULT NULL,
  `data_expira` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `data_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_stoque`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `venda`;
CREATE TABLE `venda` (
  `id_venda` int(11)  NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_operador` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `descricao` varchar(400) NOT NULL,
  `qtd` int(11) NOT NULL,
  `taxa_iva` int(11) NOT NULL,
  `cod_iva` varchar(100) DEFAULT NULL,
  `pre_sem_iva` decimal(10,0) NOT NULL,
  `val_iva` decimal(10,0) NOT NULL,
  `preco_unit` decimal(10,0) NOT NULL,
  `desconto` decimal(10,0) NOT NULL DEFAULT 0,
  `valor_pago` decimal(10,0) DEFAULT NULL,
  `troco` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) NOT NULL,
  `pagamento` varchar(150) DEFAULT NULL,
  `doc_tipo` varchar(200) NOT NULL,
  `numero_doc` varchar(200) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'ativo',
  `hora` time DEFAULT NULL,
  `referencia` varchar(500) DEFAULT NULL,
  `data_venda` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_venda`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");

$conn->datas("DROP TABLE IF EXISTS `v_carrinho`;
CREATE TABLE `v_carrinho` (
  `id_caixa` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `descricao` varchar(400) NOT NULL,
  `preco` decimal(10,0) NOT NULL,
  `qtd` int(11) NOT NULL,
  `desconto` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `situacao` varchar(50) NOT NULL,
  `Tipo_doc` varchar(100) NOT NULL DEFAULT '',
  `num_doc` varchar(50) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `data_creat` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_caixa`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
");
$conn->datas("
CREATE TABLE IF NOT EXISTS `ep_modulo` (
  `id_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `ds_modulo` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_modulo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

");
$conn->datas(
  "DROP TABLE IF EXISTS `ep_config`;
  CREATE TABLE IF NOT EXISTS `ep_config` (
  `id_confi` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(255) DEFAULT NULL,
  `licenca` varchar(255) DEFAULT NULL,
  `tempo` varchar(255) DEFAULT NULL,
  `cod_empresa` int(11) DEFAULT NULL,
  `tipo_licenca` enum('Desktop','Web') DEFAULT 'Desktop',
  `situacao` varchar(50) DEFAULT 'Teste',
  `status` varchar(50) DEFAULT 'Activo' COMMENT 'Activo,Bloqueado,Expirado',
  `dispositivos_permitidos` int(11) DEFAULT 1,
  `parceiro` varchar(255) DEFAULT 'APTO',
  `dispositivo` varchar(255) DEFAULT NULL,
  `SO` varchar(255) DEFAULT NULL,
  `processador` varchar(255) DEFAULT NULL,
  `data_activacao` datetime DEFAULT NULL,
  `data_renovacao` datetime DEFAULT NULL,
  `data_update` datetime DEFAULT NULL,
  `data_criate` datetime DEFAULT NULL,
  PRIMARY KEY (`id_confi`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
");

 
     if ($conn->exec()) {
      $this->inserts("tabela");
      
    }
    return "Erro ao Criar as Tabelas";

    
 }

    

 protected function inserts($dados)
    {

      if ($dados=="tabela") {
            
      $conn=(new DataBase());
      $conn->datas("
  INSERT INTO `fina_banco` (`id_banco`, `ds_banco`) VALUES
	(1, 'Banco Angolano de Investimentos(BAI)'),
	(2, 'Banco de Fomento Angola'),
	(3, 'Banco Sol'),
	(4, 'Banco de Poupança e Crédito'),
	(5, 'Banco Keve'),
	(6, 'Banco Millennium Atlântico'),
	(7, 'Banco Prestígio'),
	(8, 'Banco Comercial do Huambo'),
	(9, 'Banco Caixa Geral Angola'),
	(10, 'Banco Económico'),
	(11, 'Banco Yetu'),
	(12, 'Standard Bank Angola'),
	(13, 'Banco de Comércio e Indústria'),
	(14, 'Banco Valor'),
	(15, 'Banco BIC'),
	(20, 'meu banco bom'),
	(17, 'Banco de Negócios Internacional'),
	(18, 'Finibanco Angola'),
	(19, 'Banco de Crédito do Sul');
");

$conn->datas("
  INSERT INTO `armazem` (`id`, `id_empresa`, `des_armazem`, `localizacao`) VALUES
	(1, 1, 'Comercial', 'Local'),
	(2, 1, 'Caixa', 'Local');
");
   
  
$conn->datas("
 
INSERT INTO `iva` (`id`, `codigo`, `motivo`, `razao`) VALUES
	(1, 'M00', 'Regime Simplificado', ''),
	(2, 'M02', 'Transmissão de bens e serviço não sujeita', 'Transmissão de bens e serviço não sujeita'),
	(3, 'M04', 'Regime de Exclusão', 'Regime de Exclusão'),
	(4, 'M10', 'Isento nos termos da alínea a) do nº1 do artigo 12.º do CIVA', 'A transmissão dos bens alimentares, conforme anexo I do presente código.'),
	(5, 'M11', 'Isento nos termos da alínea b) do nº1 do artigo 12.º do CIVA', 'As transmissões de medicamentos destinados exclusivamente a fins terapêuticos e profilácticos.'),
	(6, 'M12', 'Isento nos termos da alínea c) do nº1 do artigo 12.º do CIVA', 'As transmissões de cadeiras de rodas e veículos semelhantes, accionados manualmente ou por motor, para portadores de deficiência, aparelhos, máquinas de escrever com caracteres braille, impressoras para caracteres braille e os artefactos que se destinam a ser utilizados por invisuais ou a corrigir a audição.'),
	(7, 'M13', 'Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA', 'A transmissão de livros, incluindo em formato digital.'),
	(8, 'M14', 'Isento nos termos da alínea e) do nº1 do artigo 12.º do CIVA', 'A locação de bens imóveis destinados a fins habitacionais, designadamente prédios urbanos, fracções autónomas destes ou terrenos para construção, com excepção das prestações de serviços de alojamento efectuadas no âmbito da actividade hoteleira ou de outras com funções análogas.'),
	(9, 'M15', 'Isento nos termos da alínea f) do nº1 do artigo 12.º do CIVA', 'As operações sujeitas ao imposto de SISA, ainda que dele isentas.'),
	(10, 'M16', 'Isento nos termos da alínea g) do nº1 do artigo 12.º do CIVA', 'A exploração e a prática de jogos de fortuna ou azar e de diversão social, bem como as respectivas comissões e todas as operações relacionadas, quando as mesmas estejam sujeitas a Imposto Especial sobre o Jogos, nos termos da legislação aplicável.'),
	(11, 'M17', 'Isento nos termos da alínea h) do nº1 do artigo 12.º do CIVA', 'O transporte colectivo de passageiros.'),
	(12, 'M18', 'Isento nos termos da alínea i) do nº1 do artigo 12.º do CIVA', 'As operações de intermediação financeira, incluindo a locação financeira, exceptuando-se aquelas em que uma taxa, ou contraprestação, especifica e predeterminada é cobrada pelo serviço.'),
	(13, 'M19', 'Isento nos termos da alínea j) do nº1 do artigo 12.º do CIVA', 'O seguro de saúde, bem como a prestação de seguros e resseguros do ramo vida.'),
	(14, 'M20', 'Isento nos termos da alínea k) do nº1 do artigo 12.º do CIVA', 'As transmissões de produtos petrolíferos conforme anexo II do presente código.'),
	(15, 'M21', 'Isento nos termos da alínea l) do nº1 do artigo 12.º do CIVA', 'As prestações de serviço que tenham por objecto o ensino, efectuadas por estabelecimentos integrados conforme definidos na Lei de Bases do Sistema de Educação e Ensino, bem como por estabelecimentos de Ensino Superior devidamente reconhecidos pelo Ministério de Tutela.'),
	(16, 'M22', 'Isento nos termos da alínea m) do artigo 12.º do CIVA', 'As prestações de serviço médico sanitário, efectuadas por estabelecimentos hospitalares, clinicas, dispensários e similares.'),
	(17, 'M23', 'Isento nos termos da alínea n) do artigo 12.º do CIVA', 'O transporte de doentes ou feridos em ambulâncias ou outros veículos apropriados efectuados por organismos devidamente autorizados.'),
	(18, 'M24', 'Isento nos termos da alínea o) do artigo 12.º do CIVA', 'Os equipamentos médicos para o exercício da actividade dos estabelecimentos de saúde.'),
	(19, 'M80', 'Isento nos termos da alinea a) do nº1 do artigo 14.º', 'As importações definitivas de bens cuja transmissão no território nacional seja isenta de imposto.'),
	(20, 'M81', 'Isento nos termos da alinea b) do nº1 do artigo 14.º', 'As importações de ouro, moedas ou notas de banco, efectuadas pelo Banco Nacional de Angola.'),
	(21, 'M82', 'Isento nos termos da alinea c) do nº1 do artigo 14.º', 'A importação de bens destinados a ofertas para atenuar os efeitos das calamidades naturais, tais como cheias, tempestades, secas, ciclones, sismos, terramotos e outros de idêntica natureza, desde que devidamente autorizado pelo Titular do Poder Executivo.'),
	(22, 'M83', 'Isento nos termos da alinea d) do nº1 do artigo 14.º', 'A importação de mercadorias ou equipamentos destinadas exclusiva e directamente à execução das operações petrolíferas e mineiras nos termos da Lei que estabelece o Regime Aduaneiro do Sector Petrolífero e do Código Mineiro, respectivamente.'),
	(23, 'M84', 'Isento nos termos da alínea e) do nº1 do artigo 14.º', 'Importação de moeda estrangeira efectuada pelas instituições financeiras bancárias, nos termos definidos pelo Banco Nacional de Angola.'),
	(24, 'M85', 'Isento nos termos da alinea a) do nº2 do artigo 14.º', 'No âmbito de tratados e acordos internacionais de que a República de Angola seja parte, nos termos previstos nesses tratados e acordos.'),
	(25, 'M86', 'Isento nos termos da alinea b) do nº2 do artigo 14.º', 'No âmbito de relações diplomáticas e consulares, quando a isenção resulte de tratados e acordos internacionais celebrados pela República de Angola.'),
	(26, 'M30', 'Isento nos termos da alínea a) do artigo 15.º do CIVA', 'As transmissões de bens expedidos ou transportados com destino ao estrangeiro pelo vendedor ou por um terceiro por conta deste.'),
	(27, 'M31', 'Isento nos termos da alínea b) do artigo 15.º do CIVA', 'As transmissões de bens de abastecimento postos a bordo das embarcações que efectuem navegação marítima em alto mar e que assegurem o transporte remunerado de passageiros ou o exercício de uma actividade comercial, industrial ou de pesca.'),
	(28, 'M32', 'Isento nos termos da alínea c) do artigo 15.º do CIVA', 'As transmissões de bens de abastecimento postos a bordo das aeronaves utilizadas por companhias de navegação aérea que se dediquem principalmente ao tráfego internacional e que assegurem o transporte remunerado de passageiros, ou o exercício de uma actividade comercial ou industrial.'),
	(29, 'M33', 'Isento nos termos da alínea d) do artigo 15.º do CIVA', 'As transmissões de bens de abastecimento postos a bordo das embarcações de salvamento, assistência marítima, pesca costeira e embarcações de guerra, quando deixem o país com destino a um porto ou ancoradouro situado no estrangeiro.'),
	(30, 'M34', 'Isento nos termos da alínea e) do artigo 15.º do CIVA', 'As transmissões, transformações, reparações, manutenção, frete e aluguer, incluindo a locação financeira, de embarcações e aeronaves afectas às companhias de navegação aérea e marítima que se dediquem principalmente ao tráfego internacional, assim como as transmissões de bens de abastecimento postos a bordo das mesmas e as prestações de serviços efectuadas com vista à satisfação das suas necessidades directas e da respectiva carga.'),
	(31, 'M35', 'Isento nos termos da alínea f) do artigo 15.º do CIVA', 'As transmissões de bens efectuadas no âmbito de relações diplomáticas e consulares cuja isenção resulte de acordos e convénios internacionais celebrados por Angola.'),
	(32, 'M36', 'Isento nos termos da alínea g) do artigo 15.º do CIVA', 'As transmissões de bens destinados a organismos internacionais reconhecidos por Angola ou a membros dos mesmos organismos, nos limites e com as condições fixadas em acordos e convénios internacionais celebrados por Angola.'),
	(33, 'M37', 'Isento nos termos da alínea h) do artigo 15.º do CIVA', 'As transmissões de bens efectuadas no âmbito de tratados e acordos internacionais de que a República de Angola seja parte, quando a isenção resulte desses mesmos tratados e acordos.'),
	(34, 'M38', 'Isento nos termos da alínea i) do artigo 15.º do CIVA', 'O transporte de pessoas provenientes ou com destino');

");

  
$conn->datas("
INSERT INTO `imposto` (`id_imposto`, `ds_imposto`, `ab_imposto`, `percentagem`, `data_create`) VALUES (1, 'IVA 14%', 'IMPOSTO SOBRE O VALOR ACRESCENTADO', 14, '2024-07-12 13:12:28'),
	(2, 'INSENTO', 'IMPOSTO SOBRE O VALOR ACRESCENTADO', 0, '2024-07-12 13:12:10');

");

$conn->datas("
INSERT INTO `categoria` (`id_categoria`, `cat_nome`, `data_create`) VALUES (1, 'GERAL', '2024-07-12 13:21:47');
");

$conn->datas("
INSERT INTO `pagamento` (`id_forma`, `ds_forma`, `tipo`, `conta`, `dispesa`, `data_create`) VALUES (1, 'Numerário', 'caixa', 0, 0, '2024-07-23 11:39:18');
");

$conn->datas("

INSERT INTO `fin_conta` (`id_conta`, `id_banco`, `num_conta`, `ibam`, `valor`, `data_create`) VALUES (1, 1, '43545465656', '547847854574584', 0, '2024-10-31 13:33:11');
  
");
$conn->datas("

INSERT INTO `generation_key` (`key_id`, `key_new`) VALUES (1, 'KIZU2GazWTJUW781KrmKe5QdkSdjNRKJX8lWIZKNpLF0R+v/Zn2vt6yffxfaKtncaU3Dpt3BC6KCGU6Krov8+VyZ28z3aF69mx6O0LolA97cpHyTiLGeLZ5L511jELFh74vnWGZIsOSZACYa203rxMbnWhlgeUhIwBSG9fH+48g=');

");



$conn->datas("


INSERT INTO `pessoa` (`id_pessoa`, `nome_compl`, `sexo`, `bi`, `telefone`, `email`, `data_nascimento`, `nacionalidade`, `pais`, `provincia`, `cidade`, `rua`, `edificio`, `tipo`, `status`, `data_creat`) VALUES
  (1, 'Consumidor Final', 'Neutro', '999999999', 'Consumidor Final', NULL, '2024-10-10', 'AO', 'AO', 'Não especificado', 'Não especificado', 'Não especificado', 'Não especificado', 'Cliente', 'Activo', NULL, NULL, NULL, '2024-08-06 07:40:10'),
  (2, 'Administrador do sistema', 'Neutro', '999999999', 'Consumidor Final', NULL, '2024-10-10', 'AO', 'AO', 'Não especificado', 'Não especificado', 'Não especificado', 'Não especificado', 'User', 'Activo', NULL, NULL, NULL, '2024-08-06 07:40:10');

");
     if ($conn->exec()) {

      return "dados inseridos";
      
    }
    return "erro ao inserir dados";
    
      
    }
  }
        
}