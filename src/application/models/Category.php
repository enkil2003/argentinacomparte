<?php

/**
 * Category
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Category extends BaseCategory
{
    /**
     * Returns all categories from model
     * @return array
     */
    public function getAll()
    {
        $q = Doctrine_Query::create()
            ->select('c.id, c.name, c.tooltip')
            ->from('Category c')
            ->orderBy('c.id');
        return $q->fetchArray();
    }
}