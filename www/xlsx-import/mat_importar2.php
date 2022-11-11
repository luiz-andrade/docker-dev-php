<?php

if (!isset($_REQUEST['versao'])) {
    echo "informe a versão na URL exemplo: mat_importar.php?versao=062022"; exit;
}

/* Seta configuração para não dar timeout */
ini_set('max_execution_time','-1');
ini_set('memory_limit', '8428532370');
date_default_timezone_set('America/Porto_Velho');
ini_set('max_execution_time', 3600);

/* Require com a classe de importação construída */
require 'mat_ImportaPlanilha2.php';

/* Instância conexão PDO com o banco de dados */
try {
    //172.19.0.1 ou localhost
    $pdo = new PDO("mysql:host=172.19.0.3;dbname=unimedjpr8", 'root', '123.456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo date('d-m-Y h:i:s')." | Conexao OK<hr />";

  } catch(PDOException $e) {
    echo date('d-m-Y h:i:s')." | ERRO Conexao<hr />";
    echo 'mensagem: ' . $e->getMessage(); exit;
  }

/* Instância o objeto importação e passa como parâmetro o caminho da planilha e a conexão PDO */
// echo "<br /><b>Inicio => ".date('d-m-Y h:i:s')."<br /></b>";

$obj = new ImportaPlanilha('./materiais-cod_tussA'.$_REQUEST['versao'].'.csv', $pdo);


/* Chama o método que retorna a quantidade de linhas */
echo 'Quantidade de Linhas na Planilha ' , $obj->getQtdeLinhas(), '<br>';

/* Chama o método que retorna a quantidade de colunas */
echo 'Quantidade de Colunas na Planilha ' , $obj->getQtdeColunas(), '<br>';

/* Chama o método que inseri os dados e captura a quantidade linhas importadas */

$linhasImportadas = $obj->atualizaDados();

/* Imprime a quantidade de linhas importadas */
echo '<hr />Foram importadas ', $linhasImportadas, ' linhas <br />';
echo "<br /><b>Fim => ".date('j-m-Y h:i:s')."<br /></b>";