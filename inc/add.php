<div class="col-md-6">
    <form action="index.php?do=save" method="post">
        <div class="card mt-4">
            <div class="card-header">
                <h2 class="text-center">Добавление теста</h2>
            </div>
            <div class="card-body">
                <div>
                    <label for="title" class="form-label">Название теста</label>
                    <input type="text" name="title" id="title" class="form-control">
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
            </div>
        </div>
        <div class="card mt-4 mb-4">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </div>
    </form>
</div>

<script type="module">
    import {Builder} from './js/create.js';

    let cardBody = document.querySelector('.card-body');
    cardBody.addEventListener('mouseup', function(event) {
        setTimeout(() => {
            let maximumScoreCount = 0;
            let scoreInputValues = document.getElementsByClassName('form-control score');
            for (let i = 0; i < scoreInputValues.length; i++) {
                if (scoreInputValues[i].valueAsNumber) maximumScoreCount += scoreInputValues[i].valueAsNumber;
            }
           document.querySelector('#maximumScoreCount').innerHTML = 'Максимальное количество баллов: ' + maximumScoreCount;
        }, 50);
    });
    cardBody.dispatchEvent(new Event('mouseup'));
    
    let builder = new Builder;

    let initialQuestion = builder.initialQuestionElement(1);
    document.querySelector('.question-items').append(initialQuestion);

    let initialResult = builder.resultElement(1, false);
    document.querySelector('.result-items').append(initialResult);
</script>