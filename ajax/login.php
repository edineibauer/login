<?php
$dados['email'] = trim(strip_tags(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)));
$dados['password'] = trim(strip_tags(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)));

new \Core\Sessao();