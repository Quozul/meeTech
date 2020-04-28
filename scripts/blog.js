function checkArticle() {
  const titleInput = document.getElementById('title');
  const contentInput = document.getElementById('content');
  const languageInput = document.getElementById('language');
  const file = document.getElementById('image') ;

  const title = titleInput.value;
  const content = contentInput.value;
  const language = languageInput.selectedIndex;

  if (!checkTitle(title)) {
    errorMark(titleInput);
    const error = 'Le titre doit faire entre 5 et 255 caractères.';
    displayError(error);
    return checkTitle(title);
  } else validMark(titleInput);

  if (!checkContent(content)) {
    errorMark(contentInput);
    const error = 'Le texte doit faire au moins 5 caractères.'
    displayError(error);
    return checkContent(content);
  } else validMark(contentInput);

  if (!checkLanguage(language)) {
    errorMark(languageInput);
    const error = 'Sélectionnez une langue pour votre article.'
    displayError(error);
    return checkLanguage(languageInput);
  } else validMark(languageInput);

  if (!checkFile(file)) {
    errorMark(file) ;
  }

  return checkTitle(title) && checkContent(content) && checkLanguage(language) && checkFile(file);
}


function checkTitle(t) {
  return (t.length > 5 && t.length < 256);
}

function checkContent(c) {
  return (c.length > 5);
}

function checkLanguage(language) {
  return language != 0;
}

function checkFile(file) {
  return file == NULL || file.size < 1024*1024;
}

function errorMark(label) {
  label.style.borderColor = 'red';
}

function validMark(label) {
  label.style.borderColor = 'green';
}

function displayError(text) {
  const error = document.getElementById('error');
  if (error == null) {
    const form = document.getElementById('edit_article');
    const small = document.createElement('small');
    small.className = 'alert alert-danger mb-3';
    small.id = 'error';
    small.innerHTML = text;
    form.appendChild(small);
  } else {
    error.innerHTML = text;
  }
}

function reportArticle(id) {
  let request = new XMLHttpRequest();
  request.open('GET', '/actions/blog/report_article/?article=' + id);
  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      const success = parseInt(request.responseText);
      if (success === 1) {
        location.reload();
        return false;
      } else if (success === -2) {
        alert("Vous devez être connecté pour signaler un commentaire.");
      } else {
        alert("Une erreur est survenue");
      }
    }
  };
  request.send();
}

function checkComment(id_c) {
  const contentInput = document.getElementById('collapseResp'.id_c);
  const content = contentInput.value;

  if (!checkContent(content)) {
    errorMark(contentInput);
    const error = 'Le texte doit faire au moins 5 caractères.'
    const small = document.createElement('small');
    small.className = 'alert alert-danger mb-3';
    small.id = 'error';
    small.innerHTML = text;
    contentInput.appendChild(small);
  }
  return checkContent(content);
}

function dropComment(id) {
  let request = new XMLHttpRequest();
  request.open('DELETE', '/actions/blog/drop_comment/?comm=' + id);
  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      const response = parseInt(request.responseText);
      if (response === 1) getComments();
      else if (response === 0) alert("Ce commentaire n'existe pas.");
      else alert("Une erreur est survenue");
    }
  };
  request.send();
}

function editComment(id) {
  const editionForm = document.getElementById('collapseEdit' + id).innerHTML;
  const commentDiv = document.getElementById('comment-' + id);
  commentDiv.innerHTML = editionForm;
}

function submitModif(id) {
  const content = document.getElementById('editContent' + id).value;
  let request = new XMLHttpRequest();
  request.open('POST', '/actions/blog/edit_comment/');
  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      const success = parseInt(request.responseText);
      if (success === 1) {
        getComments();
      } else if (success == 0) {
        alert("Ce commentaire n'existe pas.");
      } else if (success == -2) {
        alert("Vous devez être connecté pour éditer votre commentaire !");
      } else {
        alert("Une erreur est survenue");
      }
    }
  };
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send(`comm=${id}&commentContent=${content}`);
}

function reportComment(id) {
  let request = new XMLHttpRequest();
  request.open('GET', '/actions/blog/report_comment/?comm=' + id);
  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      const success = parseInt(request.responseText);
      if (success === 1) {
        getComments();
      } else {
        alert("Une erreur est survenue");
      }
    }
  };
  request.send();
}
