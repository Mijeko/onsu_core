<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (
	'Y' == $rz_b2_options['custom-theme'] &&
	!empty($rz_b2_options['theme-main-color']) &&
	preg_match("/^#(?:[0-9a-fA-F]{3}){1,2}$/", $rz_b2_options['theme-main-color'])
) {
	$color = $rz_b2_options['theme-main-color'];
} else {
	$color = str_replace(array('-skew', '-flat'), '', $rz_b2_options['theme-demo']);

	switch ($color) {
		case 'yellow':     $color = '#FFE023'; break;
		case 'violet':     $color = '#E76CFB'; break;
		case 'red':        $color = '#F12323'; break;
		case 'pink':       $color = '#FE4CA4'; break;
		case 'orange':     $color = '#F68E0B'; break;
		case 'mint':       $color = '#56DBBC'; break;
		case 'lightblue':  $color = '#52E9FF'; break;
		case 'green':      $color = '#9FD122'; break;
		case 'gray':       $color = '#DDD';    break;
		case 'darkviolet': $color = '#A362FA'; break;
		case 'darkblue':   $color = '#3570F2'; break;
		case 'blue':       $color = '#78BBFF'; break;
		default:           $color = '#000';    break;
	}

	$arColors = array('yellow' => '#FFE023','violet' => '#E76CFB','red' => '#F12323','pink' => '#FE4CA4','orange' => '#F68E0B','mint' => '#56DBBC','lightblue' => '#52E9FF','green' => '#9FD122','gray' => '#DDD','darkviolet' => '#A362FA','darkblue' => '#3570F2','darkblue' => '#3570F2');
}
?>

<script type="text/javascript">
RZB2.themeColor = '<?= $color ?>';
RZB2.arrDefColors = <?=CUtil::PhpToJSObject($arColors)?>;
</script>
