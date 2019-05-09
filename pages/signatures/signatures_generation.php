<?php
if (isset($_POST["options"]) && count(json_decode($_POST["options"])->values) > 0) {
    $data = json_decode($_POST["options"]);
    $wgn_accounts = $data->wgn_accounts;
    $options = $data->values;
    $background = $data->background;
    $selected_game = $_POST["game"];

    $wot_icon = "
    <svg version=\"1.1\" id=\"wot\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" width=\"24px\" height=\"43px\" viewBox=\"0 0 24 43\" enable-background=\"new 0 0 24 43\" xml:space=\"preserve\">
    <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" fill=\"#FFFFFF\" d=\"M12.0079,38L0.1203,26.2519V11.3133L6.5077,5h11.1349
        l6.2371,6.1648v15.1031L12.0079,38z M22.6072,11.6439l-5.5698-5.505H7.0958l-5.703,5.6372v13.8976l10.6144,10.4895l10.6-10.4767
        V11.6439z M9.6349,12.8178H2.7966l4.8942-4.9479h8.7921l4.8087,4.9479h-6.8403v19.9073l-2.4074,2.405l-2.409-2.405V12.8178z
         M16.6219,14.1333h4.6433v11.1782l-3.5211,3.5552l-3.2382-3.229v-0.006l2.116-2.0969V14.1333z M7.3813,23.5348l2.2045,2.1019
        l-3.3285,3.2301l-3.5185-3.5551V14.1331h4.6425V23.5348z\"></path>
    </svg>";

    $wows_icon = "
    <svg version=\"1.1\" id=\"wot\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" width=\"24px\" height=\"43px\" viewBox=\"0 0 24 43\" enable-background=\"new 0 0 24 43\" xml:space=\"preserve\">
    <path fill=\"#FFFFFF\" d=\"M15.2619,18.4452l3.7372-2.2235l0.0001-2.3485h-4.6986l0.0001-1.4529l2.222-1.9551V7.9741H7.4711v2.4911
        l2.2221,1.9551v1.4529H4.9947v2.3485l3.7373,2.2235h0.9615v9.4942l-2.5678-1.5288v-5.2818l-4.25-2.5377v10.5438l9.1244,5.4326
        l9.1243-5.4327V18.5911l-4.2562,2.5377v5.2818l-2.5676,1.5288v-9.4942H15.2619z M18.299,5.0004l5.7003,3.3904v22.4643
        l-11.9996,7.1445l-11.999-7.1445V8.3908L5.701,5.0004H18.299z M22.5618,9.2514l-4.6465-2.7637H6.0847L1.4382,9.2514V29.995
        l10.5615,6.2886l10.5621-6.2886V9.2514z\"></path>
    </svg>
    ";

    $wotb_icon = "
    <svg version=\"1.1\" id=\"wot\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" width=\"24px\" height=\"43px\" viewBox=\"0 0 24 43\" enable-background=\"new 0 0 24 43\" xml:space=\"preserve\">
    <path fill=\"#FFFFFF\" d=\"M12.00001,38L-0.00001,25.745V10.427L12.00001,5l12,5.515v15.318L12.00001,38z M22.69551,11.302
        l-10.7819-4.726L1.13059,11.302V25.22l10.86942,10.854l10.6955-10.767V11.302z M11.91361,32.923l1.6522-8.491l-3.9125,1.838
        l2.1738-8.403L7.04419,19.88L9.91361,9.376l2.0864-0.962l2,0.875l-2.0874,6.215l4.0864-1.576l-2.9568,9.191l4-1.926L11.91361,32.923
        z\"></path>
    </svg>
    ";

    $wowp_icon = "
    <svg version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" width=\"57px\" height=\"43px\" viewBox=\"0 0 57 43\" enable-background=\"new 0 0 57 43\" xml:space=\"preserve\">
    <path fill=\"#FFFFFF\" d=\"M18.3065,9.86905l10.1746-1.313l10.2979,1.3418v1.1885l1.6055,0.0096l-0.019-2.3482l-11.9034-1.7541
        L16.701,8.73815v2.3769l1.558,0.0097L18.3065,9.86905z M32.6896,12.09275v9.7763c0,0-1.045,1.3994-1.805,2.0894
        c-0.0286-3.738-0.0286-12.067-0.0286-12.067l-2.4035-2.2811l-2.3939,2.2045l-0.0096,12.1628l-1.8905-2.0607l0.0286-9.8721
        L0,12.09275l4.6075,3.7571l14.8961,0.0096l0.0095,7.9745l3.5719,3.1341l2.9546-2.9712l-0.0475,6.1437l2.527,2.2141l2.3655-2.2332
        l0.0094-6.1438l3.2301,2.8658l3.3915-3.2491l-0.0666-7.6678h15.0006L57,12.06395L32.6896,12.09275z M7.3246,17.77645l4.6264,4.8402
        l2.7835-0.0096l-0.019-4.8019L7.3246,17.77645z M39.064,23.83395l-10.5735,10.4089l-10.6114-10.3035l-0.0096-6.0287l-1.292,0.0095
        l0.0096,6.7572l11.875,11.329l11.8939-11.4153l0.0095-6.7571l-1.2729,0.0096L39.064,23.83395z M42.3035,22.64535l2.7835-0.0095
        l4.56-4.8786l-7.372,0.0288L42.3035,22.64535z\"></path>
    </svg>
    ";

    $wgn_icons = ["wot" => $wot_icon, "wows" => $wows_icon, "wotb" => $wotb_icon, "wowp" => $wowp_icon];
    $wgn_games = ["wot" => "World of Tanks", "wows" => "World of Warships", "wotb" => "World of Tanks Blitz", "wowp" => "World of Warplanes"];
echo<<<HERE
<div class="games">
HERE;

foreach ($wgn_accounts as $game) {
    $desc = "перейти на профиль";
    $isActive = "";
    if ($selected_game === $game) {
        $isActive = "active";
        $desc = "отображается сейчас";
    }

    echo "<div class=\"switch-game $isActive\" to=\"$game\">
            <div class=\"icon\">$wgn_icons[$game]</div>
            <div class=\"desc\">
            $desc
            <span>$wgn_games[$game]</span>
            </div>
        </div>";
}
echo<<<HERE
</div>
HERE;

    $option_fonts = [
        "XG-1", "XG-2", "XG-4", "XG-5", "XG-6", "XG-7", "XG-8", "XG-9", "XG-10", "XG-11", "XG-12", "XG-13", "XG-14", "XG-16", "XG-17",
        "XG-18", "XG-19", "XG-20", "XG-21", "XG-22", "XG-23", "XG-24", "XG-25", "XG-26", "XG-27", "XG-29", "XG-30"
    ];

    $option_font_sizes = [8, 10, 12, 14, 16, 18, 20, 22];

    echo "<div class='options'>";
    foreach ($options as $option => $option_value) {
        echo "<div class='option'>";

        echo "             
            <label class='option-container'>
                <input type='checkbox' class='option-enabled'>
                <span class='checkmark'></span>
                <div class='option-value'>$option: $option_value</div>                
            </label> 

            <select class='option-font'>
        ";

        foreach ($option_fonts as $font) {
            echo "<option value='$font'>$font</option>";
        }

        echo "</select> <select class='option-font-size'>";

        foreach ($option_font_sizes as $font_size) {
            echo "<option value='$font_size'>$font_size</option>";
        }

        echo "</select></div>";
    }
    echo "</div>";
} else {
    echo "<h1>Страница в разработке. Скоро...</h1>";
    echo "<button class='recently-game main-button'>Открыть другую игру</button>";
}
?>

<div class="signature" style="background-image: url('<?php echo $background ?>');"></div>
<center><button class='generate-sign main-button'>Сгенерировать</button></center>

<script> 
    $(".recently-game").click(() => { resetGame(); });
    var options = initOptionsArray();

    $(".option-enabled").change(function() {
        let isEnabled = $(this).is(':checked') ? true : false;
        let optionValue = $(this).parent().find(".option-value").text();

        if (isEnabled) $(this).parent().parent().addClass("selected");
        else $(this).parent().parent().removeClass("selected");

        options[optionValue].isEnabled = isEnabled;
        updateSignature();
    });

    $(".option-font").change(function() {
        let font = $(this).val();
        let optionValue = $(this).parent().find(".option-value").text();

        options[optionValue].font = font;
        updateSignature();
    });

    $(".option-font-size").change(function() {
        let fontSize = $(this).val();
        let optionValue = $(this).parent().find(".option-value").text();

        options[optionValue].fontSize = fontSize;
        updateSignature();
    }); 

    $(".switch-game").click(function() { switchGameTo($(this).attr("to")); });
    
    $(".generate-sign").click(function() {
        $.post("../helpers/signatures/signature_set_data.php", { access_token: $.cookie("access_token"), game: $.cookie("signatures_game"), options: options });
        location.reload();
    });

    $(".logout").click(function() {
        let access_token = $.cookie("access_token");

        $.post("../helpers/logout.php", { search: access_token });
        $.removeCookie("access_token");
        $.removeCookie("is_game_swithed");
        $.removeCookie("signatures_game");
        location.reload();
    });    
</script>
