
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<script>
    // var strWindowFeatures = "location=yes,height=570,width=520,scrollbars=yes,status=yes";
    // var URL = "https://web.whatsapp.com/";
    // var win = window.open(URL, "_blank", strWindowFeatures);
</script>
<body>

<iframe src="https://www.w3schools.com"></iframe>

</body>
</html> -->
<?php




//exit;
/*


echo "<hr />";
echo "<hr />";


$string = "Teste exemplo no php";

$saida = "<h1>🌶️</h1>";
if (str_contains($string, "nso")) {
    $saida = "<h1>👍</h1>";
}
echo "<hr />";
$saida2 = "<h1>Não inicia</h1>";
if (str_ends_with($string, "php")) {
    $saida2 = "<h1>inicia Com   </h1>";
}

echo $saida2;
echo "<br />====================<br />";
$strpbrk = "<h1>strpbrk - Nao achou</h1>";
if (strpbrk($string, "zs")) {
    $strpbrk = "<h1>strpbrk - achouuu   </h1>";
}
echo $strpbrk;
echo "<br />==========Uniao de tipos==========<br />";
*/
// class Number{
//     public function __construct(private int|float $number)
//      {echo "numero=> {$number}";}
// }
// new Number(0);
echo "<br />====================<br />";



$email = $_GET['nome'] ?? "valor pardao";

echo 'email=> '.$email;

//exit;
echo "<hr />";
echo "Página inicial teste";

$dbuser = $_ENV['MYSQL_USER'];
$dbpass = $_ENV['MYSQL_PASS'];

try {
    $pdo = new PDO("mysql:host=mysql;dbname=mvc_db", $dbuser, $dbpass);
	//$pdo = new PDO("mysql:host=172.19.0.5;dbname=mvc_db", $dbuser, $dbpass);//funciona
    $statement = $pdo->prepare("SELECT * FROM users");
    $statement->execute();
    $posts = $statement->fetchAll(PDO::FETCH_OBJ);
    
    echo "<h2>Posts</h2>";
    echo "<ul>";
    foreach ($posts as $post ) {
        echo "<li>".$post->name."</li>";
    }
    echo "</ul>";

} catch(PDOException $e) {
    echo $e->getMessage();
}