<?php
class Asds_View extends Lightning_View
{
    protected $grid;

    public function __construct()
    {
        parent::__construct('modules/base/templates/asds.php');
        $this->addItem('css', BASE_URL.'/modules/base/media/css/reset.css');
        $this->addItem('css', BASE_URL.'/modules/base/media/css/styles.css');
        $this->addItem('css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
        $this->addItem('script', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');
        $this->addItem('script', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js');
        $this->addItem('script', BASE_URL.'/modules/base/media/script/post-info.js');

        // If user is admin load extra resources
        $user = new Modules_Base_Models_User();
        if ($user->isLoggedIn()) {
            $this->addItem('css', BASE_URL.'/modules/datetimepicker/media/css/jquery.datetimepicker.min.css');
            $this->addItem('script', BASE_URL.'/modules/datetimepicker/media/script/jquery.datetimepicker.full.min.js');
        }
        
        $this->setVar('activeMenu', '_empty');
    }

    protected function getGrid()
    {
        if (!$this->grid) {
            $nsf = new Modules_Base_Models_NSF();       
            $this->grid = $nsf->getGrid();
        }
        return $this->grid;
    }
}
