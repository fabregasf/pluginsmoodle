function carregar2() {
    removerBarra();
}

function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

function removerBarra() {
    var bloco = document.getElementById('ceadAcessibilidade').cloneNode(true);
    removeAllChildNodes(document.getElementById('topofscroll'));
    document.getElementById('topofscroll').appendChild(bloco);
    document.getElementsByTagName('nav')[0].style.display = "None";
    document.getElementById('page-header').style.display = "None";
    document.getElementsByClassName('logo mr-1')[0].style.display = "None";
    document.getElementsByClassName('primary-navigation')[0].style.display = "None";
}