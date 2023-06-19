<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

\Bitrix\Main\Loader::includeModule($moduleId);

?>

<!-- Griff sale.personal.account/.default/template-->
<div class="sale-personal-account-wallet-container">
	<div class="sale-personal-account-wallet-title">
		<?= Bitrix\Main\Localization\Loc::getMessage('SPA_BILL_AT') ?>
        <?= date('d.m.Y'); ?>
	</div>
	<div class="sale-personal-account-wallet-list-container">
		<div class="sale-personal-account-wallet-list">
			<? foreach($arResult["ACCOUNT_LIST"] as $accountValue) { ?>
				<div class="sale-personal-account-wallet-list-item">
					<span class="sale-personal-account-wallet-sum">
                        <?= "Накоплено бонусов: " . CCurrencyLang::CurrencyFormat($accountValue['CURRENT_BUDGET'], "RUB") ?>
                    </span>
				</div>
			<? } ?>
            <style>
                table {
                    font-size: 14px;
                    border-collapse: collapse;
                    text-align: left;
                }
                th {
                    font-weight: normal;
                    border-bottom: 2px solid white;
                    padding: 10px 8px;
                }
                td {
                    padding: 9px 8px;
                    transition: .3s linear;
                }
            </style>
            <table class="data-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Дата операции</th>
                    <th>Сумма</th>
                    <th>Описание</th>
                    <th>Заказ</th>
                    <th>Заметка</th>
                </tr>
                </thead>
                <tbody>
                <?
                //CModule::IncludeModule("sale");
                $res = CSaleUserTransact::GetList(["ID" => "DESC"], ["USER_ID" => $USER->GetID()]);
                $i = 0;
                while ($arFields = $res->Fetch()) { ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= $arFields["TRANSACT_DATE"]?></td>
                        <td><?= ($arFields["DEBIT"]=="Y")?"+":"-" ?><?= CCurrencyLang::CurrencyFormat($arFields["AMOUNT"], "RUB") ?></td>
                        <td><?= $arFields["DESCRIPTION"] ?></td>
                        <td><?= $arFields["ORDER_ID"] ?></td>
                        <td><?= $arFields["NOTES"] ?></td>
                    </tr>
                <? } ?>
                <tbody>
            </table>
            
		</div>
	</div>
</div>