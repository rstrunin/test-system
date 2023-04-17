<?php
include_once 'db.php';
session_start();

if ($_POST['done']) {
    $_SESSION['end_time'] = time();

    $finalAnswersIdArray = [];
    foreach ($_POST as $answers) {
        if (gettype($answers) == 'array') {
            foreach ($answers as $answer) $finalAnswersIdArray[] = $answer;
        }
        else $finalAnswersIdArray[] = $answers;
    }

    $inQuery = str_repeat('?,', count($finalAnswersIdArray) - 1) . '?';
    $stmt = $db->prepare("SELECT score FROM answers WHERE id IN (" . $inQuery . ")");
    $stmt->execute($finalAnswersIdArray);
    $data = $stmt->fetchAll();

    $score = 0;
    foreach ($data as $questionData) {
        $score += $questionData['score'];
    }
    $_SESSION['test_score'] = $score;

    $stmt = $db->prepare("SELECT result FROM results WHERE test_id = :test_id AND score_min <= :score AND score_max >= :score");
    $stmt->execute([
        ':test_id' => $_SESSION['test_id'],
        ':score' => $score,
    ]);
    $_SESSION['result'] = $stmt->fetch()['result'];

    header("Location: " . $_POST['pageToShow']);
    exit();
}