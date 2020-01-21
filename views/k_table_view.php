<script type="text/javascript">
function confirm_deletion(no){
	return confirm("Ciyus gan apus "+no+"?");
}
</script>
<?php
if(isset($tableData) || 1){
?>
<table class="table" style="width:100%;">
	<thead>
		<tr>
			<th>No</th>
			<th>No Surat</th>
			<th>Tanggal</th>
			<th>Hal</th>
			<th>Dari</th>
			<th>Untuk</th>
			<th>Tembusan</th>
			<th>Konseptor</th>
			<?php
			if($superUser){
			?>
			<th></th>
			<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	$nomor=($pageInfo['pageId']-1)*$pageInfo['itemPerPage']+1;
	foreach($tableData as $td){
	?>
	<tr>
		<td><?php echo $nomor++;?></td>
		<td><a href="<?php echo base_url('surat/view/'.$td['id']);?>" class="coolLink"><?php echo $td['no_surat'];?></a></td>
		<td><?php echo $td['tgl_surat'];?></td>
		<td><?php echo $td['hal'];?></td>
		<td><?php echo $td['ttd'];?></td>
		<td><?php echo $td['receiver'];?></td>
		<td><?php echo $td['tembusan'];?></td>
		<td><?php echo $td['konseptor2'];?></td>
		<?php
		if($superUser){
		?>
		<td><a href="<?php echo base_url('surat/delete/'.$td['id']);?>" class="commonButton shAnim redGrad" style="display:inline-block;" onclick="return confirm_deletion('<?php echo $td['no_surat'];?>')">Hapus</a></td>
		<?php
		}
		?>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
?>
<?php
$link1="#";
$link2="#";
$link3="#";
$link4="#";

$cls1="";
$cls2="";
$cls3="";
$cls4="";

if($pageInfo['pageId']==1){
	$cls1=$cls2='disabled';
}else if($pageInfo['pageId']>1){
	$link2=base_url('surat/'.$pageInfo['mode'].'/'.$pageInfo['section'].'/'.$pageInfo['type'].'/'.($pageInfo['pageId']-1) );
	$link1=base_url('surat/'.$pageInfo['mode'].'/'.$pageInfo['section'].'/'.$pageInfo['type'].'/1' );
}

if($pageInfo['pageId']>=$pageInfo['totalPage']){
	$cls3=$cls4='disabled';
}else if($pageInfo['pageId'] < $pageInfo['totalPage']){
	$link3=base_url('surat/'.$pageInfo['mode'].'/'.$pageInfo['section'].'/'.$pageInfo['type'].'/'.($pageInfo['pageId']+1) );
	$link4=base_url('surat/'.$pageInfo['mode'].'/'.$pageInfo['section'].'/'.$pageInfo['type'].'/'.$pageInfo['totalPage'] );	
}

$target=base_url('surat/'.$pageInfo['mode'].'/'.$pageInfo['section'].'/'.$pageInfo['type']);
?>
<div id="pagingbox">
	<a href="<?php echo $link1;?>" class="commonButton shAnim <?php echo $cls1;?>">&lt;&lt;</a>
	<a href="<?php echo $link2;?>" class="commonButton shAnim <?php echo $cls2;?>">&lt;</a>
	<input type="text" class="si4 spinInput" value="<?php echo $pageInfo['pageId'];?>" data-target="<?php echo $target?>"/>
	<span> / <?php echo $pageInfo['totalPage'];?> [<?php echo $pageInfo['totalItem'];?>]</span>
	<a href="<?php echo $link3;?>" class="commonButton shAnim <?php echo $cls3;?>">&gt;</a>
	<a href="<?php echo $link4;?>" class="commonButton shAnim <?php echo $cls4;?>">&gt;&gt;</a>
</div>
<?php
//print_r($jenisSurat);
?>