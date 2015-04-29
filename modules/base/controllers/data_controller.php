<?php
class Base_Data_Controller_Class
{
    public function usernamelistAction()
    {
        $nsf = new Modules_Base_Models_NSF();
        $users = $nsf->getUsers();
        header('Content-Type: application/json; charset=utf-8');
        $sorted_users = array_keys($users);
        sort($sorted_users);
        echo json_encode($sorted_users);
    }

    public function userdataAction($user)
    {
        $admin = new Modules_Base_Models_User();
        $user = urldecode($user);
        $nsf = new Modules_Base_Models_NSF();
        $users = $nsf->getUsers();

        if (array_key_exists($user, $users)) {
            $response = new Lightning_View(ROOT_PATH.'/modules/base/templates/user_info.php');
            $response->setVar('user', $users[$user]);
            $response->setVar('name', $user);
            $response->setVar('admin', $admin);
            $response->render();
        }
    }
}
