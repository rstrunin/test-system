import {Builder} from './create.js'

let questionNum = 1;
let resultNum = 1;
let builder = new Builder();

document.querySelector('.card-body').addEventListener('click', event => {
    if (event.target.tagName != 'BUTTON') return;
    // Добавление вопроса к заданию и результата
    if (event.target.closest('.addAnswer')) {
        let question = event.target.closest('.addAnswer').dataset.question;
        let answer = event.target.closest('.addAnswer').dataset.answer;
        let answerBlock = event.target.closest('.answers').querySelector('.answer-items');
        event.target.closest('.addAnswer').dataset.answer = ++answer;

        let divider = builder.dividerElement(question, answer);

        let answerTypeSelect = document.querySelector(`#answer_type_${question}`);
        answerTypeSelect.disabled = true;
        let answerType = answerTypeSelect.options[answerTypeSelect.selectedIndex].value;

        let score = builder.scoreElement(question, answer, 0, answerType == 'radio' ? true : false);
        
        answerBlock.append(divider);
        answerBlock.append(score);
    }

    // Добавление задания
    if (event.target.closest('.addQuestion')) {
        questionNum++;
        let questionBlock = document.querySelector('.question-items');
        let initialQuestion = builder.initialQuestionElement(questionNum);
        questionBlock.append(initialQuestion);
    }

    // Добавление результата
    if (event.target.closest('.addResult')) {
        resultNum++;
        let resultBlock = document.querySelector('.result-items');
        let elem = builder.resultElement(resultNum, true);
        resultBlock.append(elem);
    }
});
