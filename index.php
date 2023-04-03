<?php
    include_once 'db.php';

    $do = trim(strip_tags($_GET['do']));
    
    if ($do == 'save') {
        $title = trim($_POST['title']);

        $res = $db->prepare("INSERT DELAYED IGNORE INTO tests (`title`) VALUES (:title)");
        $res->execute([
            ':title' => $title,
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

        header ('Location: index.php?do=list');
    }

    if ($do != 'add') {
        $do = 'list';
    }
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Система тестирования</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <?php include_once 'inc/' . $do . '.php'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="module">
        import {App} from './js/App.js';

        let app = new App();
        app.addAdminListener();
    </script>
</body>
</html>