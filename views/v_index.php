<!doctype html>
<html>
	<head>
		<title><?php echo $pagetitle;?></title>
		<meta charset="utf-8">
		<?php
		link_css('jquery-ui.min.css');
		link_css('reset.css');
		link_css('style.css');

		link_js('jquery.min.js');
		link_js('jquery-ui.min.js');
		link_js('menu.js');
		link_js('controls.js');
		?>
		<script type="text/javascript">
		$(function(){
			$('#umsg').fadeToggle('slow');
			setTimeout(function(){
				$('#umsg').fadeToggle('slow');
			}, 3000);
			//buat main selector
			$('#selector').change(function(e){
				var form = $(this).closest('form');
				$(form).submit();
			});
		});
		</script>
	</head>
	<body>
		<div id="header">
			<div class="container clearfix" id="headerContainer">
				<img src="<?php link_img('logo.png');?>" height="64"/>
				<span id="appTitle">Aplikasi Surat Keluar <?php echo date('Y');?></span>
			</div>
		</div>
		<nav>
			<div class="container clearfix">
				<ul>
					<li><a href="<?php echo base_url('');?>">Home</a></li>
				</ul>
			</div>
		</nav>
		<div class="container clearfix" id="mainContent">
			<div id="sideBar">
				<div class="sideSubMenu">
					<h4>Surat:</h4>
					<form method="POST" action="<?php echo base_url('surat/change_page'); ?>">
						<select class="styled" id="selector" name="section">
							<option value="">-</option>
							<option value="kpu.03" <?php if($pageInfo['section']=='kpu.03')echo 'selected'; ?> >Kepala Kantor</option>
							<option value="bg.01" <?php if($pageInfo['section']=='bg.01')echo 'selected'; ?> >Kabag Umum</option>
							<option value="bg.0101" <?php if($pageInfo['section']=='bg.0101')echo 'selected'; ?> >Kasubbag SDM</option>
							<option value="up.3" <?php if($pageInfo['section']=='up.3')echo 'selected'; ?> >Kepala Kantor (SKEPP)</option>
							<option value="up.3a" <?php if($pageInfo['section']=='up.3a')echo 'selected'; ?> >Kabag Umum (SKEPP)</option>
							<option value="up.2" <?php if($pageInfo['section']=='up.2')echo 'selected'; ?> >Kepala Kantor (UP.2)</option>
							<option value="up.4" <?php if($pageInfo['section']=='up.4')echo 'selected'; ?> >Kepala Kantor (UP.4)</option>
							<option value="up.7" <?php if($pageInfo['section']=='up.7')echo 'selected'; ?> >Kepala Kantor (UP.7)</option>
							<option value="up.2a" <?php if($pageInfo['section']=='up.2a')echo 'selected'; ?> >Kabag Umum (UP.2)</option>
							<option value="up.4a" <?php if($pageInfo['section']=='up.4a')echo 'selected'; ?> >Kabag Umum (UP.4)</option>
							<option value="up.7a" <?php if($pageInfo['section']=='up.7a')echo 'selected'; ?> >Kabag Umum (UP.7)</option>
						</select>
					</form>
					<?php
					//ambil otoritas, buat menu sesuai dengan
					$otoritas=null;
					foreach($jabatan as $j){
						if($j['kode'] == $pageInfo['section']){
							$otoritas=explode(',', $j['otoritas']);
							break;
						}
					}
					//judul menu
					$judulMenu = array(
						'SKEL'=>'Surat Keluar', 
						'SKEP'=>'Surat Keputusan', 
						'SLAIN'=>'Surat Lain', 
						'SPENG'=>'Surat Pengantar',
						'SPRINT'=>'Surat Perintah', 
						'SPD'=>'SPD', 
						'STEI'=>'Surat TEI', 
						'SMUT'=>'Surat Mutasi', 
						'ND'=>'Nota Dinas', 
						'SKEPP'=>'SKEP Pangkat',
						'RAL'=>'Lembar Ralat'
						);
					?>
					<ul class="sideMenu">
						<?php
						if(!is_null($otoritas) && count($otoritas) > 0){
							foreach($otoritas as $o){
								if(!$o)
									continue;
						?>
						<li><a href="<?php echo base_url('surat/browse/'.$pageInfo['section'].'/'.$o);?>" class="commonButton shAnim redGrad"><?php echo $judulMenu[$o]; ?></a></li>
						<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
			<div id="subContent">
			<?php
			if(isset($mainContent))
				include $mainContent;
			?>
			</div>
		</div>

		<?php
		//handle user message. set using $user->message('stuff');
		if(isset($_SESSION['user']['message'])){
		?>
		<p id="umsg">
		<?php echo $_SESSION['user']['message'];?>
		</p>
		<?php
		unset($_SESSION['user']['message']);
		}
		?>
	</body>
</html>