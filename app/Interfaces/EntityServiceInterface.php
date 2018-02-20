<?php
namespace App\Interfaces;

/**
 * EntityServiceInterface interface
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
interface EntityServiceInterface
{
    public function __construct($entity);

    public function find($id = null, $criteria = [], $order = null, $limit = 50, $offset = 0, $count = false) : array ;

    public function create(array $entity) : array ;

    public function update(array $entity, array $criteria) ;

    public function delete(array $criteria) : int ;
}

