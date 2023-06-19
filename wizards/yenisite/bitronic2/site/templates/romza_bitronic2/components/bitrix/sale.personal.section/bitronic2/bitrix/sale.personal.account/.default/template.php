<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';
\Bitrix\Main\Loader::includeModule($moduleId);?>
<div class="sale-personal-account-wallet-container">
	<div class="sale-personal-account-wallet-title">
		<?=Bitrix\Main\Localization\Loc::getMessage('SPA_BILL_AT')?>
		<?=$arResult["DATE"];?>
	</div>
	<div class="sale-personal-account-wallet-list-container">
		<div class="sale-personal-account-wallet-list">
			<?
			foreach($arResult["ACCOUNT_LIST"] as $accountValue)
			{
				?>
				<div class="sale-personal-account-wallet-list-item">
					<span class="sale-personal-account-wallet-sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($accountValue['CURRENCY'],$accountValue['ACCOUNT_LIST']['CURRENT_BUDGET'])?></span>
					<span class="sale-personal-account-wallet-currency">
						<div class="sale-personal-account-wallet-currency-item"><?=$accountValue['CURRENCY']?></div>
						<div class="sale-personal-account-wallet-currency-item"><?=$accountValue["CURRENCY_FULL_NAME"]?></div>
					</span>
				</div>
				<?
			}
			?>
		</div>
	</div>
</div>