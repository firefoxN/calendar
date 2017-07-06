<?php

namespace app\controllers;

use app\core\Controller;
use app\models\ModelEvent;
use app\models\ModelUserHelper;

class ControllerMain extends Controller
{
	public function actionIndex()
	{
		$login = false;

		$guest = !ModelUserHelper::isAuthorized();
		$this->makeRedirect($guest);

		$login = !$guest;

		if (isset($_POST['form'])) {
			$arr = json_decode($_POST['form'], true);
			$flagRegistration = ModelUserHelper::checkRegistration($arr);
			if ($flagRegistration === true) {
				$userId = $this->createUser($arr);
				ModelUserHelper::addPermissions($userId);
				return;
			}

			$flagLogin = ModelUserHelper::checkLogin($arr);
			if ($flagLogin === true) {
				$this->login($arr);
				return;
			}
		}

		$this->view->generate('main_view.php', 'template_view.php', [
			'login' => $login,
		]);
	}

	public function actionAddEvent()
	{
//		$arr = json_decode('{"eventName":"event #2","eventDescription":"dgdgfg","startDate":"2017-06-12"}', true);
		$arr = json_decode($_POST['formevent'], true);
		$newItemId = ModelEvent::createEvent($arr);
		$newItem = ModelEvent::findOne($newItemId);

		echo json_encode(['item'=>$newItem]);
	}

	public function actionGetAllEvents()
	{

	}

	public function createUser($arr)
	{
			$newUserId = ModelUserHelper::createUser($arr);
			if (is_array($newUserId)) {
				echo json_encode(['errors'=>$newUserId]);
			} else {
				ModelUserHelper::saveSession($arr['username']);
				echo json_encode(['result'=>true]);
			}
	}

	public function login($arr)
	{
			$res = ModelUserHelper::signin($arr);
			if ($res===true) {
				ModelUserHelper::saveSession($arr['username']);
				echo json_encode(['result'=>true]);
			} else {
				echo json_encode(['errors'=>$res]);
			}
	}

	/**
	 * @return array|null
	 */
	private function getUser()
	{
		if (isset($_SESSION['login'])) {
			$user = ModelUserHelper::find([
				'username' => $_SESSION['login'],
			]);

			return $user;
		}

		return null;
	}

	/**
	 * @param bool $guest
	 */
	private function makeRedirect($guest)
	{
		if ($guest === true && (!isset($_GET['registration']) && !isset($_GET['login']))) {
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/?login');
		}
	}

	private function getDatabase()
	{
		$database = $this->locator->get('database');

		return $database;
	}
}