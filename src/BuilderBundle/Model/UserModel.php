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
     * @param User $user
     *
     * @return array
     */
    public function toArray($user)
    {
        $isEnabled = $user->isEnabled();
        $userData =  [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "surname" => $user->getSurname(),
            "email" => $user->getEmail(),
            "isActive" => $isEnabled,
            "role" => $user->hasRole(Role::ADMIN),
        ];

        if (!$isEnabled && is_null($user->getActivationCode())) {
            $userData['activationCode'] = $user->getActivationCode();
        }

        return $userData;
    }
}