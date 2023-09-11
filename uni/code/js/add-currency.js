var symbol, output;
function addCurrency(currency, num) {
  symbol = currencyConvertor(currency);
  if (!isNaN(num)) {
    num = parseFloat(num).toFixed(2);
    if (Math.floor(num) == num) {
      num = Math.floor(num);
    }
  }
  num = parseFloat(num);
  if (currency == "eur") {
    output = num +""+ symbol;
  } else {
    symbol = num;
  }
  return output;
}

function currencyConvertor(currency) {
  if (currency == "eur") {
    return "€";
  } else {
    return "";
  }
}
