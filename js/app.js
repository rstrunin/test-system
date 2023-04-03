import {Builder} from './Builder.js'

export class App {
    constructor() {
        this.questionNum = 1;
        this.resultNum = 1;
        this.builder = new Builder();
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
            if (event.target.tagName != 'BUTTON') return;

            if (event.target.closest('.addQuestion')) this.addQuestionListener();
            if (event.target.closest('.addAnswer')) this.addAnswerListener(event);
            if (event.target.closest('.addResult')) this.addResultListener();
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
        let answerBlock = event.target.closest('.answers').querySelector('.answer-items');
        event.target.closest('.addAnswer').dataset.answer = ++answer;

        let divider = this.builder.dividerElement(question, answer);

        let answerTypeSelect = document.querySelector(`#answer_type_${question}`);
        answerTypeSelect.disabled = true;
        let answerType = answerTypeSelect.options[answerTypeSelect.selectedIndex].value;

        let score = this.builder.scoreElement(question, answer, 0, answerType == 'radio' ? true : false);
        
        answerBlock.append(divider);
        answerBlock.append(score);
    }

    // Добавление финального результата
    addResultListener() {
        this.resultNum++;
        let resultBlock = document.querySelector('.result-items');
        let elem = this.builder.resultElement(this.resultNum, true);
        resultBlock.append(elem);
    }
}