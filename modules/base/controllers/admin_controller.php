<?php
class Base_Admin_Controller_Class
{
    public function indexAction()
    {
        $thing = new Modules_Base_Models_Game();
        $id         = $thing->getCurrentGame()['thread_id'];
        $start_time = $thing->getCurrentGame()['start_time'];
        $end_time   = $thing->getCurrentGame()['end_time'];
        $user = new Modules_Base_Models_User();
        if ($user->isLoggedIn()) {
            $content = new Lightning_View('modules/base/templates/admin.php');
            $content->setVar('thread_id', $id);
            $content->setVar('start_time', $start_time);
            $content->setVar('end_time', $end_time);
            $content->render();
        }
    }

    public function createUserAction()
    {
    }

    public function deleteAction()
    {
    }

    public function updategameAction()
    {
        $user = new Modules_Base_Models_User();
        if ($user->isLoggedIn()) {
            if (isset($_POST['forum_topic_id'])) {
                $game = new Modules_Base_Models_Game();
                $game->addItem(
                        array(
                            'title'      => 'The next great game', 
                            'thread_id'  => $_POST['forum_topic_id'], 
                            'start_time' => date('Y/m/d H:i', strToTime($_POST['start_time'])),
                            'end_time'  => date('Y/m/d H:i', strToTime($_POST['end_time'])),
                            ));
                $game->save();
            }
        }

        return $this->indexAction();
    }

//    public function rebuildAction($key='')
//    {
//        $user = new Modules_Base_Models_User();
//        if ($user->isLoggedIn() || $key=='asldfjua632hagaosdowerhHGksar') {
//            set_time_limit(0);
//            $nsf = new Modules_Base_Models_NSF();
//            $nsf->refreshGrid(true);
//        }
//        header('Location: ' . BASE_URL);
//    }
//
//    public function refreshAction()
//    {
//        $user = new Modules_Base_Models_User();
//        if ($user->isLoggedIn()) {
//            $this->refresh();
//        }
//        header('Location: ' . BASE_URL);
//    }
//
//    public function removeAction($id, $pick)
//    {
//        $user = new Modules_Base_Models_User();
//        if (!$user->isLoggedIn()) {
//            header('Location: ' . BASE_URL);
//        } else {
//            $nsf = new Modules_Base_Models_NSF();
//            $nsf->addOverride($id, $pick);
//            $this->refresh();
//            header('Location: ' . BASE_URL);
//        }
//    }
//
//    public function reenableAction($id, $pick)
//    {
//        $user = new Modules_Base_Models_User();
//        if (!$user->isLoggedIn()) {
//            header('Location: ' . BASE_URL);
//        } else {
//            $nsf = new Modules_Base_Models_NSF();
//            $nsf->removeOverride($id, $pick);
//            $this->refresh();
//            header('Location: ' . BASE_URL);
//        }
//    }
//
}
