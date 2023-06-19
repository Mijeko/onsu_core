// THIS IS TO BE SET ON SERVER
var arDefaults = {
	menuVisibleItems: 10,
    footmenuVisibleItems: 'all',
	actionOnBuy: 'animation-n-popup',
	backnavEnabled: false,
	topLinePosition: 'fixed-top',
	quickViewEnabled: false,
    socialsType: 'visible',
	langSwitchEnabled: false,
	currencySwitchEnabled: false,
	categoriesEnabled: true,
    categoriesView: 'list',
    categoriesWithSub: false,
    categoriesWithImg: false,
	stylingType: 'flat',
	colorTheme: 'red-flat',
	additionalPricesEnabled: false,
	colorThemeButton: 'white',
    mobilePhoneAction: 'callback',
	containerWidth: 'container',
	bigSliderWidth: 'full',
	catalogPlacement: 'top',
	filterPlacement: 'side',
	siteBackground: 'color',
	headerVersion: 'v1',
    sitenavType: 'all',
	bigSliderType: 'pro',
	hoverEffect: 'border-n-shadow',
	brandsViewType: 'carousel',
	coolsliderEnabled: true,
	coolsliderNamesEnabled: true,
	sbMode: 'full',
	sbModeDefExpanded: 1000,
	productInfoMode: 'full',
	productInfoModeDefExpanded: false,
    productAvailability: 'status',
	bigimgDesc: 'disabled',
	photoViewType: 'modal',
	menuHitsEnabled: 'true',
	wowEffect: 'N',
	bs_height: '28.30%',
	footerBG: "url('img/patterns/footer_lodyas.png')",
	limitSliders: true,
	detailTextDefault: 'close',

    dragSettingsHome: {
        "sBigSlider": 0,
        "sHurry": 1,
        "sBannerTwo": 2,
        "sCoolSlider": 3,
        "sBannerOne": 4,
        "sCategories": 5,
        "sSpecialBlocks": 6,
        "sAdvantage": 7,
        "sFeedback": 8,
        "sPromoBanners": 9,
        "sContentNews": 10,
        "sContentAbout": 11,
        "sContentBrands": 12,
        "sContentNetwork": 13,
    },

    dragSettingsProduct: {
        "sPrModifications": 1,
        "sPrCollection": 2,
        "sPrSimilarProducts": 3,
        "sPrBannerOne": 4,
		"sPrSimilarView" : 5,
		"sPrSimilar" : 6,
        "sPrBannerTwo": 7,
        "sPrViewedProducts": 8,
        "sPrGiftProducts": 9,
        "sPrBannerThird": 10,
        "sPrRecommended": 11,
    },

    dragSettingsProductInfo: {
        "sPrInfCharacteristics": 1,
        "sPrInfComments": 2,
        "sPrInfVideos": 3,
        "sPrInfDocumentation": 4,
        "sPrInfAvailability": 5,
        "sPrInfReview": 6,
    },

	isFrontend: true,

	// below are dynamic settings for slider control panel
	bs_curSettingsFor: 'all',
	bs_curBlock: 'media',
	bsMediaHAlign: 'center',
	bsMediaVAlign: 'bottom',
	bsMediaAnim: 'slideRightBig',
	bsMediaLimitsBottom: '0%',
	bsMediaLimitsLeft: '51%',
	bsMediaLimitsRight: '2%',
	bsMediaLimitsTop: '0%',
	bsTextHAlign: 'right',
	bsTextVAlign: 'center',
	bsTextAnim: 'slideLeftBig',
	bsTextLimitsBottom: '0%',
	bsTextLimitsLeft: '2%',
	bsTextLimitsRight: '51%',
	bsTextLimitsTop: '0%',
	bsTextTextAlign: 'left'
};
b2.s = (typeof serverSettings !== 'undefined') ? $.extend(arDefaults, serverSettings) : arDefaults;
b2.temp = $.extend({}, b2.s);

bs.defaults = {
	media: {
		'limits': {
			top: b2.s.bsMediaLimitsTop,
			right: b2.s.bsMediaLimitsRight,
			bottom: b2.s.bsMediaLimitsBottom,
			left: b2.s.bsMediaLimitsLeft
		},
		'v-align': b2.s.bsMediaVAlign,
		'h-align': b2.s.bsMediaHAlign,
		'anim': b2.s.bsMediaAnim,
	},
	text: {
		'limits': {
			top: b2.s.bsTextLimitsTop,
			right: b2.s.bsTextLimitsRight,
			bottom: b2.s.bsTextLimitsBottom,
			left: b2.s.bsTextLimitsLeft
		},
		'v-align': b2.s.bsTextVAlign,
		'h-align': b2.s.bsTextHAlign,
		'text-align': b2.s.bsTextTextAlign,
		'anim': b2.s.bsTextAnim
	},
}

bs.slides = [ // COMES FROM SERVER
	{ // 0
		media: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '51%'},
			'v-align': 'bottom',
		},
		text: {
			'limits': {top: '0%', right: '51%', bottom: '0%', left: '0%'},
			'h-align': 'right'
		},
	},
	{ // 1
		media: {
			'limits': {top: '0%', right: '51%', bottom: '0%', left: '0%'},
			'h-align': 'right'
		},
		text: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '51%'},
		},
	},
	{ // 2
		media: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '51%'},
			'v-align': 'bottom'
		},
		text: {
			'limits': {top: '0%', right: '51%', bottom: '0%', left: '0%'},
			'h-align': 'right'
		},
	},
	{ // 3
		media: {
			'limits': {top: '0%', right: '51%', bottom: '0%', left: '0%'},
			'h-align': 'right'
		},
		text: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '51%'},
		},
	},
	{ // 4
		media: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '0%'},
			// 'anim': 'whirl',
			'locked': true,
		},
	},
	{ // 5
		media: {
			'limits': {top: '0%', right: '12%', bottom: '0%', left: '51%'},
			'v-align': 'top',
			// 'anim': 'fade',
			'locked': true,
		},
		text: {
			'limits': {top: '0%', right: '51%', bottom: '0%', left: '0%'},
			'h-align': 'right'
		}
	},
	{ // 6
		media: {
			'limits': {top: '0%', right: '0%', bottom: '0%', left: '0%'},
			'v-align': 'center',
			// 'anim': 'whirl',
			'locked': true,
		},
	}
]