<?php

/**
 * Project: auth.local;
 * File: User.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 25.11.2019, 0:30
 * Comment:
 */


namespace app\Models;

use ma1ex\Core\Model;

class User extends Model {

    private $table = 'users';

    /**
     * @param array $profileData
     */
    public function add(array $profileData) {
        extract($profileData);
        $this->db->addTable($this->table);
        $this->db
            ->from('users')
            ->insert([
                'login' => $login,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'name' => $name
            ]);
    }

    /**
     * @return array|bool|mixed
     */
    public function getAllEmails() {
        $this->db->cache = false;
        return $this->db->from($this->table)->select('email')->all();
    }

    /**
     * @return array|bool|mixed
     */
    public function getAllLogins() {
        $this->db->cache = false;
        return $this->db->from($this->table)->select('login')->all();
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void {
        $this->table = $table;
    }

}