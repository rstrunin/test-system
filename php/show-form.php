<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Прохождение теста</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<?php
include_once 'db.php';
session_start();

if (!isset($_SESSION['test_id'])) header('Location: ../redirect/already-done.html');

echo "<form action='result.php' method='post'>";
echo "<h2 class='mt-4 text-center'>" . $_SESSION['test_title'] . "</h2>";
echo "<input type='hidden' name='done' value='true'></p>";

$id = $_SESSION['test_id'];
$res = $db->query("SELECT * FROM questions WHERE test_id = {$id}");
$res->execute();
$rows = $res->fetchAll();
foreach ($rows as $row) {
    $questionArr[] = ['id' => $row['id'], 'title' => $row['question'], 'type' => $row['type']];
}

foreach ($questionArr as $key => $question) {
    $key++;
    $res = $db->query("SELECT * FROM answers WHERE question_id = {$question['id']}");
    $answers = $res->fetchAll();

    $cardBody = '';
    $answerInputName = "question_" . $key . "_answer_id";
    if ($question['type'] == 'checkbox')
        $answerInputName .= '[]';

    foreach ($answers as $answer) {
        if ($question['type'] == 'radio' || $question['type'] == 'checkbox') {
            $cardBody .= "
                <div>
                    <input type='" . $question['type'] . "' name='" . $answerInputName . "'
                    value=" . $answer['id'] . "> " . $answer['answer'] . "
                </div>";
        }
        else if ($question['type'] == 'text') {
            $answerInputName = 'text_' . $answer['id'];
            $cardBody .= "
                <div>
                    <input type='" . $question['type'] . "' name='" . $answerInputName . "'>
                </div>";
        }
        else if ($question['type'] == 'number') {
            $answerInputName = 'number_' . $answer['id'];
            $cardBody .= "
                <div>
                    <input type='" . $question['type'] . "' name='" . $answerInputName . "'>
                </div>";
        }
    }

    echo "<input type='hidden'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='text-center mt-5'>
                    <p>Вопрос " . $key . " из " . count($questionArr) . "</p>
                </div>
                <div class='card mt-3'>
                    <div class='card-header'>
                        <h3>" . $question['title'] . "</h3>
                    </div>
                    <div class='card-body'>". $cardBody . "</div>
                </div>
            </div>
        </div>";

    $questionNum++;
}

echo "<div class='mt-4 mb-4 col text-center'>
        <button type='submit' class='btn btn-success'>Получить результат</button>
    </div>
    <input type='hidden' name='pageToShow' value='show-result.php'>";
echo "</form>";