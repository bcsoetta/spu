<?php
class surat extends Base_Model{
	function __construct(){
		parent::__construct();
		$this->load_db();
		if(!isset($_SESSION)){
			session_start();
		}
		$this->getSettings('kk');
	}

	function resetSettings($name='kk'){
		$_SESSION[$name]=array(
			'pageId'=>1,
			'totalPage'=>1,
			'totalItem'=>0,
			'section'=>'kpu.03',
			'type'=>'SKEL',
			'itemPerPage'=>20,
			'mode'=>'browse'
			);
	}

	function setSetting($for, $name, $value){
		$_SESSION[$for][$name]=$value;
	}

	function getSetting($for, $name){
		if(!isset($_SESSION[$for]))
			$this->resetSettings($for);
		return $_SESSION[$for][$name];
	}

	function getSettings($for){
		if(!isset($_SESSION[$for]))
			$this->resetSettings($for);
		return $_SESSION[$for];
	}

	function saveSearchParams($nomor, $hal, $ke, $kepada, $tembusan, $lampiran, $keterangan, $konseptor){
		if(!isset($_SESSION['search']))
			$_SESSION['search']=array();
		$_SESSION['search']['nomor']=$nomor;
		$_SESSION['search']['hal']=$hal;
		$_SESSION['search']['ke']=$ke;
		$_SESSION['search']['kepada']=$kepada;
		$_SESSION['search']['tembusan']=$tembusan;
		$_SESSION['search']['lampiran']=$lampiran;
		$_SESSION['search']['keterangan']=$keterangan;
		$_SESSION['search']['konseptor']=$konseptor;
	}

	function getSearchParams(){
		if(!isset($_SESSION['search']))
			$this->saveSearchParams('','','','','','','');
		return $_SESSION['search'];
	}

	function getSurat($itemLimit, $pageId, $jenis, $owner){
		//update setting
		//$qstring="SELECT tb_surat.*, DATE_FORMAT(tanggal, '%d/%m/%Y') AS tgl_surat, CONCAT(tb_surat.head, '-',  LPAD(tb_surat.nomor,5,'0'),tb_jabatan.kop, YEAR(tanggal))AS no_surat FROM `tb_surat` LEFT JOIN tb_jabatan ON tb_surat.dari=tb_jabatan.kode WHERE jenis=:jenis ORDER BY id DESC LIMIT :itemcount OFFSET :offset;";
		$qstring="SELECT tb_surat.*, DATE_FORMAT(tanggal, '%d/%m/%Y') AS tgl_surat, CASE WHEN ke = '-' THEN tb_surat.kepada ELSE j3.uraian END AS receiver, CONCAT(tb_surat.head, '-',  LPAD(tb_surat.nomor,5,'0'),j1.kop, YEAR(waktu_input))AS no_surat, j1.uraian AS ttd, j2.uraian AS konseptor2 FROM `tb_surat` LEFT JOIN tb_jabatan j1 ON tb_surat.dari=j1.kode LEFT JOIN tb_jabatan  j2 ON tb_surat.konseptor=j2.kode LEFT JOIN tb_jabatan j3 ON tb_surat.ke=j3.kode WHERE jenis=:jenis AND dari=:owner ORDER BY id DESC LIMIT :itemcount OFFSET :offset;";
		$data=array(
			'jenis'=>$jenis,
			'itemcount'=>$itemLimit,
			'offset'=>$itemLimit * ($pageId-1),
			'owner'=>$owner
			);

		try{
			$stmt = $this->db->prepare($qstring);
			$stmt2 = $this->db->prepare("SELECT COUNT(id) AS totalItem, CEIL(COUNT(id)/:itemcount) AS totalPage FROM `tb_surat` WHERE jenis=:jenis AND dari=:owner;");

			$stmt->execute($data);
			$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//cari info page
			$r=$stmt2->execute(array(
				'itemcount'=>$itemLimit, 
				'jenis'=>$jenis,
				'owner'=>$owner
				));
			$ret2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
			$info = $ret2[0];

			$this->getSettings('kk');

			$this->setSetting('kk','pageId', $pageId);
			$this->setSetting('kk', 'itemPerPage', $itemLimit);
			$this->setSetting('kk', 'section', $owner);
			$this->setSetting('kk','totalPage', $info['totalPage']);
			$this->setSetting('kk','totalItem', $info['totalItem']);
			$this->setSetting('kk', 'type', $jenis);

			return $ret;
		}catch(PDOExeption $e){
			echo $e->getMessage();
			return null;
		}
		return null;
	}

	//ini fungsi buat cari surat
	function cariSurat($itemLimit, $pageId, $owner, $type, $nomor, $hal, $ke, $kepada, $tembusan, $lampiran, $keterangan, $konseptor){
		//build dynamic query
		$data=array(
			'owner'=>$owner,
			'type'=>$type,
			'itemcount'=>$itemLimit,
			'offset'=>($pageId-1)*$itemLimit
			);
		$whereClause = " dari=:owner AND jenis=:type ";

		//isi opsi
		if(strlen($nomor)){
			$data['nomor']=$nomor;
			$whereClause.=" AND nomor = :nomor ";
		}
		if(strlen($hal)){
			$data['hal']="%$hal%";
			$whereClause.=" AND hal LIKE :hal ";
		}
		if(strlen($ke)){
			$data['ke']=$ke;
			$whereClause.=" AND ke = :ke ";
		}
		if(strlen($kepada)){
			$data['kepada']="%$kepada%";
			$whereClause.=" AND kepada LIKE :kepada ";
		}
		if(strlen($tembusan)){
			$data['tembusan']="%$tembusan%";
			$whereClause.=" AND tembusan LIKE :tembusan ";
		}
		if(strlen($lampiran)){
			$data['lampiran']="%$lampiran%";
			$whereClause.=" AND lampiran LIKE :lampiran ";
		}
		if(strlen($keterangan)){
			$data['keterangan']="%$keterangan%";
			$whereClause.=" AND keterangan LIKE :keterangan ";
		}
		if(strlen($konseptor)){
			$data['konseptor']=$konseptor;
			$whereClause.=" AND konseptor = :konseptor ";
		}

		$qstring = "SELECT tb_surat.*, DATE_FORMAT(tanggal, '%d/%m/%Y') AS tgl_surat, CONCAT(tb_surat.head, '-',  LPAD(tb_surat.nomor,5,'0'),j1.kop, YEAR(waktu_input))AS no_surat, j1.uraian AS ttd, j2.uraian AS konseptor2, CASE WHEN ke = '-' THEN tb_surat.kepada ELSE j3.uraian END AS receiver FROM `tb_surat` LEFT JOIN tb_jabatan AS j1 ON tb_surat.dari=j1.kode LEFT JOIN tb_jabatan AS j2 ON tb_surat.konseptor=j2.kode LEFT JOIN tb_jabatan j3 ON tb_surat.ke=j3.kode WHERE ".$whereClause." ORDER BY id DESC LIMIT :itemcount OFFSET :offset;";
		$qstring2="SELECT COUNT(id) AS totalItem, CEIL(COUNT(id)/:itemcount) AS totalPage FROM `tb_surat` WHERE ".$whereClause;

		/*echo $qstring.'<br>';
		echo $qstring2.'<br>';*/
		try{
			//kueri pertama (data)
			$stmt=$this->db->prepare($qstring);
			$stmt->execute($data);

			$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);

			//jalanin kueri2 (pagination)
			unset($data['offset']);

			$stmt2=$this->db->prepare($qstring2);
			$stmt2->execute($data);

			$ret2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			$info=$ret2[0];

			$this->getSettings('kk');

			$this->setSetting('kk','pageId', $pageId);
			$this->setSetting('kk', 'itemPerPage', $itemLimit);
			$this->setSetting('kk', 'section', $owner);
			$this->setSetting('kk','totalPage', $info['totalPage']);
			$this->setSetting('kk','totalItem', $info['totalItem']);
			$this->setSetting('kk', 'type', $type);

			return $ret;
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return null;
	}

	//ambil data surat. paramternya
	//id dalam database
	function getDataSurat($id){
		$qstring = "SELECT tb_surat.*, j1.uraian AS ttd, j2.uraian AS konseptor2, CONCAT(head,'-',LPAD(nomor, 5, '0'),j1.kop,YEAR(waktu_input)) AS no_surat, DATE_FORMAT(tanggal, '%d/%m/%Y') AS tgl_surat FROM tb_surat LEFT JOIN tb_jabatan AS j1 ON tb_surat.dari=j1.kode LEFT JOIN tb_jabatan AS j2 ON tb_surat.konseptor=j2.kode WHERE tb_surat.id=:id";
		try{
			$stmt=$this->db->prepare($qstring);
			$stmt->execute(array(
				'id'=>$id
				));
			$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($ret)!==1)
				return null;
			return $ret[0];
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return null;	//means we get nothing
	}

	//fungsi ini mengembalikan seluruh data
	//uraian jabatan yang ada dalam database
	function getJabatan(){
		$qstring = "SELECT *, CASE LENGTH(kode) WHEN 5 THEN CONCAT('-', uraian) WHEN 7 THEN CONCAT('--', uraian) ELSE uraian END AS uraian2 FROM tb_jabatan";
		try{
			$stmt = $this->db->prepare($qstring);
			$stmt->execute();
			$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ret;
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return null;
	}

	//sama ky atas, tapi data seluruh jenis surat
	function getJenisSurat($type){
		$qstring = "SELECT *, CONCAT(uraian, ' (', head,')') AS display FROM tb_jenis_surat WHERE jenis=:jenis;";
		try{
			$stmt=$this->db->prepare($qstring);
			$stmt->execute(array('jenis'=>$type));
			$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ret;
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return null;
	}

	//kalo ini ambil semua jenis surat
	function getAllJenisSurat(){
		$qstring = "SELECT *, CONCAT(uraian, ' (', head,')') AS display FROM tb_jenis_surat";
		try{
			$stmt=$this->db->prepare($qstring);
			$stmt->execute();
			$ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ret;
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return null;	
	}

	//fungsi ini nginput data surat baru
	//ke dalam database
	function addSurat($owner, $type, $head, $tgl, $hal, $dari, $ke, $kepada, $tembusan, $lampiran, $keterangan, $konseptor, &$nomor){
		$qstring = "INSERT INTO tb_surat(jenis, head, nomor, tanggal, hal, dari, ke, kepada, tembusan, lampiran, keterangan, konseptor)
				VALUES(:type, :head, getSequence(:owner, YEAR(NOW()), :head2), STR_TO_DATE(:tgl, '%d/%m/%Y'), :hal, :dari, :ke, :kepada, :tembusan, :lampiran, :keterangan, :konseptor);";
		$qstring2 = "SELECT CONCAT(head, '-', LPAD(nomor, 5, '0'), tb_jabatan.kop, YEAR(tanggal) ) AS no_surat FROM tb_surat LEFT JOIN tb_jabatan ON tb_surat.dari=tb_jabatan.kode WHERE tb_surat.id=LAST_INSERT_ID();";
		try{
			$stmt = $this->db->prepare($qstring);
			$stmt2= $this->db->prepare($qstring2);

			$this->db->beginTransaction();

			$stmt->execute(array(
				'type'=>$type,
				'head2'=>$head,
				'head'=>$head,
				'owner'=>$owner,
				'tgl'=>$tgl,
				'hal'=>$hal,
				'dari'=>$dari,
				'ke'=>$ke,
				'kepada'=>$kepada,
				'tembusan'=>$tembusan,
				'lampiran'=>$lampiran,
				'keterangan'=>$keterangan,
				'konseptor'=>$konseptor
				));
			$stmt2->execute();
			$ret=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			
			$nomor=$ret[0]['no_surat'];

			$this->db->commit();
			return true;
		}catch(PDOExeption $e){
			$this->db->rollBack();
			return $e->getMessage();
		}
		return 'Unknown error';
	}

	//ni fungsi buat ngupdate gan
	//NB: Nomor kagak berubah kalo ttdnya masih sama
	function updateSurat($id, $idjenis, $tanggal, $dari, $ke, $kepada, $hal, $tembusan, $lampiran, $keterangan, $konseptor){
		//bagian vital ada jika owner berubah, maka nomor harus diperbaharui
		//ambil data lama dulu? atau update conditional
		$qstring="UPDATE tb_surat a LEFT JOIN tb_jabatan b ON b.kode=:dari LEFT JOIN tb_jenis_surat c ON c.id=:idjenis SET a.nomor= (CASE WHEN a.dari <> b.kode THEN getSequence(b.kode, YEAR(a.waktu_input), c.head )
				ELSE a.nomor END), a.tanggal=STR_TO_DATE(:tanggal, '%d/%m/%Y'), a.dari = b.kode, a.jenis=c.jenis, a.head=c.head, a.ke=:ke, a.kepada=:kepada, a.hal=:hal, a.tembusan=:tembusan, a.lampiran=:lampiran, a.keterangan=:keterangan, a.konseptor=:konseptor WHERE a.id=:id;";
		try{
			$stmt = $this->db->prepare($qstring);

			$data = array(
				'idjenis'=>$idjenis,
				'tanggal'=>$tanggal,
				'ke'=>$ke,
				'kepada'=>$kepada,
				'dari'=>$dari,
				'hal'=>$hal,
				'tembusan'=>$tembusan,
				'lampiran'=>$lampiran,
				'keterangan'=>$keterangan,
				'konseptor'=>$konseptor,
				'id'=>$id
				);
			$ret=$stmt->execute($data);
			return $ret;
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		return false;
	}

	//ni fungsi buat ngapus gan
	function delSurat($id){
		$qstring = "DELETE FROM tb_surat WHERE id=:id LIMIT 1;";
		try{
			$stmt=$this->db->prepare($qstring);
			$stmt->execute(array(
				'id'=>$id
				));
			return true;
		}catch(PDOExeption $e){
			echo $e->getMessage();
		}
		return false;
	}
}
?>