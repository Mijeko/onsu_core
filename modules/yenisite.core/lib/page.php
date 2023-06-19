<?php
namespace Yenisite\Core;

/**
 * Class to manipulate page content and attributes
 */
class Page
{
	/**
	 * @var array $arMetaProperties - list of properties to add into page head
	 */
	protected static $arMetaProperties = array();

	/**
	 * @var bool $isRegistered - 
	 */
	protected static $isRegistered = false;

	/**
	 * Add or replace meta property content
	 *
	 * @param string $prop - meta property attribute value
	 * @param string $content - meta content attribute value
	 *
	 * @return bool
	 */
	public static function setMetaProperty($prop, $content)
	{
		if (empty($prop)) return false;

		static::$arMetaProperties[$prop] = $content;

		if (!static::$isRegistered) {
			AddEventHandler('main', 'OnEpilog', array('\\Yenisite\\Core\\Page', 'OnEpilogHandler'));
			static::$isRegistered = true;
		}

		return true;
	}

	/**
	 * Add or replace meta Open Graph property content
	 *
	 * @param string $prop - Open Graph property name
	 * @param string $content - Open Graph property value
	 *
	 * @return bool
	 */
	public static function setOGProperty($prop, $content)
	{
		if (empty($prop)) return false;

		return static::setMetaProperty('og:' . $prop, $content);
	}

	public static function OnEpilogHandler()
	{
		$asset = \Bitrix\Main\Page\Asset::getInstance();

		foreach (static::$arMetaProperties as $property => $content) {
			$asset->addString('<meta property="' . htmlspecialcharsBx($property) . '" content="' . htmlspecialcharsBx($content) . '" />');
		}

		static::$arMetaProperties = array();
	}
}
