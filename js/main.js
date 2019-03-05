function findNickname(nickname) {
    $.post("../helpers/check_the_game.php", { search: nickname })
    .done(function(response) {
        if ("error" in JSON.parse(response))
            $(".find-nickname-error").html("Такого пользователя не существует.");
        else {
            let last_game = JSON.parse(response)["last_game"];
            if ($.cookie('nickname') === undefined) {
                $.cookie("game", last_game, { expires: 1, path: "/"});
            }
            
            $.cookie("nickname", nickname, { expires: 1, path: "/"});
            
            let game = $.cookie("game");

            switch (game) {
                case "wot":
                    $.post("../helpers/wot_account_info.php", { search: nickname })
                    .done(function(response) {
                        $(".main-container").load("../pages/wot_account_info.php", { account_info: response });
                    });
                    break;
        
                case "wows":
                    $.post("../helpers/wows_account_info.php", { search: nickname })
                    .done(function(response) {
                        $(".main-container").load("../pages/wows_account_info.php", { account_info: response });
                    });
                    break;
        
                case "wotb":
                    $.post("../helpers/wotb_account_info.php", { search: nickname })
                    .done(function(response) {
                        $(".main-container").load("../pages/wotb_account_info.php", { account_info: response });
                    });
                    break;
        
                case "wowp":
                    $.post("../helpers/wowp_account_info.php", { search: nickname })
                    .done(function(response) {
                        $(".main-container").load("../pages/wowp_account_info.php", { account_info: response });
                    });
                    break;
            }
        }    
    });
}

$(function() {
    if ($.cookie('nickname') !== undefined) {
        let nickname = $.cookie('nickname');

        $(".main-container").html("loading...");
        findNickname(nickname);
    }

    $(".find-nickname").submit(function(event) {
        event.preventDefault();

        let nickname = $(this).serializeArray()[0]["value"];

        $(".find-nickname-error").html("");
        findNickname(nickname);        
    });
});