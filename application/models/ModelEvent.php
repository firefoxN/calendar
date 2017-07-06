<?php
/**
 * @TODO Add verification that the date corresponds to the format in "createEvent" function
 */

namespace app\models;


use app\core\Model;

class ModelEvent extends Model {
	const OBJECT_EVENT = 1;
	const ID_EVENT_EDIT = 2;
	const ID_EVENT_DELETE = 4;

	public static function getAllEvents()
	{
		$db = self::getDatabase();
		$queryString = 'SELECT * FROM object_items';
		$db->select($queryString);
	}

	public static function createEvent(Array $params)
	{
		$name = stripslashes($params['eventName']);
		$name = htmlspecialchars($name);
		$description = stripslashes($params['eventDescription']);
		$description = htmlspecialchars($description);
//		$d = new \DateTime($params['startDate']);
		$startDate = $endDate = trim($params['startDate']);
//		echo $params['startDate'];die;

		$queryString = 'INSERT INTO `object_items` (id_object_rights, name, id_user, description, date_start, date_end) VALUES ({?}, {?}, {?}, {?}, {?}, {?})';
		$user = ModelUserHelper::find(['username'=>$_SESSION['login']]);

		$idUser = $user[0]['id'];
		$arrParams = [
			self::OBJECT_EVENT,
			$name,
			$idUser,
			$description,
			$startDate,
			$endDate
		];

		$db = self::getDatabase();
		$res = $db->query($queryString, $arrParams);

		self::addPermission($idUser, $res);

		return $res;
	}

	protected static function addPermission($idUser, $idItem)
	{
		$queryString = 'INSERT INTO rights_action (id_object_rights, id_rights_name, id_object_item, sign, id_action) VALUES ({?},{?},{?},{?},{?})';
		$arrParams = [
			self::OBJECT_EVENT,
			ModelUserHelper::USER_PERMISSION_ID,
			$idItem,
			1,
			self::ID_EVENT_EDIT
		];
		$db = self::getDatabase();
		$db->query($queryString, $arrParams);
		$arrParams2 = [
			self::OBJECT_EVENT,
			ModelUserHelper::USER_PERMISSION_ID,
			$idItem,
			1,
			self::ID_EVENT_DELETE
		];
		$db->query($queryString, $arrParams2);
	}

	public static function findOne($id)
	{
		$id = intval($id);
		$db = self::getDatabase();
		$queryString = 'SELECT * FROM `object_items` WHERE id = {?}';
		$arrParams = [$id];
		$res =  $db->select($queryString, $arrParams);

		if(!empty($res)) {
			return $res[0];
		} else {
			return false;
		}
	}
}