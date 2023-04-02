<?php

$finalAnswersIdArray = [];
foreach ($_POST as $answers) {
    if (gettype($answers) == 'array') {
        foreach ($answers as $answer) 
            $finalAnswersIdArray[] = $answer;
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

$stmt = $db->prepare("SELECT result FROM results WHERE test_id = :test_id AND score_min <= :score AND score_max >= :score");
$stmt->execute([
    ':test_id' => $_SESSION['test_id'],
    ':score' => $score,
]);
$result = $stmt->fetch()['result'];

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
                <div class='result-print'> " . $score . " из " . $_SESSION['test_score_max'] . " </div>
                <div class='result-print'> " . $result . " </div>
            </div>
        </div>
    </div>
</div>";

session_destroy();