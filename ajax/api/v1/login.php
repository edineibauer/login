<?php
$dados['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$dados['password'] = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));
$dados['key'] = trim(strip_tags(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));
$lang = json_decode(file_get_contents(PATH_HOME . "lang/pt-BR.json"), true);

$login = Login::Access($dados);
var_dump($login);

if (is_numeric($login)) {
    $data['error'] = $lang['login'][$login];
} else {
    $data = [
        "response" => 1,
        "error" => "",
        "data" => $login
    ];
}