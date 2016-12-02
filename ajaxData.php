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
    //setcookie("city_temp_in", $city_temp_in );

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

    $query = $db->query("SELECT dry_density, cal_coef_therm_cond_b, cal_coef_therm_cond_a, dry_therm_cond, dry_spec_heat,calc_wat_in_mater_a, calc_wat_in_mater_b, cal_coef_vapor,therm_res_calc, is_izol FROM goods WHERE id =" . $_POST['mat_id']);
    if(isset($_POST["city_zone_ab"]) && !empty($_POST["city_zone_ab"])) {
        $zone_ab = $_POST["city_zone_ab"];
    }
    // готовим данные из инпута.
    // TODO: Добавить обновление всего по изменении инпута
	
	/* @@@@@@@@@@@@@@@@@@@@@ MAX @@@@@@@@@@@@@@@@@@@@ */
    $mat_depth = array();
	if(isset($_POST["mat_depth"]))
	{
		foreach($_POST["mat_depth"] as $i => $value)
		{
			$mat_depth[$i-1] = $value;
		}
	}
	
    // for ($i=1;$i<=9;$i++){
        // if (isset($_POST["mat_depth_" . $i]) && !empty($_POST["mat_depth_" . $i])) {
            // $mat_depth[$i-1]=$_POST["mat_depth_" . $i];
        // }
    // }
	
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
    while ($row = $query->fetch_assoc()) {
        $dry_density=$row['dry_density'];
        $cal_coef_therm_cond=$row['cal_coef_therm_cond_a'];
        if ($zone_ab == "A") {
            $area = 0.27 * sqrt($row['dry_therm_cond'] * $dry_density * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_a']);
        } else
        {
            $area =  0.27 * sqrt($row['dry_therm_cond'] * $dry_density * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_b']);
        }
        $cal_coef_vapor=$row['cal_coef_vapor'];
        $therm_res_calc=$row['therm_res_calc'];
        if ($therm_res_calc == 0 ) {
            $therm_res_calc=round($_POST["mat_depth_cur"]/1000/$cal_coef_therm_cond,3);
        }
        $d=$area * $therm_res_calc;
        $d1dn=$d;

        if($d >= 1) {
            $y=$d;
        } else{
            $y=($therm_res_calc*($area^2)+8.7)/(1+$area*8.7);
        }
        if ($zone_ab == "A") {
            $dw = $row['calc_wat_in_mater_a'];
        } else {
            $dw = $row['calc_wat_in_mater_b'];
        }
        $therm_lag=0;
        foreach ($mat_depth as $item) {
            $therm_lag=$therm_lag+(($item/1000)/$cal_coef_therm_cond)*$area;
        }
        $therm_lag=round($therm_lag,2);
        if(isset($_POST["city_temp_in"]) && !empty($_POST["city_temp_in"]) && isset($_POST["city_temp_out"]) && !empty($_POST["city_temp_out"])) {
            $surface_temp = $_POST["city_temp_in"] - ($_POST["city_temp_in"]-$_POST["city_temp_out"]/(1)); // add (G18*C17)
        }
        $is_izol=$row['is_izol'];

    }
    // готовим массивы с данными со страницы
    $mat_r[]=array();
    $mat_l[]=array();

    if(isset($_POST["mat_r"]))
    {
        foreach($_POST["mat_r"] as $i => $value)
        {
            $mat_r[$i-1] = $value;
        }
    }
    if(isset($_POST["mat_l"]))
    {
        foreach($_POST["mat_l"] as $i => $value)
        {
            $mat_l[$i-1] = $value;
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

    //TODO: учесть все все считаеться только для слоев изоляции

    $rcon=0;
    if (count($mat_l) > 0 and count($mat_depth) > 0) {
        $min_val = min(count($mat_l), count($mat_depth));
        for ($i = 0; $i <= $min_val; $i++) {
            if ($mat_l[$i] != 0) {
                $rcon = $rcon + $mat_depth[$i] / $mat_l[$i] ;
            }
        }
        $rcon=$rcon/1000;
    }

    $dry_density=round($dry_density,2);
    $cal_coef_therm_cond=round($cal_coef_therm_cond,2);
    $area=round($area,2);
    $cal_coef_vapor=round($cal_coef_vapor,2);
    $therm_res_calc=round($therm_res_calc,2);
    $d=round($d,2);
    $d1dn=round($d1dn,2);
    $y=round($y,2);
    $dw=round($dw,2);
    $summ_depth=round($summ_depth,2);
    $therm_lag=round($therm_lag,2);
    $surface_temp=round($surface_temp,2);
    $summ_r=round($summ_r,2);
    $rcon=round($rcon,2);

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
            "summ_r" => "$summ_r",
            "rcon" => "$rcon")
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