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

function get_max_izol_cal_coef_therm_cond() {
    global $db;
    $max_cal_coef_therm_cond=0;
    foreach ($_SESSION['cal_coef_therm_cond'] as $i => $item){
        $id=addslashes($_SESSION['mat_id'][$i+1]);
        $is_izol = $db->getArray("SELECT is_izol FROM goods WHERE id ='" . $id . "'");
        if (isset($is_izol) && $is_izol[0]['is_izol'] == "1"){
            if ($max_cal_coef_therm_cond < $item) {
                $max_cal_coef_therm_cond=$item;
            }
        }

    }
    return $max_cal_coef_therm_cond;
}