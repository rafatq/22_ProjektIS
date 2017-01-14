<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Projekt - Maciaszek Mateusz</title>

<link rel="stylesheet" type="text/css" href="css.css" media="screen">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		
    	$("#submit").click(function(){
			$("#info").slideUp(2);
        	var a = $("#numer").val();
			
			if (check(a)){
				return true;
			} else{
			
				$("#info").slideDown(200);
				return false;
			}
			
    });
});


function check(a){
	
	var ch = /^[0-9]+$/;
	var ret = 0;
	
	if (a.length === 8){ //sprawdzanie długości ciągu
		if (ch.test(a)){ // sprawdzanie reguły ciągu 
			ret = 1;
		} 
		else ret = 0;
	} else ret = 0;

	return ret;
}
</script>
</head>

<body>
	<div id="center-column">
     <h1><a href="http://polmak.ayz.pl/">PROJEKT</a></h1>
    	
    <div id="form">
    	<div class="form">
    	<form action="index.php" method="post">
        	<label>WSTAW NUMER ID PRZEDMIOTU Z CENEO<br/><span style="font-size: 8px; letter-spacing: 4px;">* DANE MUSZĄ MIEĆ POSTAĆ CIĄGU 8 CYFR</label>
        	<input type="text" maxlength="8" id="numer" name="numer" />
            <input type="submit" id="submit" value="SUBMIT"/>
         </form>
         </div>
    </div>
    <div id="info">WPROWADZONE DANE SĄ NIEPOPRAWNE</div>
    
<?php
/* zainkludowanie biblioteki simple html */
 require_once('simple_html_dom.php');
 
if ( $_SERVER['REQUEST_URI'] == '/'){
 
 $fp = fopen("link.txt", "w");
				fputs($fp, NULL);
				fclose($fp);
}
 
/* obsługa przesłanego ID z formularza */

if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		
	if ($_POST['numer']){
		$numer = (int) ($_POST['numer']);
	}

	if(preg_match('/^[0-9]+$/D', $numer)){
				$id_formularza = trim($numer);
				$ceneo_link =  trim('http://www.ceneo.pl/'.$id_formularza);
				
				/*zapis linku do pliku link.txt */
				$fp = fopen("link.txt", "w");
				fputs($fp, $ceneo_link);
				fclose($fp);
				
				$var_1 = 1;
			} else $var_1 = 0;
	} else $var_1 = 3;
	



function check($a){
	if ($a == 0 or $a == 3){
		$info =  '<div id="info" style="display: block">WPROWADZONE DANE SĄ NIEPRAWIDŁOWE</div>';	
	}
	return $info;
}


/* komunikat nieprawidłowo przesłanych danych */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	echo check($var_1);
}
/* END */




/* sprawdzanie linku ceneo */

if ($ceneo_link){
	if ($html = file_get_html($ceneo_link)){
		
		echo '<div id="menu"><h2>Pobrano dane dla strony: '.$ceneo_link.'</h2></div>';
		$html = file_get_html($ceneo_link);
	}
	else echo '<div id="menu"><h2><a href="'.$ceneo_link.'" target=_blank">STRONA '.$ceneo_link.'NA CENEO.PL PRAWDOPODOBNIE NIE ISTNIEJE</a></h2></div>';
}


	 
 /* END */

?>


<!-- MENU BAZY DANYCH -->
<div id="sub-menu">

<?php

/* odczyt linku */
$fp = fopen("link.txt", "r");

if($fp) {
	$ceneo_link = fread($fp, 200);
	
	if ($ceneo_link !=''){
		
		$html = file_get_html($ceneo_link);
		$zawartosc_pliku = 1;
	}
	else $zawartosc_pliku = 0;
}
fclose($fp);



/* MENU OBSŁUGI BAZY DANYCH */



if ($_GET['link'] && ($_GET['query'])){

		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=insert">WSTAW DO BAZY DANYCH</a>';
		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=query">ZOBACZ ZAWARTOŚĆ BAZY DANYCH</a>';
		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=delete">WYCZYŚĆ BAZĘ DANYCH</a>';

}

elseif ($ceneo_link) {
		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=insert">WSTAW DO BAZY DANYCH</a>';
		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=query">ZOBACZ ZAWARTOŚĆ BAZY DANYCH</a>';
		echo '<a href="http://polmak.ayz.pl?link='.$ceneo_link.'&query=delete">WYCZYŚĆ BAZĘ DANYCH</a>';
}
/* END */



/* zmienna przechowująca adres strony */ 
$adres_strony =  $_SERVER['REQUEST_URI'];
/* END */
?>

</div>
<div id="db-block">
<h4>BLOK OBSŁUGI BAZY DANYCH</h4>





<?php
/* POŁĄCZENIE Z BAZĄ DANYCH */

try
{
    $db = new PDO('mysql:host=localhost;dbname=polmak_maciaszek','polmak_maciaszek','mac123');
}
catch (PDOException $e)
{
    print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
    die();
}

/* END */





/* zapytania do bazy danych --> wyświetlanie wyników */

if ($_GET['query'] == 'query'){

$stmt = $db->query('SELECT id, urzadzenie, marka, model, uwagi FROM user');
   echo '<ul>';
   foreach($stmt as $row)
   {
       echo '<li>Urządzenie:<b> '.$row['urzadzenie'].'</b></li>';
	   echo '<li>Marka:<b> '.$row['marka'].'</b></li>';
	   echo '<li>Model:<b> '.$row['model'].'</b></li>';
	   echo '<li>Uwagi:<b> '.$row['uwagi'].'</b></li>';
   }
   echo '</ul>';
}
/* END */



/* zapytania do bazy danych --> czyszczenie bazy */

if ($_GET['query'] == 'delete'){

$stmt = $db->exec('DELETE FROM user');
 if($stmt){
		echo '<p>Rekordy bazy zadnych zostały usunięte.</p>'; 
 } else echo '<p>Nie udało się usunąć rekordów.</p>';
}
/* END */






/* zapytania do bazy danych --> wstawianie rekordów */

if ($zawartosc_pliku == 1){

$nazwa_produktu =  $html->find("strong.[class=js_searchInGoogleTooltip]", 1)->innertext;
$uwagi_prod =  $html->find("div.[class=ProductSublineTags]", 0)->innertext;
$kategoria =  $html->find("ul.[class=linked-pages]", 0)->children();
$kategoria_numer =  1 /* count($kategoria)-1 */;
$array_urzadzenie = explode(' ', $nazwa_produktu);


/*zmienne do zapisania w bazie danych */
$urzadzenie = $html->find("ul.[class=linked-pages]", 0)->children($kategoria_numer)->innertext;
$marka = $array_urzadzenie[0];
$model = trim($array_urzadzenie[1]);
$uwagi = $uwagi_prod;

if ($_GET['query'] == 'insert'){
$stmt = $db->exec('DELETE FROM user');
$stmt = $db->prepare("INSERT INTO user(id, urzadzenie, marka, model, uwagi) VALUES(:id, :urzadzenie, :marka, :model, :uwagi)");
$stmt->execute(array(':id'=>NULL, ':urzadzenie'=> $urzadzenie, ':marka' => $marka, ':model' => $model, ':uwagi' => $uwagi));

 if($stmt){
		echo '<p>Baza danych została zaktualizowana.</p>'; 
 } else echo '<p>Aktualizacja bazy danych nie powiodła się.</p>';
}
}

/* END */
?>
</div>

    
<h2>Nazwa produktu: <b><?php echo $nazwa_produktu; ?></b></h2>
<?php if ($zawartosc_pliku == 1){
echo '<h3>Dane pobrane z strony: <a href="'.$ceneo_link.'" target="_blank">'.$ceneo_link.'</a> </h3>';
}
?>
<div id="ocena">
<p><span>ŚREDNIA OCENA PRODUKTU</span></p>
<?php
if ($zawartosc_pliku == 1){
$ocena =  $html->find("span.[class=product-score]", 0)->innertext;
echo '<p>'.$ocena.'</p>';
}
?>

</div>	


<div id="opinie">	
<h4>OPINIE UŻYTKOWNIKÓW:</h4>
<?php
/* POBIERANIE OPINII UŻYTKOWNIKÓW */

if ($zawartosc_pliku == 1){
$data['comment'] = $html->find("li.[class=product-review]");
echo "<ol>";
for($x = 0; $x < count($data['comment']); $x++){
	echo $html->find("li.[class=product-review]", $x)->innertext;
	echo '<br/>';	
}
}

echo "</ol>"; 

?>
</div>
<div id="footer"></div>
</div>
</body>
</html>




