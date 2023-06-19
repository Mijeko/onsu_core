<?php
/**
 * Created by PhpStorm.
 * User: Gredasow Iwan (Griff19)
 * Date: 28.05.2019
 * Time: 16:31
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["ACCOUNT_LIST"] = [CSaleUserAccount::GetByUserID($USER->GetID(), "RUB")];