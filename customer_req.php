<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "FileUtility.php";


use FileUtility\FileUtility;
$profile_panel_fileName = 'profile_panel_price.csv';
$trim_fileName = 'trim_price_list.csv';

$util = new FileUtility();
$profile_list = $util->csv_to_array_array($profile_panel_fileName, 2);
$trim_list = $util->csv_to_array_array($trim_fileName, 2);



function panel_val($input, $panel_price_from_CSV) {
    $panel_qty_array = [];
    $panel_feet_to_inches = [];
    $panel_total_array = [];

    foreach ($input as $key => $value) {
        $panel_qty_array[] = (float)$value['qty'];
        $panel_feet_to_inches[] = (float)$value['feet to inches'];
        $panel_total_array[] = (float)$value['total'];
    }

    for ($i = 0; $i < count($panel_qty_array); $i++) {
        $temp_price = $panel_feet_to_inches[$i] *  ($panel_price_from_CSV/12);
        $comfirmed_total_price = round($temp_price * $panel_qty_array[$i], 2);
        if (!($panel_total_array[$i] == $comfirmed_total_price)) {
            return 0;
        }
   }
   return 1;
}

$trim_val = function ($trim) use($trim_list) {
    $trim_names_array = []; 
    $trim_qty_array = [];
    $trim_price_array = [];
    $trim_total_array = [];

    foreach ($trim as $key => $value) {
        $trim_names_array[] = $key; 
        $trim_qty_array[] = $value['qty'];
        $trim_price_array[] = $value['price'];
        $trim_total_array[] = $value['total'];
    }

    for ($i = 0; $i < count($trim_names_array); $i++) {
        $name = $trim_names_array[$i];
        $qty = $trim_qty_array[$i];
        $price = (float)$trim_price_array[$i];
        $total = (float)$trim_total_array[$i];
        $server_price = (float)$trim_list[$name][0];

        if (!($total == $server_price) && !(($server_price * $qty) == $total)) {
            return 0;
        }
   }
   return 1;
};



if (isset($_POST['customer'])) {
    $data = json_decode($_POST['customer'], true);
    $panel = $data['panel'];
    $trim = $data['trim'];

    $profile = $panel['profile'];
    $panel_price_from_CSV = (float)$profile_list[$profile][0];  // Price from file
    $price = $panel['price'];
    $price_per_inch = $panel['price per inch'];
    $input = $panel['input'];  // array

    $comfirm_panels = panel_val($input, $panel_price_from_CSV);  // If this is zero after this loop, than the price has been changed in the HTML source code
    $comfirm_trim = $trim_val($trim);
    
   if ($comfirm_panels == 0 || $comfirm_trim == 0) {
        setcookie("price_changed", 1, time()+3600);
        echo json_encode(0);    
   } else {
        echo json_encode(1);
   }
 }