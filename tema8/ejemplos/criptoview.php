<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Consumiendo el API de Criptomonedas</title>
	</head>
	<body>

		<?php


			$json = file_get_contents("https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?CMC_PRO_API_KEY=e360a943-74c6-4e69-8401-4f59155f88c7&limit=25&convert=EUR");
			https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest
			$coins = json_decode($json);

			foreach($coins->data as $coin) {
				printf("%s - %.4f €<br>",$coin->name,$coin->quote->EUR->price);
			}


		?>
	</body>
</html>