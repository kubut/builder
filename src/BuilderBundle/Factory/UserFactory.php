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
            "username" => '',
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