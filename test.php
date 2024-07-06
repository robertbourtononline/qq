<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once "FileUtility.php";
include_once "views/header.php";

use FileUtility\FileUtility;


function csv_to_array_array(string $file, int $array_size, bool $skip_first_line = true) : array {
    $csv_file = fopen($file, "r");
    $array_array = [];
    $index = 0;
    $once_only = false;

    while (($cells = fgetcsv($csv_file)) !== false) {
        if ($skip_first_line && !$once_only) {
            $once_only = true;
            continue;
        }
        $array = [];
        $name = $cells[0];
        for ($i = 0; $i < $array_size; $i++) {
            if (array_key_exists($i +1, $cells)) {
                array_push($array, $cells[$i +1]);
            }
        }
        $array_array[$name] = $array;   
    }
    return $array_array; 
}

function csv_first_column(string $file, bool $skip_first_line = true) : array {
    $csv_file = fopen($file, "r");
    $array = [];
    $index = 0;
    $once_only = false;

    while (($cells = fgetcsv($csv_file)) !== false) {
        if ($skip_first_line && !$once_only) {
            $once_only = true;
            continue;
        }
        $name = $cells[0];
        array_push($array, $name);   
    }
    return $array; 
}

$file_name = 'color_data.csv';

$util = new FileUtility();

$array_to_print = csv_to_array_array($file_name, 3);

$util->array_print($array_to_print);
