import {Builder} from './Builder.js'

export class Admin extends Builder {
    constructor() {
        super();
        this.questionNum = 1;
        this.resultNum = 1;
    }

    addAjaxPreventDuplicationSearch() {
        document.addEventListener('DOMContentLoaded', () => { 
            document.querySelector('#title').addEventListener('keyup', (event) => {
                let searchText = event.target.value;
                let duplication = document.querySelector('#duplication');
                let xmlhttp = new XMLHttpRequest();

                if (searchText === "") {
                    duplication.hidden = true;
                    return;
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                        let response = JSON.parse(xmlhttp.responseText);

                        if (!response) {
                            duplication.hidden = true;
                        } 
                        else {
                            duplication.hidden = false;
                        }
                    }
                };

                this.sendRequest(xmlhttp, 'query=' + searchText + '&strict=true')
            });
        });
    }

    addAdminScoreCounter() {
        let cardBody = document.querySelector('.card-body');
        cardBody.addEventListener('mouseup', function() {
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
    }

    addAdminListener() {
        document.querySelector('.card-body').addEventListener('click', event => {
            if (event.target.closest('.addQuestion')) this.addQuestionListener();
            if (event.target.closest('.addAnswer')) this.addAnswerListener(event);
            if (event.target.closest('.addResult')) this.addResultListener();
            if (event.target.closest('.btn-close')) this.closeCard(event);
            if (event.target.closest('.statistics-type')) this.statisticsCodeInputStatus(event);
        });

        document.querySelector('.card-body').addEventListener('keyup', event => {
            if (
                event.target.closest('input') ||
                event.target.closest('textarea') ||
                event.target.closest('select')
            ) {
                this.changeAlertStatus();
                this.changeButtonStatus();
            }
        });
    }

    statisticsCodeInputStatus(event) {
        let input = document.querySelector('#statistics-code');

        if (event.target.closest('#public')) {
            input.classList.add('disabled');
            input.value = '';
        }
        if (event.target.closest('#private')) input.classList.remove('disabled');
    }

    // Добавление задания
    addQuestionListener() {
        this.questionNum++;
        let questionBlock = document.querySelector('.question-items');
        let initialQuestion = this.initialQuestionElement(this.questionNum);
        questionBlock.append(initialQuestion);
    }

    // Добавление вопроса к заданию и кол-ва баллов
    addAnswerListener(event) {
        let question = event.target.closest('.addAnswer').dataset.question;
        let answer = event.target.closest('.addAnswer').dataset.answer;
        let answerBlock = event.target.closest('.answer').querySelector('.answer-items');
        event.target.closest('.addAnswer').dataset.answer = ++answer;

        let answerTypeSelect = document.querySelector(`#answer_type_${question}`);
        answerTypeSelect.classList.add('disabled');
        let answerType = answerTypeSelect.options[answerTypeSelect.selectedIndex].value;
        
        answerBlock.append(this.answerElement(question, answer, 0, answerType == 'radio' ? true : false));
    }

    // Убрать ответ или весь вопрос целиком
    closeCard(event) {
        event.target.closest('.closeable').remove();
    }

    changeAlertStatus() {
        let fields = [];
        fields.push(document.querySelectorAll('input'));
        fields.push(document.querySelectorAll('select'));
        fields.push(document.querySelectorAll('textarea'));

        let arr = [];
        for (let row of fields) for (let e of row) arr.push(e);

        const isEmpty = str => !str.trim().length;
        let alert = document.querySelector('#notfilled');

        alert.hidden = true;
        for (let i = 0; i < arr.length; i++) {
            if (isEmpty(arr[i].value) && !arr[i].classList.contains('disabled')) {
                alert.hidden = false;
            }
        }
    }

    changeButtonStatus() {
        let alerts = document.querySelectorAll('.alert-danger');
        let btnSuccess = document.querySelector('.btn-success');

        for (let alert of alerts) {
            if (!alert.hidden) {
                btnSuccess.disabled = true;
                return;
            }
        }

        btnSuccess.disabled = false;
    }

    // Добавление финального результата
    addResultListener() {
        this.resultNum++;
        let resultBlock = document.querySelector('.result-items');
        let elem = this.resultElement(this.resultNum, true);
        resultBlock.append(elem);
    }

    scoreElement(question, answer, score, isBanned) {
        let scoreBlock = this.createElement(`
            <div class="mt-2">
                <label for="answer_score_${question}_${answer}" class="form-label">Балл за ответ #${answer}</label>
                <input placeholder="Введите количество баллов для данного ответа" type="number" name="answer_score_${question}_${answer}" id="answer_score_${question}_${answer}" value="${score}" class="form-control score" min="0">
            </div>
        `);
    
        if (isBanned) scoreBlock.querySelector('input').classList.add('disabled');
        else scoreBlock.querySelector('input').classList.remove('disabled');
        return scoreBlock;
    }

    dividerElement(question, answer) {
        return this.createElement(`
            <div>
                <div class="d-flex justify-content-between">
                    <label for="answer_text_${question}_${answer}" class="form-label">Ответ #${answer}</label>
                    <button type="button" class="text-right btn-close" aria-label="Close"></button>
                </div>
                <input placeholder="Введите вариант ответа" type="text" name="answer_text_${question}_${answer}" id="answer_text_${question}_${answer}" class="form-control">
            </div>
        `);
    }

    answerElement(question, answer, score, isBanned) {
        let element = this.createElement(`<div class="closeable"></div>`);
        let dividerElement = this.dividerElement(question, answer);
        let scoreElement = this.scoreElement(question, answer, score, isBanned);

        element.append(dividerElement);
        element.append(scoreElement);

        return element;
    }

    initialQuestionElement(question) {
        let element = this.createElement(`
            <div class="mt-4 closeable">
                <div class="d-flex justify-content-between">
                    <label for="question_${question}" class="form-label">Вопрос #${question}</label>
                    <button type="button" class="text-right btn-close" aria-label="Close"></button>
                </div>
                <input placeholder="Введите текст вопроса" type="text" name="question_${question}" id="question_${question}" class="form-control">

                <div class="mt-2">
                    <label for="answer_type_${question}" class="form-label">Выберите тип вопроса</label>
                    <select name="answer_type_${question}" id="answer_type_${question}" class="form-select">
                        <option value="radio" selected="selected">Единичный выбор</option>
                        <option value="checkbox">Множественный выбор</option>
                    </select>
                </div>

                <hr>

                <div class="answer">
                    <div class="answer-items">
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light border addAnswer" data-question="${question}" data-answer="1">Добавить вариант ответа</button>
                    </div>
                </div>
            </div>
        `);

        element.querySelector('.answer-items').append(this.answerElement(question, 1, 1, false));
        return element;
    }

    resultElement(result, isDivided) {
        let elem = this.createElement(`
            <div class="mt-4 closeable">
                <div>
                    <div class="d-flex justify-content-between">
                        <label for="result_${result}" class="form-label">Результат #${result}</label>
                        <button type="button" class="text-right btn-close" aria-label="Close"></button>
                    </div>
                    <textarea placeholder="Введите комментарий для заданного количества баллов" name="result_${result}" id="result_1" class="form-control"></textarea>
                </div>
                <div class="mt-2">
                    <label for="result_score_min_${result}" class="form-label">Балл (от) #${result}</label>
                    <input placeholder="Введите нижнюю границу баллов (включительно)" type="number" name="result_score_min_${result}" id="result_score_min_${result}" class="form-control" min="0">
                </div>
                <div class="mt-2">
                    <label for="result_score_max_${result}" class="form-label">Балл (до) #${result}</label>
                    <input placeholder="Введите верхнюю границу баллов (включительно)" type="number" name="result_score_max_${result}" id="result_score_max_${result}" class="form-control" min="0">
                </div>
            </div>
        `);

        if (isDivided) elem.classList.add('divider');
        return elem;
    }
}