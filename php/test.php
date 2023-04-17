<?php
include_once 'db.php';
session_start();

$testId = (int) $_GET['id'];
$login = $_GET['login'];

$stmt = $db->prepare("SELECT title FROM tests WHERE id = ${testId}");
$stmt->execute();

if ($stmt->rowCount() <= 0) {
    header("Location: redirect/404.html");
    exit();
}
else {
    $data = $stmt->fetch()['title'];
    $_SESSION['login'] = $login;

    $_SESSION['test_title'] = $data;
    $_SESSION['test_id'] = $testId;
    $_SESSION['result'] = 'Результат отсутствует, возникла ошибка сессии';

    $stmt = $db->prepare("SELECT id FROM questions WHERE test_id = {$testId}");
    $stmt->execute();
    $data = $stmt->fetchAll();
    foreach ($data as $questionData) {
        $testQuestionIds[] = $questionData['id'];
    }

    $inQuery = str_repeat('?,', count($testQuestionIds) - 1) . '?';
    $stmt = $db->prepare("SELECT score FROM answers WHERE question_id IN (" . $inQuery . ")");
    $stmt->execute($testQuestionIds);
    $data = $stmt->fetchAll();
    $score = 0;
    foreach ($data as $questionData) {
        $score += $questionData['score'];
    }

    $_SESSION['test_score_max'] = $score;
    $_SESSION['test_score'] = 0;

    $_SESSION['start_time'] = time();
    $_SESSION['end_time'] = time() + 3600;

    $res = $db->query("SELECT * FROM tests WHERE id = {$testId}");
    $row = $res->fetch();
    $testTitle = $row['title']; // Название теста

    $res = $db->query("SELECT count(*) AS count FROM questions WHERE test_id = {$testId}");
    $row = $res->fetch();
    $questionCount = $row['count']; // Количество вопросов ввыбранном тесте

    header("Location: show-form.php");
    exit();
}