<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>

<style>
	img.edost_ico { vertical-align: middle; padding-right: 5px; }
	img.edost_ico_normal { width: 60px; height: 32px; }
	img.edost_ico_small { width: 28px; height: 16px; padding: 2px 5px 2px 0px; }

	span.edost_name { color: #000; font-weight: bold; vertical-align: middle; }
	span.edost_day { color: #555; vertical-align: middle; }
	span.edost_price { color: #000; font-weight: bold; vertical-align: middle; }
	span.edost_normal { color: #000; vertical-align: middle; }
	span.edost_error { color: #F00; font-weight: bold; vertical-align: middle; }
	span.edost_description { color: #555; vertical-align: middle; }

	div.edost_window_fon {
		z-index: 100;
		position: fixed;
		top: 0px;
		left: 0px;
		bottom: 0px;
		right: 0px;
		background-image: url(<?=$arResult['COMPONENT_PATH']?>/images/window_fon.png);
	}
	div.edost_window {
		z-index: 101;
		position: fixed;
		top: 200px;
		left: 200px;
		width: <?=$arResult['FRAME_X']?>px;
		background: #FFF;
		border: 5px solid <?=$arResult["COLOR"]?>;
<? if (isset($arResult['RADIUS'])) { ?>
		border-radius: <?=$arResult['RADIUS']?>px;
<? } ?>
		box-shadow: 0px 0px 10px 0px <?=($arResult['CLEAR_WHITE'] ? '#888' : $arResult['COLOR_SHADOW'])?>;
	}
	td.edost_window_head {
		padding-left: 5px;
		padding-bottom: 5px;
		color: <?=($arResult['CLEAR_WHITE'] ? '#AAA' : $arResult['COLOR_FONT'])?>;
		font-size: 15px;
		font-weight: bold;
		background: <?=$arResult['COLOR']?>;
	}
	div.edost_window_close {
		width: 15px;
		height: 17px;
		padding: 1px 2px 0px 0px;
		float: right;
		background: url(<?=$arResult['COMPONENT_PATH']?>/images/close<?=($arResult['CLEAR_WHITE'] ? '_black' : '')?>.png) no-repeat 0px 2px;
	}
	div.edost_window_close:hover {
		background: url(<?=$arResult['COMPONENT_PATH']?>/images/close<?=($arResult["CLEAR_WHITE"] ? '_black' : '')?>_hover.png) no-repeat 0px 2px;
	}

	div.edost_button {
		height: 22px;
		cursor: default;
		color: <?=$arResult['COLOR_FONT']?>;
		text-align: center;
		font-size: 18px;
		font-weight: bold;
		padding: 5px 0px;
		border: 1px solid <?=($arResult['CLEAR_WHITE'] ? '#DDD' : $arResult['COLOR'])?>;
		border-radius: 4px;
		background: <?=$arResult['COLOR']?>;
	}
	div.edost_button:hover {
		background: <?=$arResult['COLOR_UP']?>;
<? if ($arResult['CLEAR_WHITE']) { ?>
		color: #999;
<? } ?>
	}
	div.edost_button:active {
		color: <?=$arResult['COLOR_FONT_UP']?>;
	}
</style>


<script type="text/javascript">

var edost_window_top = 0;
var edost_location_id = '';
var edost_product_id = 0;
var edost_quantity_timer;
var edost_quantity = 1;

function edost_GetCookie(name) {
	var r = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return (r ? decodeURIComponent(r[2]) : '');
}

function edost_SetCatalogDeliveryData(data) {

	for (iMode = 0; iMode < 2; iMode++) {
		if (iMode == 0 && data.inside != undefined) {
			var mode = 'inside';
			var iData = data.inside;
		}
		else if (iMode == 1 && data.window != undefined) {
			var mode = 'window';
			var iData = data.window;
		}
		else continue;

		var s = '';
		var tariff = iData.data.split('|');
		var name = (mode == 'inside' ? data.param.location_short_name : data.param.location_name);
		var no_delivery = (name != '' ? '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="center"><?=(isset($arResult['NO_DELIVERY_MESSAGE']) ? $arResult['NO_DELIVERY_MESSAGE'] : GetMessage('EDOST_CATALOG_DELIVERY_NO_DELIVERY'))?></td></tr></table>' : '');

		if (mode == 'inside') {
			if (name == '') name = '<?=GetMessage('EDOST_CATALOG_DELIVERY_GET_CITY')?>'
			var link_begin = "<span style=\"cursor: pointer; color: #00F;\" onclick=\"edost_catalogdelivery_show('', ''";
			var link_end = '</span>';
		}
		else {
			edost_location_id = new Array(data.param.location_id, data.param.country_id, name);
			document.cookie = 'edostcdcity=' + data.param.location_id + '|' + data.param.country_id + '|' + encodeURIComponent(name) + '; path=/; expires=Thu, 01-Jan-2016 00:00:01 GMT';
			document.cookie = "edostcdaddcart=" + (data.param.add_cart == 'Y' ? '1' : '0') + "; path=/; expires=Thu, 01-Jan-2016 00:00:01 GMT";

			var E = document.getElementById('edost_catalogdelivery_param');
			if (E) E.value = data.param.param_string;

			var E = document.getElementById('edost_city_sel');
			if (E) E.value = data.param.location_id;
		}

		if (name != '') {
			var E = document.getElementById('edost_catalogdelivery_' + mode + '_city');
			if (E) E.innerHTML = (mode == 'inside' ? link_begin + ", 'getcity');\">" + name + link_end : name);
		}

		if (tariff.length <= 1) s = no_delivery;
		else {
			var table = (iData.minimize != '' ? false : true);
			if (table) s += '<table class="edost_delivery_table" width="95%" cellspacing="0" cellpadding="0" border="0">';

			for (i = 0; i < Math.floor(tariff.length/7); i++) {
				var i2 = i*7;

				var s2 = '';
				s2 += '<span class="edost_name">' + tariff[i2+1] + '</span>';
				if (tariff[i2+3] != '') s2 += '<span class="edost_day">, ' + tariff[i2+3] + '</span>';
				if (tariff[i2+4] != '') s2 += '<span class="edost_normal"> - </span><span class="edost_price">' + (tariff[i2+4] == 0 ? '<?=GetMessage('EDOST_CATALOG_DELIVERY_FREE')?>' : tariff[i2+4]) + '</span>';
				if (tariff[i2+5] != '') s2 += (table ? '<br>' : ' - ') + '<span class="edost_error">' + tariff[i2+5] + '</span>';
				s2 += '<br>';

				var ico = tariff[i2];
				if (ico != '') {
					if (ico.length <= 2) ico = '/bitrix/images/delivery_edost_img/' + (!table ? 'small/' : '') + ico + '.gif';
					ico = '<img class="edost_ico ' + (table ? 'edost_ico_normal' : 'edost_ico_small') + '" src="' + ico + '" border="0">';
				}

				if (table) {
					s += '<tr>';
					s += '<td align="left" valign="top" width="60" style="padding: 5px 0px; margin: 0px;">' + ico + '</td>';
					s += '<td align="left" valign="center" style="padding: 0px; margin: 0px;">' + s2 + (tariff[i2+2] != '' ? '<span class="edost_description">' + tariff[i2+2] + '</span>' : '') + '</td>';
					s += '</tr>';
		        }
		        else s += ico + s2;
			}

			if (table) s += '</table>';

			if (mode == 'inside' && data.param.detailed == 'Y') {
				var E = document.getElementById('edost_catalogdelivery_inside_detailed');
				if (E) E.innerHTML = link_begin + ');"><?=GetMessage('EDOST_CATALOG_DELIVERY_DETAILED')?>' + link_end;
			}
		}

		var E = document.getElementById('edost_catalogdelivery_' + mode);
		if (E) {
			E.innerHTML = s;
			if (mode == 'window') {
				edost_SetWindowPosition('update');
				if (name == '') {
					var E = document.getElementById('edost_window');
					if (E && E.style.display == 'block') edost_catalogdelivery_show('', '', 'getcity');
				}
			}
		}
	}

}

function edost_catalogdelivery_show(product_id, product_name, param) {

	var display = (param == 'close' || param == 'calc' ? 'none' : 'block');

	var E = document.getElementById('edost_window');
	if (!E) return;
	E.style.display = display;

	var E = document.getElementById('edost_window_fon');
	if (E) E.style.display = display;

	if (param == 'close') return;


	if (product_name == '') {
		var E = document.getElementById('edost_catalogdelivery_product_name');
		if (E) product_name = E.value;
	}
	if (product_name != '') {
		var E = document.getElementById('edost_window_head');
		if (E) E.innerHTML = <?=($arResult['CLEAR_WHITE'] ? '"'.GetMessage('EDOST_CATALOG_DELIVERY_TITLE').': " + ' : '')?>product_name;
	}

	var product_id_old = edost_product_id;
	if (product_id == '') {
		var E = document.getElementById('edost_catalogdelivery_product_id');
		if (E) product_id = E.value;
	}
	if (product_id != '') edost_product_id = product_id;

	if (edost_GetCookie('edostcdaddcart') == 1) {
		var E = document.getElementById('edost_add_cart');
		if (E) E.checked = true;
	}

	if (edost_location_id == undefined || edost_location_id == '') {
		var id = edost_GetCookie('edostcdcity');

		if (id == '') {
			id = edost_GetCookie('YS_GEO_IP_LOC_ID');
			var ar = edost_GetCookie('YS_GEO_IP_CITY').split('/');
			if (id != '' && ar[2] != undefined && ar[2] != '') id = id + '|0|' + ar[2];
		}

		if (id != '') edost_location_id = id.split('|');

		if (edost_location_id[2] != undefined && edost_location_id[2] != '') {
			var E = document.getElementById('edost_catalogdelivery_window_city');
			if (E) E.innerHTML = edost_location_id[2];
		}
	}

	if (param == 'calc') {
		edost_Calc();
		return;
	}

	if (param == 'getcity' || edost_location_id[2] == undefined || edost_location_id[2] == '') {
		var E = document.getElementById('edost_catalogdelivery_window_city');
		if (E) E.style.display = 'none';

		var E = document.getElementById('edost_catalogdelivery_window_getcity_div');
		if (E) {
			E.style.display = 'block';

			E = document.getElementById('edost_catalogdelivery_window_no_city');
			if (E) {
				document.getElementById('edost_catalogdelivery_window').innerHTML = '';
				edost_LoadCities('start');
			}
		}
	}
	else {
		var E = document.getElementById('edost_catalogdelivery_window_no_data');
		if (E || (product_id_old > 0 && edost_product_id != product_id_old)) edost_Calc();
	}

	edost_SetWindowPosition(param == 'getcity' ? 'getcity' : 'new');

}

function edost_SetWindowPosition(mode) {

	var E = document.getElementById('edost_window');
	var E2 = document.getElementById('edost_catalogdelivery_window');
	if (!(E && E2 && E.style.display != 'none')) return;


	var w = (document.documentElement.clientWidth == 0 ? document.body.clientWidth : document.documentElement.clientWidth);
	var h = (document.documentElement.clientHeight == 0 ? document.body.clientHeight : document.documentElement.clientHeight);

	if (mode != 'getcity') E2.style.height = 'auto';

	var window_w = E.offsetWidth;
	var window_h = E.offsetHeight;
	var res_h = E2.offsetHeight;

<? if ($arResult['FRAME_AUTO']) { ?>
	var max_h = h - 150;
	if (res_h < <?=$arResult['FRAME_Y']?>) res_h = <?=$arResult['FRAME_Y']?>;

	E2.style.overflowY = (window_h > max_h ? 'scroll' : 'visible');
	E2.style.height = (window_h > max_h ? (res_h - (window_h - max_h)) : res_h) + 'px';
<? } else { ?>
	var new_h = <?=$arResult['FRAME_Y']?> - (window_h - res_h);
	if (new_h < 80) new_h = 80;

	E2.style.overflowY = (res_h > new_h ? 'scroll' : 'visible');
	E2.style.height = new_h + 'px';
<? } ?>

	window_h = E.offsetHeight;

	E.style.left = (w - window_w)/2 + 'px';

	var top = Math.round((h - window_h)*0.5);
	if (mode == 'new' || mode == 'getcity' || (mode == 'update' && top < edost_window_top)) {
		E.style.top = top + 'px';
		edost_window_top = top;
	}

}

function edost_LoadCities(param) {

	if (param == 'start') {
		if (!document.getElementById('edost_catalogdelivery_window_no_city')) return;

		var id = 'edost_catalogdelivery_window_getcity_div';
		var location_id = (edost_location_id[0] != undefined && edost_location_id[0] != '' ? edost_location_id[0] : 0);
		var country = (edost_location_id[1] != undefined && edost_location_id[1] != '' ? edost_location_id[1] : 0);
	}
	else {
		param = 'update';
		var id = 'edost_cities';
		var location_id = -1;
		var country = document.getElementById('edost_country_sel').value;
	}

	var location_id_default = 0;
	var E = document.getElementById('edost_catalogdelivery_param');
	if (E) {
		var s = E.value;
		var p = s.indexOf('location_id_default(');
		if (p >= 0) {
			s = s.substr(p + 20);
			var p = s.indexOf(')');
			if (p > 0) location_id_default = s.substr(0, p);
		}
	}

	var E = document.getElementById(id);
	if (!E) return;

	if (param == 'update' && country == -1) {
		E.innerHTML = '';
		return;
	}

	E.innerHTML = '<img style="vertical-align: top;" src="<?=$arResult['COMPONENT_PATH']?>/images/loading_small_w.gif" width="20" height="20" border="0">';
	BX.ajax.post('<?=$arResult['COMPONENT_PATH']?>/edost_delivery_loadcities.php', 'mode=' + param + '&id=' + location_id + '&country=' + country + '&default=' + location_id_default + '<?=($arResult['EDOST_LOCATIONS'] == 'Y' ? '&edostlocations=Y' : '')?>', function(res) {
		E.innerHTML = res;
<? if ($arResult['EDOST_LOCATIONS'] != 'Y') { ?>
		edost_Calc();
<? } ?>
	});

}

function edost_Calc(param) {

	var E = document.getElementById('edost_qty');
	var quantity = (E ? E.value : 1);

	if (param == 'quantity') {
		if (quantity != edost_quantity && quantity > 0) edost_quantity = quantity;
		else return;
	}


	var id = 0;
	var E = document.getElementById('edost_city_sel');
	if (E) id = E.value;
	else if (edost_location_id[0] != undefined && edost_location_id[0] != '') id = edost_location_id[0];
	if (!(id > 0)) return;


	var E = document.getElementById('edost_add_cart');
	var add_cart = (E && E.checked ? 1 : 0);

	var E = document.getElementById('edost_catalogdelivery_param');
	var param = (E ? E.value : '');

	var E = document.getElementById('edost_country_sel');
	var country_id = (E ? E.value : '');

	var mode = (document.getElementById('edost_catalogdelivery_inside') ? 'double' : 'window');

	document.getElementById('edost_catalogdelivery_window').innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="center"><img style="vertical-align: top;" src="<?=$arResult['COMPONENT_PATH']?>/images/loading_w.gif" width="64" height="64" border="0"></td></tr></table>';
	BX.ajax.post('<?=$arResult['COMPONENT_PATH']?>/edost_delivery_goodscalc.php', 'mode=' + mode + '&param=' + param + '&id=' + id + '&product=' + edost_product_id + '&quantity=' + quantity + '&addcart=' + add_cart, function(res) {
		if (res == '') return;
		res = (window.JSON && window.JSON.parse ? JSON.parse(res) : eval('(' + res + ')'));
		if (res.window != undefined) edost_SetCatalogDeliveryData(res);
	});

}

</script>

<input id="edost_catalogdelivery_param" value="<?=$arResult['PARAM']?>" type="hidden">

<div class="edost_window_fon" id="edost_window_fon" style="display: none;" onclick="edost_catalogdelivery_show('', '', 'close');"></div>
<div class="edost_window" id="edost_window" style="display: none;">
	<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
		<tr height="20"><td class="edost_window_head" align="left">
			<div class="edost_window_close" onclick="edost_catalogdelivery_show('', '', 'close');"></div>
			<span id="edost_window_head"></span>
		</td></tr>

<?		if (isset($arResult['INFO'])) { ?>
		<tr><td style="padding: <?=($arResult['CLEAR_WHITE'] ? '0px 10px 8px 10px;' : '10px 10px 0px 10px;')?>" align="left" valign="center">
			<?=html_entity_decode($arResult['INFO'])?>
		</td></tr>
<?		} ?>

		<tr><td style="padding: <?=($arResult['CLEAR_WHITE'] ? 0 : 10)?>px 10px 0px 10px;" align="left" valign="center">
			<table width="95%" cellpadding="2" cellspacing="0" border="0">
				<tr height="25">
					<td width="150" align="right" valign="top">
						<div style="<?=($arResult['SHOW_QTY'] == 'Y' || $arResult['SHOW_ADD_CART'] == 'Y' ? '' : 'color: #AAA;')?>"><?=GetMessage('EDOST_CATALOG_DELIVERY_TO')?></div>
					</td>
					<td align="left" valign="top">
						<div id="edost_catalogdelivery_window_city" style="cursor: pointer; color: #00F; font-weight: bold;" onclick="edost_catalogdelivery_show('', '', 'getcity');"></div>
						<div id="edost_catalogdelivery_window_getcity_div" style="display: none; padding-top: 0px;"><input id="edost_catalogdelivery_window_no_city" value="" type="hidden"></div>
					</td>
				</tr>
<?				if ($arResult['SHOW_QTY'] == 'Y' || $arResult['SHOW_ADD_CART'] == 'Y') { ?>
				<tr>
<?					if ($arResult['SHOW_QTY'] == 'Y') { ?>
					<td align="right">
						<?=GetMessage('EDOST_CATALOG_DELIVERY_QTY')?>
					</td>
<?					} ?>
					<td align="left" colspan="2" style="vertical-align: middle;">
<?						if ($arResult['SHOW_QTY'] == 'Y') { ?>
						<input name="edost_qty" id="edost_qty" value="1" size="4" style="vertical-align: middle; width: 40px;" onfocus="if (edost_quantity_timer != undefined) window.clearInterval(edost_quantity_timer); edost_quantity_timer = window.setInterval('edost_Calc(\'quantity\')', 300);" onblur="if (edost_quantity_timer != undefined) edost_quantity_timer = window.clearInterval(edost_quantity_timer);">
<?						} ?>
<?						if ($arResult['SHOW_ADD_CART'] == 'Y') { ?>
						<input type="checkbox" id="edost_add_cart" style="margin-left: <?=($arResult['SHOW_QTY'] == "Y" ? '30' : '0')?>px; vertical-align: middle;" onclick="edost_Calc();"> <label style="vertical-align: middle;" for="edost_add_cart"><?=GetMessage('EDOST_CATALOG_DELIVERY_ADD_CART')?></label><span id="edost_cart_weight"></span>
<?						} ?>
					</td>
				</tr>
<?				} ?>
			</table>
		</td></tr>

		<tr><td style="padding: 10px;" align="left" valign="center">
			<div id="edost_catalogdelivery_window" style="padding-bottom: 4px;">
				<input type="hidden" id="edost_catalogdelivery_window_no_data" value="">
			</div>
		</td></tr>

<?		if ($arResult['SHOW_BUTTON'] == 'Y') { ?>
		<tr height="65"><td>
			<table height="100%" width="100%" cellpadding="2" cellspacing="0" border="0"><tr>
				<td width="<?=($arResult['FRAME_X'] - 320)?>">&nbsp;</td>
				<td width="150" align="center">
<?				if ($arResult['SHOW_QTY'] == 'Y' || $arResult['SHOW_ADD_CART'] == 'Y') { ?>
					<div class="edost_button" style="width: 150px;" onclick="edost_Calc();"><?=GetMessage('EDOST_CATALOG_DELIVERY_BUTTON_RECALC')?></div>
<?				} ?>
				</td>
				<td align="center">
					<div class="edost_button" style="width: 120px;" onclick="edost_catalogdelivery_show('', '', 'close');"><?=GetMessage('EDOST_CATALOG_DELIVERY_BUTTON_CLOSE')?></div>
				</td>
			</tr></table>
		</td></tr>
<?		} ?>
	</table>
</div>