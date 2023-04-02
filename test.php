<?php
    include_once  'db.php';
    session_start();

    $id = (int) $_GET['id'];
    
    // Если айди теста < 0 тест не найден (тут надо сделать редирект на 404) 
    if ($id < 0) {
        header ('location: 404.html');
    }

    $testId = $id;
    if (!isset($_POST['pageToShow'])) {
        var_dump($_SESSION);
        $_SESSION['test_id'] = $testId;

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
    }

    $res = $db->query("SELECT * FROM tests WHERE id = {$testId}");
    $row = $res->fetch();
    $testTitle = $row['title']; // Название теста

    $res = $db->query("SELECT count(*) AS count FROM questions WHERE test_id = {$testId}");
    $row = $res->fetch();
    $questionCount = $row['count']; // Количество вопросов ввыбранном тесте

    if (isset($_POST['pageToShow'])) $pageToShow = $_POST['pageToShow'];
    else $pageToShow = 'show-form.php';

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
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Выполнение теста</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="container">
        <?php 
            include($pageToShow);
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>