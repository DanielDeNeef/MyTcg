// JavaScript for Update Modal (prefill with data)
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

// Function to delete user
function deleteGame(gameId) {
    if (confirm("Are you sure you want to delete this game?")) {
        window.location.href = 'collectionMng.php?id=' + gameId;
    }
}

