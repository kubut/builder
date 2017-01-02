<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\UserToken;
use BuilderBundle\Repository\UserTokenRepository;
use BuilderBundle\Entity\User;

class UserTokenModel
{
    /** @var  UserTokenRepository $userTokenRepository */
    protected $userTokenRepository;

    /**
     * UserModel constructor.
     * @param UserTokenRepository $userTokenRepository
     */
    public function __construct(UserTokenRepository $userTokenRepository)
    {
        $this->userTokenRepository = $userTokenRepository;
    }

    /**
     * @param User $user
     * @return UserToken
     */
    public function createToken($user)
    {
        $token = new UserToken();
        $DateTime = new \DateTime();
        $token->setCreateAt($DateTime);
        $token->setExpireAt($this->addDateValidTokenInterval());
        $token->setToken($this->generateToken());
        $token->setUser($user);


        return $token;
    }

    /**
     * @param $token
     */
    public function save($token)
    {
        $this->userTokenRepository->save($token);
    }

    /**
     * @return \DateTime
     */
    public function addDateValidTokenInterval()
    {
        $DateTime = new \DateTime();
        return $DateTime->modify('+4 hour');
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        $DateTime = new \DateTime();
        $string = mt_rand(1, 100000000) .  $DateTime->format("YmdHis");

        return sha1($string);
    }
}