<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SAP_MODULE_NOT_INSTALL"));
	return;
}
//if (!CBXFeatures::IsFeatureEnabled('SaleAccounts'))
//	return;

global $USER;

$arParams["SELL_CURRENCY"] = strlen($arParams["SELL_CURRENCY"])>0 ? trim($arParams["SELL_CURRENCY"]): "";
if(strLen($arParams["VAR"])<=0)
	$arParams["VAR"] = "buyMoney";
$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
if (strlen($arParams["PATH_TO_BASKET"]) <= 0)
	$arParams["PATH_TO_BASKET"] = "/personal/basket.php";
if($arParams["SET_TITLE"]!="N")
	$APPLICATION->SetTitle(GetMessage('SAP_TITLE'));
if($arParams["REDIRECT_TO_CURRENT_PAGE"]=="Y")
	$arResult["CURRENT_PAGE"] = htmlspecialcharsEx($APPLICATION->GetCurPageParam());

if(CModule::IncludeModule("currency"))
	$baseCurrency = CCurrency::GetBaseCurrency();

if(strlen($_REQUEST[$arParams["VAR"]]) > 0)
{
	$price = $_REQUEST[$arParams["VAR"]];
	$currency = $arParams["SELL_CURRENCY"];
	
	if(floatval($price) <= 0 || empty($currency))
		$arResult["errorMessage"] = GetMessage("SAP_WRONG_PRICE");
	if(count($arResult["errorMessage"])==0)
	{
		$tmpPrice = $price;
		$tmpCurrency = $currency;
		if($currency != $arParams["SELL_CURRENCY"] && strlen($arParams["SELL_CURRENCY"]) > 0)
		{
			$tmpPrice = CCurrencyRates::ConvertCurrency($price, $currency, $arParams["SELL_CURRENCY"]);
			$tmpCurrency = $arParams["SELL_CURRENCY"];
		}
		elseif($currency != $baseCurrency)
		{
			$tmpPrice = CCurrencyRates::ConvertCurrency($price, $currency, $baseCurrency);
			$tmpCurrency = $baseCurrency;
		}

		$arCur = CCurrencyLang::CurrencyFormat($price,$currency,false);
		$arCurrency = CCurrencyLang::GetCurrencyFormat($currency,LANGUAGE_ID);
		$sumCur = str_replace('#',$arCur,$arCurrency['FORMAT_STRING']);
			
		$arFields = array(
			"PRODUCT_ID" => 1,
			"NOTES" => 'RF',
			"PRICE" => $tmpPrice,
			"CURRENCY" => $tmpCurrency,
			"QUANTITY" => 1,
			"LID" => SITE_ID,
			"DELAY" => "N",
			"CAN_BUY" => "Y",
			"NAME" => str_replace("#SUM#", $sumCur, GetMessage("SAP_BASKET_NAME")),
			"MODULE" => $arParams['MODULE_ID']
		);

		\CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
		
		$basketID = CSaleBasket::Add($arFields);
		if ($basketID)
		{
			if($arParams["REDIRECT_TO_CURRENT_PAGE"] == "Y")
				LocalRedirect($arResult["CURRENT_PAGE"]);
			elseif($arParams["REDIRECT_TO_CURRENT_PAGE"] != "Y")
				LocalRedirect($arParams["PATH_TO_BASKET"]);
		}
		else
		{
			$arResult["errorMessage"] = GetMessage("SAP_ERROR_ADD_BASKET")."<br>";
			if ($ex = $GLOBALS["APPLICATION"]->GetException())
				$arResult["errorMessage"] .= $ex->GetString();
		}
	}
}

if(isset($arResult["PAY_ACCOUNT_AMOUNT"]) && is_array($arResult["PAY_ACCOUNT_AMOUNT"]))
{
	foreach($arResult["PAY_ACCOUNT_AMOUNT"] as $k => $v)
	{
		$tmp = $v;
		if(strlen($arParams["SELL_CURRENCY"]) > 0)
		{
			if($v["CURRENCY"] != $arParams["SELL_CURRENCY"])
				$tmp = Array("AMOUNT" => CCurrencyRates::ConvertCurrency($v["AMOUNT"], $v["CURRENCY"], $arParams["SELL_CURRENCY"]), "CURRENCY" => $arParams["SELL_CURRENCY"]);
		}
		$arResult["AMOUNT_TO_SHOW"][] = Array(
				"ID" => $k, 
				"NAME" => SaleFormatCurrency($tmp["AMOUNT"], $tmp["CURRENCY"]), 
				"LINK" => $APPLICATION->GetCurPageParam($arParams["VAR"]."=".$k, Array("buyMoney"))
			);
	}
}

$this->IncludeComponentTemplate();
?>