<?php 
ini_set('max_execution_time','-1');
// require_once "SimpleXLSX.class.php";
require_once "./simplexlsx-master/src/SimpleXLSX.php";

class ImportaPlanilha{

	// Atributo recebe a instância da conexão PDO
	private $conexao  = null;

     // Atributo recebe uma instância da classe SimpleXLSX
	private $planilha = null;

	// Atributo recebe a quantidade de linhas da planilha
	private $linhas   = null;

	// Atributo recebe a quantidade de colunas da planilha
	private $colunas  = null;

	/*
	 * Método Construtor da classe
	 * @param $path - Caminho e nome da planilha do Excel xlsx
	 * @param $conexao - Instância da conexão PDO
	 */
	public function __construct($path=null, $conexao=null){
		// if ( $xlsx = SimpleXLSX::parse($path) ) {
		// 	print_r( $xlsx->rows() );
		// } else {
		// 	echo SimpleXLSX::parseError();
		// }
		// exit;

		// if(!empty($path) && file_exists($path)):
		// 	$this->planilha = new SimpleXLSX($path);

		require_once './CSVLib-master/src/CsvReader.php';
		$this->planilha = new CsvReader( './medicamentos'.$_REQUEST['versao'].'.csv',';','"');

		if(!empty($conexao)):
			$this->conexao = $conexao;
		else:
			echo 'Conexão não informada!';
			exit();
		endif;

//close the connection
	}

	/*
	 * Método que retorna o valor do atributo $linhas
	 * @return Valor inteiro contendo a quantidade de linhas na planilha
	 */
	public function getQtdeLinhas(){
		$this->linhas = count($this->planilha->read());
		
		return $this->linhas;
	}

	/*
	 * Método que retorna o valor do atributo $colunas
	 * @return Valor inteiro contendo a quantidade de colunas na planilha
	 */
	public function getQtdeColunas(){
		$this->colunas = count($this->planilha->getIndices());
		
		return $this->colunas;
	}

	/*
	 * Método que verifica se o registro CPF da planilha já existe na tabela cliente
	 * @param $cpf - CPF do cliente que está sendo lido na planilha
	 * @return Valor Booleano TRUE para duplicado e FALSE caso não 
	 */
	private function isRegistroDuplicado($cpf=null){
		$retorno = false;
		try{
			if(!empty($cpf)):
				$sql = 'SELECT termo FROM medicamento'.$_REQUEST['versao'].' WHERE termo = ?';
				$stm = $this->conexao->prepare($sql);
				$stm->bindValue(1, $cpf);
				$stm->execute();
				$dados = $stm->fetchAll();

				if(!empty($dados)):
					$retorno = true;
				else:
					$retorno = false;
				endif;
			endif;

			
		}catch(Exception $erro){
			echo 'Erro: ' . $erro->getMessage();
			$retorno = false;
		}

		return $retorno;
	}

	/*
	 * Método para ler os dados da planilha e inserir no banco de dados
	 * @return Valor Inteiro contendo a quantidade de linhas importadas
	 */
	public function insertDados(){

		// // https://github.com/osians/CSVLib/
		try{
			$sql = 'INSERT INTO `medicamento'.$_REQUEST['versao'].'`(`cod_tuss`,`tiss_tp`,`tiss`,`termo`,`des_produto`,`generico`,`gru_farmaco`,`clas_farmaco`,`for_farmace`,`uni_fracao`,`cnpj_importador`,`laboratorio`,`reg_anvisa`,`pre_max`,`fat_fracao`,`tax_log`,`obs`,`cod_anterior`,`tipo_codificacao`,`dat_inicio_vig`,`dat_fim_vig`,`mot_inat_ativ`,`dat_fim_imp`,`cod_brasindice`,`des_brasindice`,`apre_brasindice`)
					VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?)';
			// $sql = 'INSERT INTO `material102021`(`cod_tuss`,`tiss_tp`)
				// VALUES (?, ?)';
			$stm = $this->conexao->prepare($sql);
			
			$linha = 0;

			foreach($this->planilha->read() as $chave => $valor):

				if ($linha % 5000 == 0 || $linha == $this->linhas) {
					echo "<br />importado => ".$linha."<br />";
				}				

				$cod_tuss         =substr(trim($valor['cod_tuss']),0,10);
				$tiss_tp          =substr(trim($valor['tiss_tp']),0,2);
				$tiss             =substr(trim($valor['tiss']),0,10);
				$termo            =substr(trim($valor['termo']),0,200);
				$des_produto      =substr(trim($valor['des_produto']),0,200);
				$generico         =substr(trim($valor['generico']),0,5);
				$gru_farmaco      =substr(trim($valor['gru_farmaco']),0,200);
				$clas_farmaco     =substr(trim($valor['clas_farmaco']),0,200);
				$for_farmace      =substr(trim($valor['for_farmace']),0,200);
				$uni_fracao       =substr(trim($valor['uni_fracao']),0,200);
				$cnpj_importador  =substr(trim($valor['cnpj_importador']),0,200);
				$laboratorio      =substr(trim($valor['laboratorio']),0,200);
				$reg_anvisa       =substr(trim($valor['reg_anvisa']),0,15);
				$pre_max          =substr(trim($valor['pre_max']),0,15);
				$fat_fracao       =str_replace(",", ".", substr(trim($valor['fat_fracao']),0,10));
				$tax_log          =substr(trim($valor['tax_log']),0,10);
				$obs              =substr(trim($valor['obs']),0,250);
				$cod_anterior     =substr(trim($valor['cod_anterior']),0,10);
				$tipo_codificacao =substr(trim($valor['tipo_codificacao']),0,10);
				$dat_inicio_vig   =substr(trim($valor['dat_inicio_vig']),0,20);
				$dat_fim_vig      =substr(trim($valor['dat_fim_vig']),0,20);
				$mot_inat_ativ    =substr(trim($valor['mot_inat_ativ']),0,200);
				$dat_fim_imp      =substr(trim($valor['dat_fim_imp']),0,20);
				$cod_brasindice   =substr(trim($valor['cod_brasindice']),0,200);
				$des_brasindice   =substr(trim($valor['des_brasindice']),0,200);
				$apre_brasindice  =substr(trim($valor['apre_brasindice']),0,200);

				$stm->bindValue(1, $cod_tuss);
				$stm->bindValue(2, $tiss_tp);
				$stm->bindValue(3, $tiss);
				$stm->bindValue(4, $termo);
				$stm->bindValue(5, $des_produto);
				$stm->bindValue(6, $generico);
				$stm->bindValue(7, $gru_farmaco);
				$stm->bindValue(8, $clas_farmaco);
				$stm->bindValue(9, $for_farmace);
				$stm->bindValue(10, $uni_fracao);
				$stm->bindValue(11, $cnpj_importador);
				$stm->bindValue(12, $laboratorio);
				$stm->bindValue(13, $reg_anvisa);
				$stm->bindValue(14, $pre_max);
				$stm->bindValue(15, $fat_fracao);
				$stm->bindValue(16, $tax_log);
				$stm->bindValue(17, $obs);
				$stm->bindValue(18, $cod_anterior);
				$stm->bindValue(19, $tipo_codificacao);
				$stm->bindValue(20, $dat_inicio_vig);
				$stm->bindValue(21, $dat_fim_vig);
				$stm->bindValue(22, $mot_inat_ativ);
				$stm->bindValue(23, $dat_fim_imp);
				$stm->bindValue(24, $cod_brasindice);
				$stm->bindValue(25, $des_brasindice);
				$stm->bindValue(26, $apre_brasindice);
				
				$retorno = $stm->execute();

				if($retorno == true) $linha++;
			
			endforeach;

			return $linha;
		}catch(Exception $erro){
			echo 'Erro: ' . $erro->getMessage();
		}

	}
}