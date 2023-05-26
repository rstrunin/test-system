<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Добавление теста</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body class="d-flex justify-content-center">
    <div class="col-md-6">
        <form action="save.php" method="post">
            <div class="card mt-4">
                <div class="card-header">
                    <h2 class="text-center">Добавление теста</h2>
                </div>
                <div class="card-body">
                    <div>
                        <label for="title" class="form-label">Название теста</label>
                        <input placeholder="Введите название теста" type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="mt-2 alert alert-danger" id="duplication" role="alert" hidden="true">
                        Тест с таким названием уже существует. Пожалуйста, выберете другое название
                    </div>
                    <div class="mt-5 text-center">
                        <h4>Добавление вопросов</h4>
                    </div>
                    <div class="questions">
                        <div class="question-items">
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary addQuestion">Добавить вопрос</button>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <h4>Добавление результатов</h4>
                    </div>
                    <div class="mt-5 text-left">
                        <h5 id="maximumScoreCount"></h5>
                    </div>
                    <div class="results">
                        <div class="result-items">
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary addResult" >Добавить результат</button>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <h4>Тип статистики</h4>
                    </div>
                    <div>
                        <div class="text-left mt-4">
                            <h6>Вы можете просматривать статистику по выполненнам тестам. Пожалуйста, выберите тип 
                                статистики:</h6>
                            <div>
                                <input type="radio" id="public" name="statistics-type" class="statistics-type" value="public" checked/>
                                <label for="public"><b>Открытая статистика.</b> Доступна всем пользователям</label>
                            </div>
                            <div>
                                <input type="radio" id="private" name="statistics-type" class="statistics-type" value="private" />
                                <label for="private"><b>Закрытая статистика.</b> Доступна только вам по уникальному ключу</label>
                            </div>
                            <div class="mt-2">
                                <label for="password" class="form-label">Пароль к статистике</label>
                                <input placeholder="Введите кодовое слово" type="text" name="password" id="statistics-code" class="form-control disabled">
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4 mb-4">
                <div class="card-body text-center">
                    <div class="alert alert-danger" id="notfilled" role="alert">
                        Для сохранения теста необходимо заполнить все поля
                    </div>
                    <a href="../index.php" class="btn btn-danger">На главную</a>
                    <button type="submit" disabled = "true" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script type="module">
        import {Admin} from '../js/Admin.js';
        let admin = new Admin();

        admin.addAjaxPreventDuplicationSearch();
        admin.addAdminListener();
        admin.addAdminScoreCounter();

        let initialQuestion = admin.initialQuestionElement(1);
        document.querySelector('.question-items').append(initialQuestion);

        let initialResult = admin.resultElement(1, false);
        document.querySelector('.result-items').append(initialResult);
    </script>
</body>