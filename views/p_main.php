<script type="text/javascript">
$(function(){
	$('.pageControl li').click(function(e){
		var v=$(this).data('id');
		//alert(v);
		$(this).closest('ul').children('li').removeClass('selected');
		$(this).addClass('selected');
		//show yg ada itunya
		$('#pages .subContent').hide();
		var p=$('#pages').find('div[data-id="'+v+'"]').show();
	});
	$('.pageControl li:first-child').click();
});
</script>
<div class="subContent">
	<ul class="pageControl clearfix">
		<li class="blackGrad shAnim" data-id="page1">List Surat</li>
		<li class="blackGrad shAnim" data-id="page2">Daftar</li>
		<li class="blackGrad shAnim" data-id="page3">Cari</li>
	</ul>
</div>
<div id="pages">
	<div class="subContent" data-id="page1" style="display:none;">
		<?php
		include 'k_table_view.php';
		?>
	</div>
	<div class="subContent" data-id="page2" style="display:none;">
		<?php
		include 'k_daftar.php';
		?>
	</div>
	<div class="subContent" data-id="page3" style="display:none;">
		<?php
		include 'k_search.php';
		?>
	</div>
</div>