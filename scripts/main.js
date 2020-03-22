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
                if (req.status == 200)
                    resolve(req);
                else
                    reject(req);
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
                if (req.status == 200)
                    resolve(req.response);
                else
                    reject(req);
        }

        req.onerror = reject;

        req.open('get', url + '?' + query, true);
        req.send();
    });
}

/**
 * Takes a form's id as input and returns a query of all form's values
 * @param {*} form_id The unique ID of the form where the values are
 */
function formToQuery(form_id) {
    const form = document.getElementById(form_id);
    const form_data = new FormData(form);
    let query = '';

    for (let pair of form_data.entries())
        if (pair[1] != '')
            query += pair[0] + '=' + pair[1] + '&';

    query = query.split('+').join('%2B');
    return query;
}