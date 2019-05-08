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
    $created_at = date("d.m.Y", $account_info->created_at);
    $updated_at = date("d.m.Y H:i:s", $account_info->updated_at);
    $last_battle_time = date("d.m.Y в H:i", $account_info->last_battle_time);

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
    }

    // all;
    $spotted = $statistics_all->spotted;
    $max_frags_tank_id = $statistics_all->max_frags_tank_id;
    $hits = $statistics_all->hits; 
    $frags = $statistics_all->frags;
    $max_xp = $statistics_all->max_xp;
    $max_xp_tank_id = $statistics_all->max_xp_tank_id;
    $wins = $statistics_all->wins;
    $losses = $statistics_all->losses;
    $capture_points = $statistics_all->capture_points; 
    $battles = $statistics_all->battles;
    $damage_dealt = $statistics_all->damage_dealt;
    $damage_received = $statistics_all->damage_received;
    $max_frags = $statistics_all->max_frags; 
    $shots = $statistics_all->shots; 
    $frags8p = $statistics_all->frags8p;
    $xp = $statistics_all->xp;
    $win_and_survived = $statistics_all->win_and_survived; 
    $survived_battles = $statistics_all->survived_battles; 
    $dropped_capture_points = $statistics_all->dropped_capture_points;
    $joint_victory = $account_max_series->jointVictory;
    $masters = $account_achievements->markOfMastery;

    // calculated values from statistics->all;
    $wins_percent = $wins / $battles * 100;
    $wins_percent_rounded = round($wins_percent, 2);
    $draw = $battles - $wins - $losses;
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
    $avg_xp = $xp / $battles;
    $avg_xp_rounded = round($avg_xp);
    $avg_damage_received = round($damage_received / $battles);    
    $solo_wins = $wins - $joint_victory;
    $joint_victory_percent = round($joint_victory / $wins * 100, 2);
    $avg_shots = round($shots / $battles, 2);
    $avg_hits = round($hits / $battles, 2);

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

        $total_tier_battles += $tank_tier * $tank_battles;
    }

    $avg_tier = $total_tier_battles / $battles;
    $avg_tier_rounded = round($avg_tier, 2);

    // ratings;
    $KPD = round($avg_damage_dealt * (10 / ($avg_tier + 2)) * (0.23 + 2 * $avg_tier / 100) + $avg_frags * 250 + $avg_spotted * 150 + (log($avg_capture + 1, 1.732)) * 150 + $avg_dropped_capture * 150, 2);				
    $WN6 = round((1240 - 1040 / pow(min($avg_tier, 6), 0.164)) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp(($wins_percent - 35) * -0.134))) - 500) * 0.45 + (6 - min($avg_tier, 6)) * -60, 2);
    $WN7 = round((1240 - 1040 / (pow(min($avg_tier, 6), 0.164))) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 * min($avg_tier, 3) / 3 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp((($wins_percent) - 35) * -0.134))) - 500) * 0.45 + (-1 * (((5 - min($avg_tier, 5)) * 125) / (1 + exp(($avg_tier - ($battles / 220^(3 / $avg_tier))) * 1.5)))), 2);
    
    if ($KPD < 0){ $KPD = "0"; }
    if ($WN6 < 0) { $WN6 = "0"; }
    if ($WN7 < 0){ $WN7 = "0"; }

    $ratings_max_value = 3000;
    $KPD_prc = $KPD * 100 / $ratings_max_value;
    $WN6_prc = $WN6 * 100 / $ratings_max_value;
    $WN7_prc = $WN7 * 100 / $ratings_max_value;

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

    $wgn_icons = ["wot" => $wot_icon, "wows" => $wows_icon, "wotb" => $wotb_icon, "wowp" => $wowp];
    
    if ($KPD >= 0 && $KPD <= 409) { $KPD_color = "bad"; }
    if ($KPD >= 410 && $KPD <= 794) { $KPD_color = "medium"; }
    if ($KPD >= 795 && $KPD <= 1184) { $KPD_color = "medium"; }
    if ($KPD >= 1185 && $KPD <= 1584) { $KPD_color = "good"; }
    if ($KPD >= 1585 && $KPD <= 1924) { $KPD_color = "perfect"; }
    if ($KPD >= 1925 && $KPD <= 9999) { $KPD_color = "excellent"; }

    if ($WN6 >= 0 && $WN6 <= 409) { $WN6_color = "bad"; }
    if ($WN6 >= 410 && $WN6 <= 794) { $WN6_color = "medium"; }
    if ($WN6 >= 795 && $WN6 <= 1184) { $WN6_color = "medium"; }
    if ($WN6 >= 1185 && $WN6 <= 1584) { $WN6_color = "good"; }
    if ($WN6 >= 1585 && $WN6 <= 1924) { $WN6_color = "perfect"; }
    if ($WN6 >= 1925 && $WN6 <= 9999) { $WN6_color = "excellent"; }

    if ($WN7 >= 0 && $WN7 <= 409) { $WN7_color = "bad"; }
    if ($WN7 >= 410 && $WN7 <= 794) { $WN7_color = "medium"; }
    if ($WN7 >= 795 && $WN7 <= 1184) { $WN7_color = "medium"; }
    if ($WN7 >= 1185 && $WN7 <= 1584) { $WN7_color = "good"; }
    if ($WN7 >= 1585 && $WN7 <= 1924) { $WN7_color = "perfect"; }
    if ($WN7 >= 1925 && $WN7 <= 9999) { $WN7_color = "excellent"; }

    $selected_game = $_COOKIE["game"];

    $heroesOfRassenayCounter = $account_achievements->heroesOfRassenay ? $account_achievements->heroesOfRassenay : 0;
    $medalLafayettePoolCounter = $account_achievements->medalLafayettePool ? $account_achievements->medalLafayettePool : 0;
    $medalRadleyWaltersCounter = $account_achievements->medalRadleyWalters ? $account_achievements->medalRadleyWalters : 0;
    $warriorCounter = $account_achievements->warrior ? $account_achievements->warrior : 0;
    $medalKolobanovCounter = $account_achievements->medalKolobanov ? $account_achievements->medalKolobanov : 0;
    $mainGunCounter = $account_achievements->mainGun ? $account_achievements->mainGun : 0;

echo<<<HERE
<div class="games">
HERE;
foreach ($wgn_accounts as $game) {
  $desc = "посмотреть профиль";
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

<div class="nickname-wrapper">
    <div class="nickname"> 
        $nickname 
        <div class="nickname-info"> 
            Последний бой: <span>$last_battle_time</span><br>
            Регистрация: <span>$created_at</span>
        </div>
    </div>
    <button class="find-new-user main-button">Найти другого</button>
</div>

<div class="ratings-wrapper">
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart $KPD_color">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="$KPD_prc, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">$KPD</text>
    </svg>
    <span>КПД</span>
  </div>
  
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart $WN6_color">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="$WN6_prc, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">$WN6</text>
    </svg>
    <span>WN6</span>
  </div>

  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart $WN7_color">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="$WN7_prc, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">$WN7</text>
    </svg>
    <span>WN7</span>
  </div>
</div>

<div class="stats-wrapper">
  <div class="stat-table">
    <div class="table-title">Общие показатели</div>
    <table class="stats">    
      <tr><td class="label">Всего боёв</td> <td class="value">$battles</td></tr>
      <tr><td class="label">Процент побед</td> <td class="value">$wins_percent_rounded %</td></tr>
      <tr><td class="label">Общее количество побед</td> <td class="value">$wins</td></tr>
      <tr><td class="label">Поражения</td> <td class="value">$losses</td></tr>
      <tr><td class="label">Ничьи</td> <td class="value">$draw</td></tr>
    </table>
  </div>

  <div class="stat-table">
    <div class="table-title"></div>
    <table class="stats">
      <tr><td class="label">Количество мастеров</td> <td class="value">$masters</td></tr>
      <tr><td class="label">Из них побед взводом (%)</td> <td class="value">$joint_victory_percent %</td></tr>
      <tr><td class="label">Взводные победы</td> <td class="value">$joint_victory</td></tr>
      <tr><td class="label">Соло победы</td> <td class="value">$solo_wins</td></tr>
      <tr><td class="label">Средний уровень танков</td> <td class="value">$avg_tier_rounded</td></tr>
    </table>
  </div>
</div>

<div class="stat-table mobile">
  <div class="table-title">Общие показатели</div>
  <table class="stats">    
    <tr><td class="label">Всего боёв</td> <td class="value">$battles</td></tr>
    <tr><td class="label">Процент побед</td> <td class="value">$wins_percent_rounded %</td></tr>
    <tr><td class="label">Из них побед взводом (%)</td> <td class="value">$joint_victory_percent %</td></tr>
    <tr><td class="label">Количество мастеров</td> <td class="value">$masters</td></tr>
    <tr><td class="label">Взводные победы</td> <td class="value">$joint_victory</td></tr>
    <tr><td class="label">Средний уровень танков</td> <td class="value">$avg_tier_rounded</td></tr>
  </table>
</div>

<div class="medals-wrapper">
  <div class="table-title">Главные достижения</div>
  <div class="medals-ribbon">
    <div class="medal" data-balloon="Медаль героев Рассейняя" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/heroesOfRassenay.png" alt="Медаль героев Рассейняя"/></div>
      <div class="medal-label">$heroesOfRassenayCounter</div>
    </div>

    <div class="medal" data-balloon="Медаль Пула" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/medalLafayettePool.png" alt="Мудаль Пула"/></div>
      <div class="medal-label">$medalLafayettePoolCounter</div>
    </div>

    <div class="medal" data-balloon="Медаль Рэдли-Уолтерса" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/medalRadleyWalters.png" alt="Медаль Рэдли-Уолтерса"/></div>
      <div class="medal-label">$medalRadleyWaltersCounter</div>
    </div>

    <div class="medal" data-balloon="Воин" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/warrior.png" alt="Воин"/></div>
      <div class="medal-label">$warriorCounter</div>
    </div>

    <div class="medal" data-balloon="Медаль Колобанова" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/medalKolobanov.png" alt="Медаль Колобанова"/></div>
      <div class="medal-label">$medalKolobanovCounter</div>
    </div>

    <div class="medal" data-balloon="Основной калибр" data-balloon-pos="down">
      <div class="medal-image"><img src="./assets/img/medals/mainGun.png" alt="Основной калибр"/></div>
      <div class="medal-label">$mainGunCounter</div>
    </div>
  </div>
</div>

<div class="stats-wrapper">
  <div class="stat-table">
    <div class="table-title">Боевая эффективность</div>
    <table class="stats">
      <tr><td class="label">Общий урон</td> <td class="value">$damage_dealt</td></tr>
      <tr><td class="label">Уничтожено</td> <td class="value">$frags</td></tr>
      <tr><td class="label">Обнаружено</td> <td class="value">$spotted</td></tr>
      <tr><td class="label">Очков захвата базы</td> <td class="value">$capture_points</td></tr>
      <tr><td class="label">Очков защиты базы</td> <td class="value">$dropped_capture_points</td></tr>
      <tr><td class="label">Произведено выстрелов</td> <td class="value">$shots</td></tr>
      <tr><td class="label">Выжил в боях</td> <td class="value">$survived_battles</td></tr>
      <tr><td class="label">Получено опыта</td> <td class="value">$xp</td></tr>
    </table>
  </div>

  <div class="stat-table">
    <div class="table-title"></div>
    <table class="stats">
      <tr><td class="label">Средний урон</td> <td class="value">$avg_damage_dealt_rounded</td></tr>
      <tr><td class="label">Уничтожено за бой</td> <td class="value">$avg_frags_rounded</td></tr>
      <tr><td class="label">Обнаружено за бой</td> <td class="value">$avg_spotted_rounded</td></tr>
      <tr><td class="label">Захват базы за бой</td> <td class="value">$avg_capture_rounded</td></tr>
      <tr><td class="label">Защита базы за бой</td> <td class="value">$avg_dropped_capture_rounded</td></tr>
      <tr><td class="label">Процент попадания</td> <td class="value">$hits_percent_rounded %</td></tr>
      <tr><td class="label">Процент выживания</td> <td class="value">$survival_percent_rounded %</td></tr>
      <tr><td class="label">Средний опыт за бой</td> <td class="value">$avg_xp_rounded</td></tr>
    </table>
  </div>

  <div class="stat-table mobile">
    <div class="table-title">Боевая эффективность</div>
    <table class="stats">
      <tr><td class="label">Средний урон</td> <td class="value">$avg_damage_dealt_rounded</td></tr>
      <tr><td class="label">Уничтожено за бой</td> <td class="value">$avg_frags_rounded</td></tr>
      <tr><td class="label">Обнаружено за бой</td> <td class="value">$avg_spotted_rounded</td></tr>
      <tr><td class="label">Захват базы за бой</td> <td class="value">$avg_capture_rounded</td></tr>
      <tr><td class="label">Защита базы за бой</td> <td class="value">$avg_dropped_capture_rounded</td></tr>
      <tr><td class="label">Процент попадания</td> <td class="value">$hits_percent_rounded %</td></tr>
      <tr><td class="label">Процент выживания</td> <td class="value">$survival_percent_rounded %</td></tr>
      <tr><td class="label">Средний опыт за бой</td> <td class="value">$avg_xp_rounded</td></tr>
    </table>
  </div>

  <div class="stat-table">
    <div class="table-title">Прочие показатели</div>
    <table class="stats">    
      <tr><td class="label">Использовано техники</td> <td class="value">$tanks_counter</td></tr>
      <tr><td class="label">Максимальный опыт</td> <td class="value">$max_xp</td></tr>
      <tr><td class="label">Выстрелов за бой</td> <td class="value">$avg_shots</td></tr>
      <tr><td class="label">Попаданий за бой</td> <td class="value">$avg_hits</td></tr>
    </table>
  </div>

  <div class="stat-table">
    <div class="table-title"></div>
    <table class="stats">
      <tr><td class="label">Максимальный уровень</td> <td class="value">$max_tier</td></tr>
      <tr><td class="label">на танке</td> <td class="value">$tanks_names[$max_xp_tank_id]</td></tr>
      <tr><td class="label">Коэф. урона</td> <td class="value">$damage_rate_rounded</td></tr>
      <tr><td class="label">Коэф. уничтожения</td> <td class="value">$destruction_rate_rounded</td></tr>
    </table>
  </div>
</div>

<div class="stat-table mobile">
  <div class="table-title">Прочие показатели</div>
  <table class="stats">    
    <tr><td class="label">Использовано техники</td> <td class="value">$tanks_counter</td></tr>
    <tr><td class="label">Максимальный опыт</td> <td class="value">$max_xp</td></tr>
    <tr><td class="label">на танке</td> <td class="value">$tanks_names[$max_xp_tank_id]</td></tr>
    <tr><td class="label">Выстрелов за бой</td> <td class="value">$avg_shots</td></tr>
    <tr><td class="label">Попаданий за бой</td> <td class="value">$avg_hits</td></tr>
    <tr><td class="label">Максимальный уровень</td> <td class="value">$max_tier</td></tr>      
    <tr><td class="label">Коэф. урона</td> <td class="value">$damage_rate_rounded</td></tr>
    <tr><td class="label">Коэф. уничтожения</td> <td class="value">$destruction_rate_rounded</td></tr>
  </table>
</div>

<div class="vehicles-wrapper">
  <table id="sortable-table" class="vehicles">
    <thead>
      <tr>
        <th class="tank-tier">Уровень</th> 
        <th class="tank-name">Танк</th> 
        <th class="tank-mastery">Мастерство</th> 
        <th class="tank-battles">Бои</th> 
        <th class="tank-wins">Победы</th> 
        <th class="tank-damage">Урон</th> 
        <th class="tank-exp">Ср.опыт</th>
      </tr>
    </thead>
    <tbody>
HERE;
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

        if ($tank_mark_of_mastery == 0) $tank_mark_of_mastery = "—";
        else $tank_mark_of_mastery = "<img src=\"./assets/img/rank_$tank_mark_of_mastery.png\" alt=\"rank_$tank_mark_of_mastery\" width=\"25px\" />";
echo<<<HERE
        <tr>
          <td class="tank-tier">$tank_tier</td> 
          <td class="tank-name">$tank_name</td> 
          <td class="tank-mastery">$tank_mark_of_mastery</td> 
          <td class="tank-battles">$tank_battles</td> 
          <td class="tank-wins">$tank_wins_percent %</td> 
          <td class="tank-damage">$tank_avg_damage_dealt</td> 
          <td class="tank-exp">$tank_avg_xp</td>
        </tr>
HERE;
    }
echo<<<HERE
    </tbody>
  </table>
</div>
HERE;
}
?>

<script>
    $(".find-new-user").click(() => { resetSearch(); });
    $(".switch-game").click(function() { switchGameTo($(this).attr("to")); });
    $("#sortable-table").tablesorter({ sortList: [[0,1]] }); 
</script>