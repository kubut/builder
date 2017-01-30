<?php
namespace BuilderBundle\Services;

use BuilderBundle\Entity\User;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Model\UserModel;
use BuilderBundle\Util\PasswordGenerator;

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

    /**
     * @param integer $userId
     */
    public function removeUser($userId)
    {
        $user = $this->userModel->getUserById($userId);

        $this->userModel->removeUser($user);
    }

    /**
     * @param user $user
     * @param string $password
     *
     * @throws \Exception
     */
    public function changePassword($user, $password)
    {
        if (strlen($password) < 8) {
            throw new \Exception('Password is too short', ExceptionCode::PASSWORD_TOO_SHORT);
        }
        $user->setActivated(true);
        $this->userModel->changePassword($user, $password);
    }

    /**
     * @param integer $userId
     */
    public function resetPassword($userId)
    {
        $user = $this->userModel->getUserById($userId);
        $password = PasswordGenerator::generateStrongPassword();
        $user->setActivated(false);
        $this->userModel->changePassword($user, $password, $password);
    }
}