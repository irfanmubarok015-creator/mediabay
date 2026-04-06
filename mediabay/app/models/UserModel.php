<?php
// UserModel
class UserModel extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function authenticate(string $email, string $password): ?array {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
