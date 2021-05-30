<?php
class User
{
    private $_db, $_data, $_sessionName, $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = Db::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                    // check the user id and session
                } else {
                    // logout
                    // $this->logout();
                }
            }
        } else {
            $this->find($user);
        }
    }
    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'email';
            $data = $this->_db->get('user', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($email = null, $password = null, $remember = false)
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($email);
            // print_r($user);
            // die;
            if ($user) {
                if (password_verify($password, $this->data()->password)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    return true;
                }
            }
        }
        return false;
    }

    public function logout()
    {
        Session::delete($this->_sessionName);
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }
    public function data()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}
