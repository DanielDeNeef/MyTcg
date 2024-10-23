(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

function fetchGameSets(gameId) {
    if (gameId === "") return;
    console.log('game id ', gameId);

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../includes/collectionService.php?game=" + gameId, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("gameSet").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function fetchCards(gameSetId) {
    if (gameSetId === "") return;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../includes/collectionService.php?gameSet=" + gameSetId, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("card").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}