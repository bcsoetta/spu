<?php
class C_user extends Base_Controller{
	public function __construct(){
		parent::__construct();	//call parent's ctor
		//load all model used
		$this->load_model('user');
	}
	
	function index(){
		//if we're not expired, keep alive
	}

	function login($error){
		$data=array();
		$data['pagetitle'] = 'PIBK - Login';
		if(isset($_GET['error']))
			$data['loginErr']=1;
		$this->load_view('login', $data);
	}

	function logout(){
		$this->user->tryLogout();
		header('location: '.base_url(''));
	}

	function validate(){
		//validasi login. redirect ke index klo berhasil
		//balikin ke login kalo gagal
		//header('location: '.base_url('user/login?error=1'));
		if(!isset($_POST['username']) || !isset($_POST['password'])){
			//insufficient data. redirect
			header('location: '.base_url('user/login?error=1'));
		}else{
			//attempt login
			if($this->user->tryLogin($_POST['username'], $_POST['password'])){
				//redirect to index page
				$this->user->message('Selamat Datang');
				header('location: '.base_url(''));
			}else{
				//failed, redirect back to error page
				header('location: '.base_url('user/login?error=1'));
			}
		}
		/*$data=array();
		$data['pagetitle'] = 'PIBK-Login';
		$data['loginErr'] = 1;
		$this->load_view('login', $data);*/
	}
}
?>