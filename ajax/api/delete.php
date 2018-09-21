<?php
ob_start();
require_once '../_config/config.php';
require_once '../vendor/autoload.php';

$id = strip_tags(trim(filter_input(INPUT_GET, 'id', FILTER_DEFAULT)));

$data['response'] = null;
if (!empty($id)) {
    $store = new Store("user");
    $data['response'] = $store->delete($id);
}

echo json_encode($data);

ob_get_flush();