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
<body class="d-flex justify-content-center">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header">
                <h2 class="text-center">Список тестов</h2>
            </div>
            <div class="card-body">

                <div class="input-group mb-3">
                    <input id="search" type="text" class="form-control" placeholder="Введите название теста" aria-describedby="basic-addon1">
                </div>

                <ul class="mt-2 list" id="testlist">
                    <?php
                        include_once 'php/db.php';
                        $res = $db->query("SELECT * FROM tests");
                        while ($row = $res->fetch()) {
                            echo "
                            <li>
                                <a target='_blank' rel='noopener noreferrer' href='php/test.php?id=" . $row['id'] . "'>" . $row['title'] . "</a>
                            </li>";
                        } 
                    ?>
                </ul>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body text-center">
                <a href="php/add.php" class="btn btn-primary">Добавить тест</a>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="module">
        import {App} from './js/App.js';

        let app = new App();
        app.addAjaxSearch();
    </script>
</body>
</html>