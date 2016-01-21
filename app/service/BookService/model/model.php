<?php
use Application\Database\Database;
use Application\Database\Model\Model;

class BookModel extends Model
{
   protected $vendor = Database::VENDOR_MYSQL;
   // table, collection etc..
   protected $stackName = 'book';
   protected $stackPrimary = 'id';

   public $id;
}
