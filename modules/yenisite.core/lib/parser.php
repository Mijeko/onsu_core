<?php

namespace Yenisite\Core;


use Bitrix\Main\IO\FileNotFoundException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class Parser {
	protected $arComponents = array();
	protected $arFile = array();
	protected $sPos = '';
	protected $filePath;
	protected $isRendered = false;
	protected $isInit = false;

	function __construct($filePath) {
		if (!Loader::includeModule('fileman')) {
			throw new LoaderException('Need fileman module installed!');
		}
		$this->filePath = $filePath;
		$this->arFile = self::getFileContent($this->filePath);
		list($this->arComponents, $this->sPos) = self::parseComponents($this->arFile);
		$this->isInit = true;
	}

	protected static function getFileContent($filePath) {
		if (!file_exists($filePath)) {
			throw new FileNotFoundException($filePath);
		}
		$sContent = file_get_contents($filePath);
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		return \CFileman::ParseFileContent($sContent, true);
	}

	public static function parseComponents($arFile) {
		$cmp = array();
		$sPos = $arFile['CONTENT'];
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$arPHP = \PHPParser::ParseFile($arFile['CONTENT']);
		$c = 0;
		foreach ($arPHP as $arPHPCode) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$ar = \PHPParser::CheckForComponent2($arPHPCode[2]);
			if ($ar) {
				$cmp[] = $ar;
				$sPos = str_replace($arPHPCode[2], '#CMP' . $c . '#', $sPos);
				++$c;
			}
		}
		return array($cmp, $sPos);
	}

	public function renderComponents() {
		$i = 0;
		$sPos = $this->sPos;
		foreach ($this->arComponents as $arComponent) {
			$code = '';
			if (!is_array($arComponent["FUNCTION_PARAMS"])) {
				$arComponent["FUNCTION_PARAMS"] = array();
			}
			$arComponent["FUNCTION_PARAMS"]["ACTIVE_COMPONENT"] = ($arComponent['ACTIVE'] == 'N' ? 'N' : 'Y');

			$code .= ($arComponent["VARIABLE"] ? $arComponent["VARIABLE"] . "=" : "");
			$code .= "<?\$APPLICATION->IncludeComponent(\"" . $arComponent["COMPONENT_NAME"] . "\", ";
			$code .= "\"" . $arComponent["TEMPLATE_NAME"] . "\", ";
			$code .= "array(\r\n\t\t" . \PHPParser::ReturnPHPStr2($arComponent["PARAMS"]) . "\r\n\t)";
			$code .= ",\r\n\t" . (strlen($arComponent["PARENT_COMP"]) > 0 ? $arComponent["PARENT_COMP"] : "false");
			if ('Y' != $arComponent["FUNCTION_PARAMS"]["ACTIVE_COMPONENT"]) {
				$code .= ",\r\n\t" . "array(\r\n\t" . \PHPParser::ReturnPHPStr2($arComponent["FUNCTION_PARAMS"]) . "\r\n\t)";
			}
			$code .= "\r\n);?>";
			$cmpCODE = '#CMP' . $i . '#';
			if (strpos($sPos, $cmpCODE) !== false) {
				$sPos = str_replace($cmpCODE, $code, $sPos);
			} else {
				$sPos .= $code;
			}
			++$i;
		}
		$this->isRendered = true;
		$this->arFile['CONTENT'] = $sPos;
	}

	public function saveFile() {
		if (!$this->isRendered) {
			$this->renderComponents();
		}
		$sFileContent = '';
		$sFileContent .= ($this->arFile['PROLOG']) ?: '';
		$sFileContent .= ($this->arFile['CONTENT']) ?: '';
		$sFileContent .= ($this->arFile['EPILOG']) ?: '';
		file_put_contents($this->filePath, $sFileContent);
	}

	/**
	 * @return array
	 */
	public function getComponents() {
		return $this->arComponents;
	}

	/**
	 * @param array $arComponents
	 */
	public function setComponents($arComponents) {
		$this->arComponents = $arComponents;
	}

	/**
	 * @return array
	 */
	public function getFile() {
		return $this->arFile;
	}

	/**
	 * @return boolean
	 */
	public function isRendered() {
		return $this->isRendered;
	}

	/**
	 * @param boolean $isRendered
	 */
	public function setRendered($isRendered) {
		$this->isRendered = $isRendered;
	}

}