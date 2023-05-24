<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Стелла Мегаполис (прайс)</title>
	<link rel="stylesheet" href="../1/css/styles.css">
	<style>
		[v-cloak] {
			display: none;
		}
	</style>

</head>
<body>
	<div class="container pt-3" id="app" v-cloak>
		<div class="card center">
			<h2>{{title}}</h2>

			<form action="getPrice.php" class='form-control'>
			<table class='table'>
				<tbody>
					<tr><td><img src="/1/icon1.png" alt=""></td><td><input type="text" name='a100'></td></tr>
					<tr><td><img src="/1/icon2.png" alt=""></td><td><input type="text" name='a95'></td></tr>
					<tr><td><img src="/1/icon3.png" alt=""></td><td><input type="text" name='a92'></td></tr>
					<tr><td><img src="/1/icon4.png" alt=""></td><td><input type="text" name='dt'></td></tr>
				</tbody>
			</table>
			
			<div style='text-align: center; margin-top: 2rem'>
				<button type='submit' class='btn primary'>Получить картинку</button>
			</div>

			</form>

		</div>
	</div>

	<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
	<script src="/1/js/app.js"></script>	
</body>
</html>