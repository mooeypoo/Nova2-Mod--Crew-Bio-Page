<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $string = random_string('alnum', 8);?>


<script type="text/javascript" src="<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-twipsy.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap-twipsy.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap-popover.css" media="screen" />

<style type="text/css">
#infocols {
	width: 650px;
	display: inline-block;
	clear: both;
}
#leftcol {
	float: left;
	width: 200px;
}

#rightcol {
	float: right;
	width: 400px;
	margin-top: 15px;
}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tabs').tabs();
		
		$('table.zebra tbody > tr:nth-child(odd)').addClass('alt');
		
		$("a[rel^='prettyPhoto']").prettyPhoto({
			theme: 'dark_rounded',
			social_tools: '<div class="pp_social"></div>'
		});

		$('[rel=tooltip]').twipsy({
			animate: false,
			offset: 5,
			placement: 'right'
		});
		
		
		$('[rel=popover]').popover({
			animate: false,
			offset: 5,
			placement: 'right'
		});
	});
</script>