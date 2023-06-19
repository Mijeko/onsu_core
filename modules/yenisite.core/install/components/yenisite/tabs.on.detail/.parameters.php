<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Iblock;

if (!Loader::includeModule('iblock'))
    return;

function addParams ($countTabs = 0, &$arComponentParameters, $hidden = 'N'){
    $i = 0;
    while ($i < $countTabs){
        $arComponentParameters['PARAMETERS'] += array(
            "TAB_HEADER_$i" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("TAB_HEADER", array("#COUNT#" => $i)),
                "TYPE" => "STRING",
                'HIDDEN' => $hidden
            ),
            "TAB_CONTENT_$i" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("TAB_CONTENT", array("#COUNT#" => $i)),
                "TYPE" => "STRING",
                'HIDDEN' => $hidden
            ),
        );
        $i++;
    }
}

$arComponentParameters = array(
    "GROUPS" => array(
        'BASE' => array(
            'NAME' => GetMessage('BASE'),
            'SORT' => '100'
        )
    ),
    "PARAMETERS" => array(
        "USE_PARAMS_OR_DATA" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("USE_PARAMS_OR_DATA"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "DEFAULT" => "DATA",
            "VALUES" => array(
                'PARAMS' => GetMessage('USE_PARAMS_PARAMS'),
                'DATA' => GetMessage('USE_PARAMS_DATA'),
            ),
            'REFRESH' => 'Y'
        ),
    ),
);


if ($arCurrentValues['USE_PARAMS_OR_DATA'] == 'PARAMS') {
    $arComponentParameters['PARAMETERS']['IBLOCK_TYPE']['HIDDEN'] = "Y";
    $arComponentParameters['PARAMETERS']['IBLOCK_ID']['HIDDEN'] = "Y";
    $arComponentParameters['PARAMETERS']['USE_SECTION_OR_ELEMENT']['HIDDEN'] = "Y";
    $arComponentParameters['PARAMETERS']['SECTION_ID']['HIDDEN'] = "Y";
    $arComponentParameters['PARAMETERS']['ELEMENTS_ID']['HIDDEN'] = "Y";
    $arComponentParameters['PARAMETERS']['HEADER_OF_TABS']['HIDDEN'] = "Y";

    $arComponentParameters['PARAMETERS'] += array(
        "COUNT_TABS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("COUNT_TABS"),
            "TYPE" => "STRING",
            'REFRESH' => 'Y',
            'HIDDEN' => 'N'
        ),
    );

    if (!empty($arCurrentValues['COUNT_TABS'])) {
        $countTabs = intval($arCurrentValues['COUNT_TABS']);
        addParams($countTabs,$arComponentParameters);
    }

} else {
    $arComponentParameters['PARAMETERS']['COUNT_TABS']['HIDDEN'] = "Y";
    if (!empty($arCurrentValues['COUNT_TABS'])) {
        $countTabs = intval($arCurrentValues['COUNT_TABS']);
        addParams($countTabs,$arComponentParameters,'Y');
    }

    $iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

    $arIBlockType = CIBlockParameters::GetIBlockTypes();

    $arIBlock = array();
    $iblockFilter = (
    !empty($arCurrentValues['IBLOCK_TYPE'])
        ? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
        : array('ACTIVE' => 'Y')
    );
    $rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
    while ($arr = $rsIBlock->Fetch())
        $arIBlock[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['NAME'];
    unset($arr, $rsIBlock, $iblockFilter);


    $arComponentParameters['PARAMETERS'] += array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
            'HIDDEN' => 'N'
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_IBLOCK"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
            'HIDDEN' => 'N'
        ),
        "USE_SECTION_OR_ELEMENT" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("USE_SECTION_OR_ELEMENT"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => array(
                'SECTION' => GetMessage('USE_SECTION'),
                'ELEMENT' => GetMessage('USE_ELEMENT'),
            ),
            'REFRESH' => 'Y',
            'HIDDEN' => 'N'
        ),
    );

    if ($arCurrentValues['USE_SECTION_OR_ELEMENT'] == 'SECTION' && !empty($arCurrentValues['IBLOCK_ID'])) {
        $dbSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y'), false, array('ID', 'NAME'), false);

        while ($arSection = $dbSections->Fetch()) {
            $arSections[$arSection['ID']] = '[' . $arSection['ID'] . '] ' . $arSection['NAME'];
        }
        unset($arSection, $dbSections);

        $arComponentParameters['PARAMETERS'] += array(
            "SECTION_ID" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("SECTION_ID"),
                "TYPE" => "LIST",
                "ADDITIONAL_VALUES" => "Y",
                "VALUES" => $arSections,
                'HIDDEN' => 'N'
            ),
        );
    } elseif ($arCurrentValues['USE_SECTION_OR_ELEMENT'] == 'ELEMENT') {
        $arComponentParameters['PARAMETERS'] += array(
            "ELEMENTS_ID" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("ELEMENTS_ID"),
                "TYPE" => "STRING",
                "MULTIPLE" => "Y",
                'HIDDEN' => 'N'
            ),
            "HEADER_OF_TABS" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("HEADER_OF_TABS"),
                "TYPE" => "STRING",
                'HIDDEN' => 'N'
            )
        );
    }

}