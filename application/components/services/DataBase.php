<?php

namespace app\components\services;


class DataBase {
	/**
	 * Single instance of a class
	 *
	 * @var null
	 */
	private static $db = null;

	/**
	 * Connection identifier
	 *
	 * @var $mysqli
	 */
	private $mysqli;

	/**
	 * The value symbol in the query
	 *
	 * @var string
	 */
	private $sym_query = "{?}";

	/**
	 * The maximum size of the packet with the query to the database
	 *
	 * @var integer
	 */
	private $mysql_max_allowed_packet;

	/**
	 * Getting an instance of a class
	 *
	 * @param $sqlHost
	 * @param $sqlBase
	 * @param $sqlUser
	 * @param $sqlPassword
	 *
	 * @return DataBase
	 */
	public static function getInstance($sqlHost, $sqlBase, $sqlUser, $sqlPassword) {
		if (self::$db == null) {
			self::$db = new DataBase($sqlHost, $sqlBase, $sqlUser, $sqlPassword);
		}

		return self::$db;
	}

	/**
	 * Returns the value of the maximum packet with a query to the database
	 *
	 * @return int
	 */
	public function getMaxAllowedPacketValue() {
		return $this->mysql_max_allowed_packet;
	}

	/**
	 * A SELECT method that returns a result table
	 *
	 * @param string $query sql query
	 * @param array|bool $params Array with parameters or false, if parameters are not needed
	 *
	 * @return array
	 */
	public function select($query, $params = false) {
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if (!$result_set) {
			return false;
		}

		return $this->resultSetToArray($result_set);
	}

	/**
	 * A SELECT method that returns one row with the result
	 *
	 * @param string $query sql query
	 * @param array|bool $params Array with parameters or false, if parameters are not needed
	 *
	 * @return array
	 */
	public function selectRow($query, $params = false) {
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if ($result_set->num_rows != 1) {
			return false;
		} else {
			return $result_set->fetch_assoc();
		}
	}

	/**
	 * A SELECT method that returns a value from a particular cell
	 *
	 * @param string $query sql query
	 * @param array|bool $params Array with parameters or false, if parameters are not needed
	 *
	 * @return string
	 */
	public function selectCell($query, $params = false) {
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if ((!$result_set) || ($result_set->num_rows != 1)) {
			return false;
		} else{
			$arr = array_values($result_set->fetch_assoc());

			return $arr[0];
		}
	}

	/**
	 * NON-SELECT methods (INSERT, UPDATE, DELETE). If the request is INSERT, then the id of the last inserted record is returned
	 *
	 * @param string $query sql query
	 * @param array|bool $params Array with parameters or false, if parameters are not needed
	 *
	 * @return bool|int
	 */
	public function query($query, $params = false) {
		$success = $this->mysqli->query($this->getQuery($query, $params));

		if ($success) {
			if ($this->mysqli->insert_id === 0) {
				return true;
			} else {
				return $this->mysqli->insert_id;
			}
		} else {
			return false;
		}
	}

	/**
	 * Converting result_set (mysqli_result) to a two-dimensional array
	 *
	 * @param mysqli_result $result_set
	 *
	 * @return array
	 */
	private function resultSetToArray($result_set) {
		$array = array();
		while (($row = $result_set->fetch_assoc()) != false) {
			$array[] = $row;
		}
		return $array;
	}

	/**
	 * Connects to the database
	 *
	 * @param string $sqlHost хост
	 * @param string $sqlBase база данных
	 * @param string $sqlUser пользователь бд
	 * @param string $sqlPassword пароль для доступа к бд
	 *
	 * @throws \Exception в случае неудачного подключения
	 */
	private function connectDB($sqlHost, $sqlBase, $sqlUser, $sqlPassword) {
		$this->mysqli = new \mysqli($sqlHost, $sqlUser, $sqlPassword, $sqlBase);
		if($this->mysqli->connect_errno) {
			throw new \Exception("Ошибка прямого соединения с БД. Дальнейшая работа была прекращена.");
		}
	}

	/**
	 * Recognizes the maximum size of a packet with a query to the database
	 *
	 * @return NULL
	 * @throws \Exception In case of failure
	 */
	private function getDBMaxAllowedPacketSize() {
		$query = 'show variables like "max_allowed_packet"';
		if($result=$this->mysqli->query($query)) {
			$arMaxPak = $result->fetch_assoc();
			if (isset($arMaxPak["Value"]) && intval($arMaxPak["Value"]) > 0) {
				$this->mysql_max_allowed_packet = intval($arMaxPak["Value"]);

				return NULL;
			}
		}
		throw new \Exception("Ошибка определения максимального размера пакета с запросом к БД. Невозможно продолжать работу.");
	}

	/**
	 * An auxiliary method that replaces the "value symbol in the query" with a particular value that passes through the "security functions"
	 *
	 * @param string $query query string
	 * @param array $params Array of parameters to insert into the query
	 *
	 * @return string query string
	 */
	public function getQuery($query, $params) {
		if ($params) {
			foreach($params as $paramValue) {
				$pos = strpos($query, $this->sym_query);
				$arg = '"'.$this->mysqli->real_escape_string($paramValue).'"';
				$query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
			}
			/*for ($i = 0; $i < count($params); $i++) {
				$pos = strpos($query, $this->sym_query);
				$arg = '"'.$this->mysqli->real_escape_string($params[$i]).'"';
				$query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
			}*/
		}

		return $query;
	}

	private function __construct($sqlHost, $sqlBase, $sqlUser, $sqlPassword) {
		$this->connectDB($sqlHost, $sqlBase, $sqlUser, $sqlPassword);
		$this->getDBMaxAllowedPacketSize();
	}

	public function __destruct() {
		if ($this->mysqli) $this->mysqli->close();
	}
}