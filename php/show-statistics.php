<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Статистика по тесту</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<body class="d-flex justify-content-center">
<div class="col-md-6">
<div class="card mt-4">
<div class="card-header">
<h2 class="text-center">Результаты тестирования</h2>
</div>

<?php
include_once 'db.php';
session_start();

$title = $_POST['title'];
$pass = $_POST['pass'];

$res = $db->prepare("SELECT id FROM tests WHERE title = :title");
$res->execute([':title' => $title]);
$id = $res->fetch()['id'];

$res = $db->prepare("SELECT statistics_pass FROM tests WHERE id = :id");
$res->execute([':id' => $id]);
$hash = $res->fetch()['statistics_pass'];

$isLegit = password_verify($pass, $hash);

//if ($isLegit) {
    echo "
        <table class='table'>
            <thead>
                <tr>
                    <th scope='col'>№</th>
                    <th scope='col'>Имя пользователя</th>
                    <th scope='col'>Дата начала</th>
                    <th scope='col'>Дата конца</th>
                    <th scope='col'>Затраченное время</th>
                    <th scope='col'>Результат</th>
                </tr>
            </thead>
            <tbody>
    ";

    $res = $db->prepare("SELECT login, start_time, end_time, test_score FROM archive WHERE test_id = :id");
    $res->execute([':id' => $id]);
    $data = $res->fetchAll();
    
    for ($i = 0; $i < count($data); $i++) {
        echo "
            <tr>
                <th scope='row'>" . $i . "</th>
                <td>" . $data[$i]['login'] . "</td> 
                <td>" . getCustomDate($data[$i]['start_time']) . "</td>
                <td>" . getCustomDate($data[$i]['end_time']) . "</td>
                <td>" . getCustomInterval($data[$i]['end_time'] - $data[$i]['start_time']) . "</td>
                <td>" . $data[$i]['test_score'] . "</td>
            </tr>
        ";
    }

    echo  "</tbody> 
        </table>
    ";
//}
?>

</div>
</div>
</body>