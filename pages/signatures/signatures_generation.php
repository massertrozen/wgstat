<?php
if (isset($_POST["options"]) && count($_POST["options"]) > 0) {
    $options = json_decode($_POST["options"]);   

    $option_fonts = [
        "XG-1", "XG-2", "XG-3", "XG-4", "XG-5", "XG-6", "XG-7", "XG-8", "XG-9", "XG-10", "XG-11", "XG-12", "XG-13", "XG-14", "XG-15",
        "XG-16", "XG-17", "XG-18", "XG-19", "XG-20", "XG-21", "XG-22", "XG-23", "XG-24", "XG-25", "XG-26", "XG-27", "XG-28", "XG-29",
        "XG-30"
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
    echo "<h1> non supported</h1>";
}
?>

<div class="signature"></div>

<button class="logout">Выйти из аккаунта</button>

<script> 
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

    $(".logout").click(function() {
        let access_token = $.cookie("access_token");

        $.post("../helpers/logout.php", { search: access_token });
        $.removeCookie("access_token");
        $.removeCookie("is_game_swithed");
        $.removeCookie("signatures_game");
        location.reload();
    });    
</script>
