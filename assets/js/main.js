function findNickname(nickname) {
    $(".find-nickname-error").html("<img width='50px' src='../assets/img/loader.svg'/>");
    $.post("../helpers/validators/check_the_game.php", { search: nickname })
    .done(function(response) {
        if ("error" in JSON.parse(response)) {
            $('.main-button').attr("disabled", false);	
            $(".find-nickname-error").html("<i class='material-icons'>highlight_off</i>Такого пользователя не существует.");
        } else {
            let last_game = JSON.parse(response)["last_game"];
            if ($.cookie("nickname") === undefined) {
                $.cookie("game", last_game, { expires: 1, path: "/"});
            }
            
            $.cookie("nickname", nickname, { expires: 1, path: "/"});
            
            let game = $.cookie("game");
            $(".main-container").hide("fade", 1000);

            switch (game) {
                case "wot":
                    $.post("../helpers/wot/account_info.php", { search: nickname })
                    .done(function(response) {
                        animatePage("wot");
                        $(".main-content").load("../pages/wot/account_info.php", { account_info: response });
                    });
                    break;
        
                case "wows":
                    $.post("../helpers/wows/account_info.php", { search: nickname })
                    .done(function(response) {
                        animatePage("wows");
                        $(".main-content").addClass("landing");
                        $(".main-content").load("../pages/wows/account_info.php", { account_info: response });
                    });
                    break;
        
                case "wotb":   
                    $.post("../helpers/wotb/account_info.php", { search: nickname })
                    .done(function(response) {
                        animatePage("wotb");
                        $(".main-content").load("../pages/wotb/account_info.php", { account_info: response });
                    });
                    break;
        
                case "wowp":
                    $.post("../helpers/wowp/account_info.php", { search: nickname })
                    .done(function(response) {
                        animatePage("wowp");
                        $(".main-content").addClass("landing");
                        $(".main-content").load("../pages/wowp/account_info.php", { account_info: response });
                    });
                    break;
            }
        }    
    });
}

function animatePage(game) {
    $("body").removeClass("main")
             .removeClass("wot")
             .removeClass("wotb")
             .removeClass("wowp")
             .removeClass("wows")
             .addClass(game);
                                  
    $(".main-container").show("fade", 1500);
    document.title = "Статистика " + $.cookie("nickname") + " | WGstat";
    $(".loader").remove();
}

function resetSearch() {
    $.removeCookie("nickname");
    $.removeCookie("game");
    location.reload();
}

function switchGameTo(game) {
    $(".main-content").append("<div class='loader'><img width='50px' src='../assets/img/loader.svg'/></div>");
    let nickname = $.cookie("nickname");
    $.cookie("game", game, { expires: 1, path: "/"});
    findNickname(nickname);
}

$(function() {
    if ($.cookie("nickname") !== undefined) {
        let nickname = $.cookie("nickname");

        $(".find-nickname-error").html("<img width='50px' src='../assets/img/loader.svg'/>");
        $(".main-input").val(nickname);
        $(".main-button").attr("disabled", true);	

        findNickname(nickname);
    }

    $(".find-nickname").submit(function(event) {
        event.preventDefault();

        let nickname = $(this).serializeArray()[0]["value"];

        $(".find-nickname-error").html("");
        $(".main-button").attr("disabled", true);	
        findNickname(nickname);        
    });
});