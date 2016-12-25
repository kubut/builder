<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\Database;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DatabaseFactory
 * @package BuilderBundle\Factory
 */
class DatabaseFactory implements EntityFactoryInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            'name' => '',
            'comment' => '',
            'status' => Database::STATUS_BAKING,
            'project' => null
        ];
        $parameters = array_merge($template, $parameters);

        $database = new Database();
        $database->setName($parameters['name'])
            ->setComment($parameters['comment'])
            ->setStatus($parameters['status'])
            ->setProject($parameters['project']);

        return $database;
    }
}