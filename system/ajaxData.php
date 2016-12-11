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
    $arBlockTypeData = $db->getArray("SELECT * FROM block_types WHERE id ='".$blocktype_id."'");

    if (!empty($arBlockTypeData)) {
        $arBlockType = current($arBlockTypeData);
        $arBlockTypeData = array();

        $arBlockTypeData['coef_heat'] = $arBlockType['coef_heat'];
        $arBlockTypeData['coef_heap_cond'] = $arBlockType['coef_heap_cond'];
        $arBlockTypeData['n'] = $arBlockType['n'];
        $arBlockTypeData['diff'] = $arBlockType['diff'];
        $arBlockTypeData['r0_min1'] = $arBlockType['r0_min1'];
        $arBlockTypeData['a'] = $arBlockType['a'];
        $arBlockTypeData['b'] = $arBlockType['b'];

        $arBlockTypeData['coef_heat'] = round($arBlockTypeData['coef_heat'], 3);
        $arBlockTypeData['coef_heap_cond'] = round($arBlockTypeData['coef_heap_cond'], 3);
        $arBlockTypeData['n'] = round($arBlockTypeData['n'], 3);
        $arBlockTypeData['diff'] = round($arBlockTypeData['diff'], 3);
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
} elseif (isset($_POST["block_id"]) && !empty($_POST["block_id"]))
    {
    if (isset($_SESSION['mat_id'][$_POST["block_id"]])) {
        $mat_id = $_SESSION['mat_id'][$_POST["block_id"]];
        $block_id = $_POST["block_id"];
    }
}

if (isset($mat_id) && isset($block_id)) {

    // Works on changing something in blocks.
    // general tab + termo
    $block_id_var = $block_id - 1;
    $mat_id = addslashes($mat_id);

    $mat_depth = get_mat_depth();


    $arBlockData = $db->getArray("SELECT dry_density, cal_coef_therm_cond_b, cal_coef_therm_cond_a, dry_therm_cond, dry_spec_heat,calc_wat_in_mater_a, calc_wat_in_mater_b, cal_coef_vapor,therm_res_calc, is_izol FROM goods WHERE id ='" . $mat_id . "'");


    if (!empty($arBlockData)) {
        $arBlock = current($arBlockData);
        $arBlockData = array();


        $arBlockData['dry_density'] = $arBlock['dry_density'];
        if($arCityData['zone_ab'] == "A") {
            $arBlockData['cal_coef_therm_cond'] = $arBlock['cal_coef_therm_cond_a'];
        } else {
            $arBlockData['cal_coef_therm_cond'] = $arBlock['cal_coef_therm_cond_b'];
        }

        $_SESSION['cal_coef_therm_cond'][$block_id_var] = $arBlockData['cal_coef_therm_cond'];

        if ($arCityData['zone_ab'] == "A") {
            $arBlockData['area'] = 0.27 * sqrt($arBlock['dry_therm_cond'] * $arBlockData['dry_density'] * ($arBlock['dry_spec_heat'] - 0.0419 * $arBlock['calc_wat_in_mater_a']));
        } else {
            $arBlockData['area'] = 0.27 * sqrt($arBlock['dry_therm_cond'] * $arBlockData['dry_density'] * ($arBlock['dry_spec_heat'] - 0.0419 * $arBlock['calc_wat_in_mater_b']));
        }
        $_SESSION['area'][$block_id_var]=$arBlockData['area'];
        $arBlockData['cal_coef_vapor'] = $arBlock['cal_coef_vapor'];
        $_SESSION['cal_coef_vapor'][$block_id_var]=$arBlockData['cal_coef_vapor'];
        $arBlockData['therm_res_calc'] = $arBlock['therm_res_calc'];

        if ($arBlockData['therm_res_calc'] == 0 && $arBlockData['cal_coef_therm_cond'] <> null) {
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
            if (!empty($item && $_SESSION['cal_coef_therm_cond'][$j] <> null)) {
                $arBlockData['therm_lag'] = $arBlockData['therm_lag'] + (($item / 1000) / $_SESSION['cal_coef_therm_cond'][$j]) * $_SESSION['area'][$j];
            }
        }

    }

    // calculating depth of all layers
    $arBlockData['summ_depth'] = array_sum($mat_depth);


    $arBlockData['summ_r'] = 0;
    $arBlockData['r_con'] = 0;
    $arBlockData['izol_summ']=0;
    $arBlockData['izol_summ_fact']=0;

    $arBlockData['summ_r'] = get_summ_r();
    $arRCon=get_r_con();
    $arBlockData['izol_summ_fact']=$arRCon['izol_summ_fact'];
    $arBlockData['r_con']=$arRCon['r_con'];

    $arBlockData['r_con'] = $arBlockData['r_con'] / 1000;
    $arBlockData['r_usl']=$arBlockData['summ_r'];
    $arBlockData['r_prev']=$arBlockData['r_usl']*$arBlockConsData['ratio'];
    $arBlockData['surface_temp'] = $arCityData['city_temp_in'] - (($arCityData['city_temp_in'] - $arCityData['calucl_tem_out_most_cold_5_day']) / ($arBlockTypeData['coef_heat'] * $arBlockData['r_usl']));

    if(isset($arBlockTypeData['r0_min1']) && $arBlockTypeData['r0_min1'] == "1") {
        $arBlockData['r0_min1']=$arBlockTypeData['coef_heat']*($arCityData['city_temp_in']-$arCityData['calucl_tem_out_most_cold_5_day'])/($arBlockTypeData['diff']*$arBlockTypeData['coef_heat']);
    }
    $arBlockData['r0_min2'] = $arBlockTypeData['a']*$arCityData['deegre_day_houses'] +$arBlockTypeData['b'];
    $_SESSION['r0_min2']=$arBlockData['r0_min2'];
    $max_izol_cal_coef_therm_cond=get_max_izol_cal_coef_therm_cond();
    $arBlockData['izol_depth_formula'] = (($arBlockData['r0_min2']/$arBlockConsData['ratio'])-$arBlockData['r_con']-(1/$arBlockTypeData['coef_heat'])- (1/$arBlockTypeData['coef_heap_cond']))*$max_izol_cal_coef_therm_cond;

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
    $arBlockData['r_con'] = round($arBlockData['r_con'], 3);
    $arBlockData['r_usl'] = $arBlockData['summ_r'];
    $arBlockData['r_prev']=round($arBlockData['r_prev'],3);
    $arBlockData['izol_depth_formula'] = round($arBlockData['izol_depth_formula'],3);

    if(isset($arBlockData['r0_min1']) && !empty($arBlockData['r0_min1'])) {
        $arBlockData['r0_min1'] = round($arBlockData['r0_min1'], 3);
    } else {
        $arBlockData['r0_min1']="";
    }
    $arBlockData['izol_summ_fact']=round($arBlockData['izol_summ_fact']/1000,3);

    // Vaporizolation
    $arVapor=get_vapor();
    $arBlockData['r_po']=$arVapor['r_po'];
    $arBlockData['r_p']=$arVapor['r_p'];
    $arBlockData['r_pn_tmp']=$arVapor['r_pn_tmp'];
    $arBlockData['r_pn']=$arVapor['r_pn'];
    $arBlockData['p']=$arVapor['p'];

    //TODO: Сделать таблицы по теплообмену с графиками.


    $arBlockData['r_po']=round($arBlockData['r_po'],3);
    $arBlockData['r_p']=round($arBlockData['r_p'],3);
    $arBlockData['r_pn_tmp']=round($arBlockData['r_pn_tmp'],3);
    $arBlockData['r_pn']=round($arBlockData['r_pn'],3);
    $arBlockData['p']=round($arBlockData['p'],3);

    if (isset($_POST["mat_id"]) && !empty($_POST["mat_id"]) && isset($_POST["block_id"]) && !empty($_POST["block_id"])) {
        response_json($arBlockData);
    }
}

if(isset($_POST['cal_izol_depth']) && (!empty($_POST['cal_izol_depth']))) {
    $arBlockData['izol_r_calculated']=0;
    $element=$_POST['cal_izol_depth'];
    $mat_depth=get_mat_depth();
    if ($mat_depth[$element]=="") {
        $mat_depth[$element]="0";
    }
    $arBlockData['summ_r'] = get_summ_r();
    while ($arBlockData['izol_r_calculated'] <= $_SESSION['r0_min2'] && $mat_depth[$element]<90000){
        $arBlockData['delta_summ_r'] = $mat_depth[$element] / 1000 / $_SESSION['cal_coef_therm_cond'][$element];
        $arBlockData['izol_r_calculated']=($arBlockData['summ_r']+$arBlockData['delta_summ_r'])*$arBlockConsData['ratio'];
        $mat_depth[$element]++;
    }
    $arBlockData['izol_depth_calculated_meters']=$mat_depth[$element]/1000;
    $arBlockData['izol_depth_calculated_meters']=round($arBlockData['izol_depth_calculated_meters'],3);
    $arBlockData['izol_depth_calculated']=$mat_depth[$element];
    $arBlockData['izol_element']=$element;

    if (isset($_POST["cal_izol_depth"]) && !empty($_POST["cal_izol_depth"])) {
        response_json($arBlockData);
    }
}

?>