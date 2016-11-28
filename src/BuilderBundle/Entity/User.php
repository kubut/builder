<?php
namespace BuilderBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=100)
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_code", type="string", length=100, nullable=true)
     */
    protected $activationCode;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

       return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

       return $this;
    }

    /**
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     *
     * @return User
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }
}