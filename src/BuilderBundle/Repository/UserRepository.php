<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\User;
use BuilderBundle\Entity\UserToken;
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
     *
     * @return bool
     */
    public function checkUserUniqueness($email)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameters([
                'email' => $email,
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return is_null($user);
    }

    /**
     * @param $userId
     * @return array
     */
    public function fetchAllUserBesides($userId)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id != :id')
            ->setParameters([
                'id' => $userId,
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param UserToken $userToken
     */
    public function removeToken(UserToken $userToken)
    {
        $this->_em->remove($userToken);
        $this->_em->flush();
    }
}