<?php
namespace App\Services\DB;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Interfaces\EntityServiceInterface;
use App\Traits\Createable;
use App\Traits\Deletable;
use App\Traits\Editable;
use App\Traits\Searchable;

/**
 * EntityService class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class EntityService implements EntityServiceInterface
{

    use Searchable;

    use Createable;

    use Editable;

    use Deletable;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    protected $manager;


    /**
     * EntityService constructor.
     *
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity     = $entity;
        $this->manager    = app('em');
        $this->repository = $this->manager->getRepository($entity);
    }


    /**
     * @return string
     */
    public function getEntity() : string
    {
        return $this->entity;
    }


    /**
     * @return EntityRepository
     */
    public function getRepository() : EntityRepository
    {
        return $this->repository;
    }


    /**
     * @return EntityManager
     */
    public function getManager() : EntityManager
    {
        return $this->manager;
    }


    /**
     * @param null $id
     * @param array $related
     * @param array $criteria
     * @param null $order
     * @param int $limit
     * @param int $offset
     * @param bool $count
     * @return array
     */
    public function find($id = null, $related = null, $criteria = [], $order = null, $limit = 50, $offset = 0, $count = false) : array
    {
        return $this->findEntity($this->getEntity(), $related, $id, $criteria, $order, $limit, $offset, $count);
    }


    /**
     * @param array $entity
     * @return array
     */
    public function create(array $entity) : array
    {
        return $this->createEntity($this->getEntity(), $entity);
    }


    /**
     * @param array $entity
     * @param array $criteria
     * @return array|null
     */
    public function update(array $entity, array $criteria)
    {
        return $this->updateEntity($this->getEntity(), $entity, $criteria);
    }


    /**
     * @param array $criteria
     * @return int
     */
    public function delete(array $criteria) : int
    {
        return $this->deleteEntity($this->getEntity(), $criteria);
    }
}
