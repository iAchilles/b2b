<?php
namespace App\Helpers\Doctrine\Hydrators;

use App\Helpers\Doctrine\Strategies\DateTimeFormatterExtractStrategy;
use Zend\Hydrator\ClassMethods;

/**
 * DoctrineHydrator class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class DoctrineHydrator extends ClassMethods
{

    /**
     * {@inheritdoc}
     */
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);
        $this->addStrategy('*', new DateTimeFormatterExtractStrategy());
        $this->removeFilter('is')->removeFilter('has')->removeFilter('get')->addFilter('get', new GetFilter());
    }
}
