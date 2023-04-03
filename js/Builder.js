export class Builder { 
    createElement(html) {
        const div = document.createElement('div');
        div.innerHTML = html;
        return div.firstElementChild;
    };

    scoreElement(question, answer, score, isBanned) {
        let scoreBlock = this.createElement(`
            <div class="mt-2">
                <label for="answer_score_${question}_${answer}" class="form-label">Балл за ответ #${answer}</label>
                <input type="number" name="answer_score_${question}_${answer}" id="answer_score_${question}_${answer}" value="${score}" class="form-control score" min="0">
            </div>
        `);
    
        scoreBlock.querySelector('input').disabled = isBanned;
        return scoreBlock;
    }

    dividerElement(question, answer) {
        return this.createElement(`
            <div class="divider">
                <label for="answer_text_${question}_${answer}" class="form-label">Ответ #${answer}</label>
                <input type="text" name="answer_text_${question}_${answer}" id="answer_text_${question}_${answer}" class="form-control">
            </div>
        `);
    }

    initialQuestionElement(question) {
        return this.createElement(`
            <div class="mt-4">
                <label for="question_${question}" class="form-label">Вопрос #${question}</label>
                <input type="text" name="question_${question}" id="question_${question}" class="form-control">

                <div class="mt-2">
                    <label for="answer_type_${question}" class="form-label">Выберите тип вопроса</label>
                    <select name="answer_type_${question}" id="answer_type_${question}" class="form-select">
                        <option value="radio" selected="selected">Единичный выбор</option>
                        <option value="checkbox">Множественный выбор</option>
                    </select>
                </div>

                <div class="answers">
                    <div class="answer-items">
                        <div>
                            <label for="answer_text_1_1" class="form-label">Ответ #1</label>
                            <input type="text" name="answer_text_1_1" id="answer_text_1_1" class="form-control">
                        </div>
                        <div class="mt-2">
                            <label for="answer_score_1_1" class="form-label">Балл за ответ #1</label>
                            <input type="number" name="answer_score_1_1" id="answer_score_1_1" class="form-control score" min="0" value="1">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light border addAnswer" data-question="${question}" data-answer="1">Добавить вариант ответа</button>
                    </div>
                </div>
            </div>
        `);
    }

    resultElement(result, isDivided) {
        let elem = this.createElement(`
            <div class="mt-4">
                <div class="">
                    <label for="result_${result}" class="form-label">Результат #${result}</label>
                    <textarea name="result_${result}" id="result_1" class="form-control"></textarea>
                </div>
                <div class="mt-2">
                    <label for="result_score_min_${result}" class="form-label">Балл (от) #${result}</label>
                    <input type="number" name="result_score_min_${result}" id="result_score_min_${result}" class="form-control" min="0">
                </div>
                <div class="mt-2">
                    <label for="result_score_max_${result}" class="form-label">Балл (до) #${result}</label>
                    <input type="number" name="result_score_max_${result}" id="result_score_max_${result}" class="form-control" min="0">
                </div>
            </div>
        `);

        if (isDivided) elem.classList.add('divider');
        return elem;
    }
}