<? use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php';

if (empty($arResult['SECTIONS'])) return;
$arItems = $arResult['SECTIONS'];
$arCurSection = $arResult['SECTION'];
$templateData['CUR_SECTION'] = $arCurSection; ?>
<div class="catalog-categories-wrap">
    <? foreach ($arResult['SECTIONS_SORT'] as $idItem => $arValue): ?>
        <?
        $arItem = $arItems[$idItem];
        $this->AddEditAction($this->__name . $arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
        $this->AddDeleteAction($this->__name . $arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"),
            array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="catalog-category-wrap">
            <div class="img-wrap">
                <a href="<?= $arItem['SECTION_PAGE_URL'] ?>" class="link link-unvisited">
                    <img class="lazy img" data-original="<?= $arItem['PICTURE'] ?>"
                         src="<?= ConsVar::showLoaderWithTemplatePath() ?>" alt="<?= $arItem['NAME'] ?>">
                </a>
            </div>
            <div class="main-wrap">
                <div class="title-main title">
                    <a href="<?= $arItem['SECTION_PAGE_URL']; ?>" class="link link-unvisited">
                        <?= $arItem['NAME']; ?>
                        <? if (!empty($arItem['ELEMENT_CNT'])): ?>
                            <sup class="count">&nbsp;<?= $arItem['ELEMENT_CNT']; ?></sup>
                        <? endif ?>
                    </a>
                </div>
                <? if (is_array($arValue)): ?>
                    <ul class="list-categories-lvl-2">
                        <? foreach ($arValue as $idItemLvl2 => $arValueLvl3): ?>
                            <?
                            $arItem = $arItems[$idItemLvl2];
                            $this->AddEditAction($this->__name . $arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
                            $this->AddDeleteAction($this->__name . $arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"),
                                array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
                            ?>
                            <li>
                                <a href="<?= $arItem['SECTION_PAGE_URL'] ?>" class="link link-unvisited">
                                    <span class="title"><?= $arItem['NAME']; ?></span>
                                    <? if (!empty($arItem['ELEMENT_CNT'])): ?>
                                        <sup class="count">
                                            &nbsp;<?= $arItem['ELEMENT_CNT']; ?>
                                        </sup>
                                    <? endif ?>
                                </a>
                                <? if (is_array($arValueLvl3)): ?>
                                    <ul class="list-categories-lvl-3">
                                        <? foreach ($arValueLvl3 as $key => $idItemLvl3): ?>
                                            <?
                                            $arItem = $arItems[$idItemLvl3];
                                            $this->AddEditAction($this->__name . $arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
                                            $this->AddDeleteAction($this->__name . $arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"),
                                                array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
                                            ?>
                                            <li>
                                                <a href="<?= $arItem['SECTION_PAGE_URL'] ?>"
                                                   class="link link-unvisited">
                                                    <span class="title"><?= $arItem['NAME']; ?></span>
                                                    <? if (!empty($arItem['ELEMENT_CNT'])): ?>
                                                        <sup class="count">
                                                            &nbsp;<?= $arItem['ELEMENT_CNT']; ?>
                                                        </sup>
                                                    <? endif ?>
                                                </a>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                <? endif; ?>
                            </li>
                        <? endforeach; ?>
                    </ul>
                <? endif; ?>
            </div>
        </div>
    <? endforeach; ?>
</div>
