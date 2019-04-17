Это сервис по созданию динамических картинок для использования в подписи на форуме.<br>
Вам необходимо расположить надписи с показателями статистики на выбранном фоне.<br>
Сами показатели будут обновляться один раз в сутки в автоматическом режиме.<br>
Ну что ж, приступим? Первым делом необходимо выбрать фон:<br>

<button id="use-default-bkg">Использовать стандартный</button>
<button id="custom-bkg-upload">Зарузить свой</button>

<hr>
<div class="croppic-container">
    <form class="bkg-selector">
        <div id="croppic"></div><br>
        <div class="croppic-error"></div>
        <input type="hidden" id="cropped-url" name="bkg">
        <button type="submit" class="croppic-select">Дальше</button>
    </form>
</div>

<div id="default-bkgs-container">
    <form class="bkg-selector">
        <div id="default-bkgs">
        <?php
            $backgrounds = scandir("../../signatures/backgrounds/");

            for ($counter = 2; $counter < count($backgrounds); $counter++) {
                $background_id = explode(".", $backgrounds[$counter])[0];
                echo "
                    <div class='default-bkg'>
                        <input id='$background_id' type='radio' name='bkg' value='../../signatures/backgrounds/$backgrounds[$counter]' >
                        <label for='$background_id'></label>
                        <img src='backgrounds/$backgrounds[$counter]' alt='$backgrounds[$counter]'/>
                    </div>
                ";
            }
        ?>
        </div>
        <button type="submit" class="default-bkg-select">Дальше</button>
    </form>
</div>
<hr>

<button class="logout">Выйти из аккаунта</button>

<script>
    $(".logout").click(function() {
        let access_token = $.cookie("access_token");

        $.post("../helpers/logout.php", { search: access_token });
        $.removeCookie("access_token");
        $.removeCookie("is_game_swithed");
        $.removeCookie("signatures_game");
        location.reload();
    });

    var croppicOptions = {
        cropUrl: "../helpers/signatures/img_crop_to_file.php",
        customUploadButtonId: "custom-bkg-upload",
        doubleZoomControls: false,
        imgEyecandy: true,
        modal: false,
        processInline: true,
        outputUrlId: "cropped-url",
        loaderHtml: "<div class='loader bubblingG'> <span id='bubblingG_1'></span> <span id='bubblingG_2'></span> <span id='bubblingG_3'></span> </div>",
        onBeforeImgUpload: function() { 
            $(".croppic-select").hide();
            $("#use-default-bkg").hide();
            $("#default-bkgs-container").hide();
            $(".croppic-container").show();
        },
        onAfterImgCrop: function() {
            $("#use-default-bkg").show();
            $(".croppic-select").show();
        }, 
        onAfterRemoveCroppedImg: function() {
            $(".croppic-select").hide();   
                      
        },
        onReset: function() {
            $("#use-default-bkg").show(); 
        }
    }	

    var croppic = new Croppic("croppic", croppicOptions);

    $("#use-default-bkg").click(function() {
        $(".croppic-container").hide(); 
        $("#default-bkgs-container").show();         
    });

    $(".bkg-selector").submit(function(event) {
        event.preventDefault();

        let background = $(this).serializeArray()[0].value;
        $.post("../helpers/signatures/signature_set_background.php", { background: background, access_token: $.cookie("access_token"), game: $.cookie("signatures_game") });
        $(".main-container").load("../pages/signatures/signatures_generation.php");
    });
</script>