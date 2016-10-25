<?php
namespace BuilderBundle\Factory;

/**
 * Interface EntityFactoryInterface
 * @package BuilderBundle\Factory
 */
interface EntityFactoryInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = []);
}