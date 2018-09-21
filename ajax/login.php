<?php
$user = trim(strip_tags(filter_input(INPUT_POST, 'user', FILTER_DEFAULT)));
$pass = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));
$key = trim(strip_tags(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));

//$ss = new Store("user");
//$ss->update("edinei", ["email" => "edinei@gmail.com", "password" => 'teste123']);
//$s = new Store("user_access");
//$s->add("maria", ["user_id" => "maria", "key" => 1]);

//var_dump(['key' => $key, 'user' => $user, 'pass' => $pass]);

$user = "edinei@gmail.com";
$pass = "teste123";

$result = Login::Access(['key' => $key, 'email' => $user, 'password' => $pass]);
if(is_int($result))
    $data['error'] = $result;
else
    $data['data'] = $result;

/*
if (empty($user) || empty($pass)) {
    $data['error'] = "informe 'user' e 'password'.";
} elseif (empty($key)) {
    $data['error'] = "informe 'key'. Chave de identificação do site";
} else {
    $store = new Store("user");
    $result = $store->search(["user" => $user, "password" => $pass]);
    var_dump($result);
    if (!empty($result)) {
        $store = new Store("user_access");
        $dados = $store->search(["user_id" => $result['user'], "key" => $key]);
        if (!empty($dados) && $dados['status']) {
            $data['data'] = $dados;
        } elseif (!empty($dados)) {
            $data['error'] = "Seu Acesso foi Desativado";
        } else {
            $data['error'] = "Você não esta registrado neste site";
        }
    } else {
        $data['error'] = "Login inválido";
    }
}*/