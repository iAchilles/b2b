<?php
namespace App\Traits;

use App\Helpers\Doctrine\Hydrators\DoctrineHydrator;
use Doctrine\ORM\EntityManager;

/**
 * Createable trait
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
trait Createable
{

    public function createEntity($entity, array $fields)
    {
        /**
         * @var EntityManager $manager
         */
        $hydrator = new DoctrineHydrator(false);
        $manager  = app('em');
        $entity   = new $entity();

        $hydrator->hydrate($fields, $entity);
        $manager->persist($entity);
        $manager->flush();

        return $hydrator->extract($entity);
    }
}
