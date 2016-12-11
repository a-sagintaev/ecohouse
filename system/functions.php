<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04.12.2016
 * Time: 11:47
 */
function get_cities()
{
    global $db;

    $cities = $db->getArray("SELECT id, name, is_region FROM cities ");
    return $cities;
}

function get_id_name($table)
{
    global $db;

    $result = $db->getArray("SELECT id, name FROM $table");
    return $result;
}

function response_json($array)
{
    echo json_encode($array);
}

function get_max_izol_cal_coef_therm_cond()
{
    global $db;
    $max_cal_coef_therm_cond = 0;
    foreach ($_SESSION['cal_coef_therm_cond'] as $i => $item) {
        $id = addslashes($_SESSION['mat_id'][$i + 1]);
        $is_izol = $db->getArray("SELECT is_izol FROM goods WHERE id ='" . $id . "'");
        if (isset($is_izol) && $is_izol[0]['is_izol'] == "1") {
            if ($max_cal_coef_therm_cond < $item) {
                $max_cal_coef_therm_cond = $item;
            }
        }

    }
    return $max_cal_coef_therm_cond;
}

function get_summ_r()
{
    $result = 0;
    foreach ($_SESSION['mat_id'] as $i => $item) {
        $result = $result + $_SESSION['therm_res_calc'][$i - 1];
    }
    $result = $result + 0.158;
    return $result;
}

function get_r_con()
{
    global $db;
    $result[] = array();
    $mat_depth = get_mat_depth();
    $result['izol_summ_fact'] = 0;
    $result['r_con'] = 0;
    foreach ($_SESSION['mat_id'] as $i => $item) {
        $item = addslashes($item);

        $mat_r = $db->getArray("SELECT is_izol FROM goods WHERE id ='" . $item . "'");
        if (!empty($mat_r)) {
            if ($mat_r[0]['is_izol'] === "0" && $_SESSION['cal_coef_therm_cond'][$i - 1] <> 0) {
                $result['r_con'] = $result['r_con'] + ($mat_depth[$i - 1] / $_SESSION['cal_coef_therm_cond'][$i - 1]);
            } elseif ($mat_r[0]['is_izol'] === "1" && $_SESSION['therm_res_calc'][$i - 1] <> 0) {
                $result['izol_summ_fact'] = $result['izol_summ_fact'] + $mat_depth[$i - 1];
                $_SESSION['izol_layer'] = $i - 1;
            }
        }
    }
    return $result;
}

function get_mat_depth()
{
    if (isset($_POST["mat_depth"])) {
        foreach ($_POST["mat_depth"] as $i => $value) {
            $result[$i] = $value;
        }
    }
    $_SESSION['mat_depth'] = $result;
    return $result;
}

function get_therm_res_calc($element)
{
    $mat_depth = get_mat_depth();
    $result = $mat_depth[$element] / 1000 / $_SESSION['cal_coef_therm_cond'][$element];
    return $result;
}

function get_vapor()
{
    $mat_depth = get_mat_depth();
    $result['r_po'] = 0;
    $result['r_p'] = 0;
    $result['r_pn_tmp'] = 0;
    $result['r_pn'] = 0;
    $result['p']=0;
    foreach ($mat_depth as $i => $item) {
        // get r
        if (isset($item) && !empty($item)) {
            if (isset($_SESSION['cal_coef_vapor'][$i]) && !empty($_SESSION['cal_coef_vapor'][$i])) {
                $result['r_po'] = $result['r_po'] + ($item / 1000 / $_SESSION['cal_coef_vapor'][$i]);
                if ($i <= $_SESSION['izol_layer']) {
                    $result['r_p'] = $result['r_p'] + ($item / 1000 / $_SESSION['cal_coef_vapor'][$i]);
                } else {
                    $result['r_pn_tmp'] = $result['r_pn_tmp'] + ($item / 1000 / $_SESSION['cal_coef_vapor'][$i]);
                }
            }
            if (isset($_SESSION['cal_coef_therm_cond'][$i]) && !empty($_SESSION['cal_coef_therm_cond'][$i])) {
                if ($i <= $_SESSION['izol_layer']) {
                    $result['p']=$result['p']+($item / 1000 / $_SESSION['cal_coef_therm_cond'][$i]);
                }
            }
        }
        //get p

    }

    if ($result['r_pn_tmp'] > 0) {
        $result['r_pn'] = $result['r_pn_tmp'];
    } else {
        $result['r_pn'] = $mat_depth[$_SESSION['izol_layer']] / 1000 / $_SESSION['cal_coef_vapor'][$_SESSION['izol_layer']];
    }
    return $result;
}
