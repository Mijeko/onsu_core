<?php

/**
 * Class CYSFeedBack
 * @author Eduard <ytko90@gmail.com>
 * @version 1.1.0
 */
class CYSFeedBack {

	static private $_module             = "yenisite.feedback";
	static public $_IBLOCK_TYPE         = false;
	static public $_IBLOCK_ID           = false;
	static public $_IP_PROPERTY         = "ip";
	static public $_ELEMENT_ID_PROPERTY = "element_id";
	static public $_EMAIL_PROPERTY      = "email";

	//Case insensitive version for array_key_exists.
	//Returns the matching key on success else false.
	function array_key_exists_nc($key, $search) {
		if (array_key_exists($key, $search)) {
			return $key;
		}
		if (!(is_string($key) && is_array($search) && count($search))) {
			return false;
		}
		$key = strtolower($key);
		foreach ($search as $k => $v) {
			if (strtolower($k) == $key) {
				return $k;
			}
		}
		return false;
	}

	static private function checkConf()
	{
		if (self::$_IBLOCK_ID === false || self::$_IBLOCK_ID == "")
		{
			echo "IBLOCK_ID NOT SET IN CYSFeedBack!<br>";
			return false;
		}
		return true;
	}

	static function bitronicExist()
	{
		if (CModule::IncludeModule("yenisite.bitronic")
		||  CModule::IncludeModule("yenisite.bitroniclite")
		||  CModule::IncludeModule("yenisite.bitronicpro")
		) return true;

		return false;
	}

	//If Bitronic installed - get COLOR_SCHEME from Bitronic
	//else - return false and use component's color scheme
	static function getBitronicColorScheme()
	{
		if (self::bitronicExist())
		{
			if (class_exists('CYSBitronicSettings'))
				return CYSBitronicSettings::getSetting("COLOR_SCHEME");
			else
			{
				global $ys_settings;
				return $ys_settings['color_scheme'];
			}
		}
		else
			return false;
	}

	static function getSections()
	{
		if (!self::checkConf())
			return false;

		$obCache = new CPHPCache;
		$life_time = 60 * 60 * 24 * 7;

		$cache_id = "feedback_sections";

		if($obCache->InitCache($life_time, $cache_id, "/yenisite.feedback/"))
		{
			$vars = $obCache->GetVars();
			$sections = $vars['SECTIONS'];
		}
		else
		{
			if($obCache->StartDataCache())
			{
				$arSelect = array('ID', 'NAME', 'CODE');
				$arFilter = array('IBLOCK_ID' => self::$_IBLOCK_ID);
				$res = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true, $arSelect);

				$sections = array();
				while($rsSection = $res->GetNext())
				{
					$sections[] = $rsSection;
				}
				$obCache->EndDataCache(array(
						"SECTIONS" => $sections
				));
			}
		}

		return $sections;
	}

	static function getPropertiesArray($printFields = false, $path = "" /*for unique cache*/)
	{
		if (!self::checkConf())
			return false;

		$obCache = new CPHPCache;
		$life_time = 60 * 60 * 24 * 7;

		if (!$printFields)
			$cacheFields = "default_" . $path;
		elseif (is_array($printFields))
		{
			$printFields[] = $path;
			$cacheFields = implode('|||', $printFields);
		}
		else
			$cacheFields = $printFields . "_" . $path;

		$cache_id = $cacheFields;
		$fields = array();

		if($obCache->InitCache($life_time, $cache_id, "/yenisite.feedback/"))
		{
			//Get cached data
			$vars = $obCache->GetVars();
			$fields = $vars['FIELDS'];
		}
		else
		{
			//else - query to DB
			if($obCache->StartDataCache())
			{
				$properties = CIBlock::GetProperties(self::$_IBLOCK_ID, Array('sort' => 'asc'));
				while ($prop_fields = $properties->GetNext())
				{
					if ($printFields == 'all')
					{
						$fields[] = array(
							'CODE'          => $prop_fields['CODE'],
							'NAME'          => $prop_fields['NAME'],
							'PROPERTY_TYPE' => $prop_fields['PROPERTY_TYPE'],
							'USER_TYPE'     => $prop_fields['USER_TYPE'],
							'IS_REQUIRED'   => $prop_fields['IS_REQUIRED']
						);
					}
					elseif ($printFields == 'all_plain')
					{
						$fields[] = $prop_fields['CODE'];
					}
					else
					{
						if ($prop_fields['IS_REQUIRED'] == "Y" || ($printFields !== false && !empty($printFields) && in_array($prop_fields['CODE'], $printFields)))
						{
							$fields[] = array(
								'CODE'          => $prop_fields['CODE'],
								'NAME'          => $prop_fields['NAME'],
								'PROPERTY_TYPE' => $prop_fields['PROPERTY_TYPE'],
								'USER_TYPE'     => $prop_fields['USER_TYPE'],
								'IS_REQUIRED'   => $prop_fields['IS_REQUIRED']
							);
						}
					}
				}

				$obCache->EndDataCache(array(
						"FIELDS"    => $fields
					));
			}
		}

		return $fields;
	}

	static function checkCAPTCHA($captcha_word, $captcha_code)
	{
		include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");

		$cpt = new CCaptcha();
		if (strlen($captcha_code) > 0)
		{
			$captchaPass = COption::GetOptionString("main", "captcha_password", "");
			if ($cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
			{
				return true;
			}
		}
		return false;
	}

	static function generateCAPTCHA()
	{
		include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
		$cpt = new CCaptcha();
		$captchaPass = COption::GetOptionString("main", "captcha_password", "");
		if (strLen($captchaPass) <= 0) {
			$captchaPass = randString(10);
			COption::SetOptionString("main", "captcha_password", $captchaPass);
		}

		$cpt->SetCodeCrypt($captchaPass);
		return htmlspecialchars($cpt->GetCodeCrypt());
	}

	static function getResponses($elements)
	{
		self::checkConf();

		$arSelect = Array('ID', 'NAME', 'PROPERTY_MESSAGE', 'PROPERTY_ELEMENT');
		$arFilter = Array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_element' => $elements);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

		while ($rsElement = $res->GetNext()) {
			$responses[$rsElement['PROPERTY_ELEMENT_VALUE']] = $rsElement;
		}

		return $responses;
	}

	static function existIPProperty()
	{
		$IPProperty = CIBlock::GetProperties(self::$_IBLOCK_ID, Array('sort' => 'asc'), Array('CODE' => self::$_IP_PROPERTY));
		$IP = $IPProperty->Fetch();
		if($IPProperty->SelectedRowsCount() != 1)
			return false;
		else
			return $IP['CODE'];
	}

	static function existElementIDProperty()
	{
		$ElementIDProperty = CIBlock::GetProperties(self::$_IBLOCK_ID, Array('sort' => 'asc'), Array('CODE' => self::$_ELEMENT_ID_PROPERTY));
		$ElementID = $ElementIDProperty->Fetch();
		if($ElementIDProperty->SelectedRowsCount() != 1)
			return false;
		else
			return $ElementID['CODE'];
	}

	static function propCodeToName($fields, $codes, $props, $arParams)
	{
		$bAdmin = (strpos($arParams['EVENT_NAME'], '_ADMIN') !== false)
		       || ($arParams['EVENT_NAME'] == 'FOUND_CHEAP')
		       || ($arParams['EVENT_NAME'] == 'ELEMENT_CONTACT');
		$newFields = array();
		foreach($fields as $key => $field) {
			if (!in_array($field['CODE'], $codes)) continue;
			if ($field['PROPERTY_TYPE'] == 'F') continue;

			$newFields[$field['CODE']]['name'] = $field['NAME'];
			if ($field['PROPERTY_TYPE'] == 'E' && $field['USER_TYPE'] == NULL) {
				$res = CIBlockElement::GetByID($props[$field['CODE']]);
				if ($ar_res = $res->GetNext()) {
					$newFields[$field['CODE']]['value'] = $ar_res['NAME'];
					$newFields[$field['CODE']]['link'] = $ar_res['DETAIL_PAGE_URL'];
					if ($bAdmin) {
						$newFields[$field['CODE']]['admin'] = BX_ROOT . '/admin/' . CIBlock::GetAdminElementEditLink($ar_res['IBLOCK_ID'], $ar_res['ID']);
						$newFields[$field['CODE']]['id'] = $ar_res['ID'];
					}
					continue;
				}
			}
			$newFields[$field['CODE']]['value'] = $props[$field['CODE']];
		}

		return $newFields;
	}

	static function arrayToStrings($array, $glue = "\n")
	{
		$http = (CMain::IsHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
		$props = "";
		foreach ($array as $key => $val) {
			$props .= $val['name'] . ": ";
			if ($val['link']) $props .= '<a href="' . $http . $val['link'] . '">';
			
			if(is_array($val['value']) && isset($val['value']['VALUE']['TEXT'])) {
				$val['value'] = $val['value']['VALUE']['TEXT'];
			}
			
			$props .= $val['value'];
			if ($val['link']) $props .= '</a>';
			if ($val['admin']) $props .= ' [<a href="' . $http . $val['admin'] . '">' . $val['id'] . '</a>]';
			$props .= $glue;
		}

		return $props;
	}

	static function addMessage($post, $code, $files, $element_add, $fields, $arParams, $USER_ID, $sectionFields, $make_section = true)
	{
		$name = (isset($post[$code][$arParams['NAME_FIELD']])) ? $post[$code][$arParams['NAME_FIELD']] : $element_add;

		foreach($fields as $field)
		{
            //var_dump($name);
            //var_dump($files);
            if (count($files) > 0 && !empty($files[$code]['name'][$field['CODE']])){
                $arr_file = Array(
                    "name" => $files[$code]['name'][$field['CODE']],
                    "size" => $files[$code]['size'][$field['CODE']],
                    "tmp_name" => $files[$code]['tmp_name'][$field['CODE']],
                    "type" => '',
                    "old_file" => '',
                    "del" => '',
                    "MODULE_ID" => 'iblock'
                );

                //var_dump($arr_file);
                $fid = CFile::SaveFile($arr_file, 'iblock');
                //echo "--- " . $fid . " ---";
            }

            //var_dump($fields);
			if (isset($post[$code][$field['CODE']]))
			{
				if($field['PROPERTY_TYPE'] == 'S' && $field['USER_TYPE'] == 'HTML')
					$PROP[$field['CODE']] = array('VALUE'=>array('TEXT' => $post[$code][$field['CODE']], 'TYPE' => $field['USER_TYPE']));

				elseif(($field['PROPERTY_TYPE'] == 'S' || $field['PROPERTY_TYPE'] == 'N') && $field['USER_TYPE'] == NULL)
					$PROP[$field['CODE']] = $post[$code][$field['CODE']];

				elseif($field['PROPERTY_TYPE'] == 'S' && $field['USER_TYPE'] == 'DateTime')
					$PROP[$field['CODE']] = $post[$code][$field['CODE']];

				elseif($field['PROPERTY_TYPE'] == 'E' && $field['USER_TYPE'] == NULL)
					$PROP[$field['CODE']] = $post[$code][$field['CODE']];
				else { // if type not supported like "L" property, try to add raw data to field
					$PROP[$field['CODE']] = $post[$code][$field['CODE']];
				}
			}
			//Process FILE field
			elseif($field['PROPERTY_TYPE'] == 'F' && $field['USER_TYPE'] == NULL)
			{
				$PROP[$field['CODE']] = CFile::MakeFileArray($fid);
			}
		}

//        var_dump($PROP);

		//$_POST[guestbook][text] to IBlockElement PREVIEW_TEXT
		//$PROP['PREVIEW_TEXT'] = Array("VALUE" => Array ("TEXT" => $_POST['guestbook']['text'], "TYPE" => "text"));
		$text = '';
		if ($arParams['TEXT_SHOW'] == 'Y')
			$text = $post[$code]['text'];

		if ($arParams['TEXT_REQUIRED'] == 'Y')
			if (empty($text))
				return "ERR_TEXT_EMPTY";

		//Validate EMAIL
		if ($emailProperty = self::array_key_exists_nc(self::$_EMAIL_PROPERTY, $PROP)) {
			$isReq = false;
			foreach ($fields as $arField) {
				if ($emailProperty == $arField['CODE']) {
					if ('Y' == $arField['IS_REQUIRED']) {
						$isReq = true;
					}
				}
			}
			$PROP[$emailProperty] = trim($PROP[$emailProperty]);
			if ($isReq || !empty($PROP[$emailProperty])) {
				if (!filter_var($PROP[$emailProperty], FILTER_VALIDATE_EMAIL)) {
					return "ERR_EMAIL_INVALID";
				}
			}
		}

		//Check IP property exist.
		//And if property exist - write IP address to this property
		$IPProperty = CYSFeedBack::existIPProperty();
		if ($IPProperty !== false)
			$PROP[$IPProperty] = $_SERVER['REMOTE_ADDR'];

		//Check element_id property exist
		//And if property exist - write ELEMENT_ID from $arParams
		/*$ElementIDProperty = CYSFeedBack::existElementIDProperty();
		if ($ElementIDProperty !== false)
			$PROP[$ElementIDProperty] = $arParams['ELEMENT_ID'];
		*/

		//$haveSections = (CIBlockSection::GetCount(array('IBLOCK_ID' => self::$_IBLOCK_ID))) ? true : false;
		$sectionErr = false;
		$sectionID = false;

		if (!empty($sectionFields['CODE']))// && $haveSections)
		do
		{
			$res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => self::$_IBLOCK_ID, 'CODE' => $sectionFields['CODE']));


			$section = $res->Fetch();

			if ($section != false && $section['ID'] > 0) {
				$sectionErr = false;
				$sectionID = $section['ID'];
				break;
			}

			$sectionErr = true;
			if ($make_section == false) break;

			$bs = new CIBlockSection;
			$arFields = array(
				"ACTIVE"            => "Y",
				"IBLOCK_SECTION_ID" => false,
				"IBLOCK_ID"         => self::$_IBLOCK_ID,
				"NAME"              => $sectionFields['NAME'],
				"CODE"              => $sectionFields['CODE']
			);
			$sectionID = $bs->Add($arFields);
			if ($sectionID == false)    return $bs->LAST_ERROR;

			$sectionErr = false;
		} while(0);/*
		elseif (!$haveSections)
		{
			$sectionErr = false;
			$sectionID = false;
		}*/

		//if true - then add new element
		if (!$sectionErr)
		{
			//AddMessage2Log(print_r($PROP, true));
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
					"MODIFIED_BY"       => $USER_ID,
					"IBLOCK_SECTION_ID" => $sectionID,
					"IBLOCK_ID"         => self::$_IBLOCK_ID,
					"PROPERTY_VALUES"   => $PROP,
					"PREVIEW_TEXT"      => $text,
					"NAME"              => $name,
					"ACTIVE"            => $arParams['ACTIVE'],
			);

			//var_dump ($arLoadProductArray);
			if($response = $el->Add($arLoadProductArray))
			{
				//if EVENT_NAME not empty - send mail
				if (!empty($arParams['EVENT_NAME']))
				{
					$_PROP = $PROP;
					unset($_PROP['name']);
					unset($_PROP['text']);
					unset($_PROP['email']);
					unset($_PROP['phone']);
					unset($_PROP['NAME']);
					unset($_PROP['EMAIL']);
					unset($_PROP['PHONE']);

					$arrKeys = array_keys($_PROP);

					$newProps = self::propCodeToName($fields, $arrKeys, $_PROP, $arParams);

					$arParams['NAME'] = trim($post[$code][$arParams['NAME']]);
					$arParams['EMAIL'] = trim($post[$code][$arParams['EMAIL']]);
					$arParams['PHONE'] = trim($post[$code][$arParams['PHONE']]);
					$arParams['MESSAGE'] = trim($post[$code]['text']);
					//$arParams['MESSAGE'] = trim($arParams['MESSAGE']);
					$props = self::arrayToStrings($newProps);

					$arEventFields = array(
							"NAME"      => $arParams['NAME'],
							"EMAIL"     => $arParams['EMAIL'],
							"PHONE"     => $arParams['PHONE'],
							"MESSAGE"   => $arParams['MESSAGE'],
							"IP"        => $_SERVER['REMOTE_ADDR'],
							"PROPERTIES"=> $props,
							);

//                    AddMessage2Log(print_r($arEventFields, true));
					CEvent::Send($arParams['EVENT_NAME'], SITE_ID, $arEventFields);
				}
				return true;
			}
			else
				return $el->LAST_ERROR;
		}
		return false;
	}

	static function SetIBlockAdminListDisplaySettings($IBlockID, $arIBlockListAdminColumns, $orderByColumnName, $orderDirection, $pageSize, $isToAllUsers = true)
	{
		$IBlockType = CIBlock::GetArrayByID($IBlockID, 'IBLOCK_TYPE_ID');
		if (false == $IBlockType) {
			return false;
		}

		$arPropertyCode = array();
		$obProperties = CIBlockProperty::GetList(array("sort"=>"asc"), array("IBLOCK_ID"=>$IBlockID));
		while ($arProp = $obProperties->GetNext(true, false)) {
			$arPropertyCode[$arProp['CODE']] = $arProp['ID'];
		}

		$arColumnList = array();
		foreach ($arIBlockListAdminColumns as $columnCode) {
			if (true == array_key_exists($columnCode, $arPropertyCode)) {
				$arColumnList[] = 'PROPERTY_'.$arPropertyCode[$columnCode];
			} else {
				$arColumnList[] = $columnCode;
			}
		}
		$columnSettings = implode(',',$arColumnList);

		$arOptions[] = array(
			'c' => 'list',
			'n' => "tbl_iblock_list_".md5($IBlockType.".".$IBlockID),
			'v' => array(
				'columns'=> strtoupper($columnSettings),
				'by'=> strtoupper($orderByColumnName),
				'order'=> strtoupper($orderDirection),
				'page_size' => $pageSize
			),
		);
		if(true == $isToAllUsers) {
			$arOptions[0]['d']='Y';
		}

		CUserOptions::SetOptionsFromArray($arOptions);
	}
}

?>
