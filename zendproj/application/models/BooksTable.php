<?php

/**
 * Model_BooksTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_BooksTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_BooksTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_Books');
    }
}