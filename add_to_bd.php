<?php
echo " <head>	
    <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
    <script src='./checkAndPost.js'></script>
    <link rel='stylesheet' href='./style-css/style.css'>
</head>
<body>";

if (
  isset($_POST['material'])
  && isset($_POST['thickeness'])
  && isset($_POST['mass'])
  && isset($_POST['moisture'])
  && isset($_POST['temperature'])
  && isset($_POST['coeftemp'])
  && isset($_POST['inittemp'])
) {

  // Переменные с формы
  $material = $_POST['material']; //название материала
  $thickeness = $_POST['thickeness']; //толщина
  $mass = $_POST['mass']; //объемная масса в сухом состоянии 
  $moisture = $_POST['moisture']; //влажность
  $temperature = $_POST['temperature']; //температура
  $coef_temp = $_POST['coeftemp']; //температура для определения теплофизических хар-к
  $start_temp = $_POST['inittemp']; // начальная температура конструкции

  $density; //p, Плотность
  $thermal_conductivity; //лямбда, Теплопроводность 
  $thermal_conductivity_one_coef;
  $thermal_conductivity_two_coef;
  $heat_capacity; //с, Теплоемкость 
  $heat_capacity_one_coef;
  $heat_capacity_two_coef;
  // Параметры для подключения
  $db_host = "localhost";
  $db_user = "root"; // Логин БД
  $db_password = ""; // Пароль БД
  $db_base = 'wordpress-abc'; // Имя БД
  $db_table = "my_table"; // Имя Таблицы БД

  // Подключение к базе данных
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));

  //Запрос в таблицу характиристик по материалу
  $query = "SELECT * FROM `thermophysicalcharacteristics` WHERE `type_materials`= '$material'";
  $result = mysqli_query($mysqli, $query);

  if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_row($result)) {
      $density = $row[2];
      $thermal_conductivity_one_coef = $row[3];
      $thermal_conductivity_two_coef = $row[4];
      $heat_capacity_one_coef = $row[5];
      $heat_capacity_two_coef = $row[6];
    }

    //Запрос в таблицу степень черноты по материалу
    $query = "SELECT * FROM `black_degree` WHERE `title`= '$material'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {

      while ($row = mysqli_fetch_row($result)) {
        $temp = $row[2];
        $e = $row[3];
      }

      $query = "SELECT * FROM `coef_k` WHERE `p` >= '$mass' ORDER BY `p` ASC LIMIT 1";
      $result = mysqli_query($mysqli, $query); //коэфициент k

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_row($result)) {
          $k = $row[2];
        }

        // расчет по формулам
        $thermal_conductivity =  $thermal_conductivity_one_coef +  $thermal_conductivity_two_coef * $coef_temp;
        $heat_capacity = $heat_capacity_one_coef + $heat_capacity_two_coef * $coef_temp;

        $apr = round(3.6 * ($thermal_conductivity / (($heat_capacity + 0.05 * $moisture) * $density)), 5); //коэффициент температуропроводности
        $acrnp = round((4.8 + 9 * $e), 3); // коэффициент теплопередачи с необогреваемой поверхности
        $Bi = round(($acrnp / $thermal_conductivity) * ($thickeness + $k * sqrt($apr)), 2); // критерий Bi
        $query = "SELECT * FROM `table_bi` WHERE `bi`>='$Bi' ORDER BY `bi` ASC LIMIT 1";
        $result = mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($db));

        if (mysqli_num_rows($result) > 0) {

          while ($row = mysqli_fetch_row($result)) {
            $mu1 = $row[2];
            $A1 = $row[4];
          }

          $tnp = $temperature;
          $tn = $start_temp;
          $tnpr = $temperature + $tn;

          $x = pow($thickeness + $k * sqrt($apr), 2);
          $y = pow($mu1, 2) * $apr;

          $z = ($tnpr - $tn) / (1250 - $tn);
          $c = $A1 / ($z  - (1 / (1 + $Bi)));

          $tay = round(2.3 * ($x / $y) * log10($c), 2);

          echo " 
          
          <div class='result'>
            <table class='table table-hover' style='width: 50%;' border='1'>
              <caption>
                Введенные данные
              </caption>
            <tbody>
            <tr>
              <td scope='col-3'>Название материала</td>
              <td scope='col-3'>$material</td>
            </tr>
            <tr>
              <td>Толщина, м</td>
              <td><span id='th2'>$thickeness</span></td>
            </tr>
            <tr>
              <td>Объем массы в сухом состоянии, кг/м<sup>3</sup></td>
              <td>$mass</td>
            </tr>
            <tr>
              <td>Начальная весовая влажность, %</td>
              <td>$moisture</td>
            </tr>
            <tr>
              <td>Температура, °C</td>
              <td>$temperature</td>
            </tr>
            <tr>
              <td>Начальная температура, °C</td>
              <td><span id='tn'>$tn</span></td>
            </tr>
            </tbody>
            </table>
            <br>
            <table class='table table-hover' style='width: 30%;' border='1'>
            <caption>
              Табличные значения
            </caption>
            <tbody>
            <tr>
              <td scope='col' >Теплопроводность материала</td>
              <td scope='col' >$thermal_conductivity</td>
            </tr>
            <tr>
              <td>Теплоемкость материала</td>
              <td>$heat_capacity</td>
            </tr>
            <tr>
              <td>Плотность материала</td>
              <td>$density</td>
            </tr>
            <tr>
              <td>k коэффициент</td>
              <td><span id='k'>$k</span></td>
            </tr>
            </tbody>
            </table>
            <p class = 'coef'>Далее определяем коэффициент температуропроводности a<sub>пр</sub>. <br> 
	          a<sub>пр</sub> = <span id='apr'>$apr</span>; <br> 
	          Вычисляем коэффициент теплопередачи с необогреваемой поверхности a<sup>ср</sup><sub>н.п</sub>.<br>
	          a<sup>ср</sup><sub>н.п</sub> = $acrnp; <br>
	          Находим критерий Bi. <br>
	          Bi = <span id='bi'>$Bi</span>; <br>
	          По найденному значению Bi находим табличное значение  μ<sub>1</sub> и А<sub>1</sub>. <br>
	          μ<sub>1</sub> = <span id='mu1'>$mu1</span>; <br> 
	          А<sub>1</sub> = <span id='a1'>$A1</span>; <br>
	          Находим предел огнестойкости материала τ. <br>
	          τ ≈ $tay часа;
	          </p>
            <script src='./three.js/build/three.js'></script>
					  <script src='./three.js/examples/js/controls/OrbitControls.js'></script>
					  <script src='./model.js'></script>

            <div class='container'>
            <div class='row justify-content-center'>
            <div class='col-md-5'>
            <label for='time_r2' class='form-label'>Температура = </label>
            <input type='text' id='inputTemp' value='' disabled>
            <input type='range' class='form-range' value='0' min=$tn max=$tnpr step='1' id='time_r2'  oninput='colorWall()'>
            </div>
            <div class='row justify-content-center'>
            <div class='col-md-5'>
            <label for='inputTime' class='form-label'>Время = </label>
            <input type='text' id='inputTime' value='' disabled>
            </div>
            </div>
            </div>
            </div>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>
	          </div>
            </body>
            ";
        } else {
          echo "<p>Такого критерия Bi нет в табице table_bi</p>";
        }
      } else {
        echo "<p>Такого k нет в табице coef_k</p>";
      }
    }
  } else {
    echo "<p>Такого материала нет в табице black_degree</p>";
  }
} else {
  echo "<p>Такого материала нет в табице thermophysicalcharacteristics</p>";
}
