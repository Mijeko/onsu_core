<?
function GetElementsImage ($id, $arParams)
{
	$obEl = CIBlockElement::GetByID($id)->GetNextElement();
	$arProps = $obEl->GetProperties();
		
	$arFields = $obEl->GetFields();
		
	$arReturn['PATH'] = $arProps[$arParams[PROPERTY_CODE]][VALUE];
	$picture['PREVIEW_PICTURE']=CFile::GetFileArray($arFields['PREVIEW_PICTURE']);
	$picture['DETAIL_PICTURE']=CFile::GetFileArray($arFields['DETAIL_PICTURE']);
	
	if($arParams["SHOW_DESCRIPTION"] != "N")
	{
		$arReturn['DESCRIPTION'] = $arProps[$arParams[PROPERTY_CODE]][DESCRIPTION];
		$arReturn['PREVIEW_PICTURE']['DESCRIPTION'] = $picture['PREVIEW_PICTURE']['DESCRIPTION'];
		$arReturn['DETAIL_PICTURE']['DESCRIPTION']  = $picture['DETAIL_PICTURE']['DESCRIPTION'];
	}
	
	$arReturn['PREVIEW_PICTURE']['SRC'] = CFile::GetPath($arFields['PREVIEW_PICTURE']);
	$arReturn['DETAIL_PICTURE']['SRC']  = CFile::GetPath($arFields['DETAIL_PICTURE']);
	
	if (is_array($arReturn['PATH'])) {
		foreach($arReturn['PATH'] as &$res) {
			$res = CFile::GetPath($res);
		}
	}
		
	if($arParams["DROP_PREVIEW_DETAIL"] != "Y"){
		if($arReturn['DETAIL_PICTURE']['SRC'])
		{
			$path = $arReturn['DETAIL_PICTURE']['SRC'];
			$descr= $arReturn['DETAIL_PICTURE']['DESCRIPTION'];
		}
		else
		{
			$path = $arReturn['PREVIEW_PICTURE']['SRC'];
			$descr= $arReturn['PREVIEW_PICTURE']['DESCRIPTION'];
		}
		if($path)
		{
			if(is_array($arReturn['PATH']))
				array_unshift($arReturn['PATH'], $path);
			else
				$arReturn['PATH'] = array($path);

			if(is_array($arReturn['DESCRIPTION']))
				array_unshift($arReturn['DESCRIPTION'], $descr);
			else
				$arReturn['DESCRIPTION'] = array($descr);
		}
	}
	
	return $arReturn;
}