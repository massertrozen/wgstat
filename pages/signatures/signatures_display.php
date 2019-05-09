<?php
if (isset($_POST["signature"])) {
    $signature = json_decode($_POST["signature"]);
    $game = $_POST["game"];
    $background = $signature->background;
    $signature_data = $signature->signature_data;
    $nickname = $signature->nickname;

    $fontsPath = [
        "XG-1" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\AdverGothic-Regular\\AdverGothic-Regular.ttf",
        "XG-2" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Aksent-Normal\\Aksent-Normal.ttf",
        "XG-4" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Arthur-Gothic-Regular\\Arthur-Gothic-Regular.ttf",
        "XG-5" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Banco-Normal\\Banco-Normal.ttf",
        "XG-6" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\BauhausCTT-Regular\\BauhausCTT-Regular.ttf",
        "XG-7" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\BenguiatGothicCTT-BoldItalic\\BenguiatGothicCTT-BoldItalic.ttf",
        "XG-8" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\CrashCTT-Regular\\CrashCTT-Regular.ttf",
        "XG-9" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Derby-Regular\\Derby-Regular.ttf",
        "XG-10" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Docker-One\\Docker-One.ttf",
        "XG-11" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\DS-Zombie-Cyr-Bold\\DS-Zombie-Cyr-Bold.ttf",
        "XG-12" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Dynar-Bold\\Dynar-Bold.ttf",
        "XG-13" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\DynarShadow-Bold\\DynarShadow-Bold.ttf",
        "XG-14" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\EuropeExt-Italic\\EuropeExt-Italic.ttf",
        "XG-16" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Glasten-Normal\\Glasten-Normal.ttf",
        "XG-17" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\GOST-type-A-Standard\\GOST-type-A-Standard.ttf",
        "XG-18" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Graffiti1CTT-Regular\\Graffiti1CTT-Regular.ttf",
        "XG-19" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\JakobCTT-Bold\\JakobCTT-Bold.ttf",
        "XG-20" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\KabelCTT-Ultra-Regular\\KabelCTT-Ultra-Regular.ttf",
        "XG-21" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\KarollaCTT-Regular\\KarollaCTT-Regular.ttf",
        "XG-22" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Madera-TYGRA-Regular\\Madera-TYGRA-Regular.ttf",
        "XG-23" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Malahit-Bold\\Malahit-Bold.ttf",
        "XG-24" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Mistral-Regular_\\Mistral-Regular_.ttf",
        "XG-25" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Moonlight-Regular\\Moonlight-Regular.ttf",
        "XG-26" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\NewZelek-Normal\\NewZelek-Normal.ttf",
        "XG-27" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\PentaDemi-Normal\\PentaDemi-Normal.ttf",
        "XG-29" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Start-Normal\\Start-Normal.ttf",
        "XG-30" => dirname(__FILE__)."\\..\\..\\assets\\fonts\\Stylo-Bold\\Stylo-Bold.ttf"
    ];

    $im = imagecreatefromjpeg($background);
    $color = imagecolorallocate($im, 255, 255, 255);
    echo "<meta http-equiv='Cache-Control' content='no-cache'>";

    foreach ($signature_data as $key => $value) {
        $isEnabled = $value->isEnabled === "true" ? true : false;

        if ($isEnabled) {
            $isEnabled = $value->isEnabled;
            $font = $value->font;
            $fontSize = $value->fontSize - 3;
            $posX = $value->X;
            $posY = $value->Y;

            imagettftext($im, $fontSize, 0, $posX - 5, $posY + $fontSize, $color, $fontsPath[$font], $key);
        }
    }
    
    // game and path to png
    imagepng($im,  dirname(__FILE__)."\\..\\..\\signatures\\{$game}\\{$nickname}\\{$nickname}.png", 0);
    imagedestroy($im);

    $link = $_SERVER['SERVER_NAME'] . "/signatures/$game/$nickname/$nickname.png";
}
?>
<div class="service-desc">
    Ваш юзер-бар готов!<br>
    Показатели статистики на созданном вами изображении будут обновлять автоматически один раз в сутки.<br>
    Скопируйте ссылку на изображение ниже и вставьте её подпись на форум.
</div>
<div class="service-nav">
    <button class="reset-sign sign-button"><i class="material-icons">cached</i> Создать сначала</button>
    <button class="reset-text sign-button"><i class="material-icons">notes</i> Сбросить надписи</button>
    <button class="logout sign-button" ><i class="material-icons">power_settings_new</i> Выйти из аккаунта</button>
</div>
<div class="signature-container">
    <img src="./wotb/SkyWex/SkyWex.png"/>
    <input value="<?php echo $link; ?>" readonly/>
<div>
<script>
    $(".logout").click(function() {
        let access_token = $.cookie("access_token");

        $.post("../helpers/logout.php", { search: access_token });
        $.removeCookie("access_token");
        $.removeCookie("is_game_swithed");
        $.removeCookie("signatures_game");
        location.reload();
    });

    $(".reset-text").click(function() {
        $.post("../helpers/signatures/signature_reset_data.php", { access_token: $.cookie("access_token"), game: $.cookie("signatures_game") });
        location.reload();
    });

    $(".reset-sign").click(function() {
        $.post("../helpers/signatures/signature_reset.php", { access_token: $.cookie("access_token"), game: $.cookie("signatures_game") });
        location.reload();
    });
</script>