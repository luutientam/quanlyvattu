<?php
class Auth {
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

/*************  ✨ Codeium Command ⭐  *************/
/******  378dbcfa-0d25-4a87-8e4c-3fddf342a931  *******/
    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    public static function getUserRole() {
        self::startSession();
        return $_SESSION['role'] ?? null;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header("Location: /Luc_Tru/login.php");
            exit();
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if (self::getUserRole() != $role) {
            header("location: /Luc_Tru/notfound.php");
            exit();
        }
    }

    public static function logout() {
        self::startSession();
        session_unset();
        session_destroy();
        header("Location: /Luc_Tru/login.php");
        exit();
    }
}
?>