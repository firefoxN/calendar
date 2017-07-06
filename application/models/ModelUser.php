<?php

namespace app\models;

use app\components\library\ServiceLocator;
use app\core\Model;

class ModelUser extends Model {
	const USER_PERMISSION_ID = 1;

	public static function find(Array $params)
	{
		$db = self::getDatabase();
		$queryStreeng = 'SELECT * FROM `user`';

		if (!empty($params)) {
			$queryStreeng .= ' WHERE';

			foreach ($params as $name=>$param) {
				$queryStreeng .= ' `'.$name.'` = {?}';
			}
		}

		return $db->select($queryStreeng, $params);
	}

	public static function createUser(Array $params)
	{
		$arrErr = self::checkRegParams($params);
		if (empty($arrErr)) {
			$queryString = 'INSERT INTO `user` (username, password, email, salt) VALUES';
			$login = stripslashes($params['username']);
			$login = htmlspecialchars($login);

			$salt = self::salt();
			$pass = md5(md5($params['password']).$salt);
			$email = trim($params['email']);

			$queryString .= ' ({?}, {?}, {?}, {?})';

			$arrParams = [$login, $pass, $email, $salt];

			$db = self::getDatabase();

			return $db->query($queryString, $arrParams);
		}

		return $arrErr;
	}

	public static function addPermissions($id)
	{
		$queryString = 'INSERT INTO `rights_group` (id_rights_name, id_user) VALUES ({?}, {?})';
		$params = [self::USER_PERMISSION_ID, intval($id)];

		$db = self::getDatabase();

		$db->query($queryString, $params);

		$queryString2 = 'INSERT INTO `rights_name` (name, one_user) VALUES ({?}, {?})';
		$queryParams = [$_SESSION['login'], 1];

		$db->query($queryString2, $queryParams);
	}

	public static function signin(Array $params)
	{
		$arrErr = self::checkLoginParams($params);
		if (empty($arrErr)) {
			$user = self::find(['username'=>$params['username']]);
			if (empty($user) || $user==false) {
				$arrErr[] = 'User with such login was not found in the database';
			} else {
				if(md5(md5($params['password']).$user[0]['salt']) ==$user[0]['password']) {
					return true;
				}
			}

		}

		return $arrErr;
	}

	public static function checkLoginParams($params)
	{
		$err = [];
		if(!preg_match("/^[a-zA-Z0-9]+$/",$params['username'])) {
			$err[] = "Login can only consist of letters of the English alphabet and numbers";
		}

		if(strlen($params['username']) < 3 or strlen($params['username']) > 16) {
			$err[] = "Login must be at least 3 characters and not more than 16";
		}

		if(!preg_match("/^[a-zA-Z0-9_]+$/",$params['password'])) {
			$err[] = "Password can only consist of letters of the English alphabet, numbers and symbol _";
		}

		return $err;
	}

	public static function checkRegParams($params)
	{
		$err = [];
		if(!preg_match("/^[a-zA-Z0-9]+$/",$params['username'])) {
			$err[] = "Login can only consist of letters of the English alphabet and numbers";
		}

		if(strlen($params['username']) < 3 or strlen($params['username']) > 16) {
			$err[] = "Login must be at least 3 characters and not more than 16";
		}

		$checkUser = self::find(['username'=>$params['username']]);
		if ($checkUser !== false && !empty($checkUser)) {
			$err[] = 'A user with a such login already exists in the database';
		}

		if(!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $params['email'])) {
			$err[] = 'Uncorrect E-mail';
		}

		$checkInvite = self::checkInvite($params['invite']);
		if ($checkInvite == false) {
			$err[] = 'Incorrect invit code or it is already activated';
		}

		return $err;
	}

	protected static function checkInvite($invite)
	{
		$db = self::getDatabase();
		$queryStreeng = 'SELECT * FROM `invite_codes` WHERE `invite` = {?} AND `status` = 0';
		$params = [$invite];

		return $db->select($queryStreeng, $params);
	}

	public static function salt()
	{
		$salt = substr(md5(uniqid()), -8);

		return $salt;
	}

	public static function passwordHash($password, $salt = null, $iterations = 10)
	{
		$salt || $salt = uniqid();
		$hash = md5(md5($password . md5(sha1($salt))));

		for ($i = 0; $i < $iterations; ++$i) {
			$hash = md5(md5(sha1($hash)));
		}

		return array('hash' => $hash, 'salt' => $salt);
	}
}