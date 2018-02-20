<?php
namespace App\Traits;

use App\Helpers\Doctrine\Hydrators\DoctrineHydrator;
use Doctrine\ORM\EntityManager;

/**
 * Deletable trait
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
trait Deletable
{

    public function deleteEntity($entity, $criteria)
    {
        /**
         * @var EntityManager $manager
         */
        $hydrator   = new DoctrineHydrator(false);
        $manager    = app('em');
        $repository = $manager->getRepository($entity);
        $object     = $repository->findOneBy($criteria);

        if (is_null($object)) {
            return 0;
        }

        $manager->remove($object);
        $manager->flush();

        return 1;
    }
}
