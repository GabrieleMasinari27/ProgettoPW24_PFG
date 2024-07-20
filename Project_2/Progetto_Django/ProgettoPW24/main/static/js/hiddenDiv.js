function testoHiddenDiv(testo) {
document.getElementById('testo').innerHTML = testo;
document.getElementById('overlay').style.display = 'block';
var hiddenDiv = document.getElementById('hiddenDiv');
hiddenDiv.style.display = 'block';
hiddenDiv.style.opacity = 1;
setTimeout(function () {
window.location.href = redirectUrl;
}, 3000);
}

window.onload = function() {
if (typeof errorMessage !== 'undefined' && errorMessage !== '') {
testoHiddenDiv(errorMessage);
} else if (typeof successMessage !== 'undefined' && successMessage !== '') {
testoHiddenDiv(successMessage);
}
}