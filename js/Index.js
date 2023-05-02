import {Builder} from './Builder.js'

export class Index extends Builder {
    addAjaxSearch() {
        document.addEventListener('DOMContentLoaded', () => { 
            document.querySelector('#search').addEventListener('keyup', (event) => {
                let searchText = event.target.value;
                let header = document.querySelector('#listHeader');
                let list = document.querySelector('#listFound');
                let divider = document.querySelector('#listDivider');
                let xmlhttp = new XMLHttpRequest();

                if (searchText === "") {
                    header.innerHTML = "";
                    list.innerHTML = "";
                    divider.hidden = true;
                    return;
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                        let response = JSON.parse(xmlhttp.responseText);

                        divider.hidden = false;
                        if (!response) {
                            header.innerHTML = "По вашему запросу ничего не найдено";
                            list.innerHTML = "";
                        } 
                        else {
                            header.innerHTML = "Найдены следующие тесты:";
                            for (let resp of response) {
                                list.innerHTML = `
                                    <li>
                                        <a target='_blank' class='testLink' rel='noopener noreferrer' data-id='${resp['id']}'>${resp['title']}</a>
                                    </li>
                                `;
                            }
                        }
                    }
                };

                this.sendRequest(xmlhttp, 'query=' + searchText);
            });
        });
    }

    addIndexListener() {
        document.querySelector('.card').addEventListener('click', event => {
            if (event.target.closest('.testLink')) {
                let testName = event.target.closest('.testLink').innerHTML;
                let testId = event.target.closest('.testLink').dataset['id'];
                this.openModal(testName, testId);
            }

            if (event.target.closest('.nav-link')) {
                this.openStatisticsModal();
            }
        });
    }

    openStatisticsModal() {
        document.body.classList.add('is-modal-open');
        document.body.append(this.modalStatisticsElement());

        document.querySelector('.modalStatistics').addEventListener('click', event => {
            if (event.target.closest('.btn-close')) this.closeModal();
        });

        window.addEventListener('keydown', event => {
            if (event.code === 'Escape') this.closeModal();
        });

        document.querySelector('.modal').addEventListener('keyup', () => {
            this.refreshModalHref();
        });
    }

    modalStatisticsElement() {
        let modal = this.createElement(`
            <form action="php/show-statistics.php" method="post">
                <div class="modal" tabindex="-1" role="dialog">
                    <div class="modal-overlay"></div>
                    <div class="modal-dialog modal-inner" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Просмотр статистики теста</h5>
                                <button type="button" class="text-right btn-close" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="title" class="form-label">Введите название теста</label>
                                <input placeholder="Название теста" name="title" class="form-control" min="0">
                                <label for="title" class="form-label">Введите пароль, если статитика приватная.
                                Если статистика публичная, оставьте поле пароля пустым, оно не будет проверяться.</label>
                                <input placeholder="Пароль к странице статистики теста" name="pass" class="form-control" min="0">
                            </div>
                            <div class="modal-footer">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        `);

        let btn = this.createElement(`
            <button type='submit' class='btn btn-success'>Загрузить</button>
        `);

        modal.querySelector('.modal-footer').append(btn);

        return modal;
    }

    openModal(testName, testId) {
        document.body.classList.add('is-modal-open');
        document.body.append(this.modalElement(testName, testId));

        document.querySelector('.modal').addEventListener('click', event => {
            if (event.target.closest('.btn-close')) this.closeModal();
        });

        window.addEventListener('keydown', event => {
            if (event.code === 'Escape') this.closeModal();
        });

        document.querySelector('.modal').addEventListener('keyup', () => {
            this.refreshModalHref();
        });
    }

    closeModal() {
        document.body.classList.remove('is-modal-open');
        document.querySelector('.modal').remove();
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