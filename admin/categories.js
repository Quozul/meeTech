function get (category, data, target) {
  let request = new XMLHttpRequest() ;
  request.open('GET', 'admin/actions/categories/get_' + data.toLowerCase() + '_' + target.toLowerCase() + '/?cat=' + category) ;
  request.onreadystatechange = function() {
    if (request.readyState === 4) {
      if (request.responseText != '0') {
        const displayDiv = document.getElementById('display-' + category) ;
        displayDiv.innerHTML = request.responseText ;
      }
    }
  } ;
  request.send() ;
}

function moveToCat (message, data) {
  const category = document.getElementById('newCat' + message).value ;
  if (confirm('Voulez-vous déplacer cet élément dans la catégorie ' + category + ' ?')) {
    let request = new XMLHttpRequest() ;
    request.open('GET', 'admin/actions/categories/move_to/?cat=' + category + '&mess=' + message) ;
    request.onreadystatechange = function() {
      if (request.readyState === 4) {
        const response = parseInt(request.responseText) ;
        if (response === 1) {
          const activeCat = document.getElementsByClassName('active')[0].innerHTML ;
          get(activeCat, data, 'messages') ;
        } else {
          alert("Une erreur est survenue") ;
        }
      }
    } ;
    request.send() ;
  }
}

function unsignal (message) {
  if (confirm('Voulez-vous valider cet article ?')) {
    let request = new XMLHttpRequest() ;
    request.open('GET', 'admin/actions/categories/validate_message/?post='+ message) ;
    request.onreadystatechange = function() {
      if (request.readyState === 4) {
        const response = parseInt(request.responseText) ;
        if (response === 1) {
          const activeCat = document.getElementsByClassName('active')[0].innerHTML ;
          get(activeCat, data, 'messages') ;
        } else {
          alert("Une erreur est survenue") ;
        }
      }
    } ;
    request.send() ;
  }
}
