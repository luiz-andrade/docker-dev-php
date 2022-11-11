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

		if(!empty($path) && file_exists($path)):
			$this->planilha = new SimpleXLSX($path);
			list($this->colunas, $this->linhas) = $this->planilha->dimension();
		else:
			echo 'Arquivo não encontrado!';
			exit();
		endif;

		if(!empty($conexao)):
			$this->conexao = $conexao;
		else:
			echo 'Conexão não informada!';
			exit();
		endif;

	}

	/*
	 * Método que retorna o valor do atributo $linhas
	 * @return Valor inteiro contendo a quantidade de linhas na planilha
	 */
	public function getQtdeLinhas(){
		return $this->linhas;
	}

	/*
	 * Método que retorna o valor do atributo $colunas
	 * @return Valor inteiro contendo a quantidade de colunas na planilha
	 */
	public function getQtdeColunas(){
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
				$sql = 'SELECT termo FROM medicamento102021 WHERE termo = ?';
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

		try{
			// $sql = 'INSERT INTO medicamento102021 (cod_tuss, tiss_tp, termo, des_produto)VALUES(0, "?", "?", "?", "?")';
			$sql = 'INSERT INTO `medicamento102021`(`cod_tuss`,`tiss_tp`,`tiss`,`termo`,`des_produto`,`generico`,`gru_farmaco`,`clas_farmaco`,`for_farmace`,`uni_fracao`,`cnpj_importador`,`laboratorio`,`reg_anvisa`,`pre_max`,`fat_fracao`,`tax_log`,`obs`,`cod_anterior`,`tipo_codificacao`,`dat_inicio_vig`,`dat_fim_vig`,`mot_inat_ativ`,`dat_fim_imp`,`cod_brasindice`,`des_brasindice`,`apre_brasindice`)
					VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?)';
			$stm = $this->conexao->prepare($sql);
			
			$linha = 0;
			foreach($this->planilha->rows() as $chave => $valor):
				// if ($chave >= 1 && !$this->isRegistroDuplicado(trim($valor[2]))):
					$cod_tuss=trim($valor[0]);
					$tiss_tp=trim($valor[1]);
					$tiss=trim($valor[2]);
					$termo=trim($valor[3]);
					$des_produto=trim($valor[4]);
					$generico=trim($valor[5]);
					$gru_farmaco=trim($valor[6]);
					$clas_farmaco=trim($valor[7]);
					$for_farmace=trim($valor[8]);
					$uni_fracao=trim($valor[9]);
					$cnpj_importador=trim($valor[10]);
					$laboratorio=trim($valor[11]);
					$reg_anvisa=trim($valor[12]);
					$pre_max=trim($valor[13]);
					$fat_fracao=trim($valor[14]);
					$tax_log=trim($valor[15]);
					$obs=trim($valor[16]);
					$cod_anterior=trim($valor[18]);
					$tipo_codificacao=trim($valor[20]);
					$dat_inicio_vig=trim($valor[21]);
					$dat_fim_vig=trim($valor[22]);
					$mot_inat_ativ=trim($valor[23]);
					$dat_fim_imp=trim($valor[24]);
					$cod_brasindice=trim($valor[25]);
					$des_brasindice=trim($valor[26]);
					$apre_brasindice=trim($valor[27]);

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

				//  endif;
			endforeach;

			// for($i = 1000; $i <= $linha; $i++){
			// 	if($i % 5000 == 0 or $i== $linha){
			// 		echo "Importando =>... ".$i." = ".date('j-m-Y h:i:s')."<br>";
			// 	}
			// }

			return $linha;
		}catch(Exception $erro){
			echo 'Erro: ' . $erro->getMessage();
		}

	}
}