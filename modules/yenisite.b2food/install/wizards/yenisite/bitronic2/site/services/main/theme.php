<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_TEMPLATE_ID"))
	return;

COption::SetOptionString($wizard->solutionName, "theme-demo", WIZARD_THEME_ID, "", WIZARD_SITE_ID);
?>