<?php
$dados['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$dados['password'] = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));
$dados['key'] = trim(strip_tags(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));

$login = Login::Access($dados);

if (is_numeric($login)) {
    $lang = json_decode(file_get_contents(PATH_HOME . "lang/pt-BR.json"), true);
    $data['error'] = $lang['login'][$login];
} else {
    $data = [
        "response" => 1,
        "error" => "",
        "data" => $login
    ];
}