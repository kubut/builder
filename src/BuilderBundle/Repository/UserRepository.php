<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package BuilderBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
        $this->_em->refresh($user);
    }

    /**
     * @param string $email
     * @param string $username
     *
     * @return bool
     */
    public function checkUserUniqueness($email, $username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->orWhere('u.username = :username')
            ->setParameters([
                'email' => $email,
                'username' => $username
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return is_null($user);
    }
}