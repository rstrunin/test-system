<?php
$res = $db->query("SELECT * FROM questions WHERE test_id = {$testId}");
$rows = $res->fetchAll();
foreach ($rows as $row) {
    $questionArr[] = ['id' => $row['id'], 'title' => $row['question'], 'type' => $row['type']];
}

echo "<form action='test.php' method='POST'>";
foreach ($questionArr as $key => $question) {
    $key++;
    $res = $db->query("SELECT * FROM answers WHERE question_id = {$question['id']}");
    $answers = $res->fetchAll();

    $cardBody = '';
    $answerInputName = "question_" . $key . "_answer_id";
    if ($question['type'] == 'checkbox') $answerInputName .= '[]';

    foreach ($answers as $answer) {
        $cardBody .= "
        <div>
            <input type='" . $question['type'] . "' name='" . $answerInputName . "'
            value=" . $answer['id'] . "> " . $answer['answer'] . "
        </div>";
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