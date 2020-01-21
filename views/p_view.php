<script type="text/javascript">
$(function(){
	var superUser = $('#superUser').val();
	if(superUser){
		//buka semua akses kecuali nomor surat
		$('input').removeAttr('readonly');
		$('input').removeAttr('disabled');
		$('textarea').removeAttr('readonly');
		$('select').removeAttr('disabled');
		$('#noSurat').attr('readonly', true);
	}

	$('#penerima').hide();
	$('select[name="ke"]').change(function(e){
		var sel=$(this).val();
		if(sel=='-')
			$('#penerima').show();
		else
			$('#penerima').hide();
	}).change();
});
</script>
<div class="subContent">
	<form method="POST" action="<?php if($superUser)echo base_url('surat/update');?>">
		<input type="hidden" value="<?php echo $surat['id'];?>" name="id"/>
		<input type="hidden" value="<?php echo $superUser?>" id="superUser"/>
		<p>
			<span class="formField">Jenis Surat</span>
			<select class="styled" disabled name="idjenis">
				<?php
				foreach($jenisSurat as $js){
				?>
				<option value="<?php echo $js['id'];?>" <?php if($js['head'] == $surat['head'] && $js['jenis'] == $surat['jenis'])echo 'selected';?> ><?php echo $js['display'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p>
			<span class="formField">Nomor</span>
			<input type="text" class="shAnim si" value="<?php echo $surat['no_surat'];?>" readonly id="noSurat"/>
		</p>
		<p>
			<span class="formField">Tanggal</span>
			<input type="text" class="datepicker dateField shAnim" value="<?php echo $surat['tgl_surat'];?>" readonly name="tanggal"/>
		</p>
		<p>
			<span class="formField">Dari</span>
			<select class="styled" disabled name="dari">
				<?php
				foreach($jabatan AS $j){
				?>
				<option value="<?php echo $j['kode'];?>" <?php if($j['kode']==$surat['dari'])echo 'selected';?> ><?php echo $j['uraian'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p>
			<span class="formField">Ke</span>
			<select class="styled" disabled name="ke">
				<?php
				foreach($jabatan AS $j){
				?>
				<option value="<?php echo $j['kode'];?>" <?php if($j['kode']==$surat['ke'])echo 'selected';?> ><?php echo $j['uraian'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p id="penerima">
			<span class="formField">Penerima</span>
			<textarea class="shAnim" readonly name="kepada"><?php echo $surat['kepada'];?></textarea>
		</p>
		<p>
			<span class="formField">Perihal</span>
			<textarea class="shAnim" readonly name="hal"><?php echo $surat['hal'];?></textarea>
		</p>
		<p>
			<span class="formField">Tembusan</span>
			<textarea class="shAnim" readonly name="tembusan"><?php echo $surat['tembusan'];?></textarea>
		</p>
		<p>
			<span class="formField">Lampiran</span>
			<textarea class="shAnim" readonly name="lampiran"><?php echo $surat['lampiran'];?></textarea>
		</p>
		<p>
			<span class="formField">Keterangan</span>
			<textarea class="shAnim" readonly name="keterangan"><?php echo $surat['keterangan'];?></textarea>
		</p>
		<p>
			<span class="formField">Konseptor</span>
			<select class="styled" disabled name="konseptor">
				<?php
				foreach($jabatan AS $j){
				?>
				<option value="<?php echo $j['kode'];?>" <?php if($j['kode']==$surat['konseptor'])echo 'selected';?> ><?php echo $j['uraian'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<?php
		if($superUser){
		?>
		<div class="underElm" id="editControl">
			<p>
				<input type="submit" value="Simpan" class="commonButton blueGrad shAnim" />
			</p>
		</div>
		<?php
		}
		?>
	</form>
</div>