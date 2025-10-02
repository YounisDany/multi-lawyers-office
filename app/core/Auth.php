<?php

class Auth {
    /**
     * التحقق مما إذا كان المستخدم مسجلاً للدخول.
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION["user_id"]);
    }

    /**
     * التحقق مما إذا كان المستخدم يمتلك دورًا معينًا.
     * @param string $role الدور المطلوب (مثال: client, lawyer, admin).
     * @return bool
     */
    public static function hasRole($role) {
        return isset($_SESSION["user_role"]) && $_SESSION["user_role"] === $role;
    }

    /**
     * تسجيل دخول المستخدم.
     * @param object $user كائن المستخدم الذي تم تسجيل دخوله.
     */
    public static function login($user) {
        $_SESSION["user_id"] = $user->id;
        $_SESSION["user_name"] = $user->name;
        $_SESSION["user_email"] = $user->email;
        $_SESSION["user_role"] = $user->role;
    }

    /**
     * تسجيل خروج المستخدم.
     */
    public static function logout() {
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
        unset($_SESSION["user_email"]);
        unset($_SESSION["user_role"]);
        session_destroy();
    }

    /**
     * الحصول على معرف المستخدم الحالي.
     * @return int|null
     */
    public static function userId() {
        return self::isLoggedIn() ? $_SESSION["user_id"] : null;
    }

    /**
     * الحصول على دور المستخدم الحالي.
     * @return string|null
     */
    public static function userRole() {
        return self::isLoggedIn() ? $_SESSION["user_role"] : null;
    }
}

