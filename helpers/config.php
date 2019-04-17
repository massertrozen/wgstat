<?php
require_once("urls.php");

// WGN database info;
$wgn_accounts_table = "wgn_accounts";
$wgn_account_id = "account_id";
$wgn_access_token = "access_token";

// Signatures database info;
$sign_table = "signatures";
$sign_account_id = "account_id";
$sign_nickname = "nickname"; // with clan tag;
$sign_game = "game";
$sign_background = "background";
$sign_global_rating = "global_rating";
$sign_kpd = "kpd";
$sign_wn6 = "wn6";
$sign_wn7 = "wn7";
$sign_battles = "battles";
$sign_wins_percent = "wins_percent";
$sign_avg_tanks_tier = "avg_tanks_tier";
$sign_avg_assist_damage = "avg_assist_damage";
$sign_avg_damage = "avg_damage";
$sign_damage_koef = "damage_koef";
$sign_kills_koef = "kills_koef";  
$sign_avg_kills = "avg_kills";
$sign_avg_spotted = "avg_spotted";
$sign_avg_captured = "avg_captured";
$sign_avg_dropped_capture = "avg_dropped_capture";
$sign_hits_percent = "hits_percent";
$sign_sirvive_percent = "survive_percent";
$sign_avg_xp = "avg_xp";
$sign_avg_damage_dealt = "avg_damage_dealt";
$sign_max_xp = "max_xp";
$sign_max_damage = "max_damage";
$sign_avg_shots = "avg_shots";
$sign_avg_hits = "avg_hits";
$sign_avg_piercings = "avg_piercings";
$sign_masters_counter = "masters_counter"; // masters / tanks;
$sign_joint_victory_percent = "joint_victory_percent";
$sign_updated = "updated";


// WOT database info:
$wot_accounts_table = "wot_accounts";
$wot_accounts_account_id = "account_id";
$wot_accounts_stat_table = "wot_accounts_stat";
$wot_stat_account_id = "account_id";
$wot_stat_clan_id = "clan_id";
$wot_stat_global_rating = "global_rating";
$wot_stat_kpd = "kpd";
$wot_stat_wn6 = "wn6";
$wot_stat_wn7 = "wn7";
$wot_stat_battles = "battles";
$wot_stat_wins_percent_rounded = "wins_percent_rounded";
$wot_stat_avg_damage_assisted = "avg_damage_assisted";
$wot_stat_avg_damage_dealt_rounded = "avg_damage_dealt_rounded";
$wot_stat_hits_percent_rounded = "hits_percent_rounded";
$wot_stat_survival_percent_rounded = "survival_percent_rounded";
$wot_stat_battle_avg_xp = "battle_avg_xp";
$wot_stat_avg_damage_blocked = "avg_damage_blocked";
$wot_stat_avg_shots = "avg_shots";
$wot_stat_avg_hits = "avg_hits";
$wot_stat_avg_piercings = "avg_piercings";
$wot_stat_date = "stat_date";

// WOTB database info:
$wotb_accounts_table = "wotb_accounts";
$wotb_accounts_account_id = "account_id";
$wotb_accounts_stat_table = "wotb_accounts_stat";
$wotb_stat_account_id = "account_id";
$wotb_stat_clan_id = "clan_id";
$wotb_stat_kpd = "kpd";
$wotb_stat_wn6 = "wn6";
$wotb_stat_wn7 = "wn7";
$wotb_stat_battles = "battles";
$wotb_stat_wins_percent_rounded = "wins_percent_rounded";
$wotb_stat_joint_victory = "joint_victory";
$wotb_stat_solo_wins = "solo_wins";
$wotb_stat_avg_damage_dealt_rounded = "avg_damage_dealt_rounded";
$wotb_stat_avg_frags_rounded = "avg_frags_rounded";
$wotb_stat_avg_spotted_rounded = "avg_spotted_rounded";
$wotb_stat_hits_percent_rounded = "hits_percent_rounded";
$wotb_stat_survival_percent_rounded = "survival_percent_rounded";
$wotb_stat_avg_xp_rounded = "avg_xp_rounded";
$wotb_stat_avg_shots = "avg_shots";
$wotb_stat_avg_hits = "avg_hits";
$wotb_stat_date = "stat_date";

?>