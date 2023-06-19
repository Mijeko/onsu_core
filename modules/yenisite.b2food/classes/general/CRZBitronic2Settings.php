<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('yenisite.core');

IncludeModuleLangFile(__FILE__);
//set constants with module name in MODULE_ROOT
include __DIR__ . '/../../constants.php';

/**
 * Class contains solution settings
 */
class CRZBitronic2Settings {
	static private $init = false;
	static private $lite = false;
	static private $gifts = false;
	static private $_module = RZ_B2_MODULE_FULL_NAME;
	static private $_groups = array();
	static private $_fieldsets = array();
	static private $_settings = array();

	public static function init() {
		if (!self::$init) {
			Loc::loadLanguageFile(__FILE__);
			self::$lite = (substr(self::$_module, -4) == 'lite');
			self::$gifts = (\Bitrix\Main\ModuleManager::isModuleInstalled('sale') && version_compare(\Yenisite\Core\Tools::getModuleVersion('sale'), '16.0.0') >= 0);
			self::$_fieldsets = array(
				'preset' => array(
					'sort' => 0,
					'name' => '',
					'class' => 'no-border preset'
				),
				'page_index' => array(
					'sort' => 100,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_INDEX'),
				),
				'page_section' => array(
					'sort' => 200,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_SECTION'),
				),
                'page_order' => array(
					'sort' => 210,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_ORDER'),
				),
				'page_detail' => array(
					'sort' => 300,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_DETAIL'),
				),
				'page_common' => array(
					'sort' => 600,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_COMMON'),
				),
				'page_basket' => array(
					'sort' => 700,
					'name' => GetMessage('BITRONIC2_FIELDSET_PAGE_BASKET'),
				),
				'slider_size' => array(
					'sort' => 400,
					'name' => GetMessage('BITRONIC2_FIELDSET_SLIDER_SIZE'),
				),
				'slider_full' => array(
					'sort' => 450,
					'name' => GetMessage('BITRONIC2_FIELDSET_SLIDER_FULL'),
					'class' => 'slider-pro_slide-settings',
				),
				'colors' => array(
					'sort' => 500,
					'name' => GetMessage('BITRONIC2_FIELDSET_COLORS'),
				),
				'page_search' => array(
					'sort' => 800,
					'name' => GetMessage('BITRONIC2_FIELDSET_SEARCH')
				),
				'page_404' => array(
					'sort' => 900,
					'name' => GetMessage('BITRONIC2_FIELDSET_404')
				)
			);
			self::$_groups = array(
				"general" => array(
					'sort' => 100,
				),
				"slider" => array(
					'sort' => 200,
					'name' => GetMessage('BITRONIC2_GROUP_SLIDER'),
				),
				"view" => array(
					'sort' => 300,
				),
				"basket" => array(
					'sort' => 400,
				),
				"blocks" => array(
					'sort' => 600,
					'name' => GetMessage('BITRONIC2_GROUP_BLOCK'),
				),
                "captcha" => array(
					'sort' => 600,
					'name' => GetMessage('BITRONIC2_GROUP_CAPTCHA'),
				),
                "drag_sort" => array(
					'sort' => 700,
					'name' => GetMessage('BITRONIC2_GROUP_DRAG_SORT'),
				)
			);

			self::$_settings = array(
				//general
				"theme-demo" => array(
					'type' => 'BLOCK',
					'group' => 'general',
					'values' => array(
						'skew' => array(
							'yellow-skew',
							'violet-skew',
							'red-skew',
							'pink-skew',
							'orange-skew',
							'mint-skew',
							'lightblue-skew',
							'green-skew',
							'gray-skew',
							'darkviolet-skew',
							'darkblue-skew',
							'blue-skew',
						),
						'flat' => array(
							'yellow-flat',
							'violet-flat',
							'red-flat',
							'pink-flat',
							'orange-flat',
							'mint-flat',
							'green-flat',
							'darkviolet-flat',
							'darkblue-flat',
							'blue-flat',
						)
					),
					'default' => 'red-flat',
					'preview' => true,
				),
				'custom-theme' => array(
					'type' => 'CHECKBOX',
					'group' => 'general',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'header' => GetMessage('BITRONIC2_SETTING_CUSTOM_THEME'),
					'name' => GetMessage('BITRONIC2_SETTING_CUSTOM_THEME_LABEL'),
					'preview' => true,
                    'label-id' => 'checkbox_custom_label'
				),
				'theme-button' => array(
					'type' => 'HIDDEN',
					'group' => 'general',
					'default' => 'white',
				),
				'theme-main-color' => array(
					'type' => 'HIDDEN',
					'group' => 'general',
					'default' => '#ff0000',
				),
				'theme-custom' => array(
					'hidden' => true
				),
                "site-font" => array(
                    'type' => 'TEXT_WITH_HELP',
                    'id-wrap' => 'font-custom-wrap',
                    'id' => 'font-family-custom',
                    'name' => GetMessage('RZ_CUSTOM_FONT'),
                    'values' => '',
                    'default' => '',
                    'group' => 'general',
                    'help-text' => GetMessage('RZ_CUSTOM_FONT_HELP'),
                    'form-additional-class' => 'form-group__font-custom',
                    'include-in-prev-params' => true,
                    'placeholder' => GetMessage('RZ_CUSTOM_FONT_PLACEHOLDER'),
                    'hidden' => true,
                    'force-show' => true
                ),
				'header-version' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'values' => array(
						'v1',
						'v2',
						'v3',
						'v4',
						'v5',
					),
					'names' => array(
						'v1' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION-v1'),
						'v2' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION-v2'),
						'v3' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION-v3'),
						'v4' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION-v4'),
						'v5' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION-v5'),
					),
					'default' => 'v4',
					'name' => GetMessage('BITRONIC2_SETTING_HEADER-VERSION'),
					'preview' => true,
				),
				'container-width' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'values' => array(
						'full_width',
						'container'
					),
					'names' => array(
						'full_width' => GetMessage('BITRONIC2_SETTING_WORK_AREA_FULL_WIDTH'),
						'container' => GetMessage('BITRONIC2_SETTING_WORK_AREA_CONTAINER'),
					),
					'default' => 'container',
					'name' => GetMessage('BITRONIC2_SETTING_WORK_AREA'),
					'preview' => true,
				),
				'catalog-placement' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'values' => array(
						'top',
						'side',
					),
					'default' => 'top',
					'preview' => true,
				),
                'limit-sliders' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'general',
                    'values' => array('Y', 'N'),
                    'default' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING_LIMIT_SLIDERS'),
                    'label-id' => 'limit-sliders',
                ),
				'filter-placement' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'values' => array(
						'top',
						'side',
						'line',
					),
					'default' => 'side',
					'preview' => true,
				),
				'menu-visible-items' => array(
					'type' => 'SELECT',
					'group' => 'general',
					'values' => array(5,6,7,8,9,10),
					'names' => array(5=>'5','6','7','8','9','10'),
					'default' => 7,
					'name' => GetMessage('BITRONIC2_SETTING_MENU_VISIBLE_ITEMS'),
					'preview' => true
				),
                'footmenu-visible-items' => array(
					'type' => 'SELECT',
					'group' => 'general',
					'values' => array(5,6,7,8,9,10,'all'),
					'names' => array(5=>'5','6','7','8','9','10', 'all' => GetMessage('BITRONIC2_SETTING_ALL')),
					'default' => 7,
					'name' => GetMessage('BITRONIC2_SETTING_MENU_BOTTOM_VISIBLE_ITEMS'),
					'preview' => true
				),
                'catalog-darken' => array(
                    'type' => 'RADIO',
                    'group' => 'general',
                    'values' => array(
                        'no',
                        'yes',
                    ),
                    'names' => array(
                        'yes' => GetMessage('BITRONIC2_SETTING_YES'),
                        'no' => GetMessage('BITRONIC2_SETTING_NO'),
                    ),
                    'default' => 'yes',
                    'name' => GetMessage('BITRONIC2_SETTING_MENU_DARKEN'),
                ),
				'color-body' => array(
					'type' => 'COLOR',
					'group' => 'general',
					'default' => '#ffffff',
					'property' => 'background',
					'selector' => 'body',
					'choose' => array(
						'color' => GetMessage('BITRONIC2_SETTING_COLOR-COLOR'),
						'pattern' => GetMessage('BITRONIC2_SETTING_COLOR-PATTERN'),
						'image' => GetMessage('BITRONIC2_SETTING_COLOR-IMAGE'),
					),
					'filter' => 'footer_',
					'name' => GetMessage('BITRONIC2_SETTING_COLOR-BODY'),
					'preview' => false,
					'hidden' => false
				),
                'type_bg_ground' => array(
                    'hidden' => true
                ),
				'color-header' => array(
					'type' => 'COLOR',
					'group' => 'general',
					'default' => 'url(' . BX_ROOT . '/images/' . self::$_module . '/patterns/concrete_seamless.png)',
                    'default-select-color' => 'color',
					'property' => 'background',
					'selector' => '.contacts-content, .page-header',
					'choose' => array(
						'color' => GetMessage('BITRONIC2_SETTING_COLOR-COLOR'),
						'pattern' => GetMessage('BITRONIC2_SETTING_COLOR-PATTERN'),
					),
					'filter' => 'footer_',
					'name' => GetMessage('BITRONIC2_SETTING_COLOR-HEADER'),
					'preview' => true,
				),
				'color-footer' => array(
					'type' => 'COLOR',
					'group' => 'general',
					'default' => '#303138',
                    'default-select-color' => 'color',
					'property' => 'background',
					'selector' => '.footer-middle',
					'name' => GetMessage('BITRONIC2_SETTING_COLOR-FOOTER'),
					'choose' => array(
						'color' => GetMessage('BITRONIC2_SETTING_COLOR-COLOR'),
						'pattern' => GetMessage('BITRONIC2_SETTING_COLOR-PATTERN'),
					),
					'filter' => '!footer_',
					'preview' => true,
				),
				'color-footer-font' => array(
					'type' => 'COLOR',
					'group' => 'general',
					'default' => '#999aa3',
					'property' => 'color',
					'selector' => '.footer-middle',
					'name' => GetMessage('BITRONIC2_SETTING_COLOR-FOOTER-FONT'),
					'preview' => true,
				),
				'wow-effect' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'default' => 'N',
					'values' => array('Y', 'N'),
					'name' => GetMessage('BITRONIC2_SETTING_WOW-EFFECT'),
					'names' => array(
						'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
						'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
					),
				),
                'mobile-phone-action' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'default' => 'callback',
					'values' => array('callback', 'calling'),
					'name' => GetMessage('BITRONIC2_SETTING_MOBILE_PHONE_ACTION'),
					'names' => array(
						'callback' => GetMessage('BITRONIC2_SETTING_MOBILE_PHONE_ACTION_Y'),
						'calling' => GetMessage('BITRONIC2_SETTING_MOBILE_PHONE_ACTION_N'),
					),
				),
                'sitenav-type' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'default' => 'all',
					'values' => array('all', 'collapse'),
					'name' => GetMessage('BITRONIC2_SETTING_SITENAV_TYPE'),
					'names' => array(
						'all' => GetMessage('BITRONIC2_SETTING_SITENAV_TYPE_Y'),
						'collapse' => GetMessage('BITRONIC2_SETTING_SITENAV_TYPE_N'),
					),
				),
                'btn-to-top' => array(
					'type' => 'RADIO',
					'group' => 'general',
					'default' => 'right',
					'values' => array('left', 'right'),
					'name' => GetMessage('BITRONIC2_SETTING_BTN_TOP_POSITION'),
					'names' => array(
						'left' => GetMessage('BITRONIC2_SETTING_BTN_TOP_POSITION_LEFT'),
                        'right' => GetMessage('BITRONIC2_SETTING_BTN_TOP_POSITION_RIGHT'),
					),
				),
				//slider
				'big-slider-width' => array(
					'type' => 'RADIO',
					'group' => 'slider',
					'values' => array(
						'full',
						'normal',
						'narrow',
					),
					'names' => array(
						'full' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH_FULL'),
						'normal' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH_NORMAL'),
						'narrow' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH_NARROW'),
					),
					'titles' => array(
						'normal' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH_NORMAL_TITLE'),
						'narrow' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH_NARROW_TITLE'),
					),
					'default' => 'full',
					'name' => GetMessage('BITRONIC2_SETTING_SLIDER-WIDTH'),
					'preview' => true,
					'fieldset' => 'slider_size',
				),
				'bs_height' => array(
					'type' => 'SLIDER',
					'group' => 'slider',
					'default' => '22.24%',
					'min' => '20',
					'max' => '50',
					'step' => '0.01',
					'postfix' => '%',
					'name' => GetMessage('BITRONIC2_SETTING_SLIDER-HEIGHT'),
					'preview' => true,
					'fieldset' => 'slider_size',
				),
				'bs_media_anim' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'slideRightBig'
				),
				'bs_media_h-align' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'left'
				),
				'bs_media_limits_bottom' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '0%'
				),
				'bs_media_limits_left' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '51%'
				),
				'bs_media_limits_right' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '2%'
				),
				'bs_media_limits_top' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '0%'
				),
				'bs_media_v-align' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'center'
				),
				'bs_text_anim' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'slideLeftBig'
				),
				'bs_text_h-align' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'right'
				),
				'bs_text_limits_bottom' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '0%'
				),
				'bs_text_limits_left' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '2%'
				),
				'bs_text_limits_right' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '51%'
				),
				'bs_text_limits_top' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => '0%'
				),
				'bs_text_text-align' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'left'
				),
				'bs_text_v-align' => array(
					'type' => 'HIDDEN',
					'group' => 'slider',
					'fieldset' => 'slider_full',
					'default' => 'center'
				),
				//view
				'product-hover-effect' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						//'border',
						//'shadow',
						'border-n-shadow',
						'detailed-expand',
						'switch_photo',
					),
					'default' => 'detailed-expand',
					'fieldset' => 'page_common'
				),
				'store_amount_type' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'graphic',
						'text',
						'numeric'
					),
					'names' => array(
						'graphic' => GetMessage('BITRONIC2_SETTING_STORE_AMOUNT_TYPE_GRAPH'),
						'text' => GetMessage('BITRONIC2_SETTING_STORE_AMOUNT_TYPE_TEXT'),
						'numeric' => GetMessage('BITRONIC2_SETTING_STORE_AMOUNT_TYPE_NUMBER'),
					),
					'default' => 'graphic',
					'name' => GetMessage('BITRONIC2_SETTING_STORE_AMOUNT_TYPE'),
					'hidden' => self::$lite,
					'fieldset' => 'page_common'
				),
				'menu-hits-position' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'TOP',
						'BOTTOM'
					),
					'names' => array(
						'TOP' => GetMessage('BITRONIC2_SETTING_MENU_HITS_POSITION_TOP'),
						'BOTTOM' => GetMessage('BITRONIC2_SETTING_MENU_HITS_POSITION_BOTTOM')
					),
					'default' => 'BOTTOM',
					'name' => GetMessage('BITRONIC2_SETTING_MENU_HITS_POSITION'),
					'fieldset' => 'page_common'
				),
				'brands_extended' => array(
					'type' => 'RADIO',
					'group' => 'view',
					'values' => array('Y', 'N'),
					'names' => array(
						'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
						'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
					),
					'titles' => array(
						'Y' => GetMessage('BITRONIC2_SETTING_BRANDS_EX_TITLE_Y'),
						'N' => GetMessage('BITRONIC2_SETTING_BRANDS_EX_TITLE_N')
					),
					'default' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_BRANDS_EX'),
					'fieldset' => 'page_common',
					'preview' => false
				),
                'hide-not-available' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
                        'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
                    ),
                    'default' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING-HIDE_NOT_AVAILABLE'),
                    'fieldset' => 'page_common',
                    'preview' => false
                ),
                'hide-zero-price' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
                        'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
                    ),
                    'default' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING-HIDE_ZERO_PRICE'),
                    'fieldset' => 'page_common',
                    'preview' => false
                ),
                'hide-empty-img' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
                        'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
                    ),
                    'default' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING-HIDE_EMPTY_IMG'),
                    'fieldset' => 'page_common',
                    'preview' => false
                ),
                'img-for-first-lvl-menu' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_IMG_SHOW'),
                        'N' => GetMessage('BITRONIC2_SETTING_IMG_HIDE'),
                    ),
                    'default' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-IMG_FOR_FIRST_LVL_MENU'),
                    'fieldset' => 'page_common',
                    'preview' => false,
                ),
                'img-for-second-lvl-menu' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_IMG_SHOW'),
                        'N' => GetMessage('BITRONIC2_SETTING_IMG_HIDE'),
                    ),
                    'default' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-IMG_FOR_SECOND_LVL_MENU'),
                    'fieldset' => 'page_common',
                    'preview' => false,
                ),
                'img-for-third-lvl-menu' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_IMG_SHOW'),
                        'N' => GetMessage('BITRONIC2_SETTING_IMG_HIDE'),
                    ),
                    'default' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-IMG_FOR_THIRD_LVL_MENU'),
                    'fieldset' => 'page_common',
                    'preview' => false,
                ),
				'sb-mode' => array(
					'type' => 'RADIO',
					'group' => 'view',
					'values' => array(
						'tabs',
						'full',
					),
					'default' => 'tabs',
					'fieldset' => 'page_index',
					'preview' => true,
				),
				'sb_full_default' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'open',
						'close',
					),
					'names' => array(
						'open' => GetMessage('BITRONIC2_SETTING_SB_FULL_DEFAULT_OPEN'),
						'close' => GetMessage('BITRONIC2_SETTING_SB_FULL_DEFAULT_CLOSE'),
					),
					'default' => 'close',
					'name' => GetMessage('BITRONIC2_SETTING_SB_FULL_DEFAULT'),
					'fieldset' => 'page_index',
				),
                'categories-view' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array(
                        'list',
                        'blocks',
                    ),
                    'name' => GetMessage('BITRONIC2_SETTING_CATEGORIES_VIEW'),
                    'names' => array(
                        'list' => GetMessage('BITRONIC2_SETTING_CATEGORIES_VIEW_LIST'),
                        'blocks' => GetMessage('BITRONIC2_SETTING_CATEGORIES_VIEW_BLOCKS'),
                    ),
                    'default' => 'list',
                    'fieldset' => 'page_index',
                    'preview' => true,
                ),
				'brands_cloud' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'names' => array(
						'Y' => GetMessage('BITRONIC2_SETTING_BRANDS_CLOUD_Y'),
						'N' => GetMessage('BITRONIC2_SETTING_BRANDS_CLOUD_N')
					),
					'fieldset' => 'page_index',
				),
                'hidden-option' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'default' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING_CATEGORIES_WITH_IMG'),
                    'hidden-element' => true,
                    'fieldset' => 'page_index',
                ),
                'categories-with-img' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'default' => 'N',
                    'fieldset' => 'page_index',
                    'name' => GetMessage('BITRONIC2_SETTING_CATEGORIES_WITH_IMG'),
                    'open_tag' => true,
                ),
                'categories-with-sub' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'default' => 'N',
                    'fieldset' => 'page_index',
                    'name' => GetMessage('BITRONIC2_SETTING_CATEGORIES_WITH_SUB'),
                    'close_tag' => true,
                ),
				'detail-info-mode' => array(
					'type' => 'RADIO',
					'group' => 'view',
					'values' => array(
						'tabs',
						'full',
					),
					'default' => 'tabs',
					'fieldset' => 'page_detail',
				),
				'detail_gallery_description' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'disabled',
						'bottom',
						'top'
					),
					'names' => array(
						'disabled' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_DESCRIPTION_DISABLED'),
						'bottom' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_DESCRIPTION_BOTTOM'),
						'top' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_DESCRIPTION_TOP'),
					),
					'default' => 'bottom',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_DESCRIPTION'),
					'fieldset' => 'page_detail',
				),
				'detail_info_full_expanded' => array(
					'type' => 'CHECKBOX',
					'group' => 'view',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL_INFO_FULL_EXPANDED'),
					'fieldset' => 'page_detail',
				),
				'detail_gallery_type' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'modal',
						'zoom',
					),
					'names' => array(
						'modal' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_TYPE_MODAL'),
						'zoom' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_TYPE_ZOOM'),
					),
					'default' => 'modal',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL_GALLERY_TYPE'),
					'fieldset' => 'page_detail',
				),
				'detail_text_default' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'open',
						'close',
					),
					'names' => array(
						'open' => GetMessage('BITRONIC2_SETTING_DETAIL_TEXT_DEFAULT_OPEN'),
						'close' => GetMessage('BITRONIC2_SETTING_DETAIL_TEXT_DEFAULT_CLOSE'),
					),
					'default' => 'close',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL_TEXT_DEFAULT'),
					'fieldset' => 'page_detail',
				),

                'socials-type' => array(
                    'type' => 'RADIO',
					'group' => 'view',
					'values' => array(
						'visible',
						'hidden',
					),
                    'names' => array(
                        'visible' => GetMessage('BITRONIC2_SETTING_NAME_SOCIAL_VISIBLE'),
                        'hidden' => GetMessage('BITRONIC2_SETTING_NAME_SOCIAL_INVISIBLE')
                    ),
                    'titles' => array(
                        'visible' => GetMessage('BITRONIC2_SETTING_TITLE_SOCIAL_VISIBLE'),
                        'hidden' => GetMessage('BITRONIC2_SETTING_TITLE_SOCIAL_INVISIBLE')
                    ),
                    'default' => 'visible',
                    'name' => GetMessage('BITRONIC2_SETTING_NAME_SOCIAL'),
					'fieldset' => 'page_detail',
                ),
                'product-availability' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array(
                        'status',
                        'expanded',
                        'tabs'
                    ),
                    'names' => array(
                        'status' => GetMessage('BITRONIC2_SETTING_PRODUCT_AVAILABILITY_STATUS'),
                        'expanded' => GetMessage('BITRONIC2_SETTING_PRODUCT_AVAILABILITY_EXPANDED'),
                        'tabs' => GetMessage('BITRONIC2_SETTING_PRODUCT_AVAILABILITY_TABS'),
                    ),
                    'default' => 'status',
                    'name' => GetMessage('BITRONIC2_SETTING_PRODUCT_AVAILABILITY'),
                    'fieldset' => 'page_detail',
                    'hidden' => self::$lite
                ),
                'view-tabs-of-chars-and-desc' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array(
                        'combine',
                        'different',
                    ),
                    'names' => array(
                        'combine' => GetMessage('BITRONIC2_SETTING_NAME_TABS_CHARS_COMBINE'),
                        'different' => GetMessage('BITRONIC2_SETTING_NAME_TABS_CAHRS_DIFFERENT')
                    ),
                    'default' => 'different',
                    'name' => GetMessage('BITRONIC2_SETTING_NAME_TABS_CAHRS_DESC'),
                    'fieldset' => 'page_detail',
                ),
                'sku-view' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
                        'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
                    ),
                    'default' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-SKU_VIEW'),
                    'fieldset' => 'page_detail',
                    'preview' => false,
                    'hidden' => self::$lite
                ),
				'pagination_type' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'default',
						'inf',
						'inf-button',
					),
					'default' => 'inf-button',
					'fieldset' => 'page_section',
				),
				'filter_type' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'auto',
						'manual',
					),
					'default' => 'auto',
					'fieldset' => 'page_section',
				),
				'catalog_view_default' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'blocks',
						'list',
						'table',
					),
					'names' => array(
						'blocks' => GetMessage('BITRONIC2_SETTING_CATALOG_VIEW_DEFAULT_BLOCKS'),
						'list' => GetMessage('BITRONIC2_SETTING_CATALOG_VIEW_DEFAULT_LIST'),
						'table' => GetMessage('BITRONIC2_SETTING_CATALOG_VIEW_DEFAULT_TABLE'),
					),
					'default' => 'list',
					'name' => GetMessage('BITRONIC2_SETTING_CATALOG_VIEW_DEFAULT'),
					'fieldset' => 'page_section',
				),
				'catalog_subsection_view' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'both',
						'text',
						'picture'
					),
					'names' => array(
						'both' => GetMessage('BITRONIC2_SETTING_CATALOG_SUBSECTION_VIEW_BOTH'),
						'text' => GetMessage('BITRONIC2_SETTING_CATALOG_SUBSECTION_VIEW_TEXT'),
						'picture' => GetMessage('BITRONIC2_SETTING_CATALOG_SUBSECTION_VIEW_PICTURE')
					),
					'default' => 'both',
					'name' => GetMessage('BITRONIC2_SETTING_CATALOG_SUBSECTION_VIEW'),
					'fieldset' => 'page_section'
				),
				'catalog_text_default' => array(
					'type' => 'SELECT',
					'group' => 'view',
					'values' => array(
						'open',
						'close',
					),
					'names' => array(
						'open' => GetMessage('BITRONIC2_SETTING_DETAIL_TEXT_DEFAULT_OPEN'),
						'close' => GetMessage('BITRONIC2_SETTING_DETAIL_TEXT_DEFAULT_CLOSE'),
					),
					'default' => 'close',
					'name' => GetMessage('BITRONIC2_SETTING_CATALOG_TEXT_DEFAULT'),
					'fieldset' => 'page_section',
				),
                'menu-opened-in-catalog' => array(
                    'type' => 'SELECT',
                    'group' => 'view',
                    'values' => array(
                        'open',
                        'close',
                    ),
                    'default' => 'close',
                    'names' => array(
                        'open' => GetMessage('BITRONIC2_SETTING_WORK_AREA_FULL_WIDTH_OPEN'),
                        'close' => GetMessage('BITRONIC2_SETTING_MENU_OPENED_IN_CATALOG_CLOSE'),
                    ),
                    'name' => GetMessage('BITRONIC2_SETTING_MENU_OPENED_IN_CATALOG'),
                    'fieldset' => 'page_section',
                ),
                'sku-view-section' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
                        'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
                    ),
                    'default' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-SKU_VIEW'),
                    'fieldset' => 'page_section',
                    'preview' => false,
                    'hidden' => self::$lite
                ),
                'hide_all_hrefs' => array(
                    'type' => 'RADIO',
                    'group' => 'view',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'names' => array(
                        'Y' => GetMessage('BITRONIC2_SETTING_YES'),
                        'N' => GetMessage('BITRONIC2_SETTING_NO'),
                    ),
                    'fieldset' => 'page_order',
                    'name' => GetMessage('BITRONIC2_SETTING_ORDER_HIDE_HREFS'),
                ),

				//basket
				'top-line-position' => array(
					'type' => 'RADIO',
					'group' => 'basket',
					'values' => array(
						'fixed-top',
						'fixed-bottom',
						'fixed-left',
						'fixed-right',
						'not-fixed',
					),
					'default' => 'fixed-right',
					'preview' => true,
				),
				'addbasket_type' => array(
					'type' => 'RADIO',
					'group' => 'basket',
					'values' => array(
						'buzz',
						'popup',
					),
					'default' => 'buzz'
				),
				'basket_popup_slider' => array(
					'type' => 'SELECT',
					'group' => 'basket',
					'values' => (
						self::$lite
						? array('similar_price')
						: array('similar_sell', 'similar', 'similar_view', 'similar_price', 'recommended', 'viewed')
					),
					'names' => array(
						'similar'       => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR'),
						'similar_price' => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR-PRICE'),
						'similar_sell'  => GetMessage('BITRONIC2_SETTING_BASKET_SLIDER_SIMILAR_SELL'),
						'similar_view'  => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR-VIEW'),
						'recommended'   => GetMessage('BITRONIC2_SETTING_DETAIL-RECOMMENDED'),
						'viewed'        => GetMessage('BITRONIC2_SETTING_DETAIL-VIEWED'),
					),
					'default' => (self::$lite ? 'similar_price' : 'similar_sell')
				),

				// blocks
				'preset' => array(
					'type' => 'STATEBOX',
					'group' => 'blocks',
					'values' => array('easy', 'medium', 'hard'),
					'names' => array(
						'easy' => GetMessage('BITRONIC2_SETTING_PRESET_EASY'),
						'medium' => GetMessage('BITRONIC2_SETTING_PRESET_MEDIUM'),
						'hard' => GetMessage('BITRONIC2_SETTING_PRESET_HARD')
					),
					'default' => 'medium',
					'name' => GetMessage('BITRONIC2_SETTING_PRESET'),
					'fieldset' => 'preset',
				),
				'currency-switcher' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'admin' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_BLOCK_SWITCH_CUR'),
					'fieldset' => 'page_common',
					'hidden' => self::$lite,
				),
				'block_main-menu-elem' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_MAIN-MENU-ELEM'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_common',
				),
				'menu-show-icons' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_MENU_SHOW_ICONS'),
					'fieldset' => 'page_common'
				),
				'block_pricelist' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => self::isPro() ? 'Y' : 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_PRICELIST'),
					'states' => array(
						'desktop' => array(
							'state' => 'disabled',
							'status' => 'unchecked'
						),
						'mobile' => array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_common',
					'hidden' => !CModule::IncludeModule('yenisite.pricegen')
				),
				'quick-view' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'admin' => 'N',
					'fieldset' => 'page_common'
				),
				'quick-view-chars' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'admin' => 'N',
					'name' => GetMessage('BITRONIC2_SETTINGS_QUICK-VIEW-CHARS'),
					'fieldset' => 'page_common'
				),
				'backnav_enabled' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'name' => GetMessage('BITRONIC2_SETTING_BREAD_BACKNAV'),
					'fieldset' => 'page_common'
				),
				'show_discount_percent' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_SHOW_DISCOUNT_PERCENT'),
					'fieldset' => 'page_common',
					'preview' => false,
					'hidden' => self::$lite
				),
				'stores' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('enabled', 'disabled'),
					'default' => self::$lite ? 'disabled' : 'enabled',
					'default_MOBILE' => self::$lite ? 'disabled' : 'enabled',
					'name' => GetMessage('BITRONIC2_SETTING_STORES'),
					'preview' => true,
					'hidden' => self::$lite,
					'fieldset' => 'page_common',
				),
				'show-stock' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_SHOW_STOCK') . (self::$lite ? '' : GetMessage('BITRONIC2_SETTING_SHOW_STOCK_MASTER')),
					'label-id' => 'show-stock',
					'fieldset' => 'page_common',
				),
				'block_show_stars' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_STARS'),
					'fieldset' => 'page_common',
				),
				'block_show_geoip' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => (self::$lite ? 'N' : 'Y'),
					'default_MOBILE' => (self::$lite ? 'N' : 'Y'),
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_GEOIP'),
					'fieldset' => 'page_common',
					'hidden' => self::$lite,
				),
				'block_show_compare' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_COMPARE'),
					'fieldset' => 'page_common',
				),
				'block_show_favorite' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_FAVORITE'),
					'fieldset' => 'page_common',
				),
                'block_show_viwed' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BITRONIC2_BLOCK_SHOW_VIEWED'),
                    'fieldset' => 'page_common',
                    'hidden' => self::$lite,
                ),
				'block_show_oneclick' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => (self::$lite ? 'N' : 'Y'),
					'default_MOBILE' => (self::$lite ? 'N' : 'Y'),
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_ONECLICK'),
					'fieldset' => 'page_common',
					'hidden' => self::$lite,
				),
				'block_show_article' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_ARTICLE'),
					'fieldset' => 'page_common',
				),
				'block_show_comment_count' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_COMMENT_COUNT'),
					'fieldset' => 'page_section',
				),
                'block_show_sort_block' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BITRONIC2_BLOCK_SHOW_SORT_BLOCK'),
                    'fieldset' => 'page_section',
                ),
				'block_show_gallery_thumb' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_GALLERY_THUMB'),
					'fieldset' => 'page_common',
				),
				'block_show_ad_banners' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_BLOCK_SHOW_AD_BANNERS'),
					'fieldset' => 'page_common',
					//'hidden' => !IsModuleInstalled('advertising'),
				),
				'block_worktime' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_WORKTIME'),
					'fieldset' => 'page_common',
				),
				'block_search_category' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_SEARCH_CATEGORY'),
					'fieldset' => 'page_common',
				),
				'block_menu_count' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_MENU_COUNT'),
					'fieldset' => 'page_common',
				),
				'block-buy_button' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'states'=> array(
						'desktop'=> array(
							'state' => 'disabled',
							'status' => 'checked'
						)
					),
					'name' => GetMessage('BITRONIC2_SETTING-BUY_BUTTON'),
					'fieldset' => 'page_common',
				),
                'block-quantity' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING-BLOCK_QUANTITY'),
                    'fieldset' => 'page_common',
                ),
				'block_home-main-slider' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-MAIN-SLIDER'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index',
				),
				'block_home-rubric' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME_RUBRIC'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index',
				),
				'block_home-cool-slider' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-COOL-SLIDER'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index',
				),
				'cool_slider_show_names' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'admin' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_COOL_SLIDER_SHOW_NAMES'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index'
				),
				'coolslider_show_stickers' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_COOLSLIDER_SHOW_STICKERS'),
					'preview' => false,
					'fieldset' => 'page_index',
				),
				'block_home-specials' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-SPECIALS'),
					'fieldset' => 'page_index',
				),
				'block_home-specials_icons' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-SPECIALS_ICONS'),
					'fieldset' => 'page_index',
				),
				'block_home-specials_count' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-SPECIALS_COUNT'),
					'fieldset' => 'page_index',
				),
				'block_home-our-adv' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-OUR-ADV'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index',
				),
				'block_home-feedback' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
                    'states'=> array(
                        'mobile'=> array(
                            'state' => 'disabled',
                            'status' => 'unchecked'
                        )
                    ),
					'name' => GetMessage('BITRONIC2_SETTING_HOME-FEEDBACK'),
					'fieldset' => 'page_index',
				),
				'catchbuy_color_heading' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => (self::$lite ? 'N' : 'Y'),
					'name' => GetMessage('BITRONIC2_SETTING_CATCHBUY_COLOR_HEADING'),
					'preview' => false,
					'fieldset' => 'page_index',
					'hidden' => self::$lite,
				),
				'block_home-catchbuy' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-CATCHBUY'),
					'fieldset' => 'page_index',
					'hidden' => self::$lite,
				),
				'block_home-news' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-NEWS'),
					'fieldset' => 'page_index',
				),
                'block_home-actions' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING_HOME-ACTIONS'),
                    'fieldset' => 'page_index',
                ),
                'block_home-reviews' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BITRONIC2_SETTING_HOME-REVIEWS'),
                    'fieldset' => 'page_index',
                ),
				'block_home-voting' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-VOTING'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_index',
				),
				'block_home-brands' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-BRANDS'),
					'fieldset' => 'page_index',
				),
				'block_home-vk' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-VK'),
					'fieldset' => 'page_index',
				),
				'block_home-ok' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-OK'),
					'fieldset' => 'page_index',
				),
				'block_home-fb' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-FB'),
					'fieldset' => 'page_index',
				),
				'block_home-tw' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-TW'),
					'fieldset' => 'page_index',
				),
                'block_home-flmp' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-FLMP'),
					'fieldset' => 'page_index',
				),
                'block_home-inst' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_HOME-INST'),
					'fieldset' => 'page_index',
				),

				'block_detail-addtoorder' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-ADDTOORDER'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-similar' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-similar-view' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR-VIEW'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-similar-price' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-SIMILAR-PRICE'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
				),
				'block_detail-recommended' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-RECOMMENDED'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-viewed' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-VIEWED'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-delivery' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-DELIVERY'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'checked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail-view3d' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-VIEW3D'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_detail',
					'hidden' => !IsModuleInstalled('yenisite.bitronic3dmodel'),
				),
				'block_detail-gift-products' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => self::$gifts ? 'Y' : 'N',
					'default_MOBILE' => self::$gifts ? 'Y' : 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-GIFT-PRODUCTS'),
					'fieldset' => 'page_detail',
					'hidden' => !self::$gifts,
				),
				'block_detail-gift-main-products' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => self::$gifts ? 'Y' : 'N',
					'default_MOBILE' => self::$gifts ? 'Y' : 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-GIFT-MAIN-PRODUCTS'),
					'fieldset' => 'page_detail',
					'hidden' => !self::$gifts,
				),
				'detail_catchbuy_slider' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_CATCHBUY_SLIDER'),
					'fieldset' => 'page_detail',
					'hidden' => self::$lite,
				),
				'block_detail_print' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_PRINT'),
					'fieldset' => 'page_detail',
				),
				'block_detail_price_updated' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_PRICE_UPDATED'),
					'fieldset' => 'page_detail',
				),
				'block_detail_review' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_REVIEW'),
					'fieldset' => 'page_detail',
				),
				'block_detail_short_info_under_image' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_SHORT_INFO_UNDER_IMAGE'),
					'fieldset' => 'page_detail',
				),
				'block_detail_feedback' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_FEEDBACK'),
					'fieldset' => 'page_detail',
				),
				'block_detail_socials' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_SOCIALS'),
					'fieldset' => 'page_detail',
				),
				'block_detail_gamification' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_GAMIFICATION'),
					'fieldset' => 'page_detail',
				),
				'block_detail_brand' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_BRAND'),
					'fieldset' => 'page_detail',
				),
                'block_detail_item_reviews' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_ITEM_REVIEWS'),
                    'fieldset' => 'page_detail',
                ),
                'block_detail_item_complects' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'Y',
                    'hidden' => self::$lite,
                    'name' => GetMessage('BTIRONIC2_SETTING_DETAIL_ITEM_COMPLECTS'),
                    'fieldset' => 'page_detail',
                ),
				'catalog_catchbuy_slider' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BTIRONIC2_SETTING_CATALOG_CATCHBUY_SLIDER'),
					'fieldset' => 'page_section',
					'hidden' => self::$lite,
				),
				'block_list-view-block' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'name' => GetMessage('BITRONIC2_SETTING_LIST-VIEW-BLOCK'),
					'fieldset' => 'page_section',
				),
				'block_list-view-list' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_LIST-VIEW-LIST'),
					'fieldset' => 'page_section',
				),
				'block_list-view-table' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_LIST-VIEW-TABLE'),
					'fieldset' => 'page_section',
				),
				'block_list-sub-sections' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_LIST-SUB-SECTIONS'),
					'fieldset' => 'page_section',
				),
				'block_list-section-desc' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'Y',
					'name' => GetMessage('BITRONIC2_SETTING_LIST-SECTION-DESC'),
					'fieldset' => 'page_section',
				),
				'block_list-hits' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_LIST-HITS'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_section',
					'hidden' => self::$lite,
				),
				'table-units-col' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array(
						'enabled',
						'disabled'
					),
					'default' => self::$lite ? 'disabled' : 'enabled',
					'default_MOBILE' => self::$lite ? 'disabled' : 'enabled',
					'name' => GetMessage('BITRONIC2_SETTING_TABLE_UNITS_COL'),
					'fieldset' => 'page_section',
					'preview' => false,
					'hidden' => self::$lite,
				),
                'use_lvl_first' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING_USE_LVL_FIRST'),
                    'fieldset' => 'page_section',
				),
                'use_reviews' => array(
                    'type' => 'CHECKBOX_MOBILE',
                    'group' => 'blocks',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'default_MOBILE' => 'N',
                    'name' => GetMessage('BITRONIC2_SETTING_USE_REVIEWS'),
                    'fieldset' => 'page_section',
				),
				'block_basket-gift-products' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => self::$gifts ? 'Y' : 'N',
					'default_MOBILE' => self::$gifts ? 'Y' : 'N',
					'name' => GetMessage('BITRONIC2_SETTING_BASKET-GIFT-PRODUCTS'),
					'fieldset' => 'page_basket',
					'hidden' => !self::$gifts,
				),
				'block_search-viewed' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-VIEWED'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_search',
					'hidden' => self::$lite,
				),
				'block_search-bestseller' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING-BESTSELLER'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_search',
					'hidden' => self::$lite,
				),
				'block_search-recommend' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING-RECOMMEND'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_search',
					'hidden' => self::$lite,
				),
				'block_404-viewed' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING_DETAIL-VIEWED'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_404',
					'hidden' => self::$lite,
				),
				'block_404-bestseller' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'N',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING-BESTSELLER'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_404',
					'hidden' => self::$lite,
				),
				'block_404-recommend' => array(
					'type' => 'CHECKBOX_MOBILE',
					'group' => 'blocks',
					'values' => array('Y', 'N'),
					'default' => 'Y',
					'default_MOBILE' => 'N',
					'name' => GetMessage('BITRONIC2_SETTING-RECOMMEND'),
					'states'=> array(
						'mobile'=> array(
							'state' => 'disabled',
							'status' => 'unchecked'
						)
					),
					'fieldset' => 'page_404',
					'hidden' => self::$lite,
				),
                'use_google_captcha' => array(
                    'type' => 'CHECKBOX',
                    'header' => GetMessage('BTIRONIC2_SETTING_USE_GOOGLE_CAPTCHA_TITTLE'),
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'N',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_USE_GOOGLE_CAPTCHA_NAME'),
                ),
                'show_google_captcha_in_auth' => array(
                    'type' => 'CHECKBOX',
                    'header' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_TITLE'),
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_AUTH'),
                ),
                'captcha-registration' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_REGISTRATION'),
                ),
                'captcha-callme' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_CALL_ORDER'),
                ),
                'captcha-when-in-stock' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_CALL_ARIVE_ITEM'),
                    'hidden' => self::$lite,
                ),
                'captcha-when-price-drops' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_CALL_LOWER_PRICE'),
                    'hidden' => self::$lite,
                ),
                'captcha-cry-for-price' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_FOUND_LOWER'),
                    'hidden' => self::$lite,
                ),
                'feedback-for-item-on-detail' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_FEEDBACK_ITEM_DETAIL'),
                ),
                'captcha-quick-buy' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_BUY_IN_ONE_CLICK'),
                    'hidden' => self::$lite,
                ),
                'captcha-feedback' => array(
                    'type' => 'CHECKBOX',
                    'group' => 'captcha',
                    'values' => array('Y', 'N'),
                    'default' => 'Y',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_FEEDBACK'),
                ),
                'captcha-link' => array(
                    'type' => 'LINK',
                    'group' => 'captcha',
                    'full-width' => true,
                    'name' => GetMessage('BTIRONIC2_SETTING_CAPTCHA_LINK'),
                    'href' => BX_ROOT.'/admin/captcha.php?lang=ru',
                ),
                'order-sBigSlider' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_BIG_SLIDER'),
                    'default' => '0',
                    'relative-from' => 'block_home-main-slider',

                ),
                'order-sHurry' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_HURRY'),
                    'hidden' => self::$lite,
                    'default' => '1',
                    'relative-from' => 'block_home-catchbuy'
                ),
                'order-sBannerTwo' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_BANNER_TWO'),
                    'default' => '2',
                    'relative-from' => 'block_show_ad_banners'
                ),
                'order-sCoolSlider' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_COOL_SLIDER'),
                    'default' => '3',
                    'relative-from' => 'block_home-cool-slider'
                ),
                'order-sBannerOne' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_BANNER_ONE'),
                    'default' => '4',
                    'relative-from' => 'block_show_ad_banners'
                ),
                'order-sCategories' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_CATEGORIES'),
                    'default' => '5',
                    'relative-from' => 'block_home-rubric'
                ),
                'order-sSpecialBlocks' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_SPECIAL_BLOCKS'),
                    'default' => '6',
                    'relative-from' => 'block_home-specials'
                ),
                'order-sAdvantage' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_ADVANTAGES'),
                    'default' => '7',
                    'relative-from' => 'block_home-our-adv'
                ),
                'order-sFeedback' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_FEEDBACK'),
                    'default' => '8',
                    'relative-from' => 'block_home-feedback'
                ),
                'order-sPromoBanners' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_PROMO_BANNERS'),
                    'default' => '9',
                    'relative-from' => 'block_show_ad_banners'
                ),
                'order-sContentNews' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'group' => 'drag_sort',
                    'drag-section' => 'home-page',
                    'name' => GetMessage('BTIRONIC2_DRAG_ACTION_NEWS'),
                    'default' => '10',
                    'relative-from' => array('block_home-actions','block_home-news')
                ),
                'order-sContentAbout' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_CONTENT_ABOUT'),
                    'default' => '11',
                ),
                'order-sContentBrands' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_CONTENT_BRAND'),
                    'default' => '12',
                    'relative-from' => 'block_home-brands'
                ),
                'order-sContentNetwork' => array(
                    'type' => 'DRAG',
                    'type-block' => 'main',
                    'drag-section' => 'home-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DRAG_CONTENT_NETWORK'),
                    'default' => '13',
                    'relative-from' => array('block_home-flmp','block_home-tw','block_home-fb','block_home-ok','block_home-vk'),
                    'end-section' => true
                ),
                'order-sPrInfDescription' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_DESCRIPTION'),
                    'default' => '0',
                    'relative-from' => '',
                    'desc' => GetMessage('BTIRONIC2_DETAIL_DESC')

                ),
                'order-sPrInfCharacteristics' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_HARACTERISTICS'),
                    'default' => '1',
                    'relative-from' => '',

                ),
                'order-sPrInfComments' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_INF_COMMENTS'),
                    'default' => '2',
                    'relative-from' => ''
                ),
                'order-sPrInfVideos' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_INF_VIDEOS'),
                    'default' => '3',
                    'relative-from' => ''
                ),
                'order-sPrInfDocumentation' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_INF_DOCS'),
                    'default' => '4',
                    'relative-from' => ''
                ),
                'order-sPrInfAvailability' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_INF_AVAILABILITY'),
                    'default' => '5',
                    'relative-from' => '',
                    'hidden' => self::$lite
                ),
                'order-sPrInfReview' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_INF_REVIEW'),
                    'default' => '6',
                    'relative-from' => array('block_detail_item_reviews'),
                    'end-section' => true,
                    'not-close-big-div' => true
                ),

                'order-sPrModifications' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_MODIFICATIONS'),
                    'default' => '0',
                    'relative-from' => '',
                    'hide-title' => true,
                    'not-open-big-div' => true
                ),
                'order-sPrCollection' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_COLLECTION'),
                    'default' => '1',
                    'relative-from' => '',
                    'hidden' => self::$lite
                ),
                'order-sPrBannerOne' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_BANNER'),
                    'default' => '2',
                    'relative-from' => ''
                ),
                'order-sPrSimilarView' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_SIMILAR_VIEW'),
                    'default' => '3',
                    'relative-from' => 'block_detail-similar-view',
                    'hidden' => self::$lite
                ),
                'order-sPrSimilar' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_SIMILAR'),
                    'default' => '4',
                    'relative-from' => 'block_detail-similar',
                    'hidden' => self::$lite
                ),
                'order-sPrSimilarProducts' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_PR_SIMILAR_PRODUCTION'),
                    'default' => '5',
                    'relative-from' => 'block_detail-similar-price'
                ),
                'order-sPrBannerTwo' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_BANNER_TWO'),
                    'default' => '6',
                    'relative-from' => ''
                ),
                'order-sPrViewedProducts' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_VIEWED_PRODUCTS'),
                    'default' => '7',
                    'relative-from' => 'block_detail-viewed',
                    'hidden' => self::$lite
                ),
                'order-sPrGiftProducts' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_GIFT_PRODUCTS'),
                    'default' => '8',
                    'relative-from' => 'block_detail-gift-products',
                    'hidden' => self::$lite
                ),
                'order-sPrRecommended' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_RECOMMENDED'),
                    'default' => '10',
                    'relative-from' => 'block_detail-recommended',
                    'hidden' => self::$lite,
                ),
                'order-sPrBannerThird' => array(
                    'type' => 'DRAG',
                    'type-block' => 'product',
                    'drag-section' => 'product-page',
                    'group' => 'drag_sort',
                    'name' => GetMessage('BTIRONIC2_DETAIL_PR_BANNER_THIRD'),
                    'default' => '9',
                    'relative-from' => '',
                    'end-section' => true,
                ),

			);

			if (CModule::IncludeModule('yenisite.pricegen')) {
				unset(self::$_settings['block_pricelist']['states']);
			}

			if (self::isPro()) {
				$bGeoip = self::isPro(1);
				$arProGroups = array(
					'pro' => array(
						'sort' => 700,
						'name' => GetMessage('BITRONIC2_GROUP_PRO'),
					),
				);
				$arProFieldsets = array();
				$arProSettings = array(
					'geoip_unite' => array(
						'type' => 'RADIO',
						'name' => GetMessage('BITRONIC2_SETTINGS_GEOIP-UNITE'),
						'group' => 'pro',
						'values' => array('Y', 'N'),
						'names' => array(
							'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
						),
						'default' => 'N',
						'hidden' => !$bGeoip
					),
					'change_contacts' => array(
						'type' => 'RADIO',
						'name' => GetMessage('BITRONIC2_SETTINGS_CHANGE_CONTACTS'),
						'group' => 'pro',
						'values' => array('Y', 'N'),
						'names' => array(
							'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
						),
						'default' => 'N',
						'hidden' => !$bGeoip
					),
					'geoip_currency' => array(
						'type' => 'RADIO',
						'name' => GetMessage('BITRONIC2_SETTINGS_GEOIP_CURRENCY'),
						'group' => 'pro',
						'values' => array('Y', 'N'),
						'names' => array(
							'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'N' => GetMessage('BITRONIC2_SETTING_VALUE_N'),
						),
						'default' => 'Y',
						'hidden' => !$bGeoip
					),
					'additional-prices-enabled' => array(
						'type' => 'RADIO',
						'group' => 'pro',
						'values' => array(
							'true',
							'false'
						),
						'names' => array(
							'true' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'false' => GetMessage('BITRONIC2_SETTING_VALUE_N')
						),
						'default' => 'false',
						'name' => GetMessage('BITRONIC2_SETTING_ADDITIONAL-PRICES-ENABLED'),
						//'fieldset' => 'page_common',
						'preview' => true
					),
					'extended-prices-enabled' => array(
						'type' => 'RADIO',
						'group' => 'pro',
						'values' => array('Y', 'N'),
						'names' => array(
							'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'N' => GetMessage('BITRONIC2_SETTING_VALUE_N')
						),
						'default' => 'N',
						'name' => GetMessage('BITRONIC2_SETTING_EXTENDED-PRICES-ENABLED'),
						//'fieldset' => 'page_common',
						'preview' => false
					),
					'pro_vbc_bonus' => array(
						'type' => 'RADIO',
						'group' => 'pro',
						'values' => array('Y', 'N'),
						'names' => array(
							'Y' => GetMessage('BITRONIC2_SETTING_VALUE_Y'),
							'N' => GetMessage('BITRONIC2_SETTING_VALUE_N')
						),
						'default' => 'Y',
						'name' => GetMessage('BITRONIC2_SETTING_PRO_VBC_BONUS'),
						'preview' => false,
						'hidden' => true
					)
				);
				if (!Loader::includeModule('vbcherepanov.bonus')) {
					$arProSettings['pro_vbc_bonus']['state'] = 'disabled';
					$arProSettings['pro_vbc_bonus']['default'] = 'N';
					$arProSettings['pro_vbc_bonus']['title'] = GetMessage(
						'BITRONIC2_SETTING_TITLE_EXTERNAL_MODULE',
						array('#MODULE_ID#' => 'vbcherepanov.bonus')
					);
				}
				self::$_groups = array_merge(self::$_groups, $arProGroups);
				self::$_settings = array_merge(self::$_settings, $arProSettings);
				self::$_fieldsets = array_merge(self::$_fieldsets, $arProFieldsets);
			}

			foreach (
				array(
					'block_home-ok' => 'yenisite.okgroup',
					'block_home-fb' => 'yenisite.fblikebox',
					'block_home-tw' => 'yenisite.twittertimelines',
					'block_home-flmp' => 'yenisite.fpcomments',
					'block_home-inst' => 'romza.widgetinstagram'
				) as $setting => $module
			) {
				if (Loader::includeModule($module)) continue;
				self::disableCheckboxMobileByModule($setting, $module);
			}
			if (!IsModuleInstalled('primepix.vkontakte')) {
				self::disableCheckboxMobileByModule('block_home-vk', 'primepix.vkontakte');
			}
			if (
				!IsModuleInstalled('edost.catalogdelivery') ||
				version_compare(\Yenisite\Core\Tools::getModuleVersion('edost.catalogdelivery'), '2.0.0') < 0
			) {
				self::disableCheckboxMobileByModule('block_detail-delivery', 'edost.catalogdelivery');
			}

			self::$init = true;
		}
	}

	/**
	 * Disable setting of CHECKBOX_MOBILE type because of not installed module
	 *
	 * @param string $setting - Key of setting to disable
	 * @param string $moduleId - Not installed module ID
	 */
	private static function disableCheckboxMobileByModule($setting, $moduleId)
	{
		$arSetting =& self::$_settings[$setting];
		$arSetting['default'] = 'N';
		$arSetting['default_MOBILE'] = 'N';
		$arSetting['states'] = array(
			'desktop' => array(
				'state'  => 'disabled',
				'status' => 'unchecked'
			),
			'mobile' => array(
				'state'  => 'disabled',
				'status' => 'unchecked'
			)
		);
		$arSetting['title'] = GetMessage(
			'BITRONIC2_SETTING_TITLE_MODULE_NOT_INSTALLED',
			array('#MODULE_ID#' => $moduleId)
		);
		$arSetting['values'] = array('N', 'N');
		unset($arSetting);
	}

	/**
	 * Return module id
	 */
	public static function getModuleId()
	{
		return self::$_module;
	}

	public static function getEdition() {
		switch (self::$_module) {
			case substr(self::$_module, -3) == 'pro':
				return 'PRO';
			case substr(self::$_module, -4) == 'lite':
				return 'LITE';
			default:
				return 'MASTER';
		}
	}

	public static function isPro($withGeoip = false, $siteId = false)
	{
		global $RZ_SITE;
		if (!$siteId) {
			 $siteId = SITE_ID;
		}
		if ( $siteId == LANGUAGE_ID
		&& !empty($_REQUEST['src_site'])
		&& strlen($_REQUEST['src_site']) == 2)
		{
			$siteId = $_REQUEST['src_site'];
		}
		if (!is_array($RZ_SITE)) $RZ_SITE = array();
		return ((self::getEdition() == 'PRO' || $RZ_SITE[$siteId] == 'PRO') && (!$withGeoip ?: Loader::includeModule('yenisite.geoipstore')));
	}

	private function setSettingsName() {
		foreach (self::$_settings as $key => $arItem) {
			if (empty(self::$_settings[$key]['name'])) {
				self::$_settings[$key]['name'] = GetMessage('BITRONIC2_SETTING_' . strtoupper($key));
			}
		}
	}

	private function setGroupsName() {
		foreach (self::$_groups as $key => $arItem) {
			if (empty(self::$_groups[$key]['name'])) {
				self::$_groups[$key]['name'] = GetMessage('BITRONIC2_GROUP_' . strtoupper($key));
			}
		}
	}

	/**
	 * Return all settings as described in array self::$_settings
	 */
	public static function getSettingsArray() {
		self::init();
		self::setSettingsName();
		return self::$_settings;
	}

	public static function getGroupsArray() {
		self::init();
		self::setGroupsName();
		return self::$_groups;
	}

	public static function getFieldsetArray() {
		self::init();
		return self::$_fieldsets;
	}

	/**
	 * Returns default setting (option) value
	 * @param string $option Setting (option) name
	 * @return string|bool
	 */
	public static function getDefaultValue($option)
	{
		foreach (self::$_settings as $key => $value) {
			if (strtolower($key) == strtolower($option)) {
				return $value['default'];
			}
		}
		return false;
	}

	public static function isCore(){
	    return Loader::includeModule('yenisite.core');
    }

    public static function checkEnebleSocialServices(){
	    global $rz_b2_options;

        foreach (
            array(
                'block_home-ok' => 'yenisite.okgroup',
                'block_home-fb' => 'yenisite.fblikebox',
                'block_home-tw' => 'yenisite.twittertimelines',
                'block_home-flmp' => 'yenisite.fpcomments',
                'block_home-inst' => 'romza.widgetinstagram'
            ) as $setting => $module
        ) {
            if ($rz_b2_options[$setting] == 'Y'){
                return true;
            };
        }
    }
}