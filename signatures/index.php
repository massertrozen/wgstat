<?php
require_once("../helpers/config.php");
require_once("../helpers/grabber.php");

?>

<html>
<head>
    <title>WGstat/signatures</title>
    <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="../assets/js/signatures-main.js"></script>
    <script type="text/javascript" src="../assets/js/croppic.js"></script>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/croppic.css" rel="stylesheet">
</head>
<body>
    <noscript>To work with this service you need to swith ON your browser JavaScript.</noscript>
    <div class="main-container">
        Для доступа к данной странице необходимо авторизоваться. <br>
        Процесс осуществляется на стороне Wargaming.net, потому наш сервис не имеет доступа к вашим конфеденциальным данным. <br>
        <a href="<? echo $url_wgn_authorization."&redirect_uri=".$signatures_redirect_uri ?>">
            <button type="submit">Авторизоваться</button>   
        </a>
        <div class="authorization-error"></div>
    </div>
</body>
</html>

<?php
if (isset($_GET["access_token"])) {
    $account_id = $_GET["account_id"];
    $access_token = $_GET["access_token"];

    $wgn_accounts_response = json_decode(grab("$url_wgn_account_info&account_id=$account_id&access_token=$access_token"), true);

    if (count($wgn_accounts_response["data"][$account_id]["private"]) > 0) {
        mysql_connect("localhost", "root", "");
        mysql_select_db("wgstat");

        $wgn_account_id_query = mysql_query("SELECT * FROM $wgn_accounts_table WHERE $wgn_account_id=$account_id");
        $wgn_account = mysql_fetch_array($wgn_account_id_query)[$wgn_account_id];
        
        if (isset($wgn_account))
            mysql_query("UPDATE $wgn_accounts_table SET $wgn_access_token='$access_token' WHERE $wgn_account_id=$account_id");
        else
            mysql_query("INSERT INTO $wgn_accounts_table VALUES ('$account_id', '$access_token')");
        
        mysql_close();

        echo "
            <script>
                $.cookie('access_token', '$access_token', { expires: 1, path: '/signatures'});
            </script>
            <meta http-equiv='refresh' content='1;URL=/signatures' />
        ";
    } else {
        echo "
            <script>
                $('.authorization-error').html('Не удалось авторизоваться. Попробуйте еще раз');
            </script>
        ";
    }
}

?>