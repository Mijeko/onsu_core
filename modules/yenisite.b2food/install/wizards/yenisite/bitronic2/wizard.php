<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Yenisite\Core\Tools;
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");



function checkNeedModules()
{
	include 'include/moduleCode.php';

	$arError = array();
	// ARRAY OF NEED MODULES
	$arChecks = array(
		array('CHECK' => 'MODULE', "MODULE" => 'yenisite.core'),
		array('CHECK' => 'MODULE', "MODULE" => $moduleId),
		array('CHECK' => 'MODULE', "MODULE" => 'yenisite.resizer2'),
		array('CHECK' => 'MODULE', "MODULE" => 'yenisite.mainspec'),
		array('CHECK' => 'MODULE', "MODULE" => 'yenisite.menu'),
		array('CHECK' => 'METHOD', "MODULE" => 'yenisite.resizer2', "CLASS" => 'CResizer2Set', "METHOD" => 'GetBySizeMode'),
		array('CHECK' => 'VERSION', 'VERSION' => '1.8.5', "MODULE" => 'yenisite.core'),
		array('CHECK' => 'VERSION', 'VERSION' => '0.9.4', "MODULE" => 'yenisite.catchbuy'),
		array('CHECK' => 'VERSION', 'VERSION' => '1.3.8', "MODULE" => 'yenisite.menu'),
		array('CHECK' => 'VERSION', 'VERSION' => '1.0.3', "MODULE" => 'yenisite.favorite'),
		//array('CHECK' => 'CLASS',  "MODULE" => 'yenisite.resizer2', "CLASS" => 'CResizer2Set'),
	);

	// CHECK NEED MODULES
	foreach( $arChecks as $arNeed )
	{
		switch($arNeed['CHECK'])
		{
			case 'MODULE':
				if(!CModule::IncludeModule($arNeed["MODULE"]))
				{	if (strlen(GetMessage("MODULE_NOT_INSTALLED_".strtoupper($arNeed["MODULE"]))) > 0){
					$arError[] = "MODULE_NOT_INSTALLED_".strtoupper($arNeed["MODULE"]);
					} else {
					$arError[] = "MODULE_NOT_INSTALLED_LINE";
					}
				}
				break;

			case 'METHOD':
				if(!empty($arNeed["MODULE"]))
					CModule::IncludeModule($arNeed["MODULE"]);

				if(!empty($arNeed["CLASS"]) && !empty($arNeed["METHOD"]) && !method_exists($arNeed["CLASS"] , $arNeed["METHOD"]))
				{
					$arError[] = "UPDATE_".strtoupper($arNeed["MODULE"]);
				}

				break;

			case 'CLASS':
				if(!empty($arNeed["MODULE"]))
					CModule::IncludeModule($arNeed["MODULE"]);

				if(!empty($arNeed["CLASS"]) && !class_exists($arNeed["CLASS"]) )
				{
					$arError[] = "UPDATE_".strtoupper($arNeed["MODULE"]);
				}
				break;

			case 'VERSION':
				if(!empty($arNeed["MODULE"]))
					$bInclude = CModule::IncludeModule($arNeed["MODULE"]);

				if($bInclude)
				{
					$arModuleVersion = array();
					require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/{$arNeed["MODULE"]}/install/version.php");
					$ver = $arModuleVersion["VERSION"];
					if (version_compare($ver, $arNeed["VERSION"]) < 0) {
						$arError[] = "UPDATE_".strtoupper($arNeed["MODULE"]);

					}

				}

				break;
		}
	}
	return $arError;
}

class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		parent::InitStep();

		$arError = checkNeedModules();

		// @var $moduleId
		include 'include/moduleCode.php';

		$wizard =& $this->GetWizard();
		$this->SetNextStep("install_type");
		$wizard->solutionName = $moduleId;  // change
        $wizard->SetVar('solutionName',$moduleId);
		$wizard->SetVar("templateID", "romza_bitronic2");	// change
		$wizard->SetVar("arError", $arError);
	}

	function ShowStep()
	{
		include 'include/moduleCode.php';

		$wizard =& $this->GetWizard();
		$arError = $wizard->GetVar("arError");

		if(count($arError) > 0) {
			$this->content .= "<p style='color:red'>";
			foreach($arError as $errCode) {
				$this->content .= GetMessage($errCode, array('#moduleID#' => $moduleId)).'<br/>';
			}
			$this->content .= GetMessage("MODULE_NOT_INSTALLED_TIP")."</p>";
			$this->SetNextStep("select_site");
		} else {
			parent::ShowStep();
		}
	}
}

class SelectInstallType extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("install_type");
		$this->SetTitle(GetMessage("WIZ_INSTALL_TYPE"));
		$this->SetPrevStep("select_site");

		$this->SetNextStep("select_theme");
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$firstInstall = true;
		$siteID = $wizard->GetVar("siteID");
		$rsTemplates = CSite::GetTemplateList($siteID);
		while($arTemplate = $rsTemplates->Fetch())
		{
			if(strpos($arTemplate['TEMPLATE'], 'romza_bitronic2') !== false)
			{
				$firstInstall = false;
				break;
			}
		}
		$wizard->SetVar("firstInstall", $firstInstall);

		$onChange = "changeNextStep()";
		$this->content .= '<div class="wizard-input-form">
							<h4 style="color:red">'.GetMessage("WARNING_REWRITE_INDEX").'</h4>';

		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="install_type">'.GetMessage("WIZ_INSTALL_TYPE").'</label></h4>';

		if($firstInstall)
		{
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("install_type", "install", array("checked" => "checked", "onchange"=>$onChange)).GetMessage("WIZ_INSTALL_FIRST").'</label></div>';
		}
		else
		{
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("install_type", "update", array("checked" => "checked", "onchange"=>$onChange)).GetMessage("WIZ_UPDATE").'</label><br>'.GetMessage("WIZ_UPDATE_DESC").'</div>';
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("install_type", "install", array("onchange"=>$onChange)).GetMessage("WIZ_INSTALL").'</label><br>'.GetMessage("WIZ_INSTALL_DESC").'</div>';
		}

		$this->content .= '</div>
		</div>';
		$this->content .= '<style>.buttons{margin-top: -50px;}</style>
		<div style="position: relative; float: left; margin-left: 260px;">
			<a class="button-prev" href="/bitrix/admin/wizard_list.php">
					<span id="prev-button-caption">'.GetMessage("WIZ_CANCEL").'</span>
			</a>
		</div>';

		$this->content .= '<script>
			function changeNextStep()
			{
				var input = document.getElementsByName("NextStepID")[0];
				var install_type = document.getElementsByName("__wiz_install_type");
				for (var i = 0; i < install_type.length; i++) {
					if (install_type[i].checked) {
							value = install_type[i].value;       
					}
				}
	
				if(value == "install")
					input.value = "select_theme";
				else if(value == "update")
					input.value = "data_install";
			}
			changeNextStep();
		</script>';

	}
}

class SelectThemeStep extends CSelectThemeWizardStep
{
	function InitStep()
	{
		$arError = checkNeedModules();

		parent::InitStep();
		$wizard =& $this->GetWizard();
		$wizard->SetVar("templateID", "romza_bitronic2"); // change
		$wizard->SetVar("arError", $arError);
		$this->SetPrevStep("install_type");
		$update = $wizard->GetVar("install_type") == 'update';
		if($update)
		{
			$this->SetNextStep("data_install");
		}
		else
		{
			$this->SetNextStep("site_settings");
		}
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		$arError = $wizard->GetVar("arError");

		if(count($arError) > 0) {
			$this->content .= "<p style='color:red'>";
			foreach($arError as $errCode) {
				$this->content .= GetMessage($errCode,array('#moduleID#'=>$moduleId) ).'<br/>';
			}
			$this->content .= GetMessage("MODULE_NOT_INSTALLED_TIP")."</p>";
			$this->SetNextStep("select_theme");
		} else {
			parent::ShowStep();
		}
	}
}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		parent::InitStep();

		$this->SetStepID("site_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetTitle(GetMessage("WIZ_STEP_SITE_SET"));

		$siteID = $wizard->GetVar("siteID");

		$this->SetNextStep("shop_settings");

		$arSocNets = array(
			"facebook"=>"WIZ_SHOP_FACEBOOK_DEF",
			"twitter"=>"WIZ_SHOP_TWITTER_DEF",
			"vk"=>"WIZ_SHOP_VK_DEF",
			"youtube"=>"WIZ_SHOP_YOUTUBE_DEF",
			"skype"=>"WIZ_SHOP_SKYPE_DEF"
		);

		foreach($arSocNets as $includeFile=>$messCode)
		{
			$arSocNetsValues[$includeFile] = "";
			$link = $this->GetFileContent(WIZARD_SITE_PATH."include_areas/footer/socnet_".$includeFile.".php", GetMessage($messCode));

			if ($link == GetMessage($messCode))
				$arSocNetsValues[$includeFile] = $link;
			else
			{
				preg_match("/\".*\"/", $link, $match);
				if ($match[0])
					$arSocNetsValues[$includeFile] = str_replace('"', '', $match[0]);
			}
		}

		// change inlude_areas
		$wizard->SetDefaultVars(
			Array(
				"siteName" => $this->GetFileContent(WIZARD_SITE_PATH."include_areas/header/logo_text.php", GetMessage("WIZ_COMPANY_NAME_DEF")),
				"siteSlogan" => $this->GetFileContent(WIZARD_SITE_PATH."include_areas/header/logo_under_text.php", GetMessage("WIZ_COMPANY_SLOGAN_DEF")),
				"siteTelephone" => GetMessage("WIZ_COMPANY_TELEPHONE_DEF"),
				// "siteSchedule" => $this->GetFileContent(WIZARD_SITE_PATH."include_areas/timesheet.php", GetMessage("WIZ_COMPANY_SCHEDULE_DEF")),
				"siteCopy" => $this->GetFileContent(WIZARD_SITE_PATH."include_areas/footer/copyright.php", GetMessage("WIZ_COMPANY_COPY_DEF")),
				"siteCopyName" => $this->GetFileContent(WIZARD_SITE_PATH."include_areas/footer/shop_name.php", GetMessage("WIZ_COMPANY_SHOP_NAME_DEF")),
				"shopEmail" => "sale@".$_SERVER["SERVER_NAME"],
				"siteMetaDescription" => GetMessage("wiz_site_desc"),
				"siteMetaKeywords" => GetMessage("wiz_keywords"),
				"shopFacebook" => $arSocNetsValues["facebook"],
				"shopTwitter" => $arSocNetsValues["twitter"],
				"shopVk" => $arSocNetsValues["vk"],
				// "shopYouTube" => $arSocNetsValues["youtube"],				
				// "shopSkype" => $arSocNetsValues["skype"],				
			)
		);

	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form">';

		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteName" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_NAME").'</label>
			'.$this->ShowInputField('text', 'siteName', array("id" => "siteName", "class" => "wizard-field")).'
		</div>';

		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSlogan" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_SLOGAN").'</label>
			'.$this->ShowInputField('text', 'siteSlogan', array("id" => "siteSlogan", "class" => "wizard-field")).'
		</div>';

		$siteLogo = $wizard->GetVar("siteLogo", true);
		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteLogo" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_LOGO").'</label>
			'.$this->ShowFileField("siteLogo", Array("show_file_info"=> "N", "id" => "siteLogo")).'<br />'.CFile::ShowImage($siteLogo, 220, 70, "border=0 vspace=5", false, false).'
		</div>'; // change show logo size

		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteTelephone" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_TELEPHONE").'</label>
			'.$this->ShowInputField('text', 'siteTelephone', array("id" => "siteTelephone", "class" => "wizard-field")).'
		</div>';

		$this->content .= '<div class="wizard-input-form-block">
				<label for="shopEmail" class="wizard-input-title">'.GetMessage("WIZ_SHOP_EMAIL").'</label>
				'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
			</div>';

		// $this->content .= '
		// <div class="wizard-input-form-block">
		// <label for="siteSchedule" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_SCHEDULE").'</label>
		// '.$this->ShowInputField('textarea', 'siteSchedule', array("rows"=>"3", "id" => "siteSchedule", "class" => "wizard-field")).'
		// </div>';	

		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteCopy" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_COPY").'</label>
			'.$this->ShowInputField('textarea', 'siteCopy', array("rows"=>"3", "id" => "siteCopy", "class" => "wizard-field")).'
		</div>';

		$this->content .= '<div class="wizard-input-form-block">
				<label for="siteCopyName" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_COPY2").'</label>
				'.$this->ShowInputField('text', 'siteCopyName', array("id" => "siteCopyName", "class" => "wizard-field")).'
			</div>';
//SocNets
		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="shopTwitter" class="wizard-input-title">'.GetMessage("WIZ_SHOP_TWITTER").'</label>
			'.$this->ShowInputField('text', 'shopTwitter', array("id" => "shopTwitter", "class" => "wizard-field")).'
		</div>';
		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="shopFacebook" class="wizard-input-title">'.GetMessage("WIZ_SHOP_FACEBOOK").'</label>
			'.$this->ShowInputField('text', 'shopFacebook', array("id" => "shopFacebook", "class" => "wizard-field")).'
		</div>';

		$this->content .= '
		<div class="wizard-input-form-block">
			<label for="shopVk" class="wizard-input-title">'.GetMessage("WIZ_SHOP_VK").'</label>
			'.$this->ShowInputField('text', 'shopVk', array("id" => "shopVk", "class" => "wizard-field")).'
		</div>';
		/*---*/
		$styleMeta = 'style="display:block"';

		$this->content .= '
		<div  id="bx_metadata" '.$styleMeta.'>
			<div class="wizard-input-form-block">
				<div class="wizard-metadata-title">'.GetMessage("wiz_meta_data").'</div>
				<label for="siteMetaDescription" class="wizard-input-title">'.GetMessage("wiz_meta_description").'</label>
				'.$this->ShowInputField("textarea", "siteMetaDescription", Array("id" => "siteMetaDescription", "rows"=>"3", "class" => "wizard-field")).'
			</div>';
		$this->content .= '
			<div class="wizard-input-form-block">
				<label for="siteMetaKeywords" class="wizard-input-title">'.GetMessage("wiz_meta_keywords").'</label><br>
				'.$this->ShowInputField('text', 'siteMetaKeywords', array("id" => "siteMetaKeywords", "class" => "wizard-field")).'
			</div>
		</div>';

//install Demo data
		$this->content .= '
			<div class="wizard-input-form-block"'.(LANGUAGE_ID != "ru" ? ' style="display:none"' : '').'>
				'.$this->ShowCheckboxField(
				"installDemoData",
				"Y",
				(array("id" => "installDemoData", "checked" => "checked"))
			).'
				<label for="installDemoData">'.GetMessage("wiz_structure_data").'</label>
			</div>';

		if(LANGUAGE_ID != "ru")
		{
			if (CModule::IncludeModule("catalog"))
			{
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch())
				{
					$this->content .= '
					<div class="wizard-input-form-block">
						<label for="shopAdr">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</label>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
							(array("id" => "install-demo-data")))
						. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

						</div>
					</div>';
				}
			}
		}

		$this->content .= '</div>';
	}
	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteLogo", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 100, "max_width" => 150, "make_preview" => "Y")); // change save logo size
	}
}

class ShopSettings extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("shop_settings");
		$this->SetTitle(GetMessage("WIZ_STEP_SS"));
		$this->SetNextStep("person_type");
		$this->SetPrevStep("site_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$siteID = $wizard->GetVar("siteID");

		$wizard->SetDefaultVars(
			Array(
				"shopLocalization" => "ru",
				"shopEmail" => "sale@".$_SERVER["SERVER_NAME"],
				"shopOfName" => GetMessage("WIZ_SHOP_OF_NAME_DEF"),
				"shopLocation" => GetMessage("WIZ_SHOP_LOCATION_DEF"),
				//"shopZip" => 101000,
				"shopAdr" => GetMessage("WIZ_SHOP_ADR_DEF"),
				"shopINN" => "1234567890",
				"shopKPP" => "123456789",
				"shopNS" => "0000 0000 0000 0000 0000",
				"shopBANK" => GetMessage("WIZ_SHOP_BANK_DEF"),
				"shopBANKREKV" => GetMessage("WIZ_SHOP_BANKREKV_DEF"),
				"shopKS" => "30101 810 4 0000 0000225",

				"installPriceBASE" => "Y",
			)
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		if (!CModule::IncludeModule("catalog"))
		{
			$this->content .= "<p style='color:red'>".GetMessage("WIZ_NO_MODULE_CATALOG")."</p>";
			$this->SetNextStep("shop_settings");
		}
		else
		{
			$this->content .= '<div class="wizard-catalog-title">'.GetMessage("WIZ_STEP_SS").'</div>
				<div class="wizard-input-form">';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopEmail">'.GetMessage("WIZ_SHOP_EMAIL").'</label>
					'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
				</div>';

			//ru
			$this->content .= '<div id="ru_bank_details" class="wizard-input-form-block" style="display:block">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">'.GetMessage("WIZ_SHOP_OF_NAME").'</label>'
				.$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation">'.GetMessage("WIZ_SHOP_LOCATION").'</label>'
				.$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr">'.GetMessage("WIZ_SHOP_ADR").'</label>'
				.$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_BANK_TITLE").'</div>
				<table class="wizard-input-table">
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_INN").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN', array("class" => "wizard-field")).'</td>
					</tr>
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KPP").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKPP', array("class" => "wizard-field")).'</td>
					</tr>
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NS").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS', array("class" => "wizard-field")).'</td>
					</tr>
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANK").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANK', array("class" => "wizard-field")).'</td>
					</tr>
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANKREKV").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANKREKV', array("class" => "wizard-field")).'</td>
					</tr>
					<tr>
						<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KS").':</td>
						<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKS', array("class" => "wizard-field")).'</td>
					</tr>
				</table>
			</div><!--ru-->
			';

			if (CModule::IncludeModule("catalog"))
			{
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch())
				{
					$this->content .= '
					<div class="wizard-input-form-block">
						<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</div>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
							(array("id" => "install-demo-data")))
						. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

						</div>
					</div>';
				}
			}

			$this->content .= '</div>';
		}
	}
}

class PersonType extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("person_type");
		$this->SetTitle(GetMessage("WIZ_STEP_PT"));
		$this->SetNextStep("pay_system");
		$this->SetPrevStep("shop_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();
		$siteID = $wizard->GetVar("siteID");

		$wizard->SetDefaultVars(
			Array(
				"personType" => Array(
					"fiz" =>  "Y",
					"ur" => "Y",
				)
			)
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<!--<div class="wizard-catalog-title">'.GetMessage("WIZ_PERSON_TYPE_TITLE").'</div>-->
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).
			' <label for="personTypeF">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).
			' <label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label><br />
					</div>';
		$this->content .= '
				</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PERSON_TYPE").'<div>
		</div>';
		$this->content .= '</div>';
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$personType = $wizard->GetVar("personType");

		if (empty($personType["fiz"]) && empty($personType["ur"]))
			$this->SetError(GetMessage('WIZ_NO_PT'));
	}

}

class PaySystem extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("pay_system");
		$this->SetTitle(GetMessage("WIZ_STEP_PS"));
		$this->SetNextStep("data_install");
		$this->SetPrevStep("person_type");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		if(LANGUAGE_ID == "ru")
		{
			$wizard->SetDefaultVars(
				Array(
					"paysystem" => Array(
						"cash" => "Y",
						"sber" => "Y",
						"bill" => "Y",
						"collect" => "Y"  //cash on delivery
					),
					"delivery" => Array(
						"courier" => "Y",
						"self" => "Y",
						"russianpost" => "N",
						"rus_post" => "N",
						"rus_post_first" => "N",
						"ua_post" => "N",
						"kaz_post" => "N"
					)
				)
			);
		}
		else
		{
			$wizard->SetDefaultVars(
				Array(
					"paysystem" => Array(
						"cash" => "Y",
						"paypal" => "Y",
					),
					"delivery" => Array(
						"courier" => "Y",
						"self" => "Y",
						"dhl" => "Y",
						"ups" => "Y",
					)
				)
			);
		}
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$paysystem = $wizard->GetVar("paysystem");

		if (
			empty($paysystem["cash"])
			&& empty($paysystem["sber"])
			&& empty($paysystem["bill"])
			&& empty($paysystem["paypal"])
			&& empty($paysystem["oshad"])
			&& empty($paysystem["collect"])
		)
			$this->SetError(GetMessage('WIZ_NO_PS'));
		/*payer type
                if(LANGUAGE_ID == "ru")
                {
                    $personType = $wizard->GetVar("personType");

                    if (empty($personType["fiz"]) && empty($personType["ur"]))
                        $this->SetError(GetMessage('WIZ_NO_PT'));
                }
        ===*/
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();

		$personType = $wizard->GetVar("personType");

		$arAutoDeliveries = array();
		if (CModule::IncludeModule("sale"))
		{
			$dbDelivery = CSaleDeliveryHandler::GetList();
			while($arDelivery = $dbDelivery->Fetch())
			{
				$arAutoDeliveries[$arDelivery["SID"]] = $arDelivery["ACTIVE"];
			}
		}
		$siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).
			' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label>
					</div>';

		if(LANGUAGE_ID == "ru")
		{
			if ($personType["fiz"] == "Y")
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).
					' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label>
								</div>';
			if ($personType["fiz"] == "Y" || $personType["ur"] == "Y")
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('paysystem[collect]', 'Y', (array("id" => "paysystemCOL"))).
					' <label for="paysystemCOL">'.GetMessage("WIZ_PAY_SYSTEM_COL").'</label>
								</div>';

			if($personType["ur"] == "Y")
			{
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).
					' <label for="paysystemB">';

				$this->content .= GetMessage("WIZ_PAY_SYSTEM_B");
				$this->content .= '</label>
							</div>';
			}
		}
		else
		{
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))).
				' <label for="paysystemP">PayPal</label>
						</div>';
		}
		$this->content .= '</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PAY_SYSTEM").'</div>
		</div>';


		$this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-catalog-title">'.GetMessage("WIZ_DELIVERY_TITLE").'</div>
				<div>
					<div class="wizard-input-form-field wizard-input-form-field-checkbox">';

		$this->content .= '<div class="wizard-catalog-form-item">
								'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).
			' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label>
							</div>
							<div class="wizard-catalog-form-item">
								'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).
			' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label>
							</div>';
		if(LANGUAGE_ID == "ru")
		{

			if ($arAutoDeliveries["russianpost"] != "Y")
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('delivery[russianpost]', 'Y', (array("id" => "deliveryR"))).
					' <label for="deliveryR">'.GetMessage("WIZ_DELIVERY_R").'</label>
										</div>';
			if ($arAutoDeliveries["rus_post"] != "Y")
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('delivery[rus_post]', 'Y', (array("id" => "deliveryR2"))).
					' <label for="deliveryR2">'.GetMessage("WIZ_DELIVERY_R2").'</label>
										</div>';
			if ($arAutoDeliveries["rus_post_first"] != "Y")
				$this->content .=
					'<div class="wizard-catalog-form-item">'.
					$this->ShowCheckboxField('delivery[rus_post_first]', 'Y', (array("id" => "deliveryRF"))).
					' <label for="deliveryRF">'.GetMessage("WIZ_DELIVERY_RF").'</label>
										</div>';
		}
		else
		{
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))).
				' <label for="deliveryD">DHL</label>
								</div>';
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))).
				' <label for="deliveryU">UPS</label>
								</div>';
		}
		$this->content .= '
					</div>
				</div>
				<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY").'</div>
			</div>';

		$this->content .= '
		<div>
			<div class="wizard-catalog-title">'.GetMessage("WIZ_LOCATION_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		if(in_array(LANGUAGE_ID, array("ru", "ua")))
		{
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
				." <label for=\"loc_ussr\">".GetMessage('WSL_STEP2_GFILE_USSR')."</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowRadioField("locations_csv", "loc_ua.csv", array("id" => "loc_ua"))
				." <label for=\"loc_ua\">".GetMessage('WSL_STEP2_GFILE_UA')."</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">'.
				$this->ShowRadioField("locations_csv", "loc_kz.csv", array("id" => "loc_kz"))
				." <label for=\"loc_kz\">".GetMessage('WSL_STEP2_GFILE_KZ')."</label>
				</div>";
		}
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
			." <label for=\"loc_usa\">".GetMessage('WSL_STEP2_GFILE_USA')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
			." <label for=\"loc_cntr\">".GetMessage('WSL_STEP2_GFILE_CNTR')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "", array("id" => "none"))
			." <label for=\"none\">".GetMessage('WSL_STEP2_GFILE_NONE')."</label>
			</div>";

		$this->content .= '
				</div>
			</div>
		</div>';

		$this->content .= '<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY_HINT").'</div>';

		$this->content .= '</div>';
	}
}
class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		if($_SESSION["BX_ESHOP_LOCATION"] == "Y")
			$this->repeatCurrentService = true;
		else
			$this->repeatCurrentService = false;

		$wizard =& $this->GetWizard();
		if($wizard->GetVar("installDemoData") != "Y")
		{
		}
	}
}

class FinishStep extends CFinishWizardStep
{
	function InitStep()
	{
		$this->SetStepID("finish");
		$this->SetNextStep("finish");
		$this->SetTitle(GetMessage("FINISH_STEP_TITLE"));
		$this->SetNextCaption(GetMessage("wiz_go"));
	}

	function ShowStep()
	{
		include 'include/moduleInclude.php';

		$wizard =& $this->GetWizard();
		if ($wizard->GetVar("proactive") == "Y")
			COption::SetOptionString("statistic", "DEFENCE_ON", "Y");

		$siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
		$rsSites = CSite::GetByID($siteID);
		$siteDir = "/";
		if ($arSite = $rsSites->Fetch())
			$siteDir = $arSite["DIR"];

		$wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));

		$update = $wizard->GetVar("install_type") == 'update';
		if(!$update)
			$this->CreateNewIndex();

		COption::SetOptionString($wizard->solutionName, "wizard_install", "Y", false, $siteID);

		CAdminNotify::DeleteByTag('RZ_BITRONIC2_UPDATE_WIZARD');

		$this->content .=
			'<table class="wizard-completion-table">
				<tr>
					<td class="wizard-completion-cell">'
			.GetMessage("FINISH_STEP_CONTENT");

		if (!empty($_SESSION['RZ_FILES_REWRITED']) && is_array($_SESSION['RZ_FILES_REWRITED'])) {
			$this->content .= '</td></tr><tr><td>' . GetMessage('FINISH_STEP_FILES') . ': <ul>';
			foreach ($_SESSION['RZ_FILES_REWRITED'] as $file => $backup) {
				$this->content .= '<li>' . $file . '</li>';
			}
			$this->content .= '</ul>' . GetMessage('FINISH_STEP_BACKUPS');
			unset($_SESSION['RZ_FILES_REWRITED']);
		}

		if (COption::GetOptionString($wizard->solutionName, 'update_2.17.0', 'N', $siteID) === 'Y') {
			COption::RemoveOption($wizard->solutionName, 'update_2.17.0', $siteID);
			$this->content .= '</td></tr><tr><td>&nbsp;</td></tr><tr><td>' . GetMessage('FINISH_STEP_SETTINGS_2.17.0');
			if (CRZBitronic2Settings::getEdition() !== 'LITE') {
			$this->content .= '</td></tr><tr><td>&nbsp;</td></tr><tr><td>' . GetMessage('FINISH_STEP_EVENT_TYPES')
				. ' <ul><li><a href="'.BX_ROOT.'/admin/type_edit.php?EVENT_NAME=ELEMENT_EXIST&lang='.LANGUAGE_ID.'">ELEMENT_EXIST</a> ' . GetMessage('FINISH_STEP_ELEMENT_EXIST')
				. '</li><li><a href="'.BX_ROOT.'/admin/type_edit.php?EVENT_NAME=ELEMENT_CONTACT&lang='.LANGUAGE_ID.'">ELEMENT_CONTACT</a> ' . GetMessage('FINISH_STEP_ELEMENT_CONTACT')
				. '</li><li><a href="'.BX_ROOT.'/admin/type_edit.php?EVENT_NAME=PRICE_LOWER&lang='.LANGUAGE_ID.'">PRICE_LOWER</a> ' . GetMessage('FINISH_STEP_PRICE_LOWER')
				. '</li></ul>';
			}
		}
		if (COption::GetOptionString($wizard->solutionName, 'update_2.19.0', 'N', $siteID) === 'Y') {
			COption::RemoveOption($wizard->solutionName, 'update_2.19.0', $siteID);
			if (CRZBitronic2Settings::getEdition() !== 'LITE') {
				$this->content .= '</td></tr><tr><td>&nbsp;</td></tr><tr><td style="border:2px solid red;padding:10px;">' . GetMessage('FINISH_STEP_2.19.0');
			}
		}

		$this->content .= '</td>
				</tr>
			</table>';
		//	$this->content .= "<br clear=\"all\"><a href=\"/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&site_id=".$siteID."&wizardName=bitrix:eshop.mobile&".bitrix_sessid_get()."\" class=\"button-next\"><span id=\"next-button-caption\">".GetMessage("wizard_store_mobile")."</span></a><br>";

		if ($wizard->GetVar("installDemoData") == "Y")
			$this->content .= GetMessage("FINISH_STEP_REINDEX");


	}
}
?>

