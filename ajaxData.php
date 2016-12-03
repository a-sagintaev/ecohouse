<?php
//Include database configuration file
include('dbConfig.php');
$db->query("SET NAMES utf8");
session_start();


if (isset($_POST["city_id"]) && !empty($_POST["city_id"])) {
    //Get all city data. All of this is one time collectiong, only on city change

    $_SESSION['city_temp_in'] = "";
    $_SESSION['vapor'] = "";
    $_SESSION['calucl_tem_out_most_cold_5_day'] = "";
    $_SESSION['dew_temp_point'] = "";
    $_SESSION['calucl_tem_out_houses'] = "";

    $query = $db->query("SELECT calucl_tem_out_most_cold_5_day,calucl_tem_out_houses,is_region,duration_heating_house,deegre_day_civil,zone_ab,cold_mid_hum_month FROM cities WHERE id =" . $_POST['city_id']);

    while ($row = $query->fetch_assoc()) {
        if ($row['is_region'] == 0) {
            if ($row['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $_SESSION['city_temp_in'] = 21;
            } else {
                $_SESSION['city_temp_in'] = 20;
            }
            $_SESSION['vapor'] = 55;
            $_SESSION['calucl_tem_out_most_cold_5_day'] = $row['calucl_tem_out_most_cold_5_day'];
            if ($row['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $_SESSION['dew_temp_point'] = 11.6;
            } else {
                $_SESSION['dew_temp_point'] = 10.7;
            }
            $_SESSION['calucl_tem_out_houses'] = $row['calucl_tem_out_houses'];
            $_SESSION['duration_heating_house'] = $row['duration_heating_house'];
            $_SESSION['deegre_day_civil'] = $row['deegre_day_civil'];
            $_SESSION['zone_ab'] = $row['zone_ab'];
            $_SESSION['cold_mid_hum_month'] = $row['cold_mid_hum_month'];
        }
    }

    // don't know if its right, but preparing data for json
    $city_temp_in = round($_SESSION['city_temp_in'], 2);
    $vapor = round($_SESSION['vapor'], 2);
    $dew_temp_point = round($_SESSION['dew_temp_point'], 2);
    $calucl_tem_out_most_cold_5_day = round($_SESSION['calucl_tem_out_most_cold_5_day'], 2);
    $calucl_tem_out_houses = round($_SESSION['calucl_tem_out_houses'], 2);
    $duration_heating_house = round($_SESSION['duration_heating_house'], 2);
    $deegre_day_civil = round($_SESSION['deegre_day_civil'], 2);
    $zone_ab = $_SESSION['zone_ab'];
    $cold_mid_hum_month = round($_SESSION['cold_mid_hum_month'], 2);

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

if (isset($_POST["mat_id"]) && !empty($_POST["mat_id"])) {
    if (isset($_POST["blockID"])) {
        // Works on changing something in blocks.
        $blockIDvar = $_POST["blockID"] - 1;

        $query = $db->query("SELECT dry_density, cal_coef_therm_cond_b, cal_coef_therm_cond_a, dry_therm_cond, dry_spec_heat,calc_wat_in_mater_a, calc_wat_in_mater_b, cal_coef_vapor,therm_res_calc, is_izol FROM goods WHERE id =" . $_POST['mat_id']);

        $mat_depth = array();
        if (isset($_POST["mat_depth"])) {
            foreach ($_POST["mat_depth"] as $i => $value) {
                $mat_depth[$i - 1] = $value;
            }
        }

        if (!isset($_SESSION['dry_density'])) {
            $_SESSION['dry_density'] = array();
        }
        if (!isset($_SESSION['cal_coef_therm_cond'])) {
            $_SESSION['cal_coef_therm_cond'] = array();
        }
        if (!isset($_SESSION['area'])) {
            $_SESSION['area'] = array();
        }
        if (!isset($_SESSION['cal_coef_vapor'])) {
            $_SESSION['cal_coef_vapor'] = array();
        }
        if (!isset($_SESSION['therm_res_calc'])) {
            $_SESSION['therm_res_calc'] = array();
        }
        if (!isset($_SESSION['d'])) {
            $_SESSION['d'] = array();
        }
        if (!isset($_SESSION['d1dn'])) {
            $_SESSION['d1dn'] = array();
        }
        if (!isset($_SESSION['y'])) {
            $_SESSION['y'] = array();
        }
        if (!isset($_SESSION['dw'])) {
            $_SESSION['dw'] = array();
        }
        if (!isset($_SESSION['is_izol'])) {
            $_SESSION['is_izol'] = array();
        }


        while ($row = $query->fetch_assoc()) {
            $_SESSION['dry_density'][$blockIDvar] = $row['dry_density'];
            $_SESSION['cal_coef_therm_cond'][$blockIDvar] = $row['cal_coef_therm_cond_a'];
            if ($_SESSION['zone_ab'] == "A") {
                $_SESSION['area'][$blockIDvar] = 0.27 * sqrt($row['dry_therm_cond'] * $_SESSION['dry_density'][$blockIDvar] * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_a']);
            } else {
                $_SESSION['area'][$blockIDvar] = 0.27 * sqrt($row['dry_therm_cond'] * $_SESSION['dry_density'][$blockIDvar] * $row['dry_spec_heat'] - 0.0419 * $row['calc_wat_in_mater_b']);
            }
            $_SESSION['cal_coef_vapor'][$blockIDvar] = $row['cal_coef_vapor'];
            $_SESSION['therm_res_calc'][$blockIDvar] = $row['therm_res_calc'];
            if ($_SESSION['therm_res_calc'][$blockIDvar] == 0) {
                $_SESSION['therm_res_calc'][$blockIDvar] = $mat_depth[$blockIDvar] / 1000 / $_SESSION['cal_coef_therm_cond'][$blockIDvar];
            }
            $_SESSION['d'][$blockIDvar] = $_SESSION['area'][$blockIDvar] * $_SESSION['therm_res_calc'][$blockIDvar];
            if (!$blockIDvar == 0) {
                $_SESSION['d1dn'][$blockIDvar] = $_SESSION['d'][$blockIDvar] + $_SESSION['d1dn'][$blockIDvar - 1];
            } else {
                $_SESSION['d1dn'][$blockIDvar] = $_SESSION['d'][$blockIDvar];
            }
            if ($_SESSION['d'][$blockIDvar] >= 1) {
                $_SESSION['y'][$blockIDvar] = $_SESSION['d'][$blockIDvar];
            } else {
                $_SESSION['y'][$blockIDvar] = ($_SESSION['therm_res_calc'][$blockIDvar] * ($_SESSION['area'][$blockIDvar] ^ 2) + 8.7) / (1 + $_SESSION['area'][$blockIDvar] * 8.7);
            }
            if ($_SESSION['zone_ab'] == "A") {
                $_SESSION['dw'][$blockIDvar] = $row['calc_wat_in_mater_a'];
            } else {
                $_SESSION['dw'][$blockIDvar] = $row['calc_wat_in_mater_b'];
            }
            $_SESSION['therm_lag'] = 0;
            foreach ($mat_depth as $item) {
                $_SESSION['therm_lag'] = $_SESSION['therm_lag'] + (($item / 1000) / $_SESSION['cal_coef_therm_cond'][$blockIDvar]) * $_SESSION['area'][$blockIDvar];
            }

            $_SESSION['surface_temp'] = $_SESSION['city_temp_in'] - ($_SESSION['city_temp_in'] - $_SESSION['calucl_tem_out_houses'] / ($_SESSION['city_temp_in'] * $_SESSION['coef_heat']));

            $_SESSION['is_izol'][$blockIDvar] = $row['is_izol'];

        }

        // вычисления сумм элементов
        $summ_depth = 0;
        foreach ($mat_depth as $item) {
            $summ_depth = $summ_depth + $item;
        }

        $summ_r = 0;
        foreach ($_SESSION['therm_res_calc'] as $item) {
            $summ_r = $summ_r + $item;
        }
        $summ_r = $summ_r + 0.158;

        //TODO: учесть все все считаеться только для слоев изоляции

        $rcon = 0;
        foreach ($_SESSION['therm_res_calc'] as $i => $item) {
            if ($_SESSION['is_izol'][$i] == 1) {
                $rcon = $rcon + ($mat_depth[$i] / $item);
            }
        }
        $rcon = $rcon / 1000;

        $dry_density = round($_SESSION['dry_density'][$blockIDvar], 3);
        $cal_coef_therm_cond = round($_SESSION['cal_coef_therm_cond'][$blockIDvar], 3);
        $area = round($_SESSION['area'][$blockIDvar], 3);
        $cal_coef_vapor = round($_SESSION['cal_coef_vapor'][$blockIDvar], 3);
        $therm_res_calc = round($_SESSION['therm_res_calc'][$blockIDvar], 3);
        $d = round($_SESSION['d'][$blockIDvar], 3);
        $d1dn = round($_SESSION['d1dn'][$blockIDvar], 3);
        $y = round($_SESSION['y'][$blockIDvar], 3);
        $dw = round($_SESSION['dw'][$blockIDvar], 3);
        $therm_lag = round($_SESSION['therm_lag'], 3);
        $surface_temp = round($_SESSION['surface_temp'], 3);
        $summ_depth = round($summ_depth, 3);
        $summ_r = round($summ_r, 3);
        $rcon = round($rcon, 3);

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
}

if (isset($_POST["blocktype_id"]) && !empty($_POST["blocktype_id"])) {
    $query = $db->query("SELECT coef_heat, coef_heap_cond, n, diff FROM block_types WHERE id =" . $_POST['blocktype_id']);

    while ($row = $query->fetch_assoc()) {
        $_SESSION['coef_heat'] = $row['coef_heat'];
        $_SESSION['coef_heap_cond'] = $row['coef_heap_cond'];
        $_SESSION['n'] = $row['n'];
        $_SESSION['diff'] = $row['diff'];
    }

    $coef_heat = round($_SESSION['coef_heat'], 3);
    $coef_heap_cond = round($_SESSION['coef_heap_cond'], 3);
    $n = round($_SESSION['n'], 3);
    $diff = round($_SESSION['n'], 3);

    echo json_encode(
        array(
            "coef_heat" => "$coef_heat",
            "coef_heap_cond" => "$coef_heap_cond",
            "n" => "$n",
            "diff" => "$diff",
        )
    );
}

if (isset($_POST["blockcons_id"]) && !empty($_POST["blockcons_id"])) {
    $query = $db->query("SELECT ratio FROM uniformity WHERE id =" . $_POST['blockcons_id']);

    while ($row = $query->fetch_assoc()) {
        $_SESSION['ratio'] = $row['ratio'];
    }
    $ratio = round($_SESSION['ratio'], 3);
    echo json_encode(
        array(
            "ratio" => "$ratio"
        )
    );
}
?>