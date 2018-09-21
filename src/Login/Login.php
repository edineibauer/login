<?php

use Store\ElasticSearch;
use Store\Store;

class Login
{
    private static $loginTable = 'user';

    /**
     * Acessa o sistema com credenciais de login
     *
     * @param array $data
     * @return mixed
     */
    public static function Access(array $data)
    {
        if (isset($_SESSION['userlogin'])) {
            return 1;
        } else {
            if (!empty($data['email']) && !empty($data['password']) && !self::attemptExceded()) {
                if (self::isHuman())
                    return self::checkCredenciais($data);
                else
                    return 9;
            } elseif (!empty($data['user']) && !empty($data['password'])) {
                return 3;
            } else {
                return 2;
            }
        }
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
        if(\Helper\Validate::email($data['email'])) {
            $store->sqlAnd(["email" => $data['email'], "password" => \Helper\Convert::password($data['password'])]);
            if ($store->getCount() > 0) {
                if (!empty($data['key'])) {
                    $ua = new ElasticSearch(self::$loginTable . "_access");
                    if($user = $ua->getResult(md5($store->getResult()['id'].$data['key']))) {
                        if ($user['status'] === 1) {
                            $token = self::getToken($user['id'].$data['key'], !empty($data['remind']) ? 12 : 1);
                            $uaCrud = new Store(self::$loginTable . "_access");
                            $uaCrud->update($user['id'], ["token" => $token]);
                            return ["token" => $token, "setor" => $user['setor'], "nivel" => $user['nivel']];
                        } else {
                            return 8;
                        }
                    } else {
                        return 7;
                    }
                } else {
                    return 6;
                }
            } else {
                $store = new ElasticSearch(self::$loginTable);
                $store->sqlAnd(["email" => $data['email']]);
                return ($store->getCount() > 0 ? 4 : 5);
            }
        } else {
            return 10;
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