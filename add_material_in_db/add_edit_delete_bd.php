<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/241b9a32d5.js" crossorigin="anonymous"></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="../style-css/style.css">
    <link rel="stylesheet" href="../style-css/index.css">
    <link rel="stylesheet" href="../style-css/material.css">
    <link rel="shortcut icon" href="../img/logo.png" type="image/png">
    <title>Форма-материалы</title>
</head>

<body>
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
                                <img src="../img/left-arrow.png" alt="">
                                Вернуться назад
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php" role="button">
                                <img src="../img/keys.svg" alt="" width="24px" height="24px">
                                Расчет-панель
                            </a>
                        </li>
                    </ul>
                    <a class="navbar-brand d-none d-lg-block px-lg-6 logo" href="../index.php"><b>Академия ГПС МЧС России</b></a>
                    <ul class="navbar-nav justify-content-start w-100">
                        <li class="nav-item">
                            <a class="nav-link" href="../add_material_in_db/add_edit_delete_bd.php" role="button">
                                <img src="../img/add-file.svg" alt="" width="24px" height="24px">
                                Добавление материала
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="../3-d _editor/index.html" role="button">
                                <img src="../img/3d-cube.svg" alt="" width="24px" height="24px">
                                Визуализация
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div id="form-material">
        <?php
        if (!isset($_GET["action"])) $_GET["action"] = "showlist";

        switch ($_GET["action"]) {
            case "showlist":    // Список всех записей в таблице БД
                show_list();
                break;
            case "addform":     // Форма для добавления новой записи
                get_add_item_form();
                break;
            case "add":         // Добавить новую запись в таблицу БД
                add_item();
                break;
            case "editform":    // Форма для редактирования записи
                get_edit_item_form();
                break;
            case "update":      // Обновить запись в таблице БД
                update_item();
                break;
            case "delete":      // Удалить запись в таблице БД
                delete_item();
                break;
            default:
                show_list();
        }





        // Функция выводит список всех записей в таблице БД
        function show_list()
        {
            $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = ""; // Пароль БД
            $db_base = 'wordpress-abc'; // Имя БД
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));
            $query = 'SELECT * FROM material';
            $query_black = "SELECT * FROM black_degree join material on material.mat = black_degree.title";
            $query_charact = "SELECT * FROM thermophysicalcharacteristics join material on material.mat = thermophysicalcharacteristics.type_materials";
            $res = mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli));
            $result_1 = mysqli_query($mysqli, $query_black) or die("Ошибка " . mysqli_error($mysqli));
            $result_2 = mysqli_query($mysqli, $query_charact) or die("Ошибка " . mysqli_error($mysqli));
            echo '
                <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Название материала</th>
                        <th scope="col">Плотность</th>
                        <th scope="col">Теплоемкость <br> (1 арг.)</th>
                        <th scope="col">Теплоемкость <br>(2 арг.)</th>
                        <th scope="col">Теплопроводимость <br>(1 арг.)</th>
                        <th scope="col">Теплопроводимость <br>(1 арг.)</th>
                        <th scope="col">Температура</th>
                        <th scope="col">Степень черноты</th>
                        <th scope="col">Редактирование</th>
                        <th scope="col">Удаление</th>
                    </tr>
                </thead>
                ';

            while ($item = mysqli_fetch_row($res)) {
                $row3 = mysqli_fetch_row($result_2);
                $row2 = mysqli_fetch_row($result_1);
                echo '<tr>';
                echo '<td>' . $item[1] . '</td>';
                echo '<td>' . $row3[2] . '</td>';
                echo '<td>' . $row3[3] . '</td>';
                echo '<td>' . $row3[4] . '</td>';
                echo '<td>' . $row3[5] . '</td>';
                echo '<td>' . $row3[6] . '</td>';
                echo '<td>' . $row2[2] . '</td>';
                echo '<td>' . $row2[3] . '</td>';
                echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?action=editform&id=' . $item[1] . '" class="btn btn-primary">Ред.</a></td>';
                echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?action=delete&id=' . $item[1] . '" class="btn btn-primary">Удл.</a></td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<p><a href="' . $_SERVER['PHP_SELF'] . '?action=addform" class="btn btn-primary">Добавить</a></p>';
        }

        // Функция формирует форму для добавления записи в таблице БД
        function get_add_item_form()
        {
            echo '<h3>Добавить</h3>';
            echo '<form name="addform" action="' . $_SERVER['PHP_SELF'] . '?action=add" method="POST">';
            echo '<div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="material" class="input-group-text">Материал</label>
                        <input type="text" class="form-control" id="material" name="material" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="density" class="input-group-text">Плотность</label>
                        <input type="number" step="any" min="0" class="form-control" id="density" name="density" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="thermal_cond_one_coef" class="input-group-text">Теплоемкость 1 арг.</label>
                        <input type="number" step="any" class="form-control" id="thermal_cond_one_coef" name="thermal_cond_one_coef" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="thermal_cond_two_coef" class="input-group-text">Теплоемкость 2 арг.</label>
                        <input type="number" step="any" class="form-control" id="thermal_cond_two_coef" name="thermal_cond_two_coef" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="heat_capacity_one_coef" class="input-group-text">Теплопроводимость 1 арг.</label>
                        <input type="number" step="any" class="form-control" id="heat_capacity_one_coef" name="heat_capacity_one_coef" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="heat_capacity_two_coef" class="input-group-text">Теплопроводимость 2 арг.</label>
                        <input type="number" step="any" class="form-control" id="heat_capacity_two_coef" name="heat_capacity_two_coef" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="temp" class="input-group-text">Температура</label>
                        <input type="number" step="any" class="form-control" id="temp" name="temp" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="e" class="input-group-text">Степень черноты</label>
                        <input type="number" step="any" class="form-control" id="e" name="e" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 case">
                    <input type="submit" class="w-100 btn btn-primary" id="add_material" value="Добавить">
                </div>
            </div>
        </div>';
            echo '</form>';
        }

        // Функция добавляет новую запись в таблицу БД 
        function add_item()
        {
            $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = ""; // Пароль БД
            $db_base = 'wordpress-abc'; // Имя БД
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));
            // $title = $mysqli->real_escape_string($_POST['title']);
            $material = $mysqli->real_escape_string($_POST['material']);
            $density = $mysqli->real_escape_string($_POST['density']);
            $thermal_cond_one_coef = $mysqli->real_escape_string($_POST['thermal_cond_one_coef']);
            $thermal_cond_two_coef = $mysqli->real_escape_string($_POST['thermal_cond_two_coef']);
            $heat_capacity_one_coef = $mysqli->real_escape_string($_POST['heat_capacity_one_coef']);
            $heat_capacity_two_coef = $mysqli->real_escape_string($_POST['heat_capacity_two_coef']);
            $temp = $mysqli->real_escape_string($_POST['temp']);
            $e = $mysqli->real_escape_string($_POST['e']);
            $query = "INSERT INTO material (mat) VALUES ('" .  $material . "');";
            $query1 = "INSERT INTO black_degree (title, temp, e) VALUES ('" .  $material . "', '" . $temp . "', '" .  $e . "');";
            $query2 = "INSERT INTO thermophysicalcharacteristics (type_materials, density, thermal_conductivity_one_coef, thermal_conductivity_two_coef, heat_capacity_one_coef, heat_capacity_two_coef) VALUES ('" . $material . "', '" . $density . "', '" . $thermal_cond_one_coef . "', '" . $thermal_cond_two_coef . "', '" .  $heat_capacity_one_coef . "', '" .  $heat_capacity_two_coef . "');";
            mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query1) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query2) or die("Ошибка " . mysqli_error($mysqli));
            header('Location: ' . $_SERVER['PHP_SELF']);
            die();
        }

        // Функция удаляет запись в таблице БД
        function delete_item()
        {
            $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = ""; // Пароль БД
            $db_base = 'wordpress-abc'; // Имя БД
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));
            $id = $_GET['id'];
            $query = "DELETE FROM material WHERE mat='" . $id . "'";
            $query1 = "DELETE FROM black_degree WHERE title='" . $id . "'";
            $query2 = "DELETE FROM thermophysicalcharacteristics WHERE type_materials='" . $id . "'";
            mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query1) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query2) or die("Ошибка " . mysqli_error($mysqli));
            header('Location: ' . $_SERVER['PHP_SELF']);
            die();
        }


        // Функция формирует форму для редактирования записи в таблице БД
        function get_edit_item_form()
        {
            echo '<h3>Редактировать</h3>';
            $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = ""; // Пароль БД
            $db_base = 'wordpress-abc'; // Имя БД
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));
            $id = $_GET['id'];
            $query = "SELECT mat FROM material WHERE mat='" . $id . "'";
            $query_black = "SELECT * FROM black_degree join material on material.mat = black_degree.title";
            $query_charact = "SELECT * FROM thermophysicalcharacteristics join material on material.mat = thermophysicalcharacteristics.type_materials";
            $res = mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli));
            $result_1 = mysqli_query($mysqli, $query_black) or die("Ошибка " . mysqli_error($mysqli));
            $result_2 = mysqli_query($mysqli, $query_charact) or die("Ошибка " . mysqli_error($mysqli));
            $item = mysqli_fetch_row($res);
            $item1 = mysqli_fetch_row($result_1);
            $item2 = mysqli_fetch_row($result_2);
            echo '<form name="editform" action="' . $_SERVER['PHP_SELF'] . '?action=update&id=' . $_GET['id'] . '" method="POST">';
            echo '<div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="material" class="input-group-text">Материал</label>
                        <input type="text" class="form-control" id="material" name="material" value="' . $item[0] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="density" class="input-group-text">Плотность</label>
                        <input type="number" step="any" min="0" class="form-control" id="density" name="density" value="' . $item2[2] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="thermal_cond_one_coef" class="input-group-text">Теплоемкость 1 арг.</label>
                        <input type="number" step="any" class="form-control" id="thermal_cond_one_coef" name="thermal_cond_one_coef" value="' . $item2[3] . '" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="thermal_cond_two_coef" class="input-group-text">Теплоемкость 2 арг.</label>
                        <input type="number" step="any" class="form-control" id="thermal_cond_two_coef" name="thermal_cond_two_coef" value="' . $item2[4] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="heat_capacity_one_coef" class="input-group-text">Теплопроводимость 1 арг.</label>
                        <input type="number" step="any" class="form-control" id="heat_capacity_one_coef" name="heat_capacity_one_coef" value="' . $item2[5] . '" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <label for="heat_capacity_two_coef" class="input-group-text">Теплопроводимость 2 арг.</label>
                        <input type="number" step="any" class="form-control" id="heat_capacity_two_coef" name="heat_capacity_two_coef" value="' . $item2[6] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="temp" class="input-group-text">Температура</label>
                        <input type="number" step="any" class="form-control" id="temp" name="temp" value="' . $item1[2] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <label for="e" class="input-group-text">Степень черноты</label>
                        <input type="number" step="any" class="form-control" id="e" name="e" value="' . $item1[3] . '" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 case">
                    <input type="submit" class="w-100 btn btn-primary" id="add_material" value="Сохранить">
                </div>
            </div>
        </div>';
            echo '</form>';
        }

        // Функция обновляет запись в таблице БД 
        function update_item()
        {
            $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = ""; // Пароль БД
            $db_base = 'wordpress-abc'; // Имя БД
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base) or die("Ошибка " . mysqli_error($mysqli));
            // $title = $mysqli->real_escape_string($_POST['title']);

            $material = $mysqli->real_escape_string($_POST['material']);
            $density = $mysqli->real_escape_string($_POST['density']);
            $thermal_cond_one_coef = $mysqli->real_escape_string($_POST['thermal_cond_one_coef']);
            $thermal_cond_two_coef = $mysqli->real_escape_string($_POST['thermal_cond_two_coef']);
            $heat_capacity_one_coef = $mysqli->real_escape_string($_POST['heat_capacity_one_coef']);
            $heat_capacity_two_coef = $mysqli->real_escape_string($_POST['heat_capacity_two_coef']);
            $temp = $mysqli->real_escape_string($_POST['temp']);
            $e = $mysqli->real_escape_string($_POST['e']);
            $id = $_GET['id'];
            $query = "UPDATE material SET mat='" .  $material . "' WHERE mat='" . $id . "'";
            $query1 = "UPDATE black_degree SET title='" .  $material . "', temp = '" .  $temp . "', e = '" .  $e . "' WHERE title='" . $id . "'";
            $query2 = "UPDATE thermophysicalcharacteristics SET type_materials='" .  $material . "', density = '" .  $density . "', thermal_conductivity_one_coef = '" .  $thermal_cond_one_coef . "', thermal_conductivity_two_coef = '" .  $thermal_cond_two_coef . "', heat_capacity_one_coef = '" . $heat_capacity_one_coef . "', heat_capacity_two_coef = '" .  $heat_capacity_two_coef . "' WHERE type_materials='" . $id . "'";
            mysqli_query($mysqli, $query) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query1) or die("Ошибка " . mysqli_error($mysqli));
            mysqli_query($mysqli, $query2) or die("Ошибка " . mysqli_error($mysqli));
            header('Location: ' . $_SERVER['PHP_SELF']);
            die();
        } ?>
    </div>
    <script src="./checkFormMaterial.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>

</html>