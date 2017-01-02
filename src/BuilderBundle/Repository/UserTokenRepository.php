<?php
namespace BuilderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use BuilderBundle\Entity\UserToken;

/**
 * Class UserTokenRepository
 * @package BuilderBundle\Repository
 */
class UserTokenRepository extends EntityRepository
{
    /**
     * @param UserToken $userToken
     */
    public function save($userToken)
    {
        $this->_em->persist($userToken);
        $this->_em->flush($userToken);
    }

    /**
     * @param UserToken $userToken
     */
    public function remove($userToken)
    {
        $this->_em->remove($userToken);
        $this->_em->flush($userToken);
    }
}