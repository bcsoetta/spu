<?php
class C_surat extends Base_Controller{
	public function __construct(){
		parent::__construct();	//call parent's ctor
		//load all model used
		$this->load_model('user');
		$this->load_model('surat');
	}
	
	function index(){
		//if we're not expired, keep alive
		header('location: '.base_url('surat/browse'));
	}

	//buat browse. type { proses, selesai, reject }
	function browse($owner, $segmen, $pageId){
		//set ke hal 1
		if(is_null($pageId))
			$pageId =1;

		$data=array();
		$data['pagetitle'] = 'Aplikasi Surat Keluar';
		$data['user'] = $this->user->getData();
		$data['mainContent'] = 'p_main.php';
		$data['jabatan'] = $this->surat->getJabatan();
		$data['jenisSurat'] = $this->surat->getJenisSurat($segmen);
		$data['superUser'] = $this->user->isSuperUser();

		//set settting mode browsing
		$this->surat->setSetting('kk', 'mode', 'browse');

		if(is_null($owner) || is_null($segmen)){
			$data['tableData'] = array();
			$this->surat->setSetting('kk', 'totalPage', 0);
			$this->surat->setSetting('kk', 'totalItem', 0);
			if(!is_null($owner))
				$this->surat->setSetting('kk', 'section', $owner);
			else
				$this->surat->setSetting('kk', 'section', '');
		}
		else
			$data['tableData'] = $this->surat->getSurat(20, $pageId, $segmen, $owner);
		$data['pageInfo'] = $this->surat->getSettings('kk');

		$this->load_view('index', $data);
	}

	//buat nyari surat
	function search($owner, $type, $pageId){
		//LAKUKAN PENCARIAN!!!!!!!
		//SETTING SEGALA MACAM DEMI BERJALANNYA SEARCH ENGINE!!!
		$newSearch=false;
		if(is_null($pageId)){
			$pageId=1;
			//di sini berarti pertama kali searching
			$newSearch=true;
		}
		//set settting mode searching
		$this->surat->setSetting('kk', 'mode', 'search');
		//data2 pencarian
		if($newSearch){
			$nomor = trim($_POST['nomor']);
			$hal = trim($_POST['hal']);
			$ke = trim($_POST['ke']);
			$kepada = trim($_POST['kepada']);
			$tembusan = trim($_POST['tembusan']);
			$lampiran = trim($_POST['lampiran']);
			$keterangan = trim($_POST['keterangan']);
			$konseptor = trim($_POST['konseptor']);
			//kalo ke <> '' AND '-' baru save
			if($ke=='' OR $ke=='-')
				$kepada='';
			//simpan ke dalam session
			$this->surat->saveSearchParams($nomor, $hal, $ke, $kepada, $tembusan, $lampiran, $keterangan, $konseptor);
		}else{
			$params = $this->surat->getSearchParams();

			$nomor = trim($params['nomor']);
			$hal = trim($params['hal']);
			$ke = trim($params['ke']);
			$kepada = trim($params['kepada']);
			$tembusan = trim($params['tembusan']);
			$lampiran = trim($params['lampiran']);
			$keterangan = trim($params['keterangan']);
			$konseptor = trim($params['konseptor']);
		}
		//settingan standar
		if(!is_null($owner))
			$this->surat->setSetting('kk', 'section', $owner);
		//simpan ke dalam
		//lempar data ke search engine
		$result = $this->surat->cariSurat(20, $pageId,$owner,$type,$nomor,$hal,$ke,$kepada,$tembusan,
			$lampiran,$keterangan,$konseptor);
		//hasil search bakal mempengaruhi settting
		$setting=$this->surat->getSettings('kk');

		//tampilkan
		$data=array();
		$data['pagetitle'] = 'Aplikasi Surat Keluar';
		$data['user'] = $this->user->getData();
		$data['mainContent'] = 'p_main.php';
		$data['jabatan'] = $this->surat->getJabatan();
		$data['jenisSurat'] = $this->surat->getJenisSurat($type);
		$data['pageInfo'] = $setting;
		$data['tableData'] = $result;
		$data['superUser'] = $this->user->isSuperUser();

		$this->load_view('index', $data);
	}

	//ini page untuk menampilkan isi surat + edit
	function view($id){
		$surat = $this->surat->getDataSurat($id);
		if($surat===null){
			$this->user->message('Surat tidak ditemukan');
			header('location: '.base_url(''));
			return;
		}

		$data=array();
		$data['pagetitle'] = 'Aplikasi Surat Keluar';
		$data['user'] = $this->user->getData();
		$data['mainContent'] = 'p_view.php';
		$data['jabatan'] = $this->surat->getJabatan();
		$data['surat'] = $this->surat->getDataSurat($id);
		$data['jenisSurat'] = $this->surat->getAllJenisSurat();
		$data['superUser'] = $this->user->isSuperUser();
		$data['pageInfo'] = $this->surat->getSettings('kk');

		$this->load_view('index', $data);
	}

	//fungsi ini merupakan form processor dari input surat
	//TODO : validasi!!
	function input($owner){
		if($owner==''){
			$this->user->message('Folder surat tidak valid');

		}else{
			//ambil data dari post
			$type=$_POST['type'];
			$head=$_POST['head'];
			$tgl=$_POST['tglSurat'];
			$hal=$_POST['hal'];
			$dari=$_POST['dari'];
			$ke=$_POST['ke'];
			$kepada=$_POST['kepada'];
			$tembusan=$_POST['tembusan'];
			$lampiran=$_POST['lampiran'];
			$konseptor=$_POST['konseptor'];
			$keterangan=$_POST['keterangan'];
	
			$ret=$this->surat->addSurat($owner, $type, $head, $tgl, $hal, $dari, $ke, $kepada, $tembusan, $lampiran, $keterangan, $konseptor, $no_surat);
			if($ret===true){
				$this->user->message('Surat telah terinput dengan nomor: '.$no_surat);
			}else{
				//gagal
				$this->user->message('Gagal input surat: '.$ret);
			}
		}
		//arahin balik ke browse
		$settings = $this->surat->getSettings('kk');
		$loc = base_url('surat/browse/'.$settings['section'].'/'.$settings['type']);
		header('location: '.$loc);
	}

	//fungsi ini ngedelete surat
	function delete($id){
		//pertama, siapin kembalian
		$setting = $this->surat->getSettings('kk');
		$location = base_url("surat/$setting[mode]/$setting[section]/$setting[type]/$setting[pageId]");
		if(!$this->user->isSuperUser()){
			$this->user->message('Ente kagak berhak!!');
			header('location:'.$location);
		}else{
			//ambil data utk terakhir kali
			$surat = $this->surat->getDataSurat($id);
			if($surat!==null){
				//ketemu
				$this->surat->delSurat($id);
				$this->user->message('Surat nomor ['.$surat['no_surat'].'] dah kehapus gan!');
				header('location:'.$location);
			}else{
				//kagak ketemu
				$this->user->message('Suratnya kagak ketemu gan!');
				header('location:'.$location);
			}
		}
	}

	//fungsi ini untuk ngupdate data surat
	//a.k.a form processor
	function update($id){
		//kumpulin data
		//print_r($_POST);
		$id 	=$_POST['id'];
		$idjenis=$_POST['idjenis'];
		$tanggal=$_POST['tanggal'];
		$dari	=$_POST['dari'];
		$ke 	=$_POST['ke'];
		$kepada	=$_POST['kepada'];
		$hal 	=$_POST['hal'];
		$tembusan=$_POST['tembusan'];
		$lampiran=$_POST['lampiran'];
		$keterangan=$_POST['keterangan'];
		$konseptor=$_POST['konseptor'];

		$ret=$this->surat->updateSurat($id, $idjenis, $tanggal, $dari, $ke, $kepada, $hal,
			$tembusan, $lampiran, $keterangan, $konseptor);

		//siap2 berpindah
		$setting=$this->surat->getSettings('kk');
		$location = base_url("surat/$setting[mode]/$setting[section]/$setting[type]/$setting[pageId]");

		$surat = $this->surat->getDataSurat($id);
		if($ret !== false){
			//berhasil, ambil data baru
			$this->user->message('Data Surat nomor ['.$surat['no_surat'].'] berhasil diubah');
		}else{
			$this->user->message('Data Surat nomor ['.$surat['no_surat'].'] gagal diubah');
		}
		header('location: '.$location);
	}

	//ni fungsi nanganin pergantian section
	//di halaman utama (selector)
	function change_page(){
		print_r($_POST);
		//simple. cukup redirect
		$section = $_POST['section'];
		$location = base_url('surat/browse/'.$section);
		header('location: '.$location);
	}
}
?>