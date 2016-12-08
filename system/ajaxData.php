<?php
// 01. GENERATING CITY DATA

// проверяем есть ли $_POST
if (isset($_POST["city_id"]) && !empty($_POST["city_id"]))
{
    // задаем переменную текущего запроса
    $city_id = $_POST["city_id"];

    // создаем или перезаписываем сессию
    $_SESSION['city_id'] = $city_id;
}

// поста нет, но есть сессия, собираем данные
elseif(isset($_SESSION['city_id'])  && !empty($_SESSION['city_id']))
{
    $city_id = $_SESSION['city_id'];
}

if(isset($city_id))
{
    // экранируем запрос
    $city_id = addslashes($city_id);
    $arCitiesData = $db->getArray("SELECT * FROM cities WHERE id = '".$city_id."'");

    if(!empty($arCitiesData))
    {
        $arCity = current($arCitiesData);
        $arCityData = array();

        if ($arCity['is_region'] == 0) {
            if ($arCity['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $arCityData['city_temp_in'] = 21;
            } else {
                $arCityData['city_temp_in'] = 20;
            }
            $arCityData['vapor'] = 55;
            $arCityData['calucl_tem_out_most_cold_5_day'] = $arCity['calucl_tem_out_most_cold_5_day'];
            if ($arCity['calucl_tem_out_most_cold_5_day'] <= "-31") {
                $arCityData['dew_temp_point'] = 11.6;
            } else {
                $arCityData['dew_temp_point'] = 10.7;
            }

            $arCityData['calucl_tem_out_houses'] = $arCity['calucl_tem_out_houses'];
            $arCityData['duration_heating_house'] = $arCity['duration_heating_house'];
            $arCityData['deegre_day_houses'] = $arCity['deegre_day_houses'];
            $arCityData['zone_ab'] = $arCity['zone_ab'];
            $arCityData['cold_mid_hum_month'] = $arCity['cold_mid_hum_month'];

            if(isset($_POST["city_id"]))
                response_json($arCityData);
        }
    }
}

if (isset($_POST["blocktype_id"]) && !empty($_POST["blocktype_id"]))
{
    // задаем переменную текущего запроса
    $blocktype_id = $_POST["blocktype_id"];

    // создаем или перезаписываем сессию
    $_SESSION['blocktype_id'] = $blocktype_id;
}

// поста нет, но есть сессия, собираем данные
elseif(isset($_SESSION['blocktype_id'])  && !empty($_SESSION['blocktype_id']))
{
    $blocktype_id = $_SESSION['blocktype_id'];
}

if (isset($blocktype_id)) {
    $blocktype_id=addslashes($blocktype_id);
    $arBlockTypeData = $db->getArray("SELECT coef_heat, coef_heap_cond, n, diff FROM block_types WHERE id ='".$blocktype_id."'");

    if (!empty($arBlockTypeData)) {
        $arBlockType = current($arBlockTypeData);
        $arBlockTypeData = array();

        $arBlockTypeData['coef_heat'] = $arBlockType['coef_heat'];
        $arBlockTypeData['coef_heap_cond'] = $arBlockType['coef_heap_cond'];
        $arBlockTypeData['n'] = $arBlockType['n'];
        $arBlockTypeData['diff'] = $arBlockType['diff'];

        $arBlockTypeData['coef_heat'] = round($arBlockTypeData['coef_heat'], 3);
        $arBlockTypeData['coef_heap_cond'] = round($arBlockTypeData['coef_heap_cond'], 3);
        $arBlockTypeData['n'] = round($arBlockTypeData['n'], 3);
        $arBlockTypeData['n'] = round($arBlockTypeData['n'], 3);
    }

    if(isset($_POST["blocktype_id"]))
        response_json($arBlockTypeData);
}



if (isset($_POST["blockcons_id"]) && !empty($_POST["blockcons_id"]))
{
    // задаем переменную текущего запроса
    $blockcons_id = $_POST["blockcons_id"];

    // создаем или перезаписываем сессию
    $_SESSION['blockcons_id'] = $blockcons_id;
}

// поста нет, но есть сессия, собираем данные
elseif(isset($_SESSION['blockcons_id'])  && !empty($_SESSION['blockcons_id']))
{
    $blockcons_id = $_SESSION['blockcons_id'];
}

if (isset($blockcons_id)) {
    $blockcons_id=addslashes($blockcons_id);
    $arBlockConsData = $db->getArray("SELECT ratio FROM uniformity WHERE id ='".$blockcons_id."'");

    if (!empty($arBlockConsData)) {
        $arBlockCons = current($arBlockConsData);
        $arBlockConsData = array();

        $arBlockConsData['ratio'] = $arBlockCons['ratio'];

        $arBlockConsData['ratio'] = round($arBlockConsData['ratio'], 3);
        if (isset($_POST["blockcons_id"])) {
            response_json($arBlockConsData);
        }
    }
}


if (isset($_POST["mat_id"]) && !empty($_POST["mat_id"]) && isset($_POST["block_id"]) && !empty($_POST["block_id"])) {
    // задаем переменную текущего запроса
    $mat_id = $_POST["mat_id"];
    $block_id = $_POST["block_id"];

    // создаем или перезаписываем сессию
    $_SESSION['mat_id'][$block_id] = $mat_id;
} elseif (isset($_SESSION['mat_id'][$_POST["block_id"]]))
{
    $mat_id = $_SESSION['mat_id'][$_POST["block_id"]];
    $block_id = $_POST["block_id"];
}


if (isset($mat_id) && isset($block_id)) {

    // Works on changing something in blocks.
    $block_id_var = $block_id - 1;
    $mat_id = addslashes($mat_id);

    $mat_depth = array();
    if (isset($_POST["mat_depth"])) {
        foreach ($_POST["mat_depth"] as $i => $value) {
            $mat_depth[$i]  = $value;
        }
    }
    $arBlockData = $db->getArray("SELECT dry_density, cal_coef_therm_cond_b, cal_coef_therm_cond_a, dry_therm_cond, dry_spec_heat,calc_wat_in_mater_a, calc_wat_in_mater_b, cal_coef_vapor,therm_res_calc, is_izol FROM goods WHERE id ='" . $mat_id . "'");


    /*      if (!isset($_SESSION['dry_density'])) {
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
  */

    if (!empty($arBlockData)) {
        $arBlock = current($arBlockData);
        $arBlockData = array();

        $arBlockData['dry_density'] = $arBlock['dry_density'];
        $arBlockData['cal_coef_therm_cond'] = $arBlock['cal_coef_therm_cond_a'];
        if($arBlock['cal_coef_therm_cond_a'] <> null) {
            $_SESSION['cal_coef_therm_cond'][$block_id_var] = $arBlockData['cal_coef_therm_cond'];
        } else {
            $_SESSION['cal_coef_therm_cond'][$block_id_var]=1;
        }
        if ($arCityData['zone_ab'] == "A") {
            $arBlockData['area'] = 0.27 * sqrt($arBlock['dry_therm_cond'] * $arBlockData['dry_density'] * ($arBlock['dry_spec_heat'] - 0.0419 * $arBlock['calc_wat_in_mater_a']));
        } else {
            $arBlockData['area'] = 0.27 * sqrt($arBlock['dry_therm_cond'] * $arBlockData['dry_density'] * ($arBlock['dry_spec_heat'] - 0.0419 * $arBlock['calc_wat_in_mater_b']));
        }
        $_SESSION['area'][$block_id_var]=$arBlockData['area'];
        $arBlockData['cal_coef_vapor'] = $arBlock['cal_coef_vapor'];
        $arBlockData['therm_res_calc'] = $arBlock['therm_res_calc'];

        if ($arBlockData['therm_res_calc'] == 0) {
            $arBlockData['therm_res_calc'] = $mat_depth[$block_id_var] / 1000 / $arBlockData['cal_coef_therm_cond'];
        }
        $_SESSION['therm_res_calc'][$block_id_var]=$arBlockData['therm_res_calc'];
        $arBlockData['d'] = $arBlockData['area'] * $arBlockData['therm_res_calc'];
        if (!$block_id_var == 0) {
            $arBlockData['d1dn'] = $arBlockData['d'] + $_SESSION['d1dn'][$block_id_var - 1]; // check
        } else {
            $arBlockData['d1dn'] = $arBlockData['d'];
        }
        $_SESSION['d1dn'][$block_id_var]=$arBlockData['d1dn'];
        if ($arBlockData['d'] >= 1) {
            $arBlockData['y'] = $arBlockData['area'];
        } else {
            $arBlockData['y'] = ($arBlockData['therm_res_calc'] * ($arBlockData['area']*$arBlockData['area']) + 8.7) / (1 + $arBlockData['therm_res_calc'] * 8.7);
        }
        if ($arCityData['zone_ab'] == "A") {
            $arBlockData['dw'] = $arBlock['calc_wat_in_mater_a'];
        } else {
            $arBlockData['dw'] = $arBlock['calc_wat_in_mater_b'];
        }
        $arBlockData['therm_lag'] = 0;
        foreach ($mat_depth as $j => $item) {
            if (!empty($item)) {
                $arBlockData['therm_lag'] = $arBlockData['therm_lag'] + (($item / 1000) / $_SESSION['cal_coef_therm_cond'][$j]) * $_SESSION['area'][$j];
            }
        }
        $arBlockData['surface_temp'] = $arCityData['city_temp_in'] - (($arCityData['city_temp_in'] - $arCityData['calucl_tem_out_houses']) / ($arCityData['city_temp_in'] * $arBlockTypeData['coef_heat']));
    }

    // вычисления сумм элементов
    $arBlockData['summ_depth'] = 0;
    foreach ($mat_depth as $item) {
        $arBlockData['summ_depth'] = $arBlockData['summ_depth'] + $item;
    }

    $arBlockData['summ_r'] = 0;
    $arBlockData['rcon'] = 0;
    foreach ($_SESSION['mat_id'] as $i => $item) {

        $arBlockData['summ_r'] = $arBlockData['summ_r'] + $_SESSION['therm_res_calc'][$i-1];

        $item = addslashes($item);
        $mat_r = $db->getArray("SELECT is_izol FROM goods WHERE id ='" . $item . "'");
        if (!empty($mat_r)) {
            if ($mat_r[0]['is_izol'] === "0" && $_SESSION['therm_res_calc'][$i-1] <> 0) {
                $arBlockData['rcon'] = $arBlockData['rcon'] + ($mat_depth[$i-1] / $_SESSION['therm_res_calc'][$i-1]);
            }
        }
    }

    $arBlockData['summ_r'] = $arBlockData['summ_r'] + 0.158;
    $arBlockData['rcon'] = $arBlockData['rcon'] / 1000;



    $arBlockData['dry_density'] = round($arBlockData['dry_density'], 3);
    $arBlockData['cal_coef_therm_cond'] = round($arBlockData['cal_coef_therm_cond'], 3);
    $arBlockData['area'] = round($arBlockData['area'], 3);
    $arBlockData['cal_coef_vapor'] = round($arBlockData['cal_coef_vapor'], 3);
    $arBlockData['therm_res_calc'] = round($arBlockData['therm_res_calc'], 3);
    $arBlockData['d'] = round($arBlockData['d'], 3);
    $arBlockData['d1dn'] = round($arBlockData['d1dn'], 3);
    $arBlockData['y'] = round($arBlockData['y'], 3);
    $arBlockData['dw'] = round($arBlockData['dw'], 3);
    $arBlockData['therm_lag'] = round($arBlockData['therm_lag'], 3);
    $arBlockData['surface_temp'] = round($arBlockData['surface_temp'], 3);
    $arBlockData['summ_depth'] = round($arBlockData['summ_depth'], 3);
    $arBlockData['summ_r'] = round($arBlockData['summ_r'], 3);
    $arBlockData['rcon'] = round($arBlockData['rcon'], 3);

    if (isset($_POST["mat_id"]) && !empty($_POST["mat_id"]) && isset($_POST["block_id"]) && !empty($_POST["block_id"])) {
        response_json($arBlockData);
    }
}


?>