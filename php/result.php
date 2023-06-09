<?php
include_once 'db.php';
session_start();

if ($_POST['done']) {

    $_SESSION['end_time'] = time();
    $score = 0;

    $finalAnswersIdArray = [];
    foreach ($_POST as $key => $value) {
        if (strripos($key, "text") !== false) {
            $stmt = $db->prepare("SELECT answer, score FROM answers WHERE id = ?");
            $id = mb_substr($key, 5);
            $stmt->execute(array($id));
            $res = $stmt->fetch();
            if (strripos(trim($value), trim($res['answer'])) !== false) 
                $score += $res['score'];
        }
        else if (strripos($key, "number") !== false) {
            $stmt = $db->prepare("SELECT answer, score FROM answers WHERE id = ?");
            $id = mb_substr($key, 7);
            $stmt->execute(array($id));
            $res = $stmt->fetch();
            if ($value == $res['answer'])
                $score += $res['score'];
        }
        else if (gettype($value) == 'array') {
            foreach ($value as $answer) $finalAnswersIdArray[] = $answer;
        }
        else $finalAnswersIdArray[] = $value;
    }

    $inQuery = str_repeat('?,', count($finalAnswersIdArray) - 1) . '?';
    $stmt = $db->prepare("SELECT score FROM answers WHERE id IN (" . $inQuery . ")");
    $stmt->execute($finalAnswersIdArray);
    $data = $stmt->fetchAll();

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

    $res = $db->prepare("
        INSERT DELAYED IGNORE INTO 
        archive (`test_id`, `login`, `start_time`, `end_time`, `test_score`) 
        VALUES (:test_id, :login, :start_time, :end_time, :test_score)
    ");
    
    $res->execute([
        ':test_id' => $_SESSION['test_id'],
        ':login' => $_SESSION['login'],
        ':start_time' => $_SESSION['start_time'],
        ':end_time' => $_SESSION['end_time'],
        ':test_score' => $_SESSION['test_score']
    ]);

    header("Location: " . $_POST['pageToShow']);
    exit();
}