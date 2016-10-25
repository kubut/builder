<?php
namespace BuilderBundle\DataFixtures\Model;

use BuilderBundle\DataFixtures\BaseFixture;
use BuilderBundle\Entity\User;

/**
 * Class ProvincesData
 *
 * @package BuilderBundle\DataFixtures\Model
 */
abstract class UserData extends BaseFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param array $params
     * @return User
     */
    public function insert($params)
    {
        return $this->container->get('app.builder.factory.user')->createFromArray($params);
    }
}
