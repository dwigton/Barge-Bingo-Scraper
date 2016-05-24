<?php
class Modules_Base_Models_Game extends Modules_Base_Models_Persistent
{
    protected function resourceName()
    {
        return 'game';
    }

    protected function properties()
    {
        return array('title', 'thread_id');
    }

    public function getCurrentGame()
    {
        $this->loadAll();
        $result = $this->items;
        if (count ($result) === 0) {
            return $this->emptyItem();
        } else {
            return $result[count($result) - 1];
        }
    }
}
