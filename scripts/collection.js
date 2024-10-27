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

let currentCardId = null;

function openUpdateAmountModal(cardId, cardName, currentAmount, userId) {
    
    currentCardId = cardId;

    // Set the title and amount in the modal
    document.getElementById('modal-card-title').innerText = cardName;
    document.getElementById('modal-amount-display').innerText = currentAmount;

    // Show the modal
    var updateModal = new bootstrap.Modal(document.getElementById('updateAmountModal'), {});
    updateModal.show();

    // Add event listeners for increasing/decreasing the amount
    var currentAmountElement = document.getElementById('modal-amount-display');
    var amount = parseInt(currentAmount);

    document.getElementById('modal-increase-btn').onclick = function () {
        amount++;
        currentAmountElement.innerText = amount;
    };

    document.getElementById('modal-decrease-btn').onclick = function () {
        amount--;
        console.log('amount '+amount);
        if (amount <= 0) {
            // Call the delete function if amount is 0 or less
            deleteCard(currentCardId, userId);
        } else {
            currentAmountElement.innerText = amount;
        }
    };

    // Handle saving the changes
    document.getElementById('save-amount-btn').onclick = function () {
        updateCardAmount(currentCardId,userId, amount);
    };
}

function updateCardAmount(cardId, userId, newAmount) {
    fetch('../includes/collectionService.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `cardId=${cardId}&currentUserId=${userId}&newAmount=${newAmount}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('card-amount-' + currentCardId).innerText = newAmount;

        // Close the modal after saving
        var updateModal = bootstrap.Modal.getInstance(document.getElementById('updateAmountModal'));
        updateModal.hide();
    })
    .catch(error => console.error('Error:', error));
}

function deleteCard(cardId, userId) {
    fetch('../includes/collectionService.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `deleteCardId=${cardId}&currentUserId=${userId}`
    })
    .then(response => response.text())
    .then(data => {
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}


