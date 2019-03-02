$(function() {
    $(".find-nickname").submit(function(event) {
        event.preventDefault();
        let nickname = $(this).serializeArray()[0]["value"];

        $.post("../helpers/wotb_account_info.php", { search: nickname })
        .done(function(response) {
            $(".main-container").load("../pages/wotb_account_info.php", { statistics_all: response });
        });
    });
});