<?php

use Store\ElasticSearch;
use Store\Store;
use \Helper\Convert;
use \Helper\Validate;

class Login
{
    private static $loginTable;

    /**
     * Acessa o sistema com credenciais de login
     *
     * @param array $data
     * @return mixed
     */
    public static function Access(array $data)
    {
        if (empty($data['key']))
            return 6;
        elseif (empty($data['email']))
            return 2;
        elseif (empty($data['password']))
            return 3;
        elseif (self::attemptExceded())
            return 11;
        elseif (!self::isHuman())
            return 9;
        elseif (!Validate::email($data['email']))
            return 10;

        self::$loginTable = $data['key'] === "fc87b14b506da600b4a080360e269518" ? "webmaster" : "user";

        return self::checkCredenciais($data);
    }

    /**
     * Verifica se as Credenciais são de algum usuário
     *
     * @param array $data
     * @return mixed
     */
    private static function checkCredenciais(array $data)
    {
        $store = new ElasticSearch(self::$loginTable);
        $id = md5($data['email'] . Convert::password($data['password']));
        if ($usuario = $store->getResult($id)) {
            $ua = new ElasticSearch(self::$loginTable . "-access");
            if (!$user = $ua->getResult(md5($id . $data['key'])))
                return 7;

            if ($user['status'] !== 1)
                return 8;

            return $id;
        } else {
            $store = new ElasticSearch(self::$loginTable);
            $store->sqlAnd(["email" => $data['email']]);
            return ($store->getCount() > 0 ? 4 : 5);
        }
    }

    /**
     * Sai do sistema
     *
     * @param string $token
     */
    public static function logOut(string $token)
    {
        $ua = new ElasticSearch(self::$loginTable . "_access");
        $ua->sqlAnd(["token" => $token]);
//        if($ua->getCount() > 0) {
//        }

        /*setcookie("token", 0, time() - 1, "/");
        if (isset($_SESSION['userlogin'])) {
            if(isset($_SESSION['userlogin']['token']) && !empty($_SESSION['userlogin']['token'])) {
                $token = new TableCrud(PRE . "usuarios");
                $token->load("token", $_SESSION['userlogin']['token']);
                if ($token->exist()) {
                    $token->setDados(["token" => null, "token_expira" => null]);
                    $token->save();
                }
            }

            session_unset();
        }*/
    }

    /**
     * Verifica se houve tentativas falhas demais nos últimos instantes
     *
     * @return bool
     */
    private static function attemptExceded()
    {
//        $ip = filter_var(\Helper\Helper::getIP(), FILTER_VALIDATE_IP);
        return false;
    }

    /**
     * Verifica se esta definido o Recaptcha do Google
     * se tive, verifica
     *
     * @return bool
     */
    private static function isHuman()
    {
        /* if (defined("RECAPTCHA")) {
             if (empty($this->recaptcha))
                 $this->setResult("resolva o captcha");

             $recaptcha = new ReCaptcha(RECAPTCHA);
             $resp = $recaptcha->verify($this->recaptcha, filter_var(Helper::getIP(), FILTER_VALIDATE_IP));
             if (!$resp->isSuccess())
                 $this->setResult('<p>' . implode('</p><p>', $resp->getErrorCodes()) . '</p>');
         }

         return $this->getResult() ? false : true;*/
        return true;
    }

    /**
     * Gera token com data de validade
     *
     * @param string $id
     * @param int $validMonth
     * @return string
     */
    private static function getToken(string $id, int $validMonth = 1): string
    {
        $date = date("Y-m-d H:i:s", strtotime("+{$validMonth} month", strtotime(date("Y-m-d H:i:s"))));
        return md5($id) . "#" . base64_encode($date);
    }
}