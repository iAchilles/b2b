<?php
namespace App\Services\DB;

use App\Entities\User;

/**
 * RecipeService class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class RecipeService extends EntityService
{
    /**
     * @param array $entity
     * @return array
     */
    public function create(array $entity) : array
    {
        $user = $this->manager->find(User::class, $entity[ 'user' ]);
        $entity[ 'user' ] = $user;

        return  $this->createEntity($this->getEntity(), $entity);
    }

}
