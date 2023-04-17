import {Builder} from './Builder.js'

export class Index {
    constructor() {
        this.builder = new Builder();
    }

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

                this.builder.sendRequest(xmlhttp, 'query=' + searchText);
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
        });
    }

    openModal(testName, testId) {
        document.body.classList.add('is-modal-open');
        document.body.append(this.builder.modalElement(testName, testId));

        document.querySelector('.modal').addEventListener('click', event => {
            if (event.target.closest('.btn-close')) this.closeModal();
        });

        window.addEventListener('keydown', event => {
            if (event.code === 'Escape') this.closeModal();
        });

        document.querySelector('.modal').addEventListener('keyup', () => {
            this.builder.refreshModalHref();
        });
    }

    closeModal() {
        document.body.classList.remove('is-modal-open');
        document.querySelector('.modal').remove();
    }
}