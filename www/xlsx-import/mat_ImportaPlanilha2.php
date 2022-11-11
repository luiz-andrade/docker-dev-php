<?php 
ini_set('max_execution_time','-1');
ini_set('memory_limit', '8428532370');
date_default_timezone_set('America/Porto_Velho');
ini_set('max_execution_time', 3600);

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

		// echo "Parado";
		// exit();

		$this->planilha = new CsvReader( './materiais-cod_tussA'.$_REQUEST['versao'].'.csv',';','"');

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
				$sql = 'SELECT termo FROM material'.$_REQUEST['versao'].' WHERE termo = ?';
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
	public function XinsertDados(){

		// // https://github.com/osians/CSVLib/

		try{
			// $sql = 'INSERT INTO `material'.$_REQUEST['versao'].'`(`cod_tuss`,`tiss_tp`,`tiss`,`termo`,`des_produto`,`esp_produto`,`clas_anvisa`,`apr_comercial`,`uni_fracao`,`cnpj_importador`,`nom_fabricante`,`reg_anvisa`,`val_max_int`,`obs`,`ref_anterior`,`ref_tam_mod`,`tipo_produto`,`tipo_codificacao`,`dat_inicio_vig`,`dat_fim_vig`,`mot_inat_ativ`,`dat_fim_imp`,`cod_simpro`,`des_simpro`)
			// 		VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?)';
            $sql = 'UPDATE SET `material'.$_REQUEST['versao'].'`(`cod_tuss`,`tiss_tp`,`tiss`,`termo`,`des_produto`,`esp_produto`,`clas_anvisa`,`apr_comercial`,`uni_fracao`,`cnpj_importador`,`nom_fabricante`,`reg_anvisa`,`val_max_int`,`obs`,`ref_anterior`,`ref_tam_mod`,`tipo_produto`,`tipo_codificacao`,`dat_inicio_vig`,`dat_fim_vig`,`mot_inat_ativ`,`dat_fim_imp`,`cod_simpro`,`des_simpro`)
					VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?)';
            // UPDATE material072022 SET cod_tuss='00000671' WHERE tiss='1900000671';
                    
			$stm = $this->conexao->prepare($sql);
			
			$linha = 0;

			foreach($this->planilha->read() as $chave => $valor):

				if ($linha % 5000 == 0 || $linha == $this->linhas) {
					echo "<br />importado => ".$linha;
				}

				// echo "<pre>";
				// print_r(str_replace(',','.', substr(trim($valor['val_max_int']),0,250))); exit;
				// echo "</pre>";

				$cod_tuss         =substr(trim($valor['cod_tuss']),0,8);
				$tiss_tp          =substr(trim($valor['tiss_tp']),0, 1);
				$tiss             =substr(trim($valor['tiss']),0,9);
				$termo            =substr(trim($valor['termo']),0,250);
				$des_produto      =substr(trim($valor['des_produto']),0,250);
				$esp_produto      =substr(trim($valor['esp_produto']),0,250);
				$clas_anvisa      =substr(trim($valor['clas_anvisa']),0,250);
				$apr_comercial    =substr(trim($valor['apr_comercial']),0,250);
				$uni_fracao       =substr(trim($valor['uni_fracao']),0,250);
				$cnpj_importador  =substr(trim($valor['cnpj_importador']),0,250);
				$nom_fabricante   =substr(trim($valor['nom_fabricante']),0,250);
				$reg_anvisa       =substr(trim($valor['reg_anvisa']),0,250);
				$val_max_int      =substr(trim($valor['val_max_int']),0,250);
				$obs              =substr(trim($valor['obs']), 0, 250);
				$ref_anterior     =substr(trim($valor['ref_anterior']),0,250);
				$ref_tam_mod      =substr(trim($valor['ref_tam_mod']),0,250);
				$tipo_produto     =substr(trim($valor['tipo_produto']),0,49);
				$tipo_codificacao =substr(trim($valor['tipo_codificacao']),0,19);
				$dat_inicio_vig   =substr(trim($valor['dat_inicio_vig']),0,29);
				$dat_fim_vig      =substr(trim($valor['dat_fim_vig']),0,29);
				$mot_inat_ativ    =substr(trim($valor['mot_inat_ativ']),0,250);
				$dat_fim_imp      =substr(trim($valor['dat_fim_imp']),0,49);
				$cod_simpro       =substr(trim($valor['cod_simpro']),0,250);
				$des_simpro       =substr(trim($valor['des_simpro']),0,250);

				$stm->bindValue(1, $cod_tuss);
				$stm->bindValue(2, $tiss_tp);
				$stm->bindValue(3, $tiss);
				$stm->bindValue(4, $termo);
				$stm->bindValue(5, $des_produto);
				$stm->bindValue(6, $esp_produto);
				$stm->bindValue(7, $clas_anvisa);
				$stm->bindValue(8, $apr_comercial);
				$stm->bindValue(9, $uni_fracao);
				$stm->bindValue(10, $cnpj_importador);
				$stm->bindValue(11, $nom_fabricante);
				$stm->bindValue(12, $reg_anvisa);
				$stm->bindValue(13, $val_max_int);
				$stm->bindValue(14, $obs);
				$stm->bindValue(15, $ref_anterior);
				$stm->bindValue(16, $ref_tam_mod);
				$stm->bindValue(17, $tipo_produto);
				$stm->bindValue(18, $tipo_codificacao);
				$stm->bindValue(19, $dat_inicio_vig);
				$stm->bindValue(20, $dat_fim_vig);
				$stm->bindValue(21, $mot_inat_ativ);
				$stm->bindValue(22, $dat_fim_imp);
				$stm->bindValue(23, $cod_simpro);
				$stm->bindValue(24, $des_simpro);

				$retorno = $stm->execute();

				if($retorno == true) $linha++;
			
			endforeach;

			return $linha;
		}catch(Exception $erro){
			echo 'Erro: ' . $erro->getMessage();
		}

	}

    public function atualizaDados(){

		try{   
			
			$linha = 0;

			foreach($this->planilha->read() as $chave => $valor):

                // $sql = 'UPDATE `material'.$_REQUEST['versao'].'` SET `cod_tuss`='.$valor['cod_tuss'].' WHERE tiss='.$valor['tiss'];
                $sql = 'UPDATE `material'.$_REQUEST['versao'].'` SET `cod_tuss`='.$valor['cod_tuss'].' WHERE tiss='.$valor['tiss'];
                //$sql = "update produtos set nome = ?, marca = ?, modelo = ?, quantidade = ?, estado = ? where id = ?";

                $stm = $this->conexao->prepare($sql);

				$registro = $stm->execute($valor);

				if($registro == true)
                {
                    $linha++;
                    echo "atualizado;".$valor['tiss'].";<br />";
                }
                exit;
			
			endforeach;

			return $linha;
		}catch(Exception $erro){
			echo 'Erro: ' . $erro->getMessage();
		}

	}
}