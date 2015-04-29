<?php
class Standard_Page_View extends Lightning_View
{
    public function __construct()
    {
        parent::__construct('modules/base/templates/standard_page.php');
        $this->addItem('css', BASE_URL.'/modules/base/media/css/reset.css');
        $this->addItem('css', BASE_URL.'/modules/base/media/css/styles.css');
        if (App::isEnvironment('production')) {
            $this->addItem('script', BASE_URL.'/modules/base/media/script/google-analytics.js');
        }
    }
}
