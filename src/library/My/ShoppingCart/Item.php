<?php
class My_ShoppingCart_Item implements My_ShoppingCart_Item_Interface
{
    public function __construct($item)
    {
        $this->_item = $item;
    }
    
    public function __get($key) {
        if (is_object($this->_item)) {
            return $this->_item->$key;
        }
        return $this->_item[$key];
    }
    
    public function __call($method, $params) {
        return call_user_func_array(array($this->_adapter, $method), $params);
	}
}