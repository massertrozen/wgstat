<?php
if (isset($_POST["statistics_all"])) {
    $statistics_all = json_decode($_POST["statistics_all"]);

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
}
?>

<table>
    <tr><td>КПД</td> <td>0<td></tr>
    <tr><td>WN6</td> <td>0<td></tr>
    <tr><td>WN7</td> <td>0<td></tr>
    <tr><td>Бронесайт</td> <td>0<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Всего боёв</td> <td>0<td></tr>
    <tr><td>Процент побед</td> <td>0<td></tr>
    <tr><td>Общее количество побед</td> <td>0<td></tr>
    <tr><td>Поражения</td> <td>0<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Из них побед взводом (%)</td> <td>0<td></tr>
    <tr><td>Из них побед соло (%)</td> <td>0<td></tr>
    <tr><td>Взводные победы</td> <td>0<td></tr>
    <tr><td>Соло победы</td> <td>0<td></tr>
    <tr><td>Средний уровень танков</td> <td>0<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Общий урон</td> <td>0<td></tr>
    <tr><td>Уничтожено</td> <td>0<td></tr>
    <tr><td>Обнаружено</td> <td>0<td></tr>
    <tr><td>Очков захвата базы</td> <td>0<td></tr>
    <tr><td>Очков защиты базы</td> <td>0<td></tr>
    <tr><td>Произведено выстрелов</td> <td>0<td></tr>
    <tr><td>Выжил в боях</td> <td>0<td></tr>
    <tr><td>Получено опыта</td> <td>0<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Средний урон</td> <td>0<td></tr>
    <tr><td>Коэф. урона</td> <td>0<td></tr>
    <tr><td>Коэф. уничтожения</td> <td>0<td></tr>
    <tr><td>Убито за бой</td> <td>0<td></tr>
    <tr><td>Обнаружено за бой</td> <td>0<td></tr>
    <tr><td>Захват базы за бой</td> <td>0<td></tr>
    <tr><td>Защита базы за бой</td> <td>0<td></tr>
    <tr><td>Процент попадания</td> <td>0<td></tr>
    <tr><td>Процент выживания</td> <td>0<td></tr>
    <tr><td>Средний опыт за бой</td> <td>0<td></tr>
    <tr><td>Вытанковано в среднем</td> <td>0<td></tr>
    <tr><td>Максимальный опыт за бой </td> <td>0<td></tr>
    <tr><td>на танке</td> <td>0<td></tr>
    <tr><td>---</td></tr>
    <tr><td>Медали</td></tr>
    <tr><td>---</td></tr>
    <tr><td>Танки</td></tr>
</table>