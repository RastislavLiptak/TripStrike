var pgOpenedArr = [];
var pgOpenedNum = 0;
var fixScrllY;
function pageScrollBlock(sts, id) {
  if (sts) {
    if (!pgOpenedArr.includes(id)) {
      ++pgOpenedNum;
      pgOpenedArr.push(id);
      fixScrllY = window.scrollY;
    }
  } else {
    if (pgOpenedArr.includes(id)) {
      --pgOpenedNum;
      pgOpenedArr.splice(pgOpenedArr.indexOf(id), 1);
    }
  }
  if (pgOpenedNum > 0) {
    document.getElementsByTagName("BODY")[0].classList.add("body-overflow-hidden");
  } else {
    document.getElementsByTagName("BODY")[0].classList.remove("body-overflow-hidden");
  }
}

function hasClass(element, cls) {
  return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}
