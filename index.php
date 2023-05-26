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
                <h2 class="text-center">Система тестирования</h2>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link statistics">Статистика</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manual.html">Руководство пользователя</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="card-body">

                <div class="input-group mb-3">
                    <input id="search" type="text" class="form-control" placeholder="Введите название теста" aria-describedby="basic-addon1">
                </div>

                <ul class="mt-2 list">
                    <div>
                        <p id="listHeader"></p>
                        <div id="listFound"></div>
                        <div id="listDivider" class="divider" hidden="true"></div>
                    </div>
                    <p>Последние созданные тесты:</p>
                    <?php
                        include_once 'php/db.php';
                        $res = $db->query("SELECT * FROM tests");
                        while ($row = $res->fetch()) {
                            echo "
                            <li>
                                <a target='_blank' class='testLink' rel='noopener noreferrer' data-id='" . $row['id'] . "'>" . $row['title'] . "</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="module">
        import {Index} from './js/Index.js';

        let index = new Index();
        index.addIndexListener();
        index.addAjaxSearch();
    </script>
</body>
</html>