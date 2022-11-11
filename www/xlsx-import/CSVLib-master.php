<?php

// https://github.com/osians/CSVLib/
require_once './CSVLib-master/src/CsvReader.php'; 

# Instanciando o Objeto de Manipulação de dados
$csv = new CsvReader( './mat_teste02.csv',';','"');

# obtendo os dados e realizando um Loop
# com foreach
foreach( $csv->read() as $linha ){
    $linha = $linha;
    var_dump( $linha['cod_tuss'] );    
}

exit;