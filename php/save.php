<?php
include_once 'db.php';
session_start();
$title = trim($_POST['title']);
$statisticsPass = trim($_POST['password']);

$hash = password_hash($statisticsPass, PASSWORD_BCRYPT);

$res = $db->prepare("INSERT DELAYED IGNORE INTO tests (`title`, `statistics_pass`) VALUES (:title, :statistics_pass)");
$res->execute([
    ':title' => $title,
    ':statistics_pass' => $hash
]);
$testId = $db->lastInsertId();

$questionNum = 1;
while (isset($_POST['question_' . $questionNum])) {
    $question = trim($_POST['question_' . $questionNum]);
    $answerType = trim($_POST['answer_type_' . $questionNum]);

    /* Обработка пустых полей */
    if (empty($question)) {
        continue;
    }

    $res = $db->prepare("INSERT DELAYED IGNORE INTO questions (`test_id`, `question`, `type`) VALUES (:test_id, :question, :answer_type)");
    $res->execute([
        ':test_id' => $testId,
        ':question' => $question,
        ':answer_type' => $answerType
    ]);
    $questionId = $db->lastInsertId();

    $answerNum = 1;
    while (isset($_POST['answer_text_' . $questionNum . '_' . $answerNum])) {
        $answer = trim($_POST['answer_text_' . $questionNum . '_' . $answerNum]);
        $score = trim($_POST['answer_score_' . $questionNum . '_' . $answerNum]);
        if (empty($answer)) {
            continue;
        }

        $res = $db->prepare("INSERT DELAYED IGNORE INTO answers (`question_id`, `answer`, `score`) 
                            VALUES (:question_id, :answer, :score)");
        $res->execute([
            ':question_id' => $questionId,
            ':answer' => $answer,
            ':score' => $score,
        ]);

        $answerNum++;
    }
    $questionNum++;
}

$resultNum = 1;
while (isset($_POST['result_' . $resultNum])) {
    $result = trim($_POST['result_' . $resultNum]);
    $scoreMin = trim($_POST['result_score_min_' . $resultNum]);
    $scoreMax = trim($_POST['result_score_max_' . $resultNum]);

    $res = $db->prepare("INSERT DELAYED IGNORE INTO results (`test_id`, `score_min`, `score_max`, `result`) 
                            VALUES (:test_id, :score_min, :score_max, :result)");
    $res->execute([
        ':test_id' => $testId,
        ':score_min' => $scoreMin,
        ':score_max' => $scoreMax,
        ':result' => $result,
    ]);

    $resultNum++;
}

header('Location: ../redirect/onsave.html');