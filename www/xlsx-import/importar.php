<?php 

/* Seta configuração para não dar timeout */
ini_set('max_execution_time','-1');
ini_set('memory_limit', '256M');

/* Require com a classe de importação construída */
require 'ImportaPlanilha.php';

/* Instância conexão PDO com o banco de dados */
$pdo = new PDO('mysql:host=172.19.0.1;dbname=tnumm', 'root', '123.456');;
// var_dump($pdo); exit;
/* Instância o objeto importação e passa como parâmetro o caminho da planilha e a conexão PDO */
echo "<br /><b>Inicio => ".date('j-m-Y h:i:s')."<br /></b>";

$obj = new ImportaPlanilha('./med_teste02.xlsx', $pdo);

// FUNCIONA
// for($i = 1000; $i <= $obj->getQtdeLinhas(); $i++){
//     if($i % 5000 == 0 or $i== $obj->getQtdeLinhas()){
//         echo "Importando... ".$i."<br>";
//     }
// }


/* Chama o método que retorna a quantidade de linhas */
echo 'Quantidade de Linhas na Planilha ' , $obj->getQtdeLinhas(), '<br>';

/* Chama o método que retorna a quantidade de colunas */
echo 'Quantidade de Colunas na Planilha ' , $obj->getQtdeColunas(), '<br>';

/* Chama o método que inseri os dados e captura a quantidade linhas importadas */
$linhasImportadas = $obj->insertDados();

/* Imprime a quantidade de linhas importadas */
echo 'Foram importadas ', $linhasImportadas, ' linhas <br />';
echo "<br /><b>Fim => ".date('j-m-Y h:i:s')."<br /></b>";