<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php
	/**********************/
	/**** CREW BIO MOD ****/
	/**********************/
?>
<?php $string = random_string('alnum', 8);?>

		<script type="text/javascript" src="<?php echo base_url().MODFOLDER.'/assets/js/jquery.lazy.js';?>"></script>

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

.popover .title {
	background-color: #34363a !important;
	color: #fff !important;
	border-bottom: none !important;
}
.popover .content {
	background: #34363a !important;
}
</style>
<?php
	/**************************/
	/**** END CREW BIO MOD ****/
	/**************************/
?>

<script type="text/javascript">
	$(document).ready(function(){

				/**********************/
				/**** CREW BIO MOD ****/
				/**********************/

				$.lazy({
					src: '<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-twipsy.js',
					name: 'twipsy',
					dependencies: {
						css: ['<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap-twipsy.css']
					},
					cache: true
				});
				
				$.lazy({
					src: '<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-popover.js',
					name: 'popover',
					dependencies: {
						js: ['<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-twipsy.js'],
						css: [
							'<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap-twipsy.css',
							'<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap-popover.css'
						]
					},
					cache: true
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

				/**************************/
				/**** END CREW BIO MOD ****/
				/**************************/


		$('#tabs').tabs();
		
		$('table.zebra tbody > tr:nth-child(odd)').addClass('alt');
		
		$("a[rel^='prettyPhoto']").prettyPhoto({
			theme: 'dark_rounded',
			social_tools: '<div class="pp_social"></div>'
		});

	});
</script>