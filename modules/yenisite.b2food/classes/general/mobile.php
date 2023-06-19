<?php
namespace Bitronic2;

use Mobile_Detect;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);



class Mobile {
	static private $_deviceType = 'computer';
	static private $_fullMode = 'Y';
	const fullModeName = 'mobile_full';
	
	public static function Init()
	{
		$detect = new Mobile_Detect;
		self::$_deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
				
		if($detect->isMobile())
		{
			global $APPLICATION;
			$cookieName = self::fullModeName;
			if(isset($_GET[$cookieName]))
			{
				self::$_fullMode = $_GET[$cookieName] == 'Y' ? 'Y' : 'N';
				
				$APPLICATION->set_cookie($cookieName, self::$_fullMode);
			}
			else
			{
				self::$_fullMode = $APPLICATION->get_cookie($cookieName);
			}			
		}
		unset($detect);
	}
	
	public static function isMobile($consider_full_mode = true)
	{
		$isMobile = false;
		$isMobile = self::$_deviceType == 'phone';
		if($consider_full_mode)
		{
			$isMobile = $isMobile && !self::isFullMode();
		}
		return $isMobile;
	}
	
	public static function isTablet()
	{
		return self::$_deviceType == 'tablet';
	}
	
	public static function isPc()
	{
		return self::$_deviceType == 'computer';
	}
	
	public static function isFullMode()
	{
		return self::$_fullMode == 'Y';
	}
}