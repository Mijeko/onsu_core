<?
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!array_key_exists('EMAIL_NOTICE', $arParams)) {
	$arParams['EMAIL_NOTICE'] = GetMessage('BITRONIC2_REGISTER_FIELD_EMAIL_NOTICE');
}