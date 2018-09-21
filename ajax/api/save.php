<?php
ob_start();
require_once '../_config/config.php';
require_once '../vendor/autoload.php';

$id = strip_tags(trim(filter_input(INPUT_GET, 'id', FILTER_DEFAULT)));
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$data['response'] = null;
if (!empty($id) && !empty($dados)) {
    $store = new Store("user");
    $data['response'] = ($store->get($id) ? $store->update($id, $dados) : $store->add($id, $dados));
}

echo json_encode($data);

ob_get_flush();