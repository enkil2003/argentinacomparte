<?php
interface My_ShoppingCart_Order_Interface
{
    public function addItem(My_ShoppingCart_Item_Interface $item);
    /**
     * Returns all the information from an order.
     * @return array
     */
    public function getOrderDetails();
    /**
     * Add an item to an order
     * @param int $productId
     * @param int $quantity
     */
    public function addItemToOrder($productId, $quantity = 1);
    /**
     * Removes an item from an order
     * @param int $productId
     */
    public function removeItemFromOrder($productId);
    /**
     * Sets the item cuantity
     * @param int $productId
     * @param int $quantity
     */
    public function setItemQuantity($productId, $quantity);
}