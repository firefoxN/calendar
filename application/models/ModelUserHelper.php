<?php

namespace app\models;


class ModelUserHelper extends ModelUser {

	/**
	 * @param array $params
	 *
	 * @return bool
	 */
	public static function checkRegistration(Array $params)
	{
		if (isset($params['registration']) && isset($params['invite']) && isset($params['email'])) {
			return true;
		} else {
			return false;
		}
	}

	public static function checkLogin(Array $params)
	{
		if (isset($params['login']) && isset($params['username']) && isset($params['password'])) {
			return true;
		} else {
			return false;
		}
	}

	public static function isAuthorized()
	{
		if (!empty($_SESSION["user"])) {
			return (bool) $_SESSION["user"];
		}
		return false;
	}

	public function logout()
	{
		if (!empty($_SESSION["login"]) && $_SESSION["user"]) {
			unset($_SESSION["login"]);
			unset($_SESSION["user"]);
		}
	}

	public static function saveSession($login, $remember = false, $http_only = true, $days = 7)
	{
		$_SESSION["login"] = $login;
		$_SESSION['user'] = true;

		if ($remember) {
			// Save session id in cookies
			$sid = session_id();

			$expire = time() + $days * 24 * 3600;
			$domain = ""; // default domain
			$secure = false;
			$path = "/";

			$cookie = setcookie("sid", $sid, $expire, $path, $domain, $secure, $http_only);
		}
	}
}