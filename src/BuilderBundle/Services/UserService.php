<?php
namespace BuilderBundle\Services;

use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Model\UserModel;

/**
 * Class UserService
 * @package BuilderBundle\Services
 */
class UserService
{
    /** @var  UserModel $userModel */
    protected $userModel;

    /**
     * UserService constructor.
     * @param UserModel $userModel
     */
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getAllUsers($userId)
    {
        $userData = [];
        $users = $this->userModel->getAllUsers($userId);

        foreach ($users as $user) {
            $userData[] = $this->userModel->toArray($user);
        }

        return $userData;
    }
}