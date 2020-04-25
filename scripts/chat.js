function create_chan() {
    let request = new XMLHttpRequest();
    request.open('POST', '../actions/chat/create_chan.php');
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            getChat(channel);
        }
    }
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(`username=${username}&name=${name}`);

}

function getChat(channel) {
    let request = new XMLHttpRequest();
    request.open('GET', '../actions/chat/display_pm.php?chan=' + channel);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            const displayDiv = document.getElementById('v-pills-' + channel);
            console.log(displayDiv);
            displayDiv.innerHTML = request.responseText;
            m = document.getElementById('message-' + channel);
        }
    }
    request.send();
}

function submitMessage(channel) {
    const message = document.getElementById('message-' + channel).value;
    console.log(message);
    let request = new XMLHttpRequest();
    request.open('POST', '../actions/chat/chat_post.php');
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            getChat(channel);
        }
    }
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(`chan=${channel}&message=${message}`);

}