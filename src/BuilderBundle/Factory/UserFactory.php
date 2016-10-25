<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Class UserFactory
 * @package BuilderBundle\Entity
 */
class UserFactory implements EntityFactoryInterface
{
//    public function __construct(UserManagerInterface $userManager)
//    {
//    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            "email" => '',
            "password" => null,
//            "password" => '$2y$13$k92e7kinctso04gkkkc4sO5wUEieXvphPhkVe2WfMU.PH0Idk0L52',
            "username" => '',
//            "salt" => 'k92e7kinctso04gkkkc4scw84ck0c0g',
            "last_login" => null,
            "roles" => ["ROLE_USER"],
        ];
        $parameters = array_merge($template, $parameters);

        $user = new User();
        $user->setEmail($parameters['email'])
            ->setPlainPassword($parameters['password'])
            ->setLastLogin($parameters['last_login'])
            ->setRoles($parameters['roles'])
            ->setUsername($parameters['username'])
            ->setEnabled(true);

        return $user;
    }
}