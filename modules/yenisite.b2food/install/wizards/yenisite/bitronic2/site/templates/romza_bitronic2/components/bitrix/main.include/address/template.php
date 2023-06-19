<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
use Yenisite\Core\Tools;
$this->setFrameMode(true);
global $rz_b2_options;
?>
<? if ($arResult["FILE"] <> '' && strlen($address = $APPLICATION->GetFileContent($arResult["FILE"])) > 0) : ?>
	<i class="flaticon-location4"></i>
	<span class="link-text">
	<a href="javascript:;" class="address pseudolink with-icon" data-toggle="modal" data-target="#modal_address-on-map" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		<? $arAddress = explode('#', $address) ?>
		<span class="link-text"><span itemprop="addressLocality"><?= $arAddress[0] ?></span><span
				itemprop="streetAddress"><?= (isset($arAddress[1]) ? $arAddress[1] : '') ?></span></span>
	</a>
</span>
<?$this->setViewTarget('bitronic2_modal_adress');?>
<div class="modal fade" id="modal_address-on-map" tabindex="-1">
	<div class="modal-dialog modal_map">
		<button class="btn-close" data-toggle="modal" data-target="#modal_address-on-map">
			<span class="btn-text"><?= GetMessage('MYMS_CLOSE') ?></span>
			<i class="flaticon-close47"></i>
		</button>
	<? if ($USER->IsAdmin()):
		$pf = '';
		if ('Y' == $rz_b2_options['change_contacts']) {
			$pf = $rz_b2_options['GEOIP']['INCLUDE_POSTFIX'];
		}
		Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/address_popup.php", true, NULL, true);
	?>
		<script type="text/javascript">RZB2.ajax.map = true;</script>
	<? else: ?>
		<div class="content"></div>
	<? endif ?>
	</div>
	<!-- /.modal-dialog -->
</div>
<?$this->endViewTarget();?>
<? endif ?>