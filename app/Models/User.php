<?php

/**
 * Project: auth.local;
 * File: User.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 25.11.2019, 0:30
 * Comment: Model User
 */


namespace app\Models;

use ma1ex\Core\Model;

class User extends Model {

    /**
     * @var string
     */
    private $table = 'users';

    /**
     * Add new user
     *
     * @param array $profileData
     */
    public function add(array $profileData) {
        extract($profileData);

        if ($this->db->emptyTable($this->table)) {
            $this->db->addTable($this->table);
        }

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
     * Get all emails field
     *
     * @return array|bool|mixed
     */
    public function getAllEmails() {
        $this->db->cache = false;
        if (!$this->db->from($this->table)->select('email')->all()) {
            return [];
        }
        return $this->db->from($this->table)->select('email')->all();
    }

    /**
     * Get all logins field
     *
     * @return array|bool|mixed
     */
    public function getAllLogins() {
        $this->db->cache = false;
        if (!$this->db->from($this->table)->select('login')->all()) {
            return [];
        }
        return $this->db->from($this->table)->select('login')->all();
    }

    /**
     * Get all records
     *
     * @return array|bool|mixed
     */
    public function getAll() {
        $this->db->cache = false;
        if (!$this->db->from($this->table)->select()->all()) {
            return [];
        }
        return $this->db->from($this->table)->select()->all();
    }

    public function getAllWithoutPass() {
        $this->db->cache = false;

        if ($this->db->emptyTable($this->table)) {
            return [];
        }

        return $this->db
            ->from($this->table)
            ->select('id, login, email, name')
            ->all();
    }

    /**
     * Set users table
     *
     * @param string $table
     */
    public function setTable(string $table): void {
        $this->table = $table;
    }

}