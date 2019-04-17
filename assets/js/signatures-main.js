function load_signatures_page(access_token) {
    $.post("../helpers/validators/check_access_token.php", { search: access_token })
    .done(function(response) {
        response = JSON.parse(response);

        if ("error" in response)
            $(".authorization-error").html("Произошла ошибка авторизации. Попробуйте авторизоваться еще раз.");
        else {
            let nickname = response.nickname;
            let account_id = response.account_id;

            $.post("../helpers/validators/check_the_game.php", { search: nickname })
            .done(function(response) {
                let last_game = JSON.parse(response)["last_game"];

                if ($.cookie("is_game_swithed") === undefined) {
                    $.cookie("signatures_game", last_game, { expires: 1, path: "/signatures"});
                }

                $.post("../helpers/signatures/signature_registration.php", { nickname: nickname, account_id: account_id,  game: $.cookie("signatures_game") })
                .done(function(response) {
                    response = JSON.parse(response);
                    if ("ok" in response) {
                        $(".main-container").load("../pages/signatures/signatures_display.php");
                    } else if ("process" in response) {
                        let game = $.cookie("signatures_game");

                        // switch (game) {
                        //     case "wot":
                        //         $.post("../helpers/wot/account_info.php", { search: nickname })
                        //         .done(function(response) {
                        //             $(".main-container").load("../pages/wot/account_info.php", { account_info: response });
                        //         });
                        //         break;
                    
                        //     case "wows":
                        //         $.post("../helpers/wows/account_info.php", { search: nickname })
                        //         .done(function(response) {
                        //             $(".main-container").load("../pages/wows/account_info.php", { account_info: response });
                        //         });
                        //         break;
                    
                        //     case "wotb":
                        //         $.post("../helpers/wotb/account_info.php", { search: nickname })
                        //         .done(function(response) {
                        //             $(".main-container").load("../pages/wotb/account_info.php", { account_info: response });
                        //         });
                        //         break;
                    
                        //     case "wowp":
                        //         $.post("../helpers/wowp/account_info.php", { search: nickname })
                        //         .done(function(response) {
                        //             $(".main-container").load("../pages/wowp/account_info.php", { account_info: response });
                        //         });
                        //         break;
                        // }
                        $(".main-container").load("../pages/signatures/signatures_generation.php");
                    } else {
                        $(".main-container").load("../pages/signatures/signatures_promo.php");
                    }                    
                });                
            });
        }
    });
}

$(function() {
    if ($.cookie("access_token") !== undefined) {
        let access_token = $.cookie("access_token");
        
        $(".main-container").html("loading...<div class='authorization-error'></div");
        load_signatures_page(access_token);
    }    
});