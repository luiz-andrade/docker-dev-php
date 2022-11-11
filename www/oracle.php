<?php
echo "teste  Conexao oracle 2 <hr />";

$tns = "  
  (DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.212)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = solus)))
       ";
$db_username = ".env";
$db_password = ".env";
try{
    $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
	echo "Sucesso na conexao";
}catch(PDOException $e){
    echo ($e->getMessage());
}
/*
$conn = oci_connect('system', 'admin1234', '192.168.0.104:1521/XE');
$stid = oci_parse($conn, 'select * from TEST');
oci_execute($stid);
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    foreach ($row as $item)
    {
        var_dump($row);
    }
}
*/
?>