<?php
if (isset($_POST["account_info"])) {
    $account_info = json_decode($_POST["account_info"]);

    $error = $account_info->error;
    $game = $account_info->game;
    $nickname = $account_info->nickname;
}

echo<<<HERE
    <h1>$error</h1>
    $game <br>
    $nickname
HERE;

?>

<hr>

<button class="find-new-user">Найти другого</button>

<script>
    $(".find-new-user").click(function() {
        $.removeCookie("nickname");
        $.removeCookie("game");
        location.reload();
    });
</script>