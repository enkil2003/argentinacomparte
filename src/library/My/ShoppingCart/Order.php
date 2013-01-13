<?php
class My_ShoppingCart_Order implements My_ShoppingCart_Order_Interface
{
    private $_order = null;
    /**
     * My_ShoppingCart_Order object
     * @var My_ShoppingCart_Order
     */
    private static $_instance = null;
    /**
     * Holds the order number
     * @var int
     */
    private $_orderNumber = null;
    private $_items = array();
    /**
     * Constructor method for My_ShoppingCart_Order
     */
    private function __construct()
    {
        $orderNumber = uniqid();
        $this->_order = new Orders();
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $this->_order->user = $user['id'];
        }
        $this->_order->ordernumber = $this->getOrderNumber();
        $this->_order->save();
    }
    
    /**
     * Binds and user id to an order that has null in user field
     * @param int $userId
     */
    public function bindUserToOrder($id)
    {
        $this->_order->user = $id;
        $this->_order->save();
    }
    
    /**
     * Sets order number
     * @param string $orderNumber
     * @return void
     */
    public function setOrderNumber($orderNumber)
    {
        $this->_orderNumber = $orderNumber;
    }
    
    /**
     * Get the order number, and generates a new one if there is no previous.
     * @return string the order number
     */
    public function getOrderNumber()
    {
        if (null === $this->_orderNumber) {
            $this->_orderNumber = uniqid();
        }
        return $this->_orderNumber;
    }
    
    /**
     * Regenerates new order number
     * @TODO all!
     */
    public function regenerateOrderNumber()
    {
        
    }
    /**
     * Returns an instance of My_ShoppingCart_Order
     * @return My_ShoppingCart_Order
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Adds an item to an order
     * @see My_ShoppingCart_Order_Interface::addItem()
     */
    public function addItem(My_ShoppingCart_Item_Interface $item)
    {
        $this->_order->Orderdetail[] = $item;
    }
    
    public function getItems()
    {
        return $this->_items;
    }
    /**
     * Checks if and item exists in an order
     * @param int $productId
     * @return int product quantity in order
     */
    public function itemExists($productId)
    {
        $count = Doctrine_Query::create()
            ->from('Orderdetail od')
            ->where('od.Product = ?', $productId)
            ->andWhere('od.Order = ?', $this->getOrderId())
            ->count();
        return $count;
    }
    /**
     * Returns the id of the order NOT the order number
     * @return int
     */
    public function getOrderId()
    {
        $identifier = $this->_order->identifier();
        return $identifier['id'];
    }
    /**
     * Adds a new item to the order, or increment the quantity on existent items.
     * @param int $productId product's id
     * @param int $quantity Optional quantity, default 1
     * @return void
     */
    public function addItemToOrder($productId, $quantity = 1)
    {
        $product = Doctrine_Core::getTable('Products')->find($productId);
        if($this->itemExists($productId)) {
            $orderdetail = Doctrine_Query::create()
                ->update('Orderdetail od')
                ->set('od.quantity', 'od.quantity + 1')
                ->where('od.Product = ?', $productId)
                ->execute();
        } else {
            $orderdetail = new Orderdetail();
            $orderdetail->order = $this->getOrderId();
            $orderdetail->price = $product['price'];
            $orderdetail->product = $product['id'];
            $orderdetail->quantity = $quantity;
            $orderdetail->sku = $product['sku'];
            $orderdetail->save();
        }
    }
    /**
     * Removes items from order.
     * @param int $productId
     * @return void
     */
    public function removeItemFromOrder($productId) {
        $product = Doctrine_Query::create()
            ->delete('Orderdetail od')
            ->where('od.Product = ?', $productId)
            ->andWhere('od.Order = ?', $this->getOrderId())
            ->execute();
    }
    /**
     * Updates items from order.
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function setItemQuantity($productId, $quantity) {
        Doctrine_Query::create()
            ->update('Orderdetail od')
            ->where('od.Product = ?', $productId)
            ->andWhere('od.Order = ?', $this->getOrderId())
            ->set('quantity', $quantity)
            ->execute();
    }
    /**
     * Returns an array with the order detail
     * @return array order details
     */
    public function getOrderDetails()
    {
        return Orders::getOrderdetails($this->getOrderId());
    }
}