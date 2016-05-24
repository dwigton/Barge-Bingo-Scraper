<?php
class Modules_Base_Models_User extends Modules_Base_Models_Persistent
{
    private $user_id;
    private $salt;
    private $users = array(
            array(
                'username' => 'username',
                'password' => 'password'),
            array(
                'username' => 'username2',
                'password' => 'password2'),
            );
    
    public function __construct()
    {
        $this->loadAll();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (
                isset($_SESSION['logged_in'])
                && isset($_SESSION['user_id'])
                && $_SESSION['logged_in']) {
            $this->user_id = $_SESSION['user_id'];
        }
    }


    protected function resourceName()
    {
        return 'admin';
    }

    protected function properties()
    {
        return array('username', 'password');
    }

    public function getSalt()
    {
        if (!isset($_SESSION['salt'])) {
            $_SESSION['salt'] = uniqid(mt_rand(), true);
        }
        return $_SESSION['salt'];
    }

    public function verify($username, $hash)
    {
        $verified = false;
        if (isset($_SESSION['salt'])) {
            foreach ($this->items as $index=>$user) {
                $current_user_verified = $user['username'] == $username;
                $current_user_verified = sha1($this->getSalt().$user['password']) == $hash
                    && $current_user_verified;
                if ($current_user_verified) {
                    $this->user_id = $index;
                    $verified = true;
                    unset($_SESSION['salt']);
                }
            }
        }
        
        unset($_SESSION['salt']);
        $_SESSION['logged_in'] = $verified;
        return $verified;
    }

    public function getUserName()
    {   
        if ($this->user_id) {
            return $this->items[$this->user_id]['username'];
        } else {
            return '';
        }
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['logged_in'])) {
            return $_SESSION['logged_in'];
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['salt']);
    }
}

