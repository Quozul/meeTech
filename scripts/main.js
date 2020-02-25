function request(url, query) {
    return new Promise(function (resolve, reject) {
        let req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4)
                resolve();
        }

        req.open('post', url, true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(query);
    });
}