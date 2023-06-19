<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
	
if (!CModule::IncludeModule("search"))
	return;
	
$exclude_mask = COption::GetOptionString("search", "exclude_mask");
if ($exclude_mask == "")
	COption::SetOptionString("search", "exclude_mask", "/bitrix/*;/404.php;/upload/*;/yenisite.pricegen/*;/yenisite.resizer2/*");
else
	COption::SetOptionString("search", "exclude_mask", $exclude_mask.";/yenisite.pricegen/*;/yenisite.resizer2/*");

if(WIZARD_SITE_ID != "")
	$NS["SITE_ID"] = WIZARD_SITE_ID;
		
if (!isset($_SESSION['SearchFirst']))
	$NS = CSearch::ReIndexAll(false, 20, $NS);
else
	$NS = CSearch::ReIndexAll(false, 20, $_SESSION['SearchNS']);
           
if (is_array($NS))  //repeat step, if indexing doesn't finish
{
	$this->repeatCurrentService = true; 
	$_SESSION['SearchNS'] = $NS;
	$_SESSION['SearchFirst'] = 1;	
}
else
{
	unset($_SESSION['SearchNS']);
	unset($_SESSION['SearchFirst']);       
}
?>