<?php
namespace BuilderBundle\Services;

use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Model\UserModel;

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
        $generatedPassword = $this->generateStrongPassword();
        $data['password'] = $generatedPassword;
        if ($data['role']){
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

    /**
     * @param int $length
     * @param bool $add_dashes
     * @param string $available_sets
     *
     * @return string
     */
    private function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
}