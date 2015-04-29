<?php
class Base_User_Controller_Class
{
    public function loginAction()
    {
        $errors = array();
                
        $page = Lightning_View::newExtendedView(
                    'modules/base/views/standard_page.php',
                    'Standard_Page_View'
                )
                ->addNewExtendedChild(
                    'content', 
                    'modules/base/views/login.php',
                    'Login_View'
                )
                ->setVar('title', 'ASDS Bingo : Login');

        if ($page->getChild('content')->getUser()->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/success');
        }

        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
        }

        $page->setVar('errors', $errors)->render();
    }

    public function verifyAction()
    {
        $user = new Modules_Base_Models_User();
        $errors = array();
        $location = BASE_URL;
        if (isset($_POST['username']) && isset($_POST['hash'])) {
            if (!$user->verify($_POST['username'], $_POST['hash'])) {
                $errors[] = 'Username and password is invalid';
                sleep(3);
                $location .= '/user/login';
            }
        } else {
            $errors[] = 'Username and password is invalid';
            sleep(3);
            $location .= '/user/login';
        }
        $_SESSION['errors'] = $errors;
        header('Location: ' . $location);
    }
    
    public function logoutAction()
    {
        $user = new Modules_Base_Models_User();
        $user->logout();
        header('Location: ' . BASE_URL);
    }
}

