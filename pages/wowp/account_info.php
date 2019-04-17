<?php
if (isset($_POST["account_info"])) {
    $account_info = json_decode($_POST["account_info"]);

    $error = $account_info->error;
    $game = $account_info->game;
    $nickname = $account_info->nickname;
}

echo "<h1>$error</h1>";

?>

<button class="find-new-user main-button card-1">Найти другого игрока</button>

<script> $(".find-new-user").click(() => { resetSearch(); }); </script>