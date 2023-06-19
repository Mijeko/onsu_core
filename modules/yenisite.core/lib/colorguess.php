<?php
namespace Yenisite\Core;
//Класс ColorGuess использует справочник цветов из хайлоадблока.
//для этого в этом блоке надо добавить числовые поля UF_COLOR_R, UF_COLOR_G и UF_COLOR_B.
//значения этих полей будут заполнены при первом запуске автоматически.
//или вы можете задать их руками.
//$colorG = new ColorGuess($hlblockId);
//$colorG->handleIblock($iblockId, $propId);

/**
 * Используется для определения цвета товара представленного на картинке.
 * Класс реализует паттерн стратегия. Для модификации аспектов его поведения
 * достаточно перекрыть соответствущие методы.
 *
 * @see http://en.wikipedia.org/wiki/Color_difference
 * @package Yenisite\Core
 **/
class ColorGuess
{
	protected $iblocks = array();
	protected $imageFilePath = '';
	protected $colorReferenceId = 0;
	static private $_cacheTime = 604800;
    static private $_cacheDir = '/yenisite/core/colorguess/';
	protected $colorReference = array();
	/** @var resource */
	protected $image = null;
	/** @var resource */
	protected $maskImage = false;
	/** @var integer */
	protected $maskColor = false;
	/** @var ColorGuess */
	protected static $_instance = NULL;
	/** @var integer */
	public $threshold = 48;

	public function __construct($colorReferenceId)
	{
		$this->colorReferenceId = intval($colorReferenceId);
	}

	public static function getInstance()
	{
		if (isset(self::$_instance))
			return self::$_instance;

		$arSettings = \COption::GetOptionString('yenisite.core', 'color_setts', 'a:0:{}');
		$arSettings = unserialize($arSettings);
		if (empty($arSettings) || intval($arSettings['reference']) < 1) {
			return NULL;
		}
		self::$_instance = new ColorGuess($arSettings['reference']);
		unset($arSettings['reference']);

		foreach ($arSettings as $iblockId => $propertyId) {
			self::$_instance->handleIblock($iblockId, $propertyId);
		}
		return self::$_instance;
	}

	public static function staticHandler($SITE_ID, &$arFields)
	{
		if (\COption::GetOptionString('yenisite.core', 'color_guess_'.$SITE_ID, 'N') !== 'Y') return;

		if (empty($arFields['IBLOCK_ID'])) return;

        $obCache = new \CPHPCache();
        $cache_id = 'SEARCH_SITE_'.$SITE_ID.$arFields['IBLOCK_ID'];

        if($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir))
        {
            $bDoSerach = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache())
        {

            $bDoSerach = false;
            $rsSites = \CIBlock::GetSite($arFields['IBLOCK_ID']);
            while ($arSite = $rsSites->Fetch()) {
                if ($SITE_ID == $arSite['SITE_ID']) {
                    $bDoSerach = true;
                }
            }

            $obCache->EndDataCache($bDoSerach);
        }
        unset($obCache);

        if (!$bDoSerach) return;

		//if (!\Bitrix\Main\ModuleManager::isModuleInstalled('yenisite.bitronic2pro')) return;

		$obGuess = self::getInstance();
		if (!$obGuess) return;

		$obGuess->eventHandler($arFields);
	}

	/**
	 * Функция добавляет инфоблок в "обработку".
	 *
	 * @param integer $iblockId Идентификатор инфоблока.
	 * @param integer $propertyId Идентификатор свойства для сохранения значения найденного цвета.
	 *
	 * @return void
	 **/
	public function handleIblock($iblockId, $propertyId)
	{
		// if (empty($this->iblocks))
		// {
		// 	AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array($this, "eventHandler"));
		// 	AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array($this, "eventHandler"));
		// }
		$this->iblocks[$iblockId] = $propertyId;
	}

	/**
	 * Вызывается в самом начале обработки события.
	 * Должна удостовериться, что вызов соответствует инфоблокам обрабатываемым
	 * этим экземпляром.
	 *
	 * @param mixed $arFields поля передаваемые в обработчик.
	 *
	 * @return boolean
	 * @see http://dev.1c-bitrix.ru/api_help/iblock/events/onbeforeiblockelementadd.php
	 * @see http://dev.1c-bitrix.ru/api_help/iblock/events/onbeforeiblockelementupdate.php
	 **/
	public function checkFields($arFields)
	{
		if (!isset($this->iblocks[$arFields["IBLOCK_ID"]]))
			return false;
		else
			return true;
	}

	/**
	 * Функция инициализирует справочник референсных цветов.
	 * В этом примере используется справочник цветов из демонстрациооной версии магазина.
	 * Можно использовать: https://ru.wikipedia.org/wiki/%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_%D1%86%D0%B2%D0%B5%D1%82%D0%BE%D0%B2
	 * Главное чтобы член класса colorReference был заполнен.
	 * Ключи этого массива - уникальные идентификаторы которые будут использованы для заполненения значений свойства.
	 * Значения - массивы из трех элементов задающие цвет array("R" => ...,"G" => ..., "B" => ...);
	 * В случае успеха возвращает true.
	 *
	 * @param boolean $bFill - should we calculate and fill RGB-values whenever empty
	 * @return boolean
	 * @see http://dev.1c-bitrix.ru/api_help/iblock/fields.php#felement
	 **/
	public function initColorReference($bFill = false)
	{
		if (!\Bitrix\Main\Loader::IncludeModule('highloadblock'))
			return false;

		$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
			"filter" => array(
				"=ID" => $this->colorReferenceId,
			)))->fetch();
		if (!$hlblock) {
			return false;
		}
		$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		$rsData = $entity_data_class::getList(array(
			"select" => array("ID", "UF_NAME", "UF_XML_ID", "UF_FILE", "UF_RGB"),
		));
		$this->colorReference = array();

		while($arData = $rsData->fetch()) {
			if ($bFill && $arData["UF_FILE"] && (!isset($arData["UF_RGB"]) || $arData['UF_RGB'] === 'NULL')) {
				$arFile = \CFile::MakeFileArray($arData["UF_FILE"]);
				if (
					$arFile
					&& \CFile::ResizeImage($arFile, array("width"=>40,"height"=>40))
					&& ($image = \CFile::CreateImage($arFile["tmp_name"]))
				)
				{
					$color = $this->getPictureColor($image);
					foreach ($color as $key => $value) {
						$color[$key] = round($value);
					}
					$arData["UF_RGB"] = $color["R"].','.$color["G"].','.$color["B"];
					$entity_data_class::update($arData["ID"], array(
						"UF_RGB" => $arData["UF_RGB"]
					));
				}
			}

			if (empty($arData["UF_RGB"]) || $arData['UF_RGB'] === 'NULL')
				continue;

			list($r, $g, $b) = explode(',', $arData['UF_RGB'], 3);
			$this->colorReference[$arData["UF_XML_ID"]] = array(
				"R" => intval($r),
				"G" => intval($g),
				"B" => intval($b),
			);
		}

		return true;
	}

	/**
	 * Функция инициализирует переменные необходимые для загрузки изображения
	 * из нужных полей инфоблока.
	 * В случае не успеха (поле не заполнено) возвращает false.
	 *
	 * @param mixed $arFields поля передаваемые в обработчик.
	 *
	 * @return boolean
	 * @see http://dev.1c-bitrix.ru/api_help/iblock/fields.php#felement
	 **/
	public function initFile($arFields)
	{
		if (!$arFields["PREVIEW_PICTURE"]["tmp_name"] && !$arFields["DETAIL_PICTURE"]["tmp_name"])
			return false;

		$arFile = \CFile::MakeFileArray($arFields["PREVIEW_PICTURE"]["tmp_name"]);
		if (!$arFile)
			$arFile = \CFile::MakeFileArray($arFields["DETAIL_PICTURE"]["tmp_name"]);
		if (!$arFile)
			return false;

		if (!\CFile::CheckImageFile($arFile["tmp_name"]))
			return false;

		$this->imageFilePath = $arFile["tmp_name"];
		return true;
	}

	public function initExistFile(&$arFields)
	{
		$img = Resize::getImgSrcFromElement($arFields, array('PREVIEW_PICTURE', 'DETAIL_PICTURE'));
		if (empty($img))
			return false;

		$imgSrc = Resize::getImgSrc($img, Resize::getTypeOfImgSrc($img));
		if (empty($imgSrc))
			return false;

		$this->imageFilePath = $_SERVER['DOCUMENT_ROOT'] . $imgSrc;
		return true;
	}

	/**
	 * Функция загружает изображение из файла и преобразует его в true color, если это необходимо.
	 *
	 * @return boolean
	 **/
	public function loadFile()
	{
		$this->image = \CFile::CreateImage($this->imageFilePath);
		if (!$this->image)
			return false;

		if (!imageistruecolor($this->image))
		{
			$sx = imagesx($this->image);
			$sy = imagesy($this->image);
			$tmp = imagecreatetruecolor($sx, $sy);
			if ($tmp)
			{
				imagecopy($tmp, $this->image, 0, 0, 0, 0, $sx, $sy);
				imagedestroy($this->image);
			}
			$this->image = $tmp;
		}

		return is_resource($this->image);
	}

	/**
	 * Функция задаёт маску которая исключит из анализа ненужный обрамляющий фон.
	 *
	 * @return boolean
	 * @see http://developers.lyst.com/data/images/2014/02/13/background-removal/
	 **/
	public function createMask()
	{
		if (!$this->image)
			return false;

		$sx = imagesx($this->image);
		$sy = imagesy($this->image);
		$this->maskImage = imagecreatetruecolor($sx, $sy);
		$this->maskColor = imagecolorallocate($this->maskImage, 255, 0, 255);
		imagealphablending($this->maskImage, false);
		imagecopy($this->maskImage, $this->image, 0, 0, 0, 0, $sx, $sy);

		//Негатив
		$this->imageNegate($this->maskImage);
		//Выделение края
		$this->imageSobel($this->maskImage);
		//Размытие
		$this->imageBlur($this->maskImage);
		//Фильтрация по порогу
		$this->imageThresholdToWhite($this->maskImage, $this->threshold);
		//Заливка с углов магентой.
		$this->imageFillCorners($this->maskImage, $this->maskColor);

		return is_resource($this->maskImage);
	}

	/**
	 * Функция определяет цвет товара на картинке.
	 *
	 * @return mixed
	 */
	public function guessColor($countIndex = 1)
	{
		$histogram = array();
		foreach ($this->colorReference as $key => $arData)
		{
			$c1 = $this->rgb2xyz($arData);
			$c1 = $this->xyz2lab($c1);
			$c1["C"] = 0; //счётчик близких цветов.
			$histogram[$key] = $c1;
		}

		$cache = array();
		$width = imagesx($this->image);
		$height = imagesy($this->image);
		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				$a = imagecolorat($this->maskImage, $x, $y);
				if ($a == $this->maskColor)
					continue;

				$rgb = imagecolorat($this->image, $x, $y);

				$rgb = $rgb & 0xFFFFFF;
				if (!isset($cache[$rgb]))
				{
					$imageColor = array(
						"R" => (($rgb >> 16) & 0xFF),
						"G" => (($rgb >> 8) & 0xFF),
						"B" => ($rgb & 0xFF),
					);
					$imageColor = $this->rgb2xyz($imageColor);
					$imageColor = $this->xyz2lab($imageColor);
					$distances = array();
					foreach ($histogram as $key => $referenceColor)
					{
						$dL = $imageColor["L"] - $referenceColor["L"];
						$dA = $imageColor["a"] - $referenceColor["a"];
						$dB = $imageColor["b"] - $referenceColor["b"];

						$distances[$key] = $dL*$dL + $dA*$dA + $dB*$dB;
					}
					asort($distances);
					reset($distances);
					$cache[$rgb] = key($distances);
				}

				$key = $cache[$rgb];
				$histogram[$key]["C"]++;
			}
		}

		\Bitrix\Main\Type\Collection::sortByColumn($histogram, array("C" => SORT_DESC), '', null, true);

		$i = 0;
		reset($histogram);
		do {
			$key = key($histogram);
			if  (!next($histogram)) break;
		} while ($countIndex > ++$i);

		return $key;
	}

	/**
	 * Функция применяет найденный ответ к полям обработчика.
	 *
	 * @param mixed &$arFields
	 * @param mixed $colorId
	 *
	 * @return void
	 */
	public function modifyResult(&$arFields, $colorId)
	{
		$iblockId = $arFields["IBLOCK_ID"];
		$propertyId = $this->iblocks[$iblockId];
		$arFields["PROPERTY_VALUES"][$propertyId] = array(
			"n0" => array(
				"VALUE" => $colorId,
			)
		);
	}

	protected function prepareToGuess(&$arFields, $bUseExist = false)
	{
		if (!$this->checkFields($arFields))
			return false;

		if (!$this->colorReference)
		{
			if (!$this->initColorReference())
				return false;
		}

		if ($bUseExist) {
			if (!$this->initExistFile($arFields))
				return false;
		} else {
			if (!$this->initFile($arFields))
				return false;
		}

		if (!$this->loadFile())
			return false;

		if (!$this->createMask())
			return false;

		return true;
	}

	/**
	 * Обработчик событий инфоблока.
	 *
	 * @param mixed &$arFields Поля элемента.
	 *
	 * @return void
	 */
	public function eventHandler(&$arFields)
	{
		if (!$this->prepareToGuess($arFields))
			return;

		$luckyGuess = $this->guessColor();

		$this->modifyResult($arFields, $luckyGuess);
	}

	public function guessColorForElement(&$arFields, $countIndex)
	{
		if (!\Bitrix\Main\Loader::IncludeModule('iblock'))
			return false;
		if (!$this->prepareToGuess($arFields, true))
			return false;

		$luckyGuess = $this->guessColor($countIndex);
		$propertyId = $this->iblocks[$arFields['IBLOCK_ID']];

		\CIBlockElement::SetPropertyValuesEx(
			$arFields['ID'],
			$arFields['IBLOCK_ID'],
			array($propertyId => $luckyGuess)
		);
		return true;
	}

	/**
	 * Функция фозвращает усреднённый цвет картинки (RGB).
	 * Если задана маска, то пикселы маски с заданным цветом исключаются.
	 *
	 * @param resource $image Изображение.
	 * @param null|resource $alpha Маска.
	 * @param integer $magenta цвет маски.
	 *
	 * @return array
	 */
	protected function getPictureColor($image, $alpha = null, $magenta = 0)
	{
		$result = array(
			"R" => 0,
			"G" => 0,
			"B" => 0,
		);
		$width = imagesx($image);
		$height = imagesy($image);
		$c = 0;
		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				$rgb = imagecolorat($image, $x, $y);
				if ($alpha)
				{
					$a = imagecolorat($alpha, $x, $y);
					if ($a == $magenta)
						continue;
				}
				$result["R"] += (($rgb >> 16) & 0xFF);
				$result["G"] += (($rgb >> 8) & 0xFF);
				$result["B"] += ($rgb & 0xFF);
				$c++;
			}
		}

		if ($c)
		{
			foreach ($result as $i => $s)
			{
				$result[$i] = $s/$c;
			}
		}

		return $result;
	}

	/**
	 * Функция преобразования цветовых координат из rgb в xyz
	 *
	 * @param array $color Массив описывающий цвет в виде array("R" => ..., "G" => ..., "B" => ...).
	 *
	 * @return array
	 * @see http://www.easyrgb.com/?X=MATH
	 **/
	public static function rgb2xyz($color)
	{
		$var_R = ( $color["R"] / 255 );        //R from 0 to 255
		$var_G = ( $color["G"] / 255 );        //G from 0 to 255
		$var_B = ( $color["B"] / 255 );        //B from 0 to 255

		if ($var_R > 0.04045)
			$var_R = pow(($var_R + 0.055) / 1.055, 2.4);
		else
			$var_R = $var_R / 12.92;

		if ($var_G > 0.04045)
			$var_G = pow(($var_G + 0.055) / 1.055, 2.4);
		else
			$var_G = $var_G / 12.92;

		if ($var_B > 0.04045)
			$var_B = pow(($var_B + 0.055) / 1.055, 2.4);
		else
			$var_B = $var_B / 12.92;

		$var_R = $var_R * 100;
		$var_G = $var_G * 100;
		$var_B = $var_B * 100;

		//Observer. = 2°, Illuminant = D65
		$result = array(
			"X" => $var_R * 0.4124 + $var_G * 0.3576 + $var_B * 0.1805,
			"Y" => $var_R * 0.2126 + $var_G * 0.7152 + $var_B * 0.0722,
			"Z" => $var_R * 0.0193 + $var_G * 0.1192 + $var_B * 0.9505,
		);
		return $result;
	}

	/**
	 * Функция преобразования цветовых координат из xyz в L*a*b*
	 *
	 * @param array $color Массив описывающий цвет в виде array("X" => ..., "Y" => ..., "Z" => ...).
	 *
	 * @return array
	 * @see http://www.easyrgb.com/?X=MATH
	 * @see http://en.wikipedia.org/wiki/Lab_color_space
	 **/
	public static function xyz2lab($color)
	{
		//Observer= 2°, Illuminant= D65
		$var_X = $color["X"] / 95.047;
		$var_Y = $color["Y"] / 100.000;
		$var_Z = $color["Z"] / 108.883;

		if ($var_X > 0.008856)
			$var_X = pow($var_X, 1/3);
		else
			$var_X = (7.787 * $var_X) + (16 / 116);

		if ($var_Y > 0.008856)
			$var_Y = pow($var_Y, 1/3);
		else
			$var_Y = (7.787 * $var_Y) + (16 / 116);

		if ($var_Z > 0.008856)
			$var_Z = pow($var_Z, 1/3);
		else
			$var_Z = (7.787 * $var_Z) + (16 / 116);

		$result = array(
			"L" => ( 116 * $var_Y ) - 16,
			"a" => 500 * ($var_X - $var_Y),
			"b" => 200 * ($var_Y - $var_Z),
		);
		return $result;
	}

	/**
	 * Делает негативное изображение.
	 *
	 * @param resource $picture Изображение над которым проводится манипуляция.
	 *
	 * @return void
	 **/
	public static function imageNegate($picture)
	{
		imagefilter($picture, IMG_FILTER_NEGATE);
	}

	/**
	 * Выделение края.
	 *
	 * @param resource $picture Изображение над которым проводится манипуляция.
	 *
	 * @return void
	 * @see http://www.emanueleferonato.com/2010/10/19/image-edge-detection-algorithm-php-version/
	 **/
	function imageSobel($picture)
	{
		$sx = imagesx($picture);
		$sy = imagesy($picture);
		$backup = imagecreatetruecolor($sx, $sy);
		imagealphablending($backup, false);
		imagecopy($backup, $picture, 0, 0, 0, 0, $sx, $sy);

		$matrix1 = array(
			array(-1, -2 ,-1),
			array(0 ,0,0),
			array(1,2,1),
		);
		$matrix2 = array(
			array(-1, 0 ,1),
			array(-2 ,0,2),
			array(-1,0,1),
		);
		for($y = 0; $y < $sy; ++$y)
		{
			for($x = 0; $x < $sx; ++$x)
			{
				$alpha = (imagecolorat($backup, $x, $y) >> 24) & 0xFF;
				$new1_r = $new1_g = $new1_b = 0;
				$new2_r = $new2_g = $new2_b = 0;

				for ($j = 0; $j < 3; ++$j)
				{
					$yv = $y - 1 + $j;
					if($yv < 0)
						$yv = 0;
					elseif($yv >= $sy)
						$yv = $sy - 1;

					for ($i = 0; $i < 3; ++$i)
					{
						$xv = $x - 1 + $i;
						if($xv < 0)
							$xv = 0;
						elseif($xv >= $sx)
							$xv = $sx - 1;

						$rgb = imagecolorat($backup, $xv, $yv);

						$m1 = $matrix1[$j][$i];
						$new1_r += (($rgb >> 16) & 0xFF) * $m1;
						$new1_g += (($rgb >> 8) & 0xFF) * $m1;
						$new1_b += ($rgb & 0xFF) * $m1;

						$m2 = $matrix2[$j][$i];
						$new2_r += (($rgb >> 16) & 0xFF) * $m2;
						$new2_g += (($rgb >> 8) & 0xFF) * $m2;
						$new2_b += ($rgb & 0xFF) * $m2;
					}
				}

				$lum1 = $new1_r*0.30+$new1_g*0.59+$new1_b*0.11;
				$lum2 = $new2_r*0.30+$new2_g*0.59+$new2_b*0.11;
				$gray = sqrt($lum1*$lum1+$lum2*$lum2);
				$new_pxl = imagecolorallocatealpha($picture, $gray, $gray, $gray, $alpha);
				imagesetpixel($picture, $x, $y, $new_pxl);
			}
		}
		imagedestroy($backup);
	}

	/**
	 * Размытие изображения.
	 *
	 * @param resource $picture Изображение над которым проводится манипуляция.
	 *
	 * @return void
	 **/
	public static function imageBlur($picture)
	{
		imagefilter($picture, IMG_FILTER_GAUSSIAN_BLUR);
	}

	/**
	 * Делает Ч/Б изображение основываясь на пороговом значении.
	 *
	 * @param resource $picture Изображение над которым проводится манипуляция.
	 * @param integer $threshold Пороговое значение делающее пиксел белым.
	 *
	 * @return void
	 **/
	public static function imageThresholdToWhite($picture, $threshold)
	{
		$sx = imagesx($picture);
		$sy = imagesy($picture);
		$white_pxl = imagecolorallocate($picture, 255, 255, 255);
		$black_pxl = imagecolorallocate($picture, 0, 0, 0);
		for($y = 0; $y < $sy; ++$y)
		{
			for($x = 0; $x < $sx; ++$x)
			{
				$rgb = imagecolorat($picture, $x, $y);
				$r = (($rgb >> 16) & 0xFF);
				$g = (($rgb >> 8) & 0xFF);
				$b = ($rgb & 0xFF);
				if ($r > $threshold && $g > $threshold && $b > $threshold)
					imagesetpixel($picture, $x, $y, $white_pxl);
				else
					imagesetpixel($picture, $x, $y, $black_pxl);
			}
		}
	}

	/**
	 * Заливка изображения заданным цветом с четырёх углов.
	 *
	 * @param resource $picture Изображение над которым проводится манипуляция.
	 * @param integer $color Цвет заливки.
	 *
	 * @return void
	 **/
	public static function imageFillCorners($picture, $color)
	{
		$sx = imagesx($picture);
		$sy = imagesy($picture);
		imagefill($picture, 0, 0, $color);
		imagefill($picture, $sx-1, 0, $color);
		imagefill($picture, 0, $sy-1, $color);
		imagefill($picture, $sx-1, $sy-1, $color);
	}
}