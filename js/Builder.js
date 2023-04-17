export class Builder { 
    createElement(html) {
        const div = document.createElement('div');
        div.innerHTML = html;
        return div.firstElementChild;
    }

    sendRequest(xmlhttp, param) {
        xmlhttp.open("POST", "../php/search.php", true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.send(param);
    }

    scoreElement(question, answer, score, isBanned) {
        let scoreBlock = this.createElement(`
            <div class="mt-2">
                <label for="answer_score_${question}_${answer}" class="form-label">Балл за ответ #${answer}</label>
                <input placeholder="Введите количество баллов для данного ответа" type="number" name="answer_score_${question}_${answer}" id="answer_score_${question}_${answer}" value="${score}" class="form-control score" min="0">
            </div>
        `);
    
        scoreBlock.querySelector('input').disabled = isBanned;
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

        element.querySelector('.answer-items').append(this.answerElement(1, 1, 1, false));
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

    modalElement(testName, testId) {
        let modal = this.createElement(`
            <div class="modal" tabindex="-1" role="dialog">
                <div class="modal-overlay"></div>
                <div class="modal-dialog modal-inner" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Начать выполнение теста&nbsp</h5>
                            <h5 class="fst-italic modal-title"><mark>${testName}</mark></h5>
                            <button type="button" class="text-right btn-close" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="login" class="form-label">Перед началом выполнения теста, введите в поле свой идентификатор (имя, фамилия или логин),
                                по которому будет отслеживаться статистика</label>
                            <input placeholder="Ваше имя, фамилия или логин" name="login" id='login' class="form-control" min="0">
                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </div>
            </div>
        `);

        this.testId = testId;

        let a = this.createElement(`
            <a target='_blank' rel='noopener noreferrer' 
                href="php/test.php?id=${this.testId}&login=${modal.querySelector('#login').value}" 
            class="btn btn-primary" id='modalLink'>Начать</a>
        `);

        modal.querySelector('.modal-footer').append(a);

        return modal;
    }

    refreshModalHref() {
        let a = this.createElement(`
            <a target='_blank' rel='noopener noreferrer' 
                href="php/test.php?id=${this.testId}&login=${document.querySelector('#login').value}" 
            class="btn btn-primary" id='modalLink'>Начать</a>
        `);
        
        document.querySelector('#modalLink').remove();

        document.querySelector('.modal-footer').append(a);
    }
}