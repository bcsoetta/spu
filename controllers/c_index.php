<?php
class C_index extends Base_Controller{
	public function __construct(){
		parent::__construct();	//call parent's ctor
		//load all model used
		$this->load_model('user');
	}
	
	function index(){
		//echo "shit";
		$this->user->message('Selamat Datang');
		header('location: '.base_url('surat/browse/'));
	}
}
?>