/**
 * Game Logic
*/

//Function for Update Modal (prefill with data)
document.addEventListener('DOMContentLoaded', function () {
    var updateGameModal = document.getElementById('updateGameModal');
    updateGameModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var gameId = button.getAttribute('data-id');
        var gameName = button.getAttribute('data-name');
        var gameLogo = button.getAttribute('data-logo');

        var modalGameId = updateGameModal.querySelector('#game_id');
        var modalGameName = updateGameModal.querySelector('#update_game_name');
        var modalGameLogo = updateGameModal.querySelector('#update_game_logo');

        modalGameId.value = gameId;
        modalGameName.value = gameName;
        modalGameLogo.value = gameLogo;
    });
});

//Function to delete game
function deleteGame(gameId) {
    if (confirm("Are you sure you want to delete this game?")) {
        window.location.href = 'collectionMng.php?id=' + gameId;
    }
}

/**
 * Set Logic
*/

//Function for update gameSet Modal (prefill with data) 
document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('[data-bs-target="#updateSetModal"]');

    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const setId = this.getAttribute('data-id');
            const setName = this.getAttribute('data-name');
            const setCode = this.getAttribute('data-code');
            const setLogo = this.getAttribute('data-logo');

            console.log('game code is '+ setCode);

            document.getElementById('updateSetId').value = setId;
            document.getElementById('updateSetName').value = setName;
            document.getElementById('updateSetCode').value = setCode;
            document.getElementById('updateSetLogo').value = setLogo;
        });
    });
});

//Function do delete game set from id
function deleteSet(setId, gameId) {
    if (confirm('Are you sure you want to delete this set?')) {
        window.location.href = `gameSets.php?set_id=${setId}&game_id=${gameId}`;
    }
}

/**
 * Card Logic
*/

//Function to open the update card modal and fill in the details
function openUpdateCardModal(cardId, cardName, cardNumber, cardImage) {
    document.getElementById('update_card_id').value = cardId;
    document.getElementById('update_card_name').value = cardName;
    document.getElementById('update_card_number').value = cardNumber;
    document.getElementById('update_card_image').value = cardImage;

    // Show the modal
    $('#updateCardModal').modal('show');
}

//Function do delete game set from id
function deleteCard(cardId, setId, gameId) {
    if (confirm('Are you sure you want to delete this set?')) {
        window.location.href = `card.php?card_id=${cardId}&set_id=${setId}&game_id=${gameId}`;
    }
}



