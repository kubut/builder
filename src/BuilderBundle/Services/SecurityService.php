<?php
namespace BuilderBundle\Services;

use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Model\UserModel;
use BuilderBundle\Util\PasswordGenerator;

/**
 * Class SecurityService
 * @package BuilderBundle\Services
 */
class SecurityService
{
    /** @var  UserModel $userModel */
    protected $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param array $data
     *
     * @return string
     * @throws \Exception
     */
    public function register($data)
    {
        if (!$this->validateData($data)) {
            throw new \Exception('Omitted params', ExceptionCode::OMITTED_PARAMS);
        }
        $generatedPassword = PasswordGenerator::generateStrongPassword();
        $data['password'] = $generatedPassword;
        if ($data['role']) {
            $data['roles'] = ['ROLE_ADMIN'];
        }

        $this->userModel->createUser($data);

        return $generatedPassword;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function validateData($data)
    {
        return isset($data['email']) && isset($data['name']) && isset($data['surname']) && isset($data['role']);
    }
}