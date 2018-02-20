<?php
namespace App\Helpers\Doctrine\Hydrators;

use Doctrine\ORM\Proxy\Proxy;
use Zend\Hydrator\Filter\GetFilter as ZendGetFilter;

/**
 * GetFilter class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class GetFilter extends ZendGetFilter
{

    private $proxyCache = [];


    /**
     * {@inheritdoc}
     */
    public function filter($property)
    {
        if (!parent::filter($property)) {
            return false;
        }

        $reflectionClass = $this->checkIsProxy($property) ?: new \ReflectionClass(explode('::', $property)[0]);
        $classMetadata   = app('em')->getClassMetadata($reflectionClass->getName());
        $associations    = $classMetadata->getAssociationNames();
        $getterProperty  = $this->convertGetterToPropertyName($property);

        if (in_array($getterProperty, $associations)) {
            return false;
        }

        return true;
    }


    /**
     * Checks if it's a Doctrine proxy class for an entity.
     * @param string $property
     * @return bool|mixed Returns ReflectionClass instance or false.
     */
    private function checkIsProxy(string $property)
    {
        $class = explode('::', $property)[0];
        if (isset($this->proxyCache[ $class ])) {
            return $this->proxyCache[ $class ];
        }

        $reflectionClass = new \ReflectionClass($class);
        if ($reflectionClass->implementsInterface(Proxy::class)) {
            $this->proxyCache[ $class ] = $reflectionClass->getParentClass();
            return $this->proxyCache [ $class ];
        }

        return false;
    }


    /**
     * Converts getter method's name into a property name.
     * @param string $property
     * @return string
     */
    private function convertGetterToPropertyName(string $property)
    {
        return lcfirst(substr($property, strpos($property, 'get') + 3));
    }
}
