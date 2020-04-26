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
            displayDiv.innerHTML = request.responseText;
            m = document.getElementById('message-' + channel);
            let elmnt = document.getElementById('display_mp-' + channel);
            elmnt.scrollTo(0, elmnt.scrollHeight);
            setTimeout(getChat, 15000);
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
            const success_div = document.getElementById('add_success-' + channel);
            const response = parseInt(request.responseText);
            if (response === 1) {
                success_div.innerHTML = "Utilisateur ajouté";
                success_div.className = "alert alert-success ml-3";
            } else {
                if (response === -1) success_div.innerHTML = "L'utilisateur n'existe pas";
                else if (response === -2) success_div.innerHTML = "L'utilisateur fait déjà partie de ce channel";
                else success_div.innerHTML = "Une erreur est survenue";
                success_div.className = "alert alert-danger ml-3";
            }
        }
    }
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(`username=${username}&channel=${channel}`);


}

function submitMessage(channel) {

    const message = document.getElementById('message-' + channel).value;
    const size_mp = document.getElementById('size_mp-' + channel);
    if (message.length < 2) {
        size_mp.innerHTML = "Le message est trop court";
        size_mp.className = "alert alert-danger";
    } else {
        let request = new XMLHttpRequest();
        request.open('POST', '../actions/chat/chat_post.php');
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                getChat(channel);
                const input = document.getElementById('message-' + channel);
                input.value = '';
                size_mp.innerHTML = "";
            }
        }
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(`chan=${channel}&message=${message}`);
    }

}

function leaveChat(channel) {
    let request = new XMLHttpRequest;
    request.open('DELETE', '../actions/chat/leave_conv.php?chan=' + channel);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            const response = parseInt(request.responseText);
            if (response === 1) {
                location.reload();
            }
        }
    }
    request.send();
}