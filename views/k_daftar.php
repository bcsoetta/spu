<script type="text/javascript">
$(function(){
	$('form').submit(function(e){
		$('form select').removeAttr('disabled');
	});
	//sembunyiin
	$('#penerima').hide();
	$('select[name="ke"]').change(function(e){
		var sel=$(this).val();
		if(sel=='-')
			$('#penerima').show();
		else
			$('#penerima').hide();
	});
});
</script>
<form id="inputSurat" class="nicePad" method="POST" action="<?php echo base_url('surat/input/'.$pageInfo['section']);?>">
	<input type="hidden" value="<?php echo $pageInfo['type'];?>" name="type"/>
	<div class="">
		<p>
			<span class="formField">Jenis Surat</span>
			<select class="styled" name="head">
				<?php
				foreach($jenisSurat as $js){
				?>
				<option value="<?php echo $js['head'];?>"><?php echo $js['display'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p>
			<span class="formField">Tanggal Surat</span>
			<input type="text" class="datepicker shAnim dateField" name="tglSurat" value="<?php echo date('d/m/Y');?>" <?php if(!$superUser)echo 'readonly'; ?> />
		</p>
		<p>
			<span class="formField">Perihal</span>
			<textarea name="hal" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Dari</span>
			<select  class="styled" disabled name="dari">
				<?php
				foreach($jabatan as $j){
				?>
				<option value="<?php echo $j['kode'];?>" <?php if($j['kode']==$pageInfo['section'])echo 'selected';?> ><?php echo $j['uraian2'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p>
			<span class="formField">Ke</span>
			<select class="styled" name="ke">
				<?php
				foreach($jabatan as $j){
				?>
				<option value="<?php echo $j['kode'];?>" <?php if($j['kode']==$pageInfo['section'])echo 'selected';?> ><?php echo $j['uraian2'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p id="penerima">
			<span class="formField">Penerima</span>
			<textarea name="kepada" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Tembusan</span>
			<textarea name="tembusan" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Lampiran</span>
			<textarea name="lampiran" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Keterangan</span>
			<textarea name="keterangan" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Konseptor</span>
			<select  class="styled" name="konseptor">
				<?php
				foreach($jabatan as $j){
				?>
				<option value="<?php echo $j['kode'];?>"><?php echo $j['uraian2'];?></option>
				<?php
				}
				?>
			</select>
		</p>
	</div>
	<div class="underElm">
		<p>
			<input type="submit" value="Daftar" class="commonButton blueGrad shAnim" />
		</p>
	</div>
</form>