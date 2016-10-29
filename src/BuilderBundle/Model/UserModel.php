<?php
namespace BuilderBundle\Model;

use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\UserFactory;
use BuilderBundle\Repository\UserRepository;

/**
 * Class UserModel
 * @package BuilderBundle\Model
 */
class UserModel
{
    /** @var UserFactory $userFactory*/
    protected $userFactory;

    /** @var UserRepository $userRepository*/
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

    public function createUser($data)
    {
        if (!$this->userRepository->checkUserUniqueness($data['email'],$data['username'])) {
            throw new \Exception('Not unique username or email', ExceptionCode::USER_NOT_UNIQUE);
        }
        $user = $this->userFactory->createFromArray($data);
        $this->userRepository->save($user);
    }
}