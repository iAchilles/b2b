<?php
namespace App\Traits;

use App\Helpers\Doctrine\Hydrators\DoctrineHydrator;
use Doctrine\ORM\EntityManager;

/**
 * Searchable trait
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
trait Searchable
{

    /**
     * @param string $entity
     * @param array $related
     * @param null $id
     * @param array $criteria
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @param bool $count
     * @return mixed
     */
    public function findEntity($entity, $related = null, $id = null, $criteria = [], $order = null, $limit = 50, $offset = 0, $count = false)
    {
        /**
         * @var EntityManager $manager
         */
        $manager    = app('em');
        $repository = $manager->getRepository($entity);
        $hydrator   = new DoctrineHydrator(false);
        $limit      = $limit > 50 ? 50 : $limit;

        if (!is_null($id)) {
            $entity = $repository->find($id);
            return is_null($entity) ? [ ] : [ $hydrator->extract($entity) ];
        }

        if($count) {
            $countable = $manager->getUnitOfWork()->getEntityPersister($entity)->count($criteria);
        }

        $entities   = $repository->findBy($criteria, $order, $limit, $offset);
        $extraction = [];

        foreach ($entities as $entity) {
            $extracted = $hydrator->extract($entity);
            if (is_array($related) && !empty($related)) {
                foreach ($related as $property) {
                    $getter = 'get'. ucfirst($property);
                    if (method_exists($entity, $getter)) {
                        $obj = $entity->$getter();
                        if (is_object($obj)) {
                            $extracted[ $property ] = $hydrator->extract($obj);
                        }
                    }
                }
            }
            array_push($extraction, $extracted);
        }

        $result = [ 'collection' => $extraction ];

        !isset($countable) ?: $result[ 'counter' ] = [ 'summary' => $countable ];

        return $result;
    }
}
