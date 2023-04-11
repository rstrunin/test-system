<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Просмотр результатов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
</head>

<?php
include_once 'db.php';
session_start();

function getCustomDate($timestamp) {
    $monthNum  = date('m');
    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
    $monthName = $dateObj->format('F');

    $dayNum  = date('d');

    return $dayNum . ' ' . $monthName . ' ' . date("G:i T", $timestamp);
}

function getCustomInterval($timestamp) {
    return gmdate('H ч. i мин. s сек.', $timestamp);
}

echo "
<div class='h-100 d-flex align-items-center justify-content-center'>
    <div class='col-md-6'>
        <div class='card mt-3'>
            <div class='card-header'>
                <h3>Ваш результат</h3>
                <p class='result-datetime'>Дата начала: " . getCustomDate($_SESSION['start_time']) .  " </p>
                <p class='result-datetime'>Дата завершения: " . getCustomDate($_SESSION['end_time']) .  " </p>
                <p class='result-datetime'>Затраченное время: " . getCustomInterval($_SESSION['end_time'] - $_SESSION['start_time']) .  " </p>
            </div>
            <div class='card-body'>
                <div class='result-print'> " . $_SESSION['test_score'] . " из " . $_SESSION['test_score_max'] . " </div>
                <div class='result-print'> " . $_SESSION['result'] . " </div>
            </div>
        </div>
    </div>
</div>";