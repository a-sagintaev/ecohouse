<?php
//Include database configuration file
include('dbConfig.php');
$db->query("SET NAMES utf8");

if(isset($_POST["city_id"]) && !empty($_POST["city_id"])) {
    //Get all city data
    $city_temp_in="";
    $vapor="";
    $calucl_tem_out_most_cold_5_day="";
    $dew_temp_point="";
    $calucl_tem_out_houses="";

    $query = $db->query("SELECT calucl_tem_out_most_cold_5_day,calucl_tem_out_houses,is_region,duration_heating_house,deegre_day_civil,zone_ab,cold_mid_hum_month FROM cities WHERE id =" . $_POST['city_id']);

    while ($row = $query->fetch_assoc()) {
        if ($row['is_region'] == 0 ) {
            if ($row['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $city_temp_in = 21;
            } else {
                $city_temp_in = 20;
            }
            $vapor = 55;
            $calucl_tem_out_most_cold_5_day = $row['calucl_tem_out_most_cold_5_day'];
            if ($row['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $dew_temp_point = 11.6;
            } else {
                $dew_temp_point = 10.7;
            }
            $calucl_tem_out_houses = $row['calucl_tem_out_houses'];
            $duration_heating_house = $row['duration_heating_house'];
            $deegre_day_civil = $row['deegre_day_civil'];
            $zone_ab = $row['zone_ab'];
            $cold_mid_hum_month = $row['cold_mid_hum_month'];
        }
    }

    echo json_encode(
     array("city_temp_in" => "$city_temp_in",
         "vapor" => "$vapor",
         "dew_temp_point" => "$dew_temp_point",
         "calucl_tem_out_most_cold_5_day" => "$calucl_tem_out_most_cold_5_day",
         "calucl_tem_out_houses" => "$calucl_tem_out_houses",
         "duration_heating_house" => "$duration_heating_house",
         "deegre_day_civil" => "$deegre_day_civil",
         "zone_ab" => "$zone_ab",
         "cold_mid_hum_month" => "$cold_mid_hum_month")
);
}

if(isset($_POST["mat_id"]) && !empty($_POST["mat_id"])) {

    $query = $db->query("SELECT dry_density, cal_coef_therm_cond_b, cal_coef_therm_cond_a, dry_therm_cond, dry_spec_heat,calc_wat_in_mater_a, calc_wat_in_mater_b, cal_coef_vapor,therm_res_calc FROM goods WHERE id =" . $_POST['mat_id']);
    if(isset($_POST["city_zone_ab"]) && !empty($_POST["city_zone_ab"])) {
        $zone_ab = $_POST["city_zone_ab"];
    }
    // готовим данные из инпута.
    // TODO: Добавить обновление всего по изменении инпута
    $mat_depth[]="";
    for ($i=1;$i<=9;$i++){
        if (isset($_POST["mat_depth_" . $i]) && !empty($_POST["mat_depth_" . $i])) {
            $mat_depth[$i-1]=$_POST["mat_depth_" . $i];
        }
    }
    while ($row = $query->fetch_assoc()) {
        $dry_density=round($row['dry_density'],2);
        $cal_coef_therm_cond=round($row['cal_coef_therm_cond_a'],2);
        if ($zone_ab == "A") {
            $area = round(0.27 * sqrt($row['dry_therm_cond'] * $dry_density * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_a']),2);
        } else {
            $area = round( 0.27 * sqrt($row['dry_therm_cond'] * $dry_density * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_b']),2);
        }
        $cal_coef_vapor=round($row['cal_coef_vapor'],2);
        $therm_res_calc=round($row['therm_res_calc'],2);
        if ($therm_res_calc == 0 ) {
            $therm_res_calc=round($_POST["mat_depth_cur"]/1000/$cal_coef_therm_cond,3);
        }
        $d=round($area * $therm_res_calc,2);
        $d1dn=round($d,2);

        if($d >= 1) {
            $y=round($d);
        } else{
            $y=round(($therm_res_calc*($area^2)+8.7)/(1+$area*8.7),2);
        }
        if ($zone_ab == "A") {
            $dw = round($row['calc_wat_in_mater_a'],2);
        } else {
            $dw = round($row['calc_wat_in_mater_b'],2);
        }
        $therm_lag=0;
        foreach ($mat_depth as $item) {
            $therm_lag=round($therm_lag+(($item/1000)/$area)*$cal_coef_vapor,2);
        }
        if(isset($_POST["city_temp_in"]) && !empty($_POST["city_temp_in"]) && isset($_POST["city_temp_out"]) && !empty($_POST["city_temp_out"])) {
            $surface_temp = $_POST["city_temp_in"] - ($_POST["city_temp_in"]-$_POST["city_temp_out"]/(1)); // add (G18*C17)
        }

    }
    // готовим массивы с данными со страницы
    $mat_r[]="";
    $mat_l[]="";
    for ($i=1;$i<=9;$i++){
        if (isset($_POST["mat_r_" . $i]) && !empty($_POST["mat_r_" . $i])) {
            $mat_r[$i-1]=$_POST["mat_r_" . $i];
        }
        if (isset($_POST["mat_l_" . $i]) && !empty($_POST["mat_l_" . $i])) {
            $mat_l[$i-1]=$_POST["mat_l_" . $i];
        }
    }
    // вычисления сумм элементов
    $summ_depth=0;
    foreach ($mat_depth as $item){
        $summ_depth=$summ_depth+$item;
    }

    $summ_r=0;
    if (count($mat_r) > 0 ) {
        foreach ($mat_r as $item) {
            $summ_r = $summ_r + $item;
        }
    }

    if ($_POST['mat_r_cur'] == 0) {
        $summ_r=$summ_r+$therm_res_calc;
    }
    $summ_r=$summ_r + 0.158;

    if (count($mat_l) > 0 and count($mat_depth) > 0) {

        foreach ($mat_l as $item) {
            $rcon = $rcon + $item;
        }
    }



    echo json_encode(
        array(
            "dry_density" => "$dry_density",
            "cal_coef_therm_cond" => "$cal_coef_therm_cond",
            "area" => "$area",
            "cal_coef_vapor" => "$cal_coef_vapor",
            "therm_res_calc" => "$therm_res_calc",
            "d" => "$d",
            "d1dn" => "$d1dn",
            "y" => "$y",
            "dw" => "$dw",
            "summ" => "$summ_depth",
            "therm_lag" => "$therm_lag",
            "surface_temp" => "$surface_temp",
            "summ_r" => "$summ_r")
    );
}

if(isset($_POST["blocktype_id"]) && !empty($_POST["blocktype_id"]))
{
    $query = $db->query("SELECT coef_heat, coef_heap_cond, n, diff FROM block_types WHERE id =" . $_POST['blocktype_id']);

    while ($row = $query->fetch_assoc()){
        $coef_heat=$row['coef_heat']  ;
        $coef_heap_cond=$row['coef_heap_cond'];
        $n = $row['n'];
        $diff=$row['diff'];
    }
    echo json_encode(
        array(
            "coef_heat" => "$coef_heat",
            "coef_heap_cond" => "$coef_heap_cond",
            "n" => "$n",
            "diff" => "$diff",
            )
    );
}

if(isset($_POST["blockcons_id"]) && !empty($_POST["blockcons_id"]))
{
    $query = $db->query("SELECT ratio FROM uniformity WHERE id =" . $_POST['blockcons_id']);

    while ($row = $query->fetch_assoc()){
        $ratio=round($row['ratio'],2);
    }
    echo json_encode(
        array(
            "ratio" => "$ratio"
        )
    );
}
?>