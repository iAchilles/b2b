<?php
namespace App\Traits;

use App\Helpers\Doctrine\Hydrators\DoctrineHydrator;
use Doctrine\ORM\EntityManager;

/**
 * Editable trait
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
trait Editable
{

    public function updateEntity($entity, $fields, $criteria)
    {
        /**
         * @var EntityManager $manager
         */
        $hydrator   = new DoctrineHydrator(false);
        $manager    = app('em');
        $repository = $manager->getRepository($entity);
        $object     = $repository->findOneBy($criteria);

        if (is_null($object)) {
            throw new \RuntimeException('Entity "' . $entity . '" with the given criteria does not exist');
        }

        $hydrator->hydrate($fields, $object);
        $manager->flush();

        return $hydrator->extract($object);
    }
}
