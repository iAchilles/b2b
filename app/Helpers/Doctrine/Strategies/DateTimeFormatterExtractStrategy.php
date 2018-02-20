<?php
namespace App\Helpers\Doctrine\Strategies;

use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * DateTimeFormatterExtractStrategy class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class DateTimeFormatterExtractStrategy implements StrategyInterface
{
    private $format;

    /**
     * Constructor
     *
     * @param string  format
     */
    public function __construct($format = \DateTime::ATOM)
    {
        $this->format = (string) $format;
    }


    public function hydrate($value)
    {
        return $value;
    }


    /**
     * Converts to date time string
     *
     * @param mixed|DateTime $value
     *
     * @return mixed|string
     */
    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format($this->format);
        }

        return $value;
    }
}
