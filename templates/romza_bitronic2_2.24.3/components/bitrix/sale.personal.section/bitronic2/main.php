<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

global $rz_b2_options;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_MAIN"));
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);

$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'],
		"name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#interface"></use></svg>',
		"icon_class" => ' interface',
	);
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ACCOUNT'],
		"name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#tool"></use></svg>'
	);
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PRIVATE'],
		"name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#user-card"></use></svg>'
	);
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{

	$delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'].$delimeter."filter_history=Y",
		"name" => Loc::getMessage("SPS_ORDER_PAGE_HISTORY"),
		"icon" => '<svg><use xlink:href="#list-on-window"></use></svg>'
	);
}

if ($arParams['SHOW_PROFILE_PAGE'] === 'Y' && CRZBitronic2Settings::isPro())
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PROFILE'],
		"name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#profiles"></use></svg>'
	);
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_BASKET'],
		"name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#cart"></use></svg>'
	);
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y' && Loader::includeModule('yenisite.favorite'))
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_SUBSCRIBE'],
		"name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#megaphone"></use></svg>'
	);
}

if ($arParams['SHOW_CONTACT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_CONTACT'],
		"name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
		"icon" => '<svg><use xlink:href="#contacts-shop"></use></svg>'
	);
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList)
{
	foreach ($customPagesList as $page)
	{
		if ($page[2] == 'compare' && $rz_b2_options['block_show_compare'] != 'Y') continue;
		if (
			($page[2] === 'favorite' || $page[0] === '#favorite')
			&& !($rz_b2_options['block_show_favorite'] == 'Y' && Loader::includeModule('yenisite.favorite'))
		) continue;

		$availablePages[] = array(
			"data" => $page[0] === '#favorite' ? ' data-popup="#popup_favorites"' : '',
			"path" => $page[0],
			"name" => $page[1],
			"icon" => (strlen($page[2])) ? '<svg><use xlink:href="#'.htmlspecialcharsbx($page[2]).'"></use></svg>' : ""
		);
	}
}
?>

<main class="container account-personal-panel">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<? include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'personal/left_menu.php' ?>
		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
<?
if (empty($availablePages)):
	ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
else: ?>
			<nav>
				<ul class="panel">
				<? foreach ($availablePages as $blockElement): ?>

					<li class="panel-item">
						<a href="<?= htmlspecialcharsbx($blockElement['path']) ?>"<?= $blockElement['data'] ?>>
							<span class="panel-icon<?= $blockElement['icon_class'] ?>">
								<?= $blockElement['icon'] ?>

							</span>
							<span class="panel-name"><?= htmlspecialcharsbx($blockElement['name']) ?></span>
						</a>
					</li>
				<? endforeach ?>

				</ul>
			</nav>
<? endif ?>
		</div>
	</div>
</main>
