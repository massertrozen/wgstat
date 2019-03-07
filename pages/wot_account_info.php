<?php
if (isset($_POST["account_info"])) {
    // statitics: info, all, tanks, achievements, max_series, clan, wgn_accounts;
    $account_info = json_decode($_POST["account_info"]);
    $statistics_all = $account_info->statistics->all; 
    $tanks_stat = $account_info->tanks_stat;
    $tanks_encyclopedia = $account_info->tanks_encyclopedia;
    $achievements = $account_info->achievements;
    $account_achievements = $achievements->achievements;
    $account_max_series = $achievements->max_series;
    $clan_info = $account_info->clan_info;  
    $clan_glossary = $account_info->glossary;
    $wgn_accounts = $account_info->wgn_accounts;
    $wgn_games = array("wot" => "World of Tanks", "wows" => "World of Warships", "wotb" => "World of Tanks Blitz", "wowp" => "World of Warplanes");
    $wgn_servers = $account_info->wgn_servers;

    // info;
    $nickname = $account_info->nickname;
    $created_at = date("d.m.Y H:i:s", $account_info->created_at);
    $created_days = round((time() - $created_at) / 86400);
    $updated_at = date("d.m.Y H:i:s", $account_info->updated_at);
    $last_battle_time = date("d.m.Y H:i:s", $account_info->last_battle_time);
    $global_rating = $account_info->global_rating;

    // clan;
    if (count($clan_info) > 0) {
        $clan_id = $clan_info->id;
        $clan_role = $clan_info->role;
        $clan_role_normal = $clan_glossary->$clan_role;
        $clan_joined_at = $clan_info->joined_at;
        $clan_name = $clan_info->name;
        $clan_members_count = $clan_info->members_count;
        $clan_created_at = date("d.m.Y H:i:s", $clan_info->created_at);
        $clan_leader_name = $clan_info->leader_name;
        $clan_tag = $clan_info->tag;
        $clan_motto = $clan_info->motto;
        $clan_description = $clan_info->description;
        $clan_days_joined = round((time() - $clan_joined_at) / 86400);
        $clan_emblem = $clan_info->emblems->x195->portal;
    }

    // all;
    $spotted = $statistics_all->spotted;
    $battles_on_stunning_vehicles = $statistics_all->battles_on_stunning_vehicles;
    $max_xp = $statistics_all->max_xp;
    $avg_damage_blocked = $statistics_all->avg_damage_blocked;
    $direct_hits_received = $statistics_all->direct_hits_received;
    $explosion_hits = $statistics_all->explosion_hits;
    $piercings_received = $statistics_all->piercings_received;
    $piercings = $statistics_all->piercings;
    $max_damage_tank_id = $statistics_all->max_damage_tank_id;
    $xp = $statistics_all->xp;
    $survived_battles = $statistics_all->survived_battles; 
    $dropped_capture_points = $statistics_all->dropped_capture_points;
    $hits_percents = $statistics_all->hits_percents;
    $draws = $statistics_all->draws;
    $max_xp_tank_id = $statistics_all->max_xp_tank_id;
    $battles = $statistics_all->battles;
    $damage_received = $statistics_all->damage_received;
    $avg_damage_assisted = $statistics_all->avg_damage_assisted;
    $max_frags_tank_id = $statistics_all->max_frags_tank_id; 
    $avg_damage_assisted_track = $statistics_all->avg_damage_assisted_track;
    $frags = $statistics_all->frags; 
    $stun_number = $statistics_all->stun_number;
    $avg_damage_assisted_radio = $statistics_all->avg_damage_assisted_radio;
    $capture_points = $statistics_all->capture_points;    
    $stun_assisted_damage = $statistics_all->stun_assisted_damage;
    $max_damage = $statistics_all->max_damage; 
    $hits = $statistics_all->hits; 
    $battle_avg_xp = $statistics_all->battle_avg_xp;
    $wins = $statistics_all->wins;
    $losses = $statistics_all->losses; 
    $damage_dealt = $statistics_all->damage_dealt;
    $no_damage_direct_hits_received = $statistics_all->no_damage_direct_hits_received;
    $max_frags = $statistics_all->max_frags;
    $shots = $statistics_all->shots;
    $explosion_hits_received = $statistics_all->explosion_hits_received;
    $tanking_factor = $statistics_all->tanking_factor;
    $masters = 0;

    // calculated values from statistics->all;
    $battles_on_stunning_vehicles_percent = $battles_on_stunning_vehicles / $battles * 100;
    $battles_on_stunning_vehicles_percent_rounded = round($battles_on_stunning_vehicles_percent, 2);
    $wins_percent = $wins / $battles * 100;
    $wins_percent_rounded = round($wins_percent, 2);
    $avg_damage_dealt = $damage_dealt / $battles;
    $avg_damage_dealt_rounded = round($avg_damage_dealt);
    $damage_rate = $damage_dealt / $damage_received;
    $damage_rate_rounded = round($damage_rate, 2);
    $destruction_rate = $frags / $survived_battles;
    $destruction_rate_rounded = round($destruction_rate, 2);
    $avg_frags = $frags / $battles;
    $avg_frags_rounded = round($avg_frags, 2);
    $avg_spotted = $spotted / $battles;
    $avg_spotted_rounded = round($avg_spotted, 2);    
    $avg_capture = $capture_points / $battles;
    $avg_capture_rounded = round($avg_capture, 2);
    $avg_dropped_capture = $dropped_capture_points / $battles;
    $avg_dropped_capture_rounded = round($avg_dropped_capture, 2);
    $hits_percent = $hits / $shots * 100;
    $hits_percent_rounded = round($hits_percent, 2);
    $survival_percent = $survived_battles / $battles * 100;
    $survival_percent_rounded = round($survival_percent, 2);
    $avg_damage_received = round($damage_received / $battles);    
    $solo_wins = $wins - $joint_victory;
    $joint_victory_percent = round($joint_victory / $wins * 100, 2);
    $avg_shots = round($shots / $battles, 2);
    $avg_hits = round($hits / $battles, 2);
    $avg_piercings = round($piercings / $battles, 2);
    $avg_direct_hits_received = round($direct_hits_received / $battles, 2);
    $avg_piercings_received = round($piercings_received / $battles, 2);

    // tanks;
    $tanks_names = [];
    $tanks_tiers = [];
    $max_tier = 0;
    $tanks_counter = count($tanks_stat);
    foreach ($tanks_encyclopedia as $tank) {
        if ($tank->tier > $max_tier) $max_tier = $tank->tier;

        $tanks_names[$tank->tank_id] = $tank->name;
        $tanks_tiers[$tank->tank_id] = $tank->tier;
    }

    foreach ($tanks_stat as $tank) {
        $tank_id = $tank->tank_id;
        $tank_battles = $tank->all->battles;
        $tank_tier = $tanks_tiers[$tank_id];
        $tank_mark_of_mastery = $tank->mark_of_mastery;
        if ($tank_mark_of_mastery == 4) $masters++;

        $total_tier_battles += $tank_tier * $tank_battles;
    }

    $avg_tier = $total_tier_battles / $battles;
    $avg_tier_rounded = round($avg_tier, 2);

    // ratings;
    $KPD = round($avg_damage_dealt * (10 / ($avg_tier + 2)) * (0.23 + 2 * $avg_tier / 100) + $avg_frags * 250 + $avg_spotted * 150 + (log($avg_capture + 1, 1.732)) * 150 + $avg_dropped_capture * 150, 2);				
    $WN6 = round((1240 - 1040 / pow(min($avg_tier, 6), 0.164)) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp(($wins_percent - 35) * -0.134))) - 500) * 0.45 + (6 - min($avg_tier, 6)) * -60, 2);
    $WN7 = round((1240 - 1040 / (pow(min($avg_tier, 6), 0.164))) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 * min($avg_tier, 3) / 3 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp((($wins_percent) - 35) * -0.134))) - 500) * 0.45 + (-1 * (((5 - min($avg_tier, 5)) * 125) / (1 + exp(($avg_tier - ($battles / 220^(3 / $avg_tier))) * 1.5)))), 2);
    
    echo "Сервера:<br>";
    foreach ($wgn_servers as $server_info) {
        echo $server_info->server.": ".$server_info->players_online."<br>";
    }

    echo "<hr>Играет в:<br>";
    foreach ($wgn_accounts as $game) {
        echo "<button class='switch-game' to='".$game."'>".$wgn_games[$game]."</button><br>";
    }
echo<<<HERE
<hr>
<table>
    <tr><td>Ник<td> <td>$nickname</td></tr>
    <tr><td>Зарегистрирован<td> <td>$created_at ($created_days дн.)</td></tr>
    <tr><td>Обновлено<td> <td>$updated_at</td></tr>
    <tr><td>Последний раз был в бою<td> <td>$last_battle_time</td></tr>
    <tr><td>---</td></tr>
    <tr><td>Клан</td> <td>[$clan_tag]</td></tr>
    <tr><td>Звание</td> <td>$clan_role_normal</td></tr>
    <tr><td>Дней в клане</td> <td>$clan_days_joined</td></tr>
    <tr><td>---</td></tr>
    <tr><td>Клан</td> <td>$clan_name [$clan_tag]</td></tr>
    <tr><td>Создан</td> <td>$clan_created_at</td></tr>
    <tr><td>Эмблема</td> <td><img src='$clan_emblem' alt='$clan_tag clan emblem' /></td></tr>
    <tr><td>Лидер</td> <td>$clan_leader_name</td></tr>
    <tr><td>Девиз</td> <td>$clan_motto</td></tr>
    <tr><td>Описание</td> <td>$clan_description</td></tr>
    <tr><td>Игроков в клане</td> <td>$clan_members_count</td></tr>
    <tr><td>---</td></tr>
    <tr><td>Глобальный рейтинг</td> <td>$global_rating</td></tr>
    <tr><td>КПД</td> <td>$KPD<td></tr>
    <tr><td>WN6</td> <td>$WN6<td></tr>
    <tr><td>WN7</td> <td>$WN7<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Всего боёв</td> <td>$battles<td></tr>
    <tr><td>Процент побед</td> <td>$wins_percent_rounded %<td></tr>
    <tr><td>Общее количество побед</td> <td>$wins<td></tr>
    <tr><td>Поражения</td> <td>$losses<td></tr>
    <tr><td>Ничьи</td> <td>$draws<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Боёв на арт.</td> <td>$battles_on_stunning_vehicles_percent_rounded %</td></tr>
    <tr><td>Средний уровень танков</td> <td>$avg_tier_rounded<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Общий урон</td> <td>$damage_dealt<td></tr>
    <tr><td>Уничтожено</td> <td>$frags<td></tr>
    <tr><td>Обнаружено</td> <td>$spotted<td></tr>
    <tr><td>Очков захвата базы</td> <td>$capture_points<td></tr>
    <tr><td>Очков защиты базы</td> <td>$dropped_capture_points<td></tr>
    <tr><td>Произведено выстрелов</td> <td>$shots<td></tr>
    <tr><td>Выжил в боях</td> <td>$survived_battles<td></tr>
    <tr><td>Получено опыта</td> <td>$xp<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Ассист урон</td> <td>$avg_damage_assisted</td></tr>
    <tr><td>По гусенице</td> <td>$avg_damage_assisted_track</td></tr>
    <tr><td>По засвету</td> <td>$avg_damage_assisted_radio</td></tr>
    <tr><td>---</td></tr>
    <tr><td>Средний урон</td> <td>$avg_damage_dealt_rounded<td></tr>
    <tr><td>Коэф. урона</td> <td>$damage_rate_rounded<td></tr>
    <tr><td>Коэф. уничтожения</td> <td>$destruction_rate_rounded<td></tr>
    <tr><td>Убито за бой</td> <td>$avg_frags_rounded<td></tr>
    <tr><td>Обнаружено за бой</td> <td>$avg_spotted_rounded<td></tr>
    <tr><td>Захват базы за бой</td> <td>$avg_capture_rounded<td></tr>
    <tr><td>Защита базы за бой</td> <td>$avg_dropped_capture_rounded<td></tr>
    <tr><td>Процент попадания</td> <td>$hits_percent_rounded %<td></tr>
    <tr><td>Процент выживания</td> <td>$survival_percent_rounded %<td></tr>
    <tr><td>Средний опыт за бой</td> <td>$battle_avg_xp<td></tr>
    <tr><td>Получено попаданий за бой</td> <td>$avg_direct_hits_received</td></tr>
    <tr><td>Получено пробитий за бой</td> <td>$avg_piercings_received</td></tr>
    <tr><td>Вытанковано в среднем</td> <td>$avg_damage_blocked<td></tr>
    <tr><td>Фактор танкования</td> <td>$tanking_factor</td></tr>
    <tr><td>Максимальный опыт за бой </td> <td>$max_xp<td></tr>
    <tr><td>на танке</td> <td>$tanks_names[$max_xp_tank_id]<td></tr>
    <tr><td>Максимальный урон за бой </td> <td>$max_damage<td></tr>
    <tr><td>на танке</td> <td>$tanks_names[$max_damage_tank_id]<td></tr>
    <tr><td>Максимальный уровень техники</td> <td>$max_tier</td></tr>
    <tr><td>Использовано техники</td> <td>$tanks_counter</td></tr>
    <tr><td>В среднем выстрелов за бой</td> <td>$avg_shots</td></tr>
    <tr><td>В среднем попаданий за бой</td> <td>$avg_hits</td></tr>
    <tr><td>В среднем пробитий за бой</td> <td>$avg_piercings</td></tr>
    <tr><td>Количество мастеров</td> <td>$masters</td></tr>
</table>
<hr>

HERE;
echo "<table>";
echo "<tr><td>Уровень</td> <td>Танк</td> <td>Мастерство</td> <td>Бои</td> <td>Победы</td> <td>Урон</td> <td>Ср.опыт</td></tr>";
    foreach ($tanks_stat as $tank) {
        $tank_id = $tank->tank_id;
        $tank_tier = $tanks_tiers[$tank_id] ? $tanks_tiers[$tank_id] : "<i>hidden</i>";
        $tank_name = $tanks_names[$tank_id] ? $tanks_names[$tank_id] : "<i>hidden</i>";
        $tank_battles = $tank->all->battles;
        $tank_wins = $tank->all->wins;
        $tank_wins_percent = round($tank_wins / $tank_battles * 100, 2);
        $tank_damage_dealt = $tank->all->damage_dealt;
        $tank_avg_damage_dealt = round($tank_damage_dealt / $tank_battles);
        $tank_xp = $tank->all->xp;
        $tank_avg_xp = round($tank_xp / $tank_battles);
        $tank_mark_of_mastery = $tank->mark_of_mastery;

        echo "<tr><td>$tank_tier</td> <td>$tank_name</td> <td>$tank_mark_of_mastery</td> <td>$tank_battles</td> <td>$tank_wins_percent %</td> <td>$tank_avg_damage_dealt</td> <td>$tank_avg_xp</td></tr>";
    }
echo "</table><hr>";

echo "<table>";
echo "<tr><td>Медаль</td> <td>Количество</td></tr>";
foreach ($account_achievements as $medal => $count) {
    echo "<tr><td>$medal</td> <td>$count</td></tr>";
}
echo "</table><hr>";

echo "<table>";
echo "<tr><td>Серия</td> <td>Значение</td></tr>";
foreach ($account_max_series as $series => $value) {
    echo "<tr><td>$series</td> <td>$value</td></tr>";
}
echo "</table>";

}
?>

<hr>

<button class="find-new-user">Найти другого</button>

<script>
    $(".find-new-user").click(function() {
        $.removeCookie("nickname");
        $.removeCookie("game");
        location.reload();
    });

    $(".switch-game").click(function() {
        let game = $(this).attr("to");
        let nickname = $.cookie('nickname');
        $.cookie("game", game, { expires: 1, path: "/"});

        switch (game) {
            case "wot":
                $.post("../helpers/wot_account_info.php", { search: nickname })
                .done(function(response) {
                    $(".main-container").load("../pages/wot_account_info.php", { account_info: response });
                });
                break;
    
            case "wows":
                $.post("../helpers/wows_account_info.php", { search: nickname })
                .done(function(response) {
                    $(".main-container").load("../pages/wows_account_info.php", { account_info: response });
                });
                break;
    
            case "wotb":
                $.post("../helpers/wotb_account_info.php", { search: nickname })
                .done(function(response) {
                    $(".main-container").load("../pages/wotb_account_info.php", { account_info: response });
                });
                break;
    
            case "wowp":
                $.post("../helpers/wowp_account_info.php", { search: nickname })
                .done(function(response) {
                    $(".main-container").load("../pages/wowp_account_info.php", { account_info: response });
                });
                break;
        }
    });
</script>