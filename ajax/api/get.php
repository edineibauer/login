<?php
ob_start();
require_once '../_config/config.php';
require_once '../vendor/autoload.php';

$id = strip_tags(trim(filter_input(INPUT_GET, 'id', FILTER_DEFAULT)));

//check key && get site

//check site user id and password



$data['response'] = null;
if (!empty($id)) {
    $store = new Store("user");
    $data['data'] = $store->get($id);
    $data['response'] = (!empty($data['data']) ? 1 : 0);
}

echo json_encode($data);

ob_get_flush();