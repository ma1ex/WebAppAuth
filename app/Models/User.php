<?php

/**
 * Project: auth.local;
 * File: User.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 25.11.2019, 0:30
 * Comment: User Model
 */


namespace app\Models;

use ma1ex\Core\Model;

class User extends Model {

    /**
     * Table name
     *
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
                'name' => $name,
                'token' => $token
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
     * Get all users
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
     * Get one user by condition
     *
     * @param string $column
     * @param string $condition
     * @return array|bool|mixed
     */
    public function getUser(string $column, string $condition) {
        $this->db->cache = false;

        if ($this->db->emptyTable($this->table)) {
            return [];
        }

        return $this->db
            ->from($this->table)
            ->select()
            ->where($column, $condition)
            ->first();
    }

    /**
     * Set authorization token
     *
     * @param string $userLogin
     * @param string $token
     * @return mixed
     */
    public function setToken(string $userLogin, string $token) {
        return $this->db
            ->from($this->table)
            ->where('login', $userLogin)
            ->limit(1)
            ->update(['token' => $token]);
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