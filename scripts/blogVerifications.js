function checkArticle() {
  const titleInput = document.getElementById('title') ;
  const contentInput = document.getElementById('content') ;
  const languageInput = document.getElementById('language') ;

  const title = titleInput.value ;
  const content = contentInput.value ;
  const language = languageInput.selectedIndex ;

  if (!checkTitle(title)) {
    errorMark(titleInput) ;
    const error = 'Le titre doit faire entre 5 et 255 caractères.' ;
    displayError(error) ;
    return checkTitle(title) ;
  }
  else validMark(titleInput) ;

  if (!checkContent(content)) {
    errorMark(contentInput) ;
    const error = 'Le texte doit faire au moins 5 caractères.'
    displayError(error) ;
    return checkContent(content) ;
  }
  else validMark(contentInput) ;

  if (!checkLanguage(language)) {
    errorMark(languageInput) ;
    const error = 'Sélectionnez une langue pour votre article.'
    displayError(error) ;
    return checkLanguage(languageInput) ;
  }
  else validMark(languageInput) ;

  return checkTitle(title) && checkContent(content) && checkLanguage(language) ;
}


function checkTitle (t) {
  return (t.length > 5 && t.length < 256) ;
}

function checkContent (c) {
  return (c.length > 5) ;
}

function checkLanguage (language) {
  return language != 0 ;
}

function errorMark (label) {
  label.style.borderColor = 'red' ;
}

function validMark (label) {
  label.style.borderColor = 'green' ;
}

function displayError (text) {
  const error = document.getElementById('error') ;
  if (error == null) {
    const form = document.getElementById('edit_article') ;
    const small = document.createElement('small') ;
    small.className = 'alert alert-danger mb-3' ;
    small.id = 'error' ;
    small.innerHTML = text ;
    form.appendChild(small) ;
  } else {
    error.innerHTML = text ;
  }
}