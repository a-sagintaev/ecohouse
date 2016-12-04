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