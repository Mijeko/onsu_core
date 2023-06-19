<? if (\Bitrix\Main\Loader::IncludeModule('yenisite.ymrs')): ?>
	<? $APPLICATION->IncludeComponent("yenisite:yandex.market_reviews_store", ".default", array(), false); ?>
<? endif ?>
