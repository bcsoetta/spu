//buat format nomor
function formatNumber (num) {
	num=num.toFixed(4);
	var idx=num.toString().indexOf('.');
    var decimals=num.toString().substr(idx);
    var number=num.toString().substring(0, idx).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    return number+decimals;
}

$(document).ready(function(){
	$('.datepicker').datepicker({
		showOn: 'both',
		dateFormat: 'dd/mm/yy',
		buttonText: ' ',
		buttonImage: 'http://192.168.146.250/carnet/assets/img/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		firstDay: 1,
		dayNamesMin: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
		beforeShow: function(i){
			if($(i).attr('readonly') || $(i).attr('disabled'))
				return false;
		}
	});
	$('.ui-datepicker-trigger').removeAttr('alt');
	$('#preview').click(function(){
		$(this).fadeToggle('slow');
	});

	$('.tidyNumber').focus(function(e){
		var v=$(this).val();
		v=v.replace(/,/g,'');
		$(this).val(parseFloat(v));
	});
	//restore format
	$('.tidyNumber').blur(function(e){
		var v=$(this).val();
		$(this).val(formatNumber(parseFloat(v)));
	});
});