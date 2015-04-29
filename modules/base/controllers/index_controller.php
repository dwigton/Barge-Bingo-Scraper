<?php
class Base_Index_Controller_Class
{
    
    public function indexAction()
    {
        $user = new Modules_Base_Models_User();
        $page = Lightning_View::newExtendedView(
                    'modules/base/views/standard_page.php',
                    'Standard_Page_View'
                )
                ->addNewExtendedChild(
                    'content', 
                    'modules/base/views/asds.php',
                    'Asds_View'
                )
                ->setVar('title', 'ASDS Bingo')
                ->setVar('user', $user)
                ->render();
    }

    public function rebuildAction()
    {
        $user = new Modules_Base_Models_User();
        if ($user->isLoggedIn()) {
            set_time_limit(0);
            $nsf = new Modules_Base_Models_NSF();
            $nsf->refreshGrid(true);
        }
        header('Location: ' . BASE_URL);
    }

    public function refreshAction()
    {
        $user = new Modules_Base_Models_User();
        if ($user->isLoggedIn()) {
            $this->refresh();
        }
        header('Location: ' . BASE_URL);
    }

    public function removeAction($id, $pick)
    {
        $user = new Modules_Base_Models_User();
        if (!$user->isLoggedIn()) {
            header('Location: ' . BASE_URL);
        } else {
            $nsf = new Modules_Base_Models_NSF();
            $nsf->addOverride($id, $pick);
            $this->refresh();
            header('Location: ' . BASE_URL);
        }
    }

    public function reenableAction($id, $pick)
    {
        $user = new Modules_Base_Models_User();
        if (!$user->isLoggedIn()) {
            header('Location: ' . BASE_URL);
        } else {
            $nsf = new Modules_Base_Models_NSF();
            $nsf->removeOverride($id, $pick);
            $this->refresh();
            header('Location: ' . BASE_URL);
        }
    }

    public function haplessAction($fullpage='true')
    { 
        $nsf = new Modules_Base_Models_NSF();
        $users = $nsf->getUsers();
        
        foreach ($users as $user=>$data) {
            if (!isset($data['square'])) {
                $hapless[] = $user;
            }
        }

        natcasesort($hapless);

        $content = new Lightning_View('modules/base/templates/users.php');
        $content->setVar('heading', count($hapless).' Users Without a Successful Vote');
        $content->setVar('users', $hapless);
        
        if ($fullpage == 'true') {
            $page = Lightning_View::newExtendedView(
                    'modules/base/views/standard_page.php',
                    'Standard_Page_View'
                )
                ->addChild('content', $content)
                ->setVar('title', 'ASDS Bingo Hapless Voters')
                ->render();
        } else {
            $content->render();
        }
    }

    public function hopefulsAction($fullpage='true')
    { 
        $nsf = new Modules_Base_Models_NSF();
        $users = $nsf->getUsers();
        
        foreach ($users as $user=>$data) {
            if (isset($data['square'])) {
                $hopefuls[] = $user;
            }
        }

        natcasesort($hopefuls);
        
        $content = new Lightning_View('modules/base/templates/users.php');
        $content->setVar('heading', count($hopefuls).' Users With a Successful Vote');
        $content->setVar('users', $hopefuls);
        
        if ($fullpage == 'true') {
            $page = Lightning_View::newExtendedView(
                    'modules/base/views/standard_page.php',
                    'Standard_Page_View'
                )
                ->addChild('content', $content)
                ->setVar('title', 'ASDS Bingo Hapless Voters')
                ->render();
        } else {
            $content->render();
        }
    }

    public function rulesAction()
    {
        $content = new Lightning_View('modules/base/templates/rules.php');
        $content->render();
    }

    public function helpAction()
    {
        $content = new Lightning_View('modules/base/templates/help.php');
        $content->render();
    }

    private function refresh()
    {
        set_time_limit(0);
        $nsf = new Modules_Base_Models_NSF();
        $nsf->refreshGrid();
    }
}
?>
