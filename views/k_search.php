<script type="text/javascript">
$(function(){
	$('#sch_penerima').hide();
	$('select[name="ke"]').change(function(e){
		var sel=$(this).val();
		if(sel=='-')
			$('#sch_penerima').show();
		else
			$('#sch_penerima').hide();
	});
});
</script>
<form method="POST" action="<?php echo base_url('surat/search/'.$pageInfo['section'].'/'.$pageInfo['type']);?>">
	<div class="">
		<p>
			<span class="formField">Nomor</span>
			<input type="text" class="shAnim si" name="nomor" />
		</p>
		<p>
			<span class="formField">Perihal</span>
			<textarea name="hal" class="shAnim"></textarea>
		</p>
		<p>
			<span class="formField">Kepada</span>
			<select  class="styled" name="ke">
				<option value="" selected>-</option>
				<?php
				foreach($jabatan as $j){
				?>
				<option value="<?php echo $j['kode'];?>"><?php echo $j['uraian2'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p id="sch_penerima">
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
				<option value="" selected>-</option>
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
			<input type="submit" value="Cari" class="commonButton blueGrad shAnim" />
		</p>
	</div>
</form>