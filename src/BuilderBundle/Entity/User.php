<?php
namespace BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var bool
     *
     * @ORM\Column(name="activated", type="boolean")
     */
    protected $activated = false;

    /**
     * @ORM\OneToMany(targetEntity="Instance", mappedBy="user", cascade={"persist"})
     */
    protected $instances;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_token_quantity", type="integer")
     */
    protected $maxTokenQuantity;

    /**
     * @ORM\OneToMany(targetEntity="UserToken", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"expireAt" = "ASC"})
     */
    protected $tokens;

    public function __construct()
    {
        $this->instances = new ArrayCollection();
        $this->maxTokenQuantity = 5;
        parent::__construct();
    }
    /**
     * Add tokens
     *
     * @param UserToken $tokens
     * @return User
     */
    public function addToken(UserToken $tokens)
    {
        $this->tokens[] = $tokens;

        return $this;
    }

    /**
     * Remove tokens
     *
     * @param UserToken $tokens
     */
    public function removeToken(UserToken $tokens)
    {
        $this->tokens->removeElement($tokens);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return int
     */
    public function getMaxTokenQuantity()
    {
        return $this->maxTokenQuantity;
    }

    /**
     * @param int $maxTokenQuantity
     */
    public function setMaxTokenQuantity($maxTokenQuantity)
    {
        $this->maxTokenQuantity = $maxTokenQuantity;
    }

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

    /**
     * @return bool
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param int $activated
     *
     * @return User
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * @param Instance $instance
     * @return $this
     */
    public function addInstance(Instance $instance)
    {
        $this->instances[] = $instance;

        return $this;
    }
}