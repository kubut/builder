<?php
namespace BuilderBundle\Security;

use BuilderBundle\Entity\User;
use BuilderBundle\Entity\UserToken;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Model\UserModel;
use BuilderBundle\Model\UserTokenModel;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * Class AuthenticationModel
 * @package BuilderBundle\Security
 */
class AuthenticationModel
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /**
     * @var EncoderFactory
     */
    protected $encoderFactory;

    /**
     * @var \FOS\UserBundle\Model\UserManager
     */
    protected $userManager;

    /**
     * AuthenticationModel constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param UserManager $userManager
     * @param EncoderFactory $encoderFactory
     * @param UserTokenModel $userTokenModel
     * @param UserModel $userModel
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserManager $userManager,
        EncoderFactory $encoderFactory,
        UserTokenModel $userTokenModel,
        UserModel $userModel
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
        $this->userManager = $userManager;
        $this->userTokenModel = $userTokenModel;
        $this->userModel = $userModel;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function addToken(Request $request, $user)
    {
        $tokens = $user->getTokens();
        $tooManyTokens = $tokens->count() > $user->getMaxTokenQuantity();
        if ($tooManyTokens) {
            $this->userModel->removeLastToken($user);
        }

        $user->setLastLogin(new \DateTime());
        $userToken = $this->userTokenModel->createToken($user);
        $user->addToken($userToken);
        $this->userModel->save($user);
        $this->userTokenModel->save($userToken);
        $request->getSession()->set('userId', $user->getId());
        $request->getSession()->set('userToken', $userToken->getToken());
    }

    /**
     * @param $user_id
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function checkToken($user_id, $token)
    {
        /** @var User $user */
        $user = $this->userManager->findUserBy(['id' => $user_id]);
        $this->userManager->reloadUser($user);
        if (empty($user)) {
            return false;
        }
        /** @var UserToken $userToken */
        $userToken = $this->getTokenIfNotExpire($user, $token);
        if (!$userToken instanceof UserToken){
            return false;
        }
        $userToken->setExpireAt($this->userTokenModel->addDateValidTokenInterval());
        $this->userTokenModel->save($userToken);
        return true;
    }

    /**
     * @param User $user
     * @param $token
     * @return bool
     */
    private function getTokenIfNotExpire($user, $token)
    {
        foreach ($user->getTokens() as $UserToken) {
            if ($UserToken->getToken() === $token && !$this->isTokenExpired($UserToken)) {
                return $UserToken;
            }
        }

        return false;
    }
    /**
     * @param UserToken $UserToken
     * @return bool
     */
    protected function isTokenExpired(UserToken $UserToken)
    {
        $CurrentTime = new \DateTime();
        $ExpireAt = clone($UserToken->getExpireAt());

        return $ExpireAt < $CurrentTime;
    }
}