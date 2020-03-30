const markers = [
  ['**', '<b>', '</b>'],
  ['*', '<i>', '</i>'],
  ['__', '<u>', '</u>'],
  ['```', '<code>', '</code>'],
  ['\n\n', '<p>', '</p>'],
  ['\n', '<br>', '']
] ;
const toMark = document.getElementsByClassName('markdown') ;
let i ;
for (i = 0 ; i < toMark.length ; ++i) {
  for (j = 0 ; j < markers.length ; ++j) {
    change(toMark[i], markers[j][0]) ;
  }
  checkClosed(toMark[i]) ;
}

function change (tag, marker) {
  let str = tag.innerHTML ;
  let newStr = '' ;
  let index = 0 ;
  let i = 0 ;
  let counter = 0 ;
  while (index != -1) {
    index = str.indexOf(marker, i) ;
    if (index != -1) {
      newStr += str.substring(i, index) ;
      newStr += replaceByTag(counter, marker) ;
    } else {
      newStr += str.substring(i) ;
    }
    counter++ ;
    i = index + marker.length ;
  }
  tag.innerHTML = newStr ;
}

function replaceByTag (counter, marker) {
  let tagBeginning ;
  let tagEnd ;
  for (let i = 0 ; i < markers.length ; ++i) {
    if (markers[i][0] == marker) {
      tagBeginning = markers[i][1] ;
      tagEnd = markers[i][2] ;
    }
  }
  if (counter % 2 == 0) tag = tagBeginning ;
  else tag = tagEnd ;
  return tag ;
}

function checkClosed (tag) {
  let str = tag.innerHTML ;
  for (let j = 0 ; j < markers.length ; ++j) {
    for (let i = str.length - 1 - markers[j][1].length ; i >= 0 ; --i) {
      if (str.substring(i, markers[j][1].length) == markers[j][1]) {
        str += markers[j][2] ;
        break ;
      }
    }
  }
  tag.innerHTML = str ;
}
