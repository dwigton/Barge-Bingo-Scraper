<?php
class Login_View extends Lightning_View
{
    protected $user;

    public function __construct()
    {
        parent::__construct('modules/base/templates/login.php');
        $this->addItem('css', BASE_URL.'/modules/base/media/css/login.css');
        $this->addItem('script', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');
        $this->addItem('script', BASE_URL.'/modules/base/media/script/sha1.js');
        $this->addItem('script', BASE_URL.'/modules/base/media/script/login.js');

        $this->user = new Modules_Base_Models_User();
    }

    protected function getFormKey()
    {
        return $this->user->getSalt();
    }

    public function getUser()
    {
        return $this->user;
    }
}
