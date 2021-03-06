
<?php
/**
*Plugin Name: Kryptoswitcher Plugin
*Description: Bestimmung eines EUR-Wertes einer Kryptowährung zu einem vergangenen Zeitraum. Shortcode [crypto]
**/

defined( 'ABSPATH' ) || exit();

function getHistory($id, $date) 
{
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.coingecko.com/api/v3/coins/".$id."/history?date=".$date,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$response = json_decode($response, true);
$coinValue = $response['market_data']['current_price']['eur'];
return (double)$coinValue;
}

function createCrypto()
{
$coinValue = null;
$errorMessage = "";
$message = "";

if(isset($_POST['SubmitButton'])) { //check if form was submitted
  $value = $_POST['cryptoValue']; //get input text
  $cryptoCurrency = $_POST['cryptoCurrency'];
  $date = $_POST['date'];
  	if($value && $cryptoCurrency && $date) {
	  $date = new DateTime($date);
	  $date = $date->format('d-m-Y');
	  $rawCoinValue = getHistory($cryptoCurrency, $date) * $value;
	  $coinValue = number_format($rawCoinValue, 2, '.', '');
	} else {
		$errorMessage = '<center style="color: red;">Fehler: Es müssen alle Felder ausgefüllt sein.</center>';
	}

	if($coinValue)
	{
		$message = "<center><b>".$coinValue."€ </b></center>";
	}
}

$output = <<<HTML

<div class="cryptoswitcher">
	<h2>Kryptoswitcher Plugin</h2>

	<form action="" method="post">
	  <label for="Kryptowährung">Kryptowährung:</label><br>
	  <input type="number" step="any" name="cryptoValue" value="">

		<select name="cryptoCurrency">
			<option value="bitcoin">BitCoin</option>
			<option value="bitcoin-cash">Bitcoin Cash</option>
			<option value="litecoin">Litecoin</option>
			<option value="dash">Dash</option>
		</select><br>

	  	<label for="time">Datum:</label><br>
	    <input type="date" id="time" name="date" value="">

		<input type="submit" name="SubmitButton" value="Submit">

		<br><label for="Euro">Euro:</label><br>
	</form><br>
	{$errorMessage}
	{$message}
</div>	

HTML;
	
return $output;		
}

add_shortcode("crypto", "createCrypto");

?>



