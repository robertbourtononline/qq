<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "ColorSamples.php";
include_once "FileUtility.php";
include_once "views/header.php";
use ColorSamples\ColorSamples;
use FileUtility\FileUtility;





$color_array = [
    'Sepia Brown' => ['assets/img/color_sample/sepiabrown.jpg', '9798'],
    'Graphite Grey' => ['assets/img/color_sample/graphitegrey.jpg', '9821'],
    'Ebony' => ['assets/img/color_sample/ebony.jpg', '9822'],
    'Deep Grey' => ['assets/img/color_sample/deepgrey.jpg', '9999'],
    'Brite White' => ['#E2E9E9', '8783'],
    'Liner White' => ['#E2E9E9', '8783'],
    'White White' => ['#CCCECB', '8317' ],
    'Bone White' => ['#BAC0BA', '8273'],
    'Regent Grey' => ['#959B99', '8730'],
    'Stone Grey' => ['#A39E93', '8305'],
    'Pebble Khaki' => ['#9A8F7F', '8129'],
    'Buckskin' => ['#847566', '8055'],
    'Tan' => ['#9E8F73', '8315'],
    'Antique Linen' => ['#DFD2B6', '8696'],
    'Burnished Slate' => ['#524A44', '8262'],
    'Dark Brown' => ['#3D3936', '8229'],
    'Coffee Brown' => ['#4C3B35', '8326'],
    'Charcoal' => ['#5E5A58', '8306'],
    'Black' => ['#1C1B21', '8262'],
    'Melchers Green' => ['#134338', '8307'],
    'Slate Blue' => ['#4E6F7B', '8260'],
    'Bright Red' => ['#B01C27', '8386'],
    'Tile Red' => ['#922D24', '8259'],
    'Dark Red' => ['#8D151C', '8250'],
    'Galvanized' => ['assets/img/color_sample/galvanized.jpg', 'GV'],
    'Galvalume' => ['assets/img/color_sample/galvalume.jpg', 'GL']
   
];

$color_samples = new ColorSamples($color_array, 'color-samples');

$color_samples->display_colors();