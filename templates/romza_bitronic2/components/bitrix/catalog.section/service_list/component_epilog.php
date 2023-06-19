<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $serviceSectionFilter;
$serviceSectionFilter = array();

if (!empty($templateData['SECTIONS'])) {
	$serviceSectionFilter = $templateData['SECTIONS'];
}
