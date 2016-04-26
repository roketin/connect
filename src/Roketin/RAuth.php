<?php

namespace Roketin;

class RAuth extends Roketin
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $email
     * @param $password
     * @param $type
     * @return mixed
     */
    public function login($email, $password, $type = "user")
    {
        $params = [
            'email'     => $email,
            'password'  => $this->encrypter->encrypt($password),
            'is_vendor' => $type == 'user' ? false : true,
        ];
        $result = $this->callAPI($type . "/authenticate", $params, "POST");
        if (!isset($result->errors)) {
            session([
                'token'     => $result->data->token,
                'user_type' => $type,
            ]);
            return true;
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        $result = $this->callAPI('user/invalidate?token=' . session('token'));
        if (!isset($result->errors)) {
            session()->forget('token');
            session()->forget('user_type');
            return true;
        }
        return $result;
    }

    public function user()
    {
        $result = $this->callAPI(session('user_type') . "?token=" . session('token'));
        if (!isset($result->errors)) {
            return (object) array_merge((array) $result->data, ['type' => session('user_type')]);
        }
        return false;
    }
}
