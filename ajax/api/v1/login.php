<?php
$dados['email'] = trim(strip_tags(filter_input(INPUT_POST, 'email', FILTER_DEFAULT)));
$dados['password'] = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));
$dados['key'] = trim(strip_tags(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));
$lang = json_decode(file_get_contents(PATH_HOME . "lang/pt-BR.json"), true);

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