<?

/**
 * There is no D7 this class locate in class.php only for easy include via
 * CBitrixComponent::includeComponentClass("yenisite:proxy"); or $this
 * Class YenisiteProxy
 */
class YenisiteProxy extends CBitrixComponent
{
	/**
	 * @param string $componentName
	 * @return string mixed
	 */
	public static function getComponentName($componentName)
	{
		return str_replace(array('-', '$'), '', $componentName);
	}

	/**
	 * returns
	 * @param string $componentName
	 * @return string
	 */
	public static function getComponentKey($componentName, $bNoPostfix = false)
	{
		$paramDel = '-';
		$postfix = '';
		if ($componentName{0} == '-') {
			$paramDel = '___';
			$componentName = substr($componentName, 1);
		}
		if (strpos($componentName, '$') !== false) {
			$arCMP = explode('$', $componentName);
			$componentName = $arCMP[0];
			$postfix = '___' . $arCMP[1] ?: 0;
		}
		$key = strtoupper(str_replace(array('.', ':'), '_', $componentName));
		if ($bNoPostfix) {
			return $key;
		}
		$key .= $postfix . $paramDel;

		return $key;
	}

	/**
	 * @param string $componentName
	 * @param array $arParams
	 * @return array
	 */
	public static function unpackParamsForComponent($arParams, $componentName)
	{
		$arReturn = array();
		$componentName = self::getComponentKey($componentName, true);
		$arAll = self::unpackParams($arParams);
		if (isset($arAll[$componentName])) {
			$arReturn = $arAll[$componentName];
		}
		return $arReturn;
	}

	/**
	 * @param array $arParams
	 * @return array
	 */
	public static function unpackParams($arParams)
	{
		$arResult = array();
		foreach ($arParams as $key => $val) {
			if (strpos($key, '___') !== false) {
				$arParam = explode('___', $key);
				$arResult[$arParam[0]][$arParam[1]] = $val;
			}elseif(strpos($key, '-') !== false) {
                $arParam = explode('-', $key);
                $arResult[$arParam[0]][$arParam[1]] = $val;
            }
		}
		return $arResult;
	}

	public static function getTemplatesForInclComponents($componentName,$siteTemplateID){
	    return CComponentUtil::GetTemplatesList($componentName, $siteTemplateID);
    }
}