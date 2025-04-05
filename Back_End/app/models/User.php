<?php

namespace App\Models;

class User extends Model
{
    public function create($name, $email, $password, $role = 'user')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        if (strlen($email) > 254) {
            throw new \InvalidArgumentException('Email is too long');
        }

        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, 'MX')) {
            throw new \InvalidArgumentException('Invalid email domain');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = self::$pdo->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$name, $email, $hashedPassword, $role]);

        return $this->findById(self::$pdo->lastInsertId());
    }

    public function findByEmail($email)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // src/Models/User.php
        public function update($id, $fields)
    {
        $updates = [];
        $params = [];

        // Validate and prepare fields dynamically
        if (isset($fields['email'])) {
            if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Invalid email format');
            }

            $domain = substr(strrchr($fields['email'], "@"), 1);
            if (!checkdnsrr($domain, 'MX')) {
                throw new \InvalidArgumentException('Invalid email domain');
            }

            $updates[] = "email = :email";
            $params['email'] = $fields['email'];
        }

        if (isset($fields['password']) && trim($fields['password']) !== '') {
            $hashedPassword = password_hash($fields['password'], PASSWORD_DEFAULT);
            $updates[] = "password = :password";
            $params['password'] = $hashedPassword;
        }

        if (empty($updates)) {
            throw new \InvalidArgumentException('No valid fields to update');
        }

        $params['id'] = $id;

        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = :id";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);

        return $this->findById($id);
    }   


    
}
