<?php
use Application\Database\Database;
use Application\Database\Model\Model;

class BookModel extends Model
{
    protected $vendor = Database::VENDOR_MYSQL;
    protected $tableName = 'book';
    protected $tablePrimary = 'id';

    public $id;
}
