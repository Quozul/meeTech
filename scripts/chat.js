function create_chan() {
    const name = document.getElementById('chanName').value;
    const username = document.getElementById('username').value;
    let request = new XMLHttpRequest();
    request.open('POST', '../actions/chat/create_chan.php');
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            channel = request.responseText;
            location.reload();
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

function add_recipient(channel) {
    const username = document.getElementById('add_user-' + channel).value;
    let request = new XMLHttpRequest();
    request.open('POST', '../actions/chat/add_recipient.php');
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            const success_div = document.getElementById('add_success' + channel);
            const reponse = parseInt(request.responseText);
            if (reponse === 1) {
                success_div.innerHTML =
                    "Utilisateur ajouté à la conversation"
                document.getElementById('add_success').className += "alert-success";
            } else if (reponse === 0) {
                "Une erreur est survenue"
            }
        }
    }
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(`username=${username}&channel=${channel}`);

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