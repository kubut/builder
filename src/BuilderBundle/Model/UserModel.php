<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\User;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\UserFactory;
use BuilderBundle\Repository\UserRepository;
use BuilderBundle\Util\Role;

/**
 * Class UserModel
 * @package BuilderBundle\Model
 */
class UserModel
{
    /** @var UserFactory $userFactory */
    protected $userFactory;

    /** @var UserRepository $userRepository */
    protected $userRepository;

    /**
     * UserModel constructor.
     * @param UserFactory $userFactory
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserFactory $userFactory,
        UserRepository $userRepository
    )
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    public function createUser(array $data)
    {
        if (!$this->userRepository->checkUserUniqueness($data['email'])) {
            throw new \Exception('Not unique username or email', ExceptionCode::USER_NOT_UNIQUE);
        }
        $user = $this->userFactory->createFromArray($data);
        $this->userRepository->save($user);
    }

    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getAllUsers($userId)
    {
        return $this->userRepository->fetchAllUserBesides($userId);
    }

    /**
     * @param integer $userId
     *
     * @return User
     * @throws \Exception
     */
    public function getUserById($userId)
    {
        $user = $this->userRepository->find($userId);

        if (is_null($user)) {
            throw new \Exception('User not exist', ExceptionCode::USER_NOT_EXIST);
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function toArray($user)
    {
        $isActivated = $user->getActivated();
        $userData = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "surname" => $user->getSurname(),
            "email" => $user->getEmail(),
            "isActive" => $isActivated,
            "role" => $user->hasRole(Role::ADMIN),
        ];

        if (!$isActivated && !is_null($user->getActivationCode())) {
            $userData['activationCode'] = $user->getActivationCode();
        }

        return $userData;
    }

    /**
     * @param User $user
     * @param string $password
     * @param null|string $activationCode
     */
    public function changePassword($user, $password, $activationCode = null)
    {
        $user->setPlainPassword($password);
        $user->setActivationCode($activationCode);
        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     */
    public function removeLastToken($user)
    {
        $UserToken = $user->getTokens()[0];
        $this->userRepository->removeToken($UserToken);
    }

    /**
     * @param $user
     */
    public function save($user)
    {
        $this->userRepository->save($user);
    }

    /**
     * @param $user
     */
    public function removeUser($user)
    {
        $this->userRepository->remove($user);
    }
}