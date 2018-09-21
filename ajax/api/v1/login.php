<?php
$dados['email'] = trim(strip_tags(filter_input(INPUT_POST, 'email', FILTER_DEFAULT)));
$dados['password'] = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));
$dados['key'] = trim(strip_tags(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));
$lang = json_decode(file_get_contents(PATH_HOME . "lang/pt-BR.json"), true);

//$st = new \Store\Store("user");
//var_dump($st->add("yuri", ["email" => "edineibauer@gmail.com 4", "password" => \Helper\Convert::password("teste"), "idade" => 20]));

/*
//Criação da relação usuário site
$store = new \Store\Store("user_access");
var_dump($store->save(md5('nena' . 'AdR)+}nl)u]O}^y]'),
    [
        "status" => 1
    ]
));*/

$login = Login::Access($dados);

if (is_numeric($login)) {
    $data['error'] = $lang['login'][$login];
} else {
    $data = [
        "response" => 1,
        "error" => "",
        "data" => $login
    ];
}