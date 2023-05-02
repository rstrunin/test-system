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
}