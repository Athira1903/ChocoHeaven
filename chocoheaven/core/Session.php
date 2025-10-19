<?php
class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public function remove($key) {
        unset($_SESSION[$key]);
    }

    public function destroy() {
        session_destroy();
    }

    public function setUser($user) {
        $this->set('user_id', $user['id']);
        $this->set('username', $user['username']);
        $this->set('email', $user['email']);
    }

    public function getUser() {
        return [
            'id' => $this->get('user_id'),
            'username' => $this->get('username'),
            'email' => $this->get('email')
        ];
    }

    public function isLoggedIn() {
        return $this->get('user_id') !== null;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit;
        }
    }
}
?>