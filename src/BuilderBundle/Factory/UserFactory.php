<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserFactory
 * @package BuilderBundle\Entity
 */
class UserFactory implements EntityFactoryInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            "email" => '',
            "name" => '',
            "surname" => '',
            "password" => null,
            "last_login" => null,
            "roles" => ["ROLE_USER"],
        ];
        $parameters = array_merge($template, $parameters);

        $user = new User();
        $user->setName($parameters['name'])
            ->setSurname($parameters['surname'])
            ->setActivationCode($parameters['password'])
            ->setEmail($parameters['email'])
            ->setPlainPassword($parameters['password'])
            ->setLastLogin($parameters['last_login'])
            ->setRoles($parameters['roles'])
            ->setUsername($parameters['email'])
            ->setEnabled(false);

        return $user;
    }
}