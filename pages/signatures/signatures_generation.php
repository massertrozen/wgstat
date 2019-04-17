<?php
    $options = [
        "nickname", "global_rating", "kpd", "wn6", "wn7", "battles", "wins_percent", "avg_tanks_tier",
        "avg_assist_damage", "avg_damage", "damage_koef", "kills_koef", "avg_kills", "avg_spotted",
        "avg_captured", "avg_dropped_capture", "hits_percent", "survive_percent", "avg_xp", "avg_damage_dealt",
        "max_xp", "max_damage", "avg_shots", "avg_hits", "avg_piercings", "masters_counter", "joint_victory_percent"
    ];

    $options_values = [
        "nickname" => "tester33 [TE]", 
        "global_rating" => 12345, 
        "kpd" => 1234, 
        "wn6" => 1234, 
        "wn7" => 1234, 
        "battles" => 12345, 
        "wins_percent" => "50%", 
        "avg_tanks_tier" => 6.43,
        "avg_assist_damage" => 643.2, 
        "avg_damage" => 643.2, 
        "damage_koef" => 2.2, 
        "kills_koef" => 2.2, 
        "avg_kills" => 2.2, 
        "avg_spotted" => 2.2,
        "avg_captured" => 20.2, 
        "avg_dropped_capture" => 20.2, 
        "hits_percent" => "50%",
        "survive_percent" => "50%", 
        "avg_xp" => 123.3, 
        "avg_damage_dealt" => 643.3,
        "max_xp" => 1234, 
        "max_damage" => 12345, 
        "avg_shots" => 2.2, 
        "avg_hits" => 2.2, 
        "avg_piercings" => 2.2, 
        "masters_counter" => "20/123", 
        "joint_victory_percent" => 20
    ];

    $option_fonts = [
        "font1", "font2", "font3", "font4", "font5", "font6", "font7", "font8", "font9", "font10"
    ];

    $option_font_sizes = [10, 12, 14, 16, 18, 20];

    echo "<div class='options'>";
    foreach ($options as $option) {
        echo "<div class='option'>";

        echo " 
            <div id='$option' class='option-value'>$option: $options_values[$option]</div>
            <input type='checkbox' for='$option' name='enabled'>  
            <select for='$option' name='font'>
        ";

        foreach ($option_fonts as $font) {
            echo "<option value='$font'>$font</option>";
        }

        echo "</select> <select for='$option' name='font-size'>";

        foreach ($option_font_sizes as $font_size) {
            echo "<option value='$font_size'>$font_size</option>";
        }

        echo "</select></div>";
    }
    echo "</div>";
?>


<div class="signature">
    <div class="option value_1">Option: 1234</div>
    <div class="option value_2">Option: 10/123</div>
    <div class="option value_3">Option: 10.10</div>
    <div class="option value_4">Option: val</div>
    <div class="option value_5">Option: val [val]</div>
    <div class="option value_6">Option: 1234</div>
    <div class="option value_7">Option: 10/123</div>
    <div class="option value_8">Option: 10.10</div>
    <div class="option value_9">Option: val</div>
    <div class="option value_10">Option: val [val]</div>
    <div class="option value_11">Option: 1234</div>
    <div class="option value_12">Option: 10/123</div>
    <div class="option value_13">Option: 10.10</div>
    <div class="option value_14">Option: val</div>
    <div class="option value_15">Option: val [val]</div>
</div>

<div class="results">
    <div class="result option value_1"></div>
    <div class="result option value_2"></div>
    <div class="result option value_3"></div>
    <div class="result option value_4"></div>
    <div class="result option value_5"></div>
    <div class="result option value_6"></div>
    <div class="result option value_7"></div>
    <div class="result option value_8"></div>
    <div class="result option value_9"></div>
    <div class="result option value_10"></div>
    <div class="result option value_11"></div>
    <div class="result option value_12"></div>
    <div class="result option value_13"></div>
    <div class="result option value_14"></div>
    <div class="result option value_15"></div>
</div>

<button class="logout">Выйти из аккаунта</button>

<script>
    function get_coordinates(element) {
        element = $(element);
        let className = element.attr("class").split(" ", 2).join(".");
        let positionTop = element.position().top;
        var postionLeft = element.position().left;
        
        $(".results .result." + className).text("X: " + postionLeft + " " + "Y: " + positionTop);
    }


    $(".option").draggable({
        containment: "parent",
        stop: function() {
            get_coordinates(this);
        }
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