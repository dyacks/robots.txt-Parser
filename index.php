<?php

use App\Classes\View;

require_once __DIR__ . '/autoload.php';
//define('ENVIRONMENT', getenv('ENVIRONMENT'));
define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

/* // working if no ЧПУ
$ctrl = isset($_GET['ctrl']) ? $_GET['ctrl'] : 'Main';
$act = isset($_GET['act']) ? $_GET['act'] : 'Index';
*/

// add ЧПУ
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // узнаём что было в url до rewriteRule / выделяем путь
$pathParts = explode('/', $path);

$ctrl = !empty($pathParts[1]) ? ucfirst($pathParts[1]) : 'Main'; // ucfirst() делает первую букву большой
$act = !empty($pathParts[2]) ? ucfirst($pathParts[2]) : 'Index';

$controllerClassName  = 'App\Controllers\\' . $ctrl . 'Controller';

//try{
    $controller = new $controllerClassName;
    $method = 'action' . $act;
    $controller->$method();
/*
} catch (Exception $e){
    $view = new View();
    $view->error = $e->getMessage();
    $view->display('error');
}
*/





















    die("<br/> to be continue ...");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>is robots.txt present?</title>
    <meta name="description" content="Форма проверки файла"/>
    <meta name="keywords" content="form"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>
<div class="container">

    <header>

        <h1>Введите <strong>URL</strong></h1>
        <h2>Для проверки наличия файла <strong>robots.txt</strong></h2>

    </header>

    <section class="main">
        <form class="form" action="index.php" method="get">
            <p class="clearfix">
                <input type="url" name="customURL" value="http://">
            </p>
            <p class="clearfix">
                <input type="submit" name="submit" value="Искать">
            </p>
        </form>
    </section>

    <?php
    if (!isset($_GET['customURL'])) {
        die('');
    } elseif (!Robots::isURL($_GET['customURL'])) {
        die("<h2 align='center'>Введите корректный <strong>URL</strong> адрес</h2><h2 align='center'>Например <strong>http://google.com</strong></h2>");
    }
    $robots = new Robots($_GET['customURL']);
    echo "<h2 align='center'>" . 'Статистика для ' . $robots->getRobotsURL() . "</h2>"; ?>

    <table>
        <tr>
            <th>Название проверки</th>
            <th colspan="2">Статус</th>
            <th>Текущее состояние</th>
        </tr>

        <?php for ($i = 0; $i <= 5; $i++) {
            $status = $robots->getStatus($i);
            if ($i == 3 && $robots->getDerectiveHostCount() < 1) {
                continue;
            } ?>

            <tr>
                <td rowspan="2"><?php echo $robots->getTask($i) ?></td>
                <td rowspan="2" id="status" bgcolor="<?php echo $robots->getColor() ?>"><?php if ($status != true) {
                        echo 'Ошибка';
                    } else {
                        echo 'Ok';
                    } ?>
                </td>
                <td>Состояние</td>
                <td colspan="2"><?php echo $robots->getState($i, $status) ?></td>
            </tr>
            <tr>
                <td>Рекомендации</td>
                <td colspan="2"><?php echo $robots->getRecomendet($i, $status) ?></td>
            </tr>

            <?php if ($robots->getCountLineFile() == 0 && $i == 1)
                break;
            if ($i == 1)
                $robots->setContent();
        } ?>

    </table>
</div>

</body>
</html>