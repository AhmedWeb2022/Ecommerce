<?php


namespace App\Params\Auth;


class LoginParam
{
    protected $email;
    protected $password;


    public function __construct(array $data = [])
    {

        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
    }

    public function setParams(array $data)
    {

        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
