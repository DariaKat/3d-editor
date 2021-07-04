<!doctype html>
<html lang="ru">

<head>
	<title>Расчет-панель</title>
	<!-- <link rel="stylesheet" href="style.css"> -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://kit.fontawesome.com/241b9a32d5.js" crossorigin="anonymous"></script>
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" href="./style-css/index.css">
	<link rel="shortcut icon" href="./img/logo.png" type="image/png">
	<script src="./checkAndPost.js"></script>
</head>

<body>

	<?php

	//подключаемся к базе данных
	$db = mysqli_connect("localhost", "root", "", "wordpress-abc");

	//выставляем параметры кодировки
	mysqli_query($db, "set character_set_client='utf8'");
	mysqli_query($db, "set character_set_results='utf8'");
	mysqli_query($db, "set collation_connection='utf8_general_ci'");

	//выбираем данные по материалам
	$result = mysqli_query($db, "select * from material");
	?>
	<header id="header">
		<nav class="navbar navbar-expand-lg navbar-light">
			<div class="container-lg">
				<a class="navbar-brand d-lg-none" href="http://localhost/wordpress-abc/"><b>Академия ГПС МЧС России</b></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="navbar-collapse collapse" id="navbarSupportedContent">
					<ul class="navbar-nav justify-content-end w-100">
						<li class="nav-item">
							<a class="nav-link" href="http://localhost/wordpress-abc/" role="button">
								<img class="icon-nav" src="./img/left-arrow.png" alt="">
								Вернуться назад
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="./index.php" role="button">
								<img class="icon-nav" src="./img/keys.svg" alt="">
								Расчет-панель
							</a>
						</li>
					</ul>
					<a class="navbar-brand d-none d-lg-block px-lg-6 logo" href="./index.php"><b>Академия ГПС МЧС России</b></a>
					<ul class="navbar-nav justify-content-start w-100">
						<li class="nav-item">
							<a class="nav-link" href="./add_material_in_db/add_edit_delete_bd.php" role="button">
								<img class="icon-nav" src="./img/add-file.svg" alt="">
								Добавление материала
							</a>
						</li>
						<li class="nav-item ">
							<a class="nav-link" href="./3-d _editor/index.html" role="button">
								<img class="icon-nav" src="./img/3d-cube.svg" alt="">
								Визуализация
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	<div id="content">
		<div id="left">
			<div class="container index">
				<div class="row justify-content-center">
					<h5>Введите данные в поля ниже.</h5>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-10">
						<select id="material" class="form-select form-select-lg mb-3" aria-label=".form-select-lg" name="material">
							<?php
							while ($myrow = mysqli_fetch_row($result)) {
								echo "
									<option value='$myrow[1]'> $myrow[1] </option>
								";
							}
							?>
						</select>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="thickeness" class="input-group-text">Толщина, м:</label>
							<input type="number" step="any" min="0" class="form-control" id="thickeness" name="thickeness">
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="mass" class="input-group-text">Объем массы в сухом состоянии, кг/м<sup>3</sup>:</label>
							<input type="number" step="any" min="0" class="form-control" id="mass" name="mass">
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="moisture" class="input-group-text">Начальная весовая влажность, %:</label>
							<input type="number" step="any" min="0" class="form-control" id="moisture" name="moisture">
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="inittemp" class="input-group-text">Начальная температура, °C:</label>
							<input type="number" step="any" min="0" class="form-control" id='inittemp' name='inittemp'>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="temperature" class="input-group-text">Температура, °C:</label>
							<input type="number" step="any" min="0" class="form-control" id='temperature' name='temperature'>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="input-group mb-2">
							<label for="coeftemp" class="input-group-text">Температура для теплофиз. хар-к, °C:</label>
							<input type="number" step="any" min="0" class="form-control" id='coeftemp' name='coeftemp'>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-8 case">
						<button type="button" class="w-100 btn btn-primary button_form" name="button_add" id="button_add">Рассчитать</button>
					</div>
				</div>
			</div>
			<div id='result_sql'>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>

</html>