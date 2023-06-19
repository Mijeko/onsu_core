<?php
namespace Yenisite\Core;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class Resize
 * Universal class for resizing images using either yenisite.resizer2 or standard library Bitrix\CFile\Resize
 * @package Yenisite\Core
 */
class Resize
{
	private static $hasResizer = null;

	/**
	 * Get image value from property
	 * @param mixed $input
	 * @return bool|mixed
	 */
	private static function __getPropertySrc($input)
	{
		$return = false;
		if (is_array($input)) {
			if (isset($input['VALUE'])) {
				if (is_array($input['VALUE'])) {
					$return = reset($input['VALUE']);
				} else {
					$return = $input;
				}
			} elseif (isset($input['VALUES'])) {
				if (is_array($input['VALUES'])) {
					$return = reset($input['VALUES']);
				} else {
					$return = $input;
				}
			}
		} else {
			$return = $input;
		}
		return $return;
	}

	/**
	 * Get image value from field
	 * @param mixed $input
	 * @return bool|mixed
	 */
	private static function __getFieldSrc($input)
	{
		$return = false;
		if (is_array($input)) {
			if (self::$hasResizer && isset($input['SRC'])) {
				$src = trim($input['SRC']);
				if (strlen($src) > 0) {
					$return = $input['SRC'];
				}
			} elseif (isset($input['ID'])) {
				if (intval($input['ID']) > 0) {
					$return = $input['ID'];
				}
			}
		} else {
			$return = $input;
		}
		return $return;
	}

	public static function getPriporetyImg(){
        static $arPriorityDef;
        if (!isset($arPriorityDef)) {
            $arPriorityDef = \COption::GetOptionString('yenisite.core', 'image_priority', '');
            if (!empty($arPriorityDef)) {
                $arPriorityDef = json_decode($arPriorityDef);
            }
            if (empty($arPriorityDef)) {
                $arPriorityDef = array(
                    0 => "DETAIL_PICTURE",
                    1 => "PREVIEW_PICTURE",
                    2 => "MORE_PHOTO",
                );
            }
            foreach ($arPriorityDef as $key => $value) {
                if ($value == '0') {
                    unset($arPriorityDef[$key]);
                }
            }
        }

        if (!empty($arPriority)) {
            $searchPriority = $arPriority;
        }
        else {
            $searchPriority = $arPriorityDef;
        }

        return $searchPriority;
    }

    public static function getImgDescritption($arGallery,$arItem){
	    $altPreview = $arItem['PREVIEW_PICTURE']['DESCRIPTION'];
	    $altDetail = $arItem['DETAIL_PICTURE']['DESCRIPTION'];
        if (empty($altDetail) && empty($altPreview)) return $arGallery;

        $bHasDetail = !empty($arItem['DETAIL_PICTURE']['SRC']);
        $bHasPreview = false;

	    $arPriorety = self::getPriporetyImg();
	    $arPriorety = array_flip($arPriorety);
	    $cntGallery = count($arGallery) - 1;

	    $key = $arPriorety['MORE_PHOTO'];

	    switch ($key){
            case 0:
               if ($arPriorety['PREVIEW_PICTURE'] < $arPriorety['DETAIL_PICTURE']){
                   if ($bHasDetail && $bHasPreview){
                       $arGallery[$cntGallery - 1]['NAME'] = $altPreview;
                       $arGallery[$cntGallery]['NAME'] = $altDetail;
                   } elseif ($bHasPreview){
                       $arGallery[$cntGallery]['NAME'] = $altPreview;
                   } elseif($bHasDetail){
                       $arGallery[$cntGallery]['NAME'] = $altDetail;
                   }
                } else{
                   if ($bHasDetail && $bHasPreview){
                       $arGallery[$cntGallery - 1]['NAME'] = $altDetail;
                       $arGallery[$cntGallery]['NAME'] = $altPreview;
                   } elseif ($bHasPreview){
                       $arGallery[$cntGallery]['NAME'] = $altPreview;
                   } elseif($bHasDetail){
                       $arGallery[$cntGallery]['NAME'] = $altDetail;
                   }
               }
            break;

            case 1:
                if ($arPriorety['PREVIEW_PICTURE'] < $arPriorety['DETAIL_PICTURE']){
                    if ($bHasDetail && $bHasPreview){
                        $arGallery[0]['NAME'] = $altPreview;
                        $arGallery[$cntGallery]['NAME'] = $altDetail;
                    } elseif ($bHasPreview){
                        $arGallery[0]['NAME'] = $altPreview;
                    } elseif($bHasDetail){
                        $arGallery[$cntGallery]['NAME'] = $altDetail;
                    }
                } else{
                    if ($bHasDetail && $bHasPreview){
                        $arGallery[0]['ALT'] = $altDetail;
                        $arGallery[$cntGallery]['NAME'] = $altPreview;
                    } elseif ($bHasPreview){
                        $arGallery[$cntGallery]['NAME'] = $altPreview;
                    } elseif($bHasDetail){
                        $arGallery[0]['NAME'] = $altDetail;
                    }
                }
            break;

            case 2:
                if ($arPriorety['PREVIEW_PICTURE'] < $arPriorety['DETAIL_PICTURE']){
                    if ($bHasDetail && $bHasPreview){
                        $arGallery[0]['NAME'] = $altPreview;
                        $arGallery[1]['NAME'] = $altDetail;
                    } elseif ($bHasPreview){
                        $arGallery[0]['NAME'] = $altPreview;
                    } elseif($bHasDetail){
                        $arGallery[0]['NAME'] = $altDetail;
                    }
                } else{
                    if ($bHasDetail && $bHasPreview){
                        $arGallery[0]['NAME'] = $altDetail;
                        $arGallery[1]['NAME'] = $altPreview;
                    } elseif ($bHasPreview){
                        $arGallery[0]['NAME'] = $altPreview;
                    } elseif($bHasDetail){
                        $arGallery[0]['NAME'] = $altDetail;
                    }
                }
            break;
        }

        return $arGallery;

    }
	/**
	 * Search picture value in array
	 * @param array $array
	 * @param array $arPriority
	 * @return array|bool|mixed
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function getImgSrcFromElement($array, $arPriority = array())
	{
		$bFound = false;
		$foundVal = false;
		static $arPriorityDef;
		if (!isset($arPriorityDef)) {
			$arPriorityDef = \COption::GetOptionString('yenisite.core', 'image_priority', '');
			if (!empty($arPriorityDef)) {
				$arPriorityDef = json_decode($arPriorityDef);
			}
			if (empty($arPriorityDef)) {
				$arPriorityDef = array(
					0 => "DETAIL_PICTURE",
					1 => "PREVIEW_PICTURE",
					2 => "MORE_PHOTO",
				);
			}
			foreach ($arPriorityDef as $key => $value) {
				if ($value == '0') {
					unset($arPriorityDef[$key]);
				}
			}
		}

		if (!empty($arPriority)) {
			$searchPriority = $arPriority;
		}
		else {
			$searchPriority = $arPriorityDef;
		}
		if (!$bFound) {
			foreach ($searchPriority as &$field) {
				if ($field == "MORE_PHOTO") {
					if (isset($array['PROPERTIES'][$field])) {
						if (empty($array['PROPERTIES'][$field]['VALUE'])) continue;
						$foundVal = self::__getPropertySrc($array['PROPERTIES'][$field]);
						if (!empty($foundVal)) {
							$bFound = true;
							break;
						}
					} else {
						$itemId = isset($array['PRODUCT_ID']) ? $array['PRODUCT_ID'] : $array['ID'];
						if ($itemId > 0 && Loader::includeModule('iblock')) {
							$morePhotoVal = 0;
							$obCache = new \CPHPCache();
							if ($obCache->InitCache(36000, $itemId, "romza/ResizerToolMorePhoto")) {
								$morePhotoVal = $obCache->GetVars();
							} elseif ($obCache->StartDataCache()) {
								/** @noinspection PhpDynamicAsStaticMethodCallInspection */
								$rsElem = \CIBlockElement::GetList(array(),
									array('ID' => $itemId),
									false,
									false,
									array('ID', 'PROPERTY_MORE_PHOTO')
								);
								$arElem = $rsElem->GetNext();
								if (!empty($arElem['PROPERTY_MORE_PHOTO_VALUE'])) {
									$morePhotoVal = $arElem['PROPERTY_MORE_PHOTO_VALUE'];
								}
								$obCache->EndDataCache($morePhotoVal);
							}
							if (!empty($morePhotoVal)) {
								$bFound = true;
								$foundVal = $morePhotoVal;
								break;
							}
						}
					}
				} else {
					if (!empty($array[$field])) {
						$bFound = true;
						$foundVal = self::__getFieldSrc($array[$field]);
						break;
					}
				}
			}
			unset($field);
		}
		if (!$bFound && isset($array['OFFERS']) && count($array['OFFERS']) > 0) {
			$firstOffer = reset($array['OFFERS']);
			foreach ($searchPriority as &$field) {
				if ($field == "MORE_PHOTO") {
					if (isset($firstOffer['PROPERTIES'][$field])) {
						$foundVal = self::__getPropertySrc($firstOffer['PROPERTIES'][$field]['VALUE']);
						if (!empty($foundVal)) {
							break;
						}
					}
				} else {
					if (isset($firstOffer[$field])) {
						$foundVal = self::__getFieldSrc($firstOffer[$field]);
						break;
					}
				}
			}
			unset($field);
		}
		return $foundVal;
	}

	/**
	 * Get image SRC
	 * @param mixed $input
	 * @param string $inputType
	 * @return mixed|bool
	 */
	public static function getImgSrc($input, $inputType)
	{
		static $arCache;
		$inputID = md5(serialize($input));
		if (isset($arCache[$inputID])) {
			return $arCache[$inputID];
		}

		$result = false;
		if ($inputType == 'id') {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$result = \CFile::GetPath($input);
		} elseif ($inputType == 'array' && isset($input['SRC'])) {
			$result = $input['SRC'];
		} elseif ($inputType == 'array' && isset($input['FILE_NAME'])) {
			$result = $input['FILE_NAME'];
		}
		$arCache[$inputID] = $result;
		return $result;
	}

	/**
	 * Get type of $input variable for image
	 * @param mixed $input
	 * @return bool|string
	 */
	public static function getTypeOfImgSrc($input)
	{
		if (!isset($input)) {
			return false;
		}
		$inputType = "array";
		if (!is_array($input)) {
			if (is_numeric($input)) {
				$inputType = "id";
			} elseif (is_string($input)) {
				$inputType = "src";
			} else {
				$inputType = false;
			}
		}
		if ($inputType == 'src' && strlen(trim($input)) <= 0) {
			$inputType = false;
		}
		return $inputType;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * resizer method
	 * @param string $imgSrc
	 * @param array $arParams
	 * @return string
	 */
	private static function __resizeByResizer2($imgSrc, $arParams)
	{
		// strip DOCUMENT_ROOT
		$imgSrc = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imgSrc);
		if (intval($arParams['SET_ID'] > 0)) {
			/** @noinspection PhpUndefinedClassInspection */
			return \CResizer2Resize::ResizeGD2($imgSrc, $arParams['SET_ID']);
		} else {
			/** @noinspection PhpUndefinedClassInspection */
			return \CResizer2Resize::ResizeGD2($imgSrc, 0, $arParams['WIDTH'], $arParams['HEIGHT'],$arParams['STATIC'],$arParams['QUALITY']);
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * Shortcut for \CFile::ResizeImageGet
	 * @param mixed $imgSrc
	 * @param array $arParams
	 * @return string
	 */
	private static function __resizeByCfile($imgSrc, $arParams)
	{
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$arSrc = \CFile::ResizeImageGet($imgSrc,
			array(
				'width' => $arParams['WIDTH'],
				'height' => $arParams['HEIGHT'],
			),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			false
		);
		return $arSrc['src'];
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * Shortcut for \CFile::ResizeImageFile
	 * @param mixed $imgSrc
	 * @param array $arParams
	 * @return string
	 */
	private static function __resizeByCfileSRC($imgSrc, $arParams)
	{
		$dest = $_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/rz_' . $arParams['WIDTH'] . '_' . $arParams['HEIGHT'] . '_' . basename($imgSrc);
		if (strpos($imgSrc, $_SERVER['DOCUMENT_ROOT']) === false) {
			$io = \CBXVirtualIo::GetInstance();
			$imgSrc = $io->RelativeToAbsolutePath($imgSrc);
		}
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$res = \CFile::ResizeImageFile($imgSrc,
			$dest,
			array(
				'width' => $arParams['WIDTH'],
				'height' => $arParams['HEIGHT'],
			),
			BX_RESIZE_IMAGE_PROPORTIONAL
		);

		$return = false;
		if ($res) {
			$return = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dest);
		}
		return $return;
	}

	/**
	 * Main function for get resized image
	 * @param mixed $input
	 * @param bool|array $resizerOpt
	 * @param string $noPhotoSrc
	 * @return string
	 */
	public static function GetResizedImg($input, $resizerOpt = false, $noPhotoSrc = '')
	{
		static $arCache;

		$resizerOptDefault = array(
			"SET_ID" => false,
			"WIDTH" => 400,
			"HEIGHT" => 600,
            'STATIC' => false,
            "QUALITY" => 100
		);

		if (!is_array($resizerOpt) || empty($resizerOpt)) {
			$resizerOpt = $resizerOptDefault;
		} else {
			$resizerOpt = array_merge($resizerOptDefault, $resizerOpt);
		}

		$inputID = md5(serialize($input));
		$optID = md5(serialize($resizerOpt));
		if (isset($arCache[$inputID][$optID]['RESIZE'])) {
			return $arCache[$inputID][$optID]['RESIZE'];
		}
		$resultSrc = "";

		$methodName = '__resizeByCfile';

		if (!isset(self::$hasResizer)) {
			self::$hasResizer = Loader::includeModule('yenisite.resizer2');
		}

		$hasResizer = self::$hasResizer;

		if ($hasResizer) {
			$methodName = '__resizeByResizer2';
			$noPhotoSrc = empty($noPhotoSrc) ? self::$methodName(false, $resizerOpt) : $noPhotoSrc;
		}
		$inputType = self::getTypeOfImgSrc($input);

		if (!$inputType) {
			return $noPhotoSrc;
		} else {
			if ($inputType == 'array') {
				if (!isset($arCache[$inputID][$optID]['SRC'])) {
					$imgSrc = self::getImgSrcFromElement($input);
					$arCache[$inputID][$optID]['SRC'] = $imgSrc;
				} else {
					$imgSrc = $arCache[$inputID][$optID]['SRC'];
				}
				if ($imgSrc === false) {
					return $noPhotoSrc;
				}
				$imgType = self::getTypeOfImgSrc($imgSrc);
				if ($hasResizer && $imgType != 'src') {
					$imgSrc = self::getImgSrc($imgSrc, $imgType);
				} elseif (!$hasResizer && $imgType == 'src') {
					$methodName = '__resizeByCfileSRC';
				} elseif (!$hasResizer && $imgType == 'array' && isset($imgSrc['ID'])) {
					$imgSrc = $imgSrc['ID'];
					if (intval($imgSrc) == 0) {
						return $noPhotoSrc;
					}
				}
				$resultSrc = self::$methodName($imgSrc, $resizerOpt);
			} elseif ($inputType == 'id') {
				$imgSrc = $input;
				if ($hasResizer) {
					$imgSrc = self::getImgSrc($imgSrc, 'id');
				}
				$resultSrc = self::$methodName($imgSrc, $resizerOpt);
			} elseif ($inputType == 'src') {
				$imgSrc = $input;
				if (!$hasResizer) {
					$methodName = '__resizeByCfileSRC';
				}
				$resultSrc = self::$methodName($imgSrc, $resizerOpt);
			}
		}
		$arCache[$inputID][$optID]['RESIZE'] = $resultSrc;
		return $resultSrc;
	}

	public static function getDescriptionOfImg($id){
        $arFile = \CFile::GetFileArray($id);
        $description = $arFile['DESCRIPTION'] ? : '';
        return $description;
    }

	public static function getGallery($arItem, $resizeOpt, $returnNoPhoto = true)
	{
		$arResult = array();

		$arFirst = &$arResult[];
		$arFirst = array(
				'THUMB' => self::GetResizedImg($arItem, $resizeOpt['THUMB']),
				'SRC' => self::GetResizedImg($arItem, $resizeOpt['SRC']),
		);
		$bEmptyGallery = ($arFirst['THUMB'] == self::GetResizedImg(null, $resizeOpt['THUMB']));

		if(!$returnNoPhoto && $bEmptyGallery) return array();

		if (isset($resizeOpt['BIG'])) {
			$arFirst['BIG'] = self::GetResizedImg($arItem, $resizeOpt['BIG']);
		}
        if (isset($resizeOpt['HOR'])) {
            $arFirst['HOR']  = self::GetResizedImg($arItem, $resizeOpt['HOR']);
        }
        if (isset($resizeOpt['VER'])) {
            $arFirst['VER'] = self::GetResizedImg($arItem, $resizeOpt['VER']);
        }

		if ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']) {
			foreach ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE'] as $key => &$arPhoto) {
				$thumb = self::GetResizedImg($arPhoto, $resizeOpt['THUMB']);

				$bNewImg = $arFirst['THUMB'] !== $thumb; // if this img already in array
				if($bNewImg) {
					$arNew = &$arResult[];
					$arNew['THUMB'] = $thumb;
					$arNew['SRC'] = self::GetResizedImg($arPhoto, $resizeOpt['SRC']);
                    if (isset($resizeOpt['HOR'])) {
                        $arNew['HOR']  = self::GetResizedImg($arPhoto, $resizeOpt['HOR']);
                    }
                    if (isset($resizeOpt['VER'])) {
                        $arNew['VER'] = self::GetResizedImg($arPhoto, $resizeOpt['VER']);
                    }
					if (isset($resizeOpt['BIG'])) {
						$arNew['BIG'] = self::GetResizedImg($arPhoto, $resizeOpt['BIG']);
					}
				}
				else
				{
					$arNew = &$arResult[0];
				}
				if (!empty($arItem['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][$key])) {
					$arNew['NAME'] = $arItem['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][$key];
				}
				if (empty($arNew['NAME'])){
                    $arNew['NAME'] = self::getDescriptionOfImg($arPhoto);
                }
			}
		}
		unset($arPhoto);
		return $arResult;
	}

	/**
	 * Get path to resized User avatar
	 * @param array $resizerOpt
	 * @param string $noPhotoPath
	 * @return string
	 */
	public static function getUserAvatar($resizerOpt = array(), $noPhotoPath)
	{
		$resizerOptDefault = array(
			"SET_ID" => false,
			"WIDTH" => 90,
			"HEIGHT" => 90,
		);
		$userAvatar = null;

		if (empty($noPhotoPath)) {
			$noPhotoPath = SITE_TEMPLATE_PATH . '/images/i/userpic_big.png';
		}
		if (!is_array($resizerOpt) || empty($resizerOpt)) {
			$resizerOpt = $resizerOptDefault;
		} else {
			$resizerOpt = array_merge($resizerOptDefault, $resizerOpt);
		}
		global $USER;
		if ($USER->IsAuthorized()) {
			$obCache = new \CPHPCache();
			if ($obCache->InitCache(36000, $USER->GetID(), "romza/userAvatar")) {
				$userAvatar = $obCache->GetVars();
			} elseif ($obCache->StartDataCache()) {
				$rsUser = $USER->GetByID($USER->GetID());
				$arUser = $rsUser->GetNext();
				if (intval($arUser['PERSONAL_PHOTO']) > 0) {
					$userAvatar = $arUser['PERSONAL_PHOTO'];
				} else {
					$userAvatar = $noPhotoPath;
				}
				$obCache->EndDataCache($userAvatar);
			}
		} else {
			$userAvatar = $noPhotoPath;
		}
		return self::GetResizedImg($userAvatar, $resizerOpt);
	}

	/**
	 * add to .parameters.php at template folder to support resizer2 sets
	 * !! need external lang file with 'RESIZER_'.$name $MESS
	 * @param array $arResizerNames
	 * @param string $arTemplateParameters
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function AddResizerParams($arResizerNames, &$arTemplateParameters)
	{
		Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/templates/' . $_REQUEST['siteTemplateId'] . '/resizer.php');
		if (!empty($arResizerNames) && Loader::includeModule("yenisite.resizer2")) {
			$arResizerSet = array();
			$rs = \CResizer2Set::GetList();
			while ($ar = $rs->Fetch()) {
				$arResizerSet[$ar["id"]] = "[" . $ar["id"] . "] " . $ar["NAME"];
			}

			global $arComponentParameters;
			$namePrefix = '';
			if (!empty($arComponentParameters)) {
				$arComponentParameters["GROUPS"]["RESIZER_SETS"] = array(
					"NAME" => GetMessage("RZ_RESIZER_SETS"),
					"SORT" => 1,
				);
			} else {
				$namePrefix = GetMessage('RZ_RESIZER_PREFIX');
			}
			foreach ($arResizerNames as $key => $name) {
				$name = 'RESIZER_' . $name;
				$paramName = $name;
				if ("" . (int)$key != $key) {
					$paramName = $key;
				}
				$arTemplateParameters[$paramName] = array(
					"PARENT" => "RESIZER_SETS",
					"NAME" => $namePrefix . GetMessage($name),
					"TYPE" => "LIST",
					"VALUES" => $arResizerSet,
					"DEFAULT" => "",
				);
			}
		}
	}

}