/**
 * Execute a document without getting its content, returns a Promise
 * @param {string} url String containing the url
 * @param {string} query String containing the query parameters
 */
function request(url, query) {
    return new Promise(function (resolve, reject) {
        let req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4)
                resolve();
        }

        req.open('post', url, true);
        req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        req.send(query);
    });
}

/**
 * Get the content of an URL, returns a Promise
 * @param {string} url String containing the url
 * @param {string} query String containing the query parameters
 */
function getHtmlContent(url, query) {
    return new Promise(function (resolve, reject) {
        let req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4)
                resolve(req.response);
        }

        req.onerror = reject;

        req.open('get', url + '?' + query, true);
        req.setRequestHeader('Content-Type', 'text/html');
        req.responseType = 'document';
        req.send();
    });
}