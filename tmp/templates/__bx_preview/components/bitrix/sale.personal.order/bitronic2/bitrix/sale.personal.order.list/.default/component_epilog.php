<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<script>
jQuery(window).load(function(){
	require(['back-end/order_filter'], function(){
		b2.init.orderListPage();
	});
});
</script>