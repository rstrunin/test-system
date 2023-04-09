import {Builder} from './Builder.js'
export class App {
    constructor() {
        this.questionNum = 1;
        this.resultNum = 1;
        this.builder = new Builder();
    }

    /*addHrefBySelector(selector, url) {
        selector.addEventListener('click', () => {
            location.href = '/' . url;
        });
    }*/

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
        });

        document.querySelector('.card-body').addEventListener('keyup', event => {
            if (
                event.target.closest('input') ||
                event.target.closest('textarea') ||
                event.target.closest('select')
            ) this.changeButtonStatus();
        });
    }

    // Добавление задания
    addQuestionListener() {
        this.questionNum++;
        let questionBlock = document.querySelector('.question-items');
        let initialQuestion = this.builder.initialQuestionElement(this.questionNum);
        questionBlock.append(initialQuestion);
    }

    // Добавление вопроса к заданию и кол-ва баллов
    addAnswerListener(event) {
        let question = event.target.closest('.addAnswer').dataset.question;
        let answer = event.target.closest('.addAnswer').dataset.answer;
        let answerBlock = event.target.closest('.answer').querySelector('.answer-items');
        event.target.closest('.addAnswer').dataset.answer = ++answer;

        let answerTypeSelect = document.querySelector(`#answer_type_${question}`);
        answerTypeSelect.disabled = true;
        let answerType = answerTypeSelect.options[answerTypeSelect.selectedIndex].value;
        
        answerBlock.append(this.builder.answerElement(question, answer, 0, answerType == 'radio' ? true : false));
    }

    // Убрать ответ или весь вопрос целиком
    closeCard(event) {
        event.target.closest('.closeable').remove();
    }

    changeButtonStatus() {
        let fields = [];
        fields.push(document.querySelectorAll('input'));
        fields.push(document.querySelectorAll('select'));
        fields.push(document.querySelectorAll('textarea'));

        let arr = [];
        for (let row of fields) for (let e of row) arr.push(e);

        const isEmpty = str => !str.trim().length;
        let btnSuccess = document.querySelector('.btn-success');
        let alert = document.querySelector('.alert-danger');

        btnSuccess.disabled = false;
        alert.hidden = true;
        for (let i = 0; i < arr.length; i++) {
            if (isEmpty(arr[i].value)) {
                console.log('empty');
                btnSuccess.disabled = true;
                alert.hidden = false;
            }
        }
    }

    // Добавление финального результата
    addResultListener() {
        this.resultNum++;
        let resultBlock = document.querySelector('.result-items');
        let elem = this.builder.resultElement(this.resultNum, true);
        resultBlock.append(elem);
    }
}