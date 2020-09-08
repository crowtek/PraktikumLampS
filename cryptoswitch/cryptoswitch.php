
<?php
/**
*Plugin Name: Kryptoswitcher Plugin
*Description: Bestimmung eines EUR-Wertes einer Kryptowährung zu einem vergangenen Zeitraum.
**/
use Codenixsv\CoinGeckoApi\CoinGeckoClient;


add_action("admin_menu", "addMenu");
function addMenu()
{
  add_menu_page("Kryptowährung Plugin", "Kryptowährung Plugin", 4, "kryptowährung Plugin", "exampleMenu" );
}

function exampleMenu()
{

require '../vendor/autoload.php';
$client = new CoinGeckoClient();
$data = $client->coins()->getHistory('bitcoin', '08-09-2020')['market_data']['current_price']['eur'];

$message = "";
if(isset($_POST['SubmitButton'])){ //check if form was submitted
  $input = $_POST['Euro']; //get input text
  $message = "Value ".$input;
}

echo <<< 'EOD'

<h2>Kryptoswitcher Plugin</h2>

<form action="" method="post">
  <label for="Euro">Euro:</label><br>
  <input type="number" name="Euro" value="">

	<select>
		<option Value="BitCoin">BitCoin</option>
		<option Value="Bitcoin Cash">Bitcoin Cash</option>
		<option Value="Litecoin">Litecoin</option>
		<option Value="Dash">Dash</option>
	</select><br>

  	<label for="time">Datum:</label><br>
    <input type="datetime-local" id="time" name="time" value="2020-06-12T19:30" min="2000-06-07T00:00" max="2020-06-14T00:00">

	<input type="submit" name="SubmitButton" value="Submit">
</form><br>

<label for="Kryptowährung">Kryptowährung:</label><br>
<input type="text" name="Kryptowährung" value=""><br>
  
EOD;
echo '<pre>' . var_export($data, true) . '</pre>';
}


?>
