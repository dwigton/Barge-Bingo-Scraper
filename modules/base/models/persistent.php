<?php
abstract class Modules_Base_Models_Persistent
{
    protected $items = array();

    abstract protected function resourceName();

    abstract protected function properties();

    public function save()
    {
        if (!file_exists(ROOT_PATH."/var/data")) {
            mkdir(ROOT_PATH."/var/data");
        }
        
        $fp = fopen(ROOT_PATH."/var/data/{$this->resourceName()}.dat", 'w');
        fwrite($fp, json_encode($this->items));
        fclose($fp);
    }

    public function addItem($item)
    {
        $properties = $this->properties();
        foreach ($properties as $property) {
            if (!isset($item[$property])) {
                throw new Exception($this->resourceName() . " requires a $property property.");
                return $this;
            }
        }

        foreach ($item as $property => $value) {
            if (!in_array($property, $properties)) {
                throw new Exception($this->resourceName() . " should not have a $property property.");
                return $this;
            }
        }

        $this->items[] = $item;
    }

    public function loadAll()
    {
        if (!file_exists(ROOT_PATH."/var/data/{$this->resourceName()}.dat")) {
            $this->items = array();
        } else {
            $file = file_get_contents(ROOT_PATH."/var/data/{$this->resourceName()}.dat");
            $this->items = json_decode($file, true);
        }
    }

    public function emptyItem()
    {
        $result = array_fill_keys($this->properties(), null);
        return $result;
    }
    
}
