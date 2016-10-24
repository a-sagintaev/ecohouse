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

    $query = $db->query("SELECT dry_density,cal_coef_therm_cond_b, cal_coef_therm_cond_a FROM goods WHERE id =" . $_POST['mat_id']);
    while ($row = $query->fetch_assoc()) {
        $dry_density=$row['dry_density'];
        $cal_coef_therm_cond=$row['cal_coef_therm_cond_a'];


    }

    echo json_encode(
        array(
            "dry_density" => "$dry_density",
            "cal_coef_therm_cond" => "$cal_coef_therm_cond")
    );
}
?>