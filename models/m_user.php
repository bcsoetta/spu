<?php
/*
	Model: pibk
	berisi fungsi2 pembantu utk manipulasi data pibk
*/

class user extends Base_Model{
	function __construct(){
		parent::__construct();
		$this->load_db();	//we load database
		if(!isset($_SESSION)){
			session_start();
			//init search data
			if(!isset($_SESSION['user']))
				$_SESSION['user'] = array();
		}
	}

	//private
	private function getNewTimeOut(){
		//do not forget to update user log info
		return time() + 1800;	//1800secs or 30 mins
	}

	//apakah user termasuk superuser
	function isSuperUser(){
		if(!isset($_SESSION['user']['superuser'])){
			$_SESSION['user']['superuser'] = false;	//bukan super user
			$ip = $_SERVER['REMOTE_ADDR'];
			//kueri database
			$qstring = "SELECT * FROM tb_superuser WHERE ip = :ip";
			try{
				$stmt=$this->db->prepare($qstring);
				$stmt->execute(array(
					'ip'=>$ip
					));
				$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$_SESSION['user']['superuser'] = count($ret) ? true : false;
			}catch(PDOException $e){
			}
		}

		return $_SESSION['user']['superuser'];
		//baca dari database ajah
		/*$superUser = array(
			'192.168.146.211',
			'192.168.146.210',
			'192.168.148.65',
			'::1',
			'127.0.0.1'
			);
		return in_array($_SERVER['REMOTE_ADDR'], $superUser);*/
	}

	//attempt to login
	function tryLogin($uname, $pass){
		$qstring = "SELECT * FROM tb_user WHERE nip = :uname AND password = MD5(:pass);";
		$stmt = $this->db->prepare($qstring);
		$data = array(
			'uname'=>$uname,
			'pass'=>$pass
			);
		//there can be only one
		if($stmt->execute($data) && $stmt->rowCount()==1){
			//user was able to log in...gather some data here
			$_SESSION['user']['loginStatus'] = 1;	//login status
			$_SESSION['user']['timeOut'] = $this->getNewTimeOut();	//the timeout
			//fetch data
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			foreach($data as $k=>$v){
				$_SESSION['user'][$k]=$v;
			}
			return true;
		}
		return false;
	}	

	//attempt logout
	function tryLogout(){
		unset($_SESSION['user']);
		unset($_SESSION['user']['loginStatus']);
		session_destroy();
		//$_SESSION['user'] = array();
	}

	function message($msg){
		if(isset($msg)){
			$_SESSION['user']['message'] = $msg;
		}else
			return $_SESSION['user']['message'];
	}

	//check if user is logged in
	function isLoggedIn(){
		//gotta refresh time here.....
		return isset($_SESSION['user']['loginStatus']);
	}

	//get important data
	function getData(){
		return $_SESSION['user'];
	}

	//get properties
	function getProperty($name){
		return $_SESSION['user'][$name];
	}

	//get
	function isTimeout(){
		return $_SESSION['user']['timeOut'] < time();
	}

	//force redirect
	function forceLogin(){
		if(!$this->isLoggedIn()){
			header('Location: '.base_url('user/login'));
		}else{
			//refresh timer
			if($this->isTimeout()){
				$this->tryLogout();		//logout
				header('Location: '.base_url('user/login'));	//we've timeout, so force new login
			}
			else
				$_SESSION['user']['timeOut'] = $this->getNewTimeOut();	//push timeout further
		}
	}
}
?>