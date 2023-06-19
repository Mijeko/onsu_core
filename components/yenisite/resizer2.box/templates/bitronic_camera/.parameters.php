<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
    $arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arOpacity = array('0' => '0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5', '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1' => '1');

$arPiePos = array(
    'rightTop' => GetMessage('RIGHT_TOP'),
    'leftTop'  => GetMessage('LEFT_TOP'),
    'leftBottom'  => GetMessage('LEFT_BOTTOM'),
    'rightBottom' => GetMessage('RIGHT_BOTTOM')
);

$arBarPos = array(
    'left'   => GetMessage('LEFT'),
    'right'  => GetMessage('RIGHT'),
    'top'    => GetMessage('TOP'),
    'bottom' => GetMessage('BOTTOM')
);

$arTime = array(
    '2000' => '2',
    '3000' => '3',
    '4000' => '4',
    '5000' => '5',
    '6000' => '6',
    '7000' => '7',
    '8000' => '8',
    '9000' => '9',
    '10000' => '10'
);

$arTrans = array(
    '500' => '0.5',
    '1000' => '1.0',
    '1500' => '1.5',
    '2000' => '2.0',
    '2500' => '2.5',
    '3000' => '3.0'
);

$arLoader = array(
    'pie'  => GetMessage('PIE'),
    'bar'  => GetMessage('BAR'),
    'none' => GetMessage('NONE')
);

$arEffect = array(
    'random'     => 'random',
    'simpleFade' => 'simpleFade',
    'curtainTopLeft'     => 'curtainTopLeft',
    'curtainTopRight'    => 'curtainTopRight',
    'curtainBottomLeft'  => 'curtainBottomLeft',
    'curtainBottomRight' => 'curtainBottomRight',
    'curtainSliceLeft'   => 'curtainSliceLeft',
    'curtainSliceRight'  => 'curtainSliceRight',
    'blindCurtainTopLeft'     => 'blindCurtainTopLeft',
    'blindCurtainTopRight'    => 'blindCurtainTopRight',
    'blindCurtainBottomLeft'  => 'blindCurtainBottomLeft',
    'blindCurtainBottomRight' => 'blindCurtainBottomRight',
    'blindCurtainSliceBottom' => 'blindCurtainSliceBottom',
    'blindCurtainSliceTop'    => 'blindCurtainSliceTop',
    'stampede' => 'stampede',
    'mosaic'   => 'mosaic',
    'mosaicReverse' => 'mosaicReverse',
    'mosaicRandom'  => 'mosaicRandom',
    'mosaicSpiral'  => 'mosaicSpiral',
    'mosaicSpiralReverse' => 'mosaicSpiralReverse',
    'topLeftBottomRight'  => 'topLeftBottomRight',
    'bottomRightTopLeft'  => 'bottomRightTopLeft',
    'bottomLeftTopRight'  => 'bottomLeftTopRight',
    'scrollLeft'   => 'scrollLeft',
    'scrollRight'  => 'scrollRight',
    'scrollHorz'   => 'scrollHorz',
    'scrollBottom' => 'scrollBottom',
    'scrollTop'    => 'scrollTop'
);

$arSkin = array(
    'default'   => GetMessage('DEFAULT'),
    'beige'     => GetMessage('BEIGE'),
    'white'     => GetMessage('WHITE'),
    'turquoise' => GetMessage('TURQUOISE'),
    'burgundy'  => GetMessage('BURGUNDY'),
    'yellow'    => GetMessage('YELLOW'),
    'green'     => GetMessage('GREEN'),
    'gold'      => GetMessage('GOLD'),
    'indigo'    => GetMessage('INDIGO'),
    'brown'     => GetMessage('BROWN'),
    'coffee'    => GetMessage('COFFEE'),
    'red'       => GetMessage('RED'),
    'maroon'    => GetMessage('MAROON'),
    'azure'     => GetMessage('AZURE'),
    'lime'      => GetMessage('LIME'),
    'tangerine' => GetMessage('TANGERINE'),
    'olive'     => GetMessage('OLIVE'),
    'orange'    => GetMessage('ORANGE'),
    'ash'       => GetMessage('ASH'),
    'pink'      => GetMessage('PINK'),
    'grey'      => GetMessage('GREY'),
    'blue'      => GetMessage('BLUE'),
    'charcoal'  => GetMessage('CHARCOAL'),
    'violet'    => GetMessage('VIOLET'),
    'pistachio' => GetMessage('PISTACHIO'),
    'fuchsia'   => GetMessage('FUCHSIA'),
    'magenta'   => GetMessage('MAGENTA'),
    'khaki'     => GetMessage('KHAKI'),
    'cyan'      => GetMessage('CYAN'),
    'black'     => GetMessage('BLACK'),
    'chocolate' => GetMessage('CHOCOLATE'),
    'amber'     => GetMessage('AMBER')
);


$arTemplateParameters = array(
    'SET_BIG_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_BIG_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet,
        'DEFAULT' => '1'
    ),

    'SET_SMALL_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_SMALL_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet,
        'DEFAULT' => '6'
    ),
    
    'SHOW_DESCRIPTION' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('SHOW_DESCRIPTION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'EFFECT' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('EFFECT'),
        'TYPE' => 'LIST',
        'VALUES' => $arEffect,
        'MULTIPLE' => 'Y',
        'SIZE' => 8,
        'DEFAULT' => 'random'
    ),
    
    'SKIN' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('SKIN'),
        'TYPE' => 'LIST',
        'VALUES' => $arSkin,
        'DEFAULT' => 'default'
    ),
    
    'PLAY_PAUSE' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('PLAY_PAUSE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'NAVIGATION' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('NAVIGATION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),

    'NAVIGATION_HOVER' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('NAVIGATION_HOVER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),

    'PAUSE_ON_CLICK' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('PAUSE_ON_CLICK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'HOVER' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('HOVER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'THUMB' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('THUMB'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'PAGINATION' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('PAGINATION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ),
     
    'TIME' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('TIME'),
        'TYPE' => 'LIST',
        'VALUES' => $arTime,
        'DEFAULT' => '7000'
    ),
     
    'TRANS_PERIOD' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('TRANS_PERIOD'),
        'TYPE' => 'LIST',
        'VALUES' => $arTrans,
        'DEFAULT' => '1500'
    ),
     
    'LOADER' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('LOADER'),
        'TYPE' => 'LIST',
        'VALUES' => $arLoader,
        'REFRESH' => 'Y',
        'DEFAULT' => 'pie'
    )
);

if (empty($arCurrentValues['LOADER'])) {
	$bLoader = ($arTemplateParameters['LOADER']['DEFAULT'] != 'none');
	$bPie    = ($arTemplateParameters['LOADER']['DEFAULT'] == 'pie');
	$bBar    = ($arTemplateParameters['LOADER']['DEFAULT'] == 'bar');
} else {
	$bLoader = ($arCurrentValues['LOADER'] != 'none');
	$bPie    = ($arCurrentValues['LOADER'] == 'pie');
	$bBar    = ($arCurrentValues['LOADER'] == 'bar');
}

if ($bLoader) {
    $arTemplateParameters += array(
        'LOADER_COLOR' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('LOADER_COLOR'),
            'TYPE' => 'COLORPICKER',
            'DEFAULT' => 'EEEEEE'
        ),
        
        'LOADER_BG_COLOR' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('LOADER_BG_COLOR'),
            'TYPE' => 'COLORPICKER',
            'DEFAULT' => '222222'
        ),
        
        'LOADER_OPACITY' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('LOADER_OPACITY'),
            'TYPE' => 'LIST',
            'VALUES' => $arOpacity,
            'DEFAULT' => '0.8'
        )
    );
}

if ($bPie) {
    $arTemplateParameters += array(
        'PIE_POSITION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('LOADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => $arPiePos,
            'DEFAULT' => 'rightTop'
        )
    );
}

if ($bBar) {
    $arTemplateParameters += array(
        'BAR_POSITION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('LOADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => $arBarPos,
            'REFRESH' => 'Y',
            'DEFAULT' => 'bottom'
        ),
        
        'BAR_DIRECTION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('BAR_DIRECTION'),
            'TYPE' => 'LIST'
        )
    );
    
    if($arCurrentValues['BAR_POSITION'] == 'left'
    || $arCurrentValues['BAR_POSITION'] == 'right')
    {
        $arBarDir = array(
            'topToBottom' => GetMessage('TOP_TO_BOTTOM'),
            'bottomToTop' => GetMessage('BOTTOM_TO_TOP')
        );
        $default = 'topToBottom';
    } else {
        $arBarDir = array(
            'leftToRight' => GetMessage('LEFT_TO_RIGHT'),
            'rightToLeft' => GetMessage('RIGHT_TO_LEFT')
        );
        $default = 'leftToRight';
    }
    
    $arTemplateParameters['BAR_DIRECTION']['VALUES'] = $arBarDir;
    $arTemplateParameters['BAR_DIRECTION']['DEFAULT'] = $default;
}

?>
