<?php

global $custom_drivers;

if (!is_array($custom_drivers)) {
    $custom_drivers = [];
}

$driver_folder = dirname(__FILE__);

$custom_drivers['wpdb_driver_pdo_wpdb'] = $driver_folder.DS.'pdo_wpdb.php';
