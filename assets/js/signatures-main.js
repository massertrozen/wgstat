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
                        $(".main-content").load("../pages/signatures/signatures_display.php");
                    } else if ("process" in response) {
                        let game = $.cookie("signatures_game");

                        switch (game) {
                            case "wot":
                                $.post("../helpers/wot/account_info.php", { search: nickname })
                                .done(function(response) {
                                    $.post("../helpers/signatures/get_wot_options.php", { account_info: response })
                                    .done(function(response) {
                                        $(".main-content").load("../pages/signatures/signatures_generation.php", { options: response, game: game });
                                    });
                                });
                                break;
                    
                            case "wows":
                                $.post("../helpers/wows/account_info.php", { search: nickname })
                                .done(function(response) {
                                    $.post("../helpers/signatures/get_wows_options.php", { account_info: response })
                                    .done(function(response) {
                                        $(".main-content").addClass("landing");
                                        $(".main-content").load("../pages/signatures/signatures_generation.php", { options: response, game: game });
                                    });
                                });
                                break;
                    
                            case "wotb":
                                $.post("../helpers/wotb/account_info.php", { search: nickname })
                                .done(function(response) {
                                    $.post("../helpers/signatures/get_wotb_options.php", { account_info: response })
                                    .done(function(response) {
                                        $(".main-content").load("../pages/signatures/signatures_generation.php", { options: response, game: game });
                                    });
                                });
                                break;
                    
                            case "wowp":
                                $.post("../helpers/wowp/account_info.php", { search: nickname })
                                .done(function(response) {
                                    $.post("../helpers/signatures/get_wowp_options.php", { account_info: response })
                                    .done(function(response) {
                                        $(".main-content").addClass("landing");
                                        $(".main-content").load("../pages/signatures/signatures_generation.php", { options: response, game: game  });
                                    });
                                });
                                break;
                        }
                    } else {
                        $(".main-content").load("../pages/signatures/signatures_promo.php");
                    }                    
                });                
            });
        }
    });
}

function switchGameTo(game) {
    $(".main-content").append("<div class='loader'><img width='50px' src='../assets/img/loader.svg'/></div>");
    let nickname = $.cookie("nickname");
    $.cookie("signatures_game", game, { expires: 1, path: "/signatures"});
    $.cookie("is_game_swithed", true, { expires: 1, path: "/signatures"});
    location.reload();
}

function resetGame() {
    $(".main-content").append("<div class='loader'><img width='50px' src='../assets/img/loader.svg'/></div>");
    $.removeCookie("signatures_game");
    $.removeCookie("is_game_swithed");
    location.reload();
}

function initOptionsArray() {
    let options = {};

    $(".option").each(function(key, element) {
        let isEnabled = $(this).find(".option-enabled").is(':checked') ? true : false;
        let optionValue = $(this).find(".option-value").text();
        let font = $(this).find(".option-font").val();
        let fontSize = parseInt($(this).find(".option-font-size").val());

        let option = { isEnabled, font, fontSize, X: 0, Y: 0 };
        options[optionValue] = option;
    });
    
    return options;
}

function updateCoordinates(element) {
    element = $(element);
    let option = element.text();
    let positionTop = element.position().top;
    var postionLeft = element.position().left;
    
    options[option].X = positionTop;
    options[option].Y = postionLeft;
}

function updateSignature() {
    $(".signature").html("");

    for (let option in options) {
        let isEnabled = options[option].isEnabled;
        let font = options[option].font;
        let fontSize = options[option].fontSize;
        let X = options[option].X;
        let Y = options[option].Y;

        if (isEnabled) {
            let signOption = "<div class='sign-option' style='font-family: " + font + "; font-size: " + fontSize + "; top: " + X + "; left: " + Y + "'>" + option + "<div>";
            $(".signature").append(signOption);
        }
    }

    $(".sign-option").draggable({
        containment: "parent",
        stop: function() {
            updateCoordinates(this);
        }
    });
}

$(function() {
    if ($.cookie("access_token") !== undefined) {
        let access_token = $.cookie("access_token");
        
        $(".main-content").append("<div class='loader'><img width='50px' src='../assets/img/loader.svg'/></div>");
        load_signatures_page(access_token);
    } 
});