<?php declare(strict_types=1);
namespace Application\Database\Model\Stack;

interface StackInterface
{
   public function find();
   public function findAll();
   public function save();
   public function remove();
}
