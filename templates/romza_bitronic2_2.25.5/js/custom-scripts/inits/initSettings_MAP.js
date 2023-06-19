/*
 SETTINGS SUMMARY
 Все настройки являются свойствами глобального объекта b2.s:
 b2.s.<имя настройки в camelCase>
 Название настройки ОБЯЗАНО совпадать с атрибутом name соответствующего инпута,
 приведенным к camelCase ('action-on-buy' -> actionOnBuy)

 При загрузке страницы объект настроек должен полностью инициализироваться с сервера,
 см. начало файла initSettings.js

 На изменение любого свойства срабатывает обработчик события 'set' на объекте b2.s,
 которому передаются параметры [name, value, setInput]:
 0. Если новое значение совпадает с текущим, ничего дальше не выполняется.
 1. Соответствующее свойство объекта перезаписывается новым
 2. Каждое измененное свойство и его значение записывается в b2.changedS. При этом, если
 новое значение совпадает со значением на момент открытия модалки (хранится в b2.initialS),
 то такое свойтсво из b2.changedS удаляется. Таким образом, в b2.changedS всегда находится
 актуальный список измененных свойств.
 3. Ищется DOM-элемент по атрибуту [data-<name>] и если находится - в атрибут записывается
 новое значение
 4. Если byInput не установлен, значит событие вызвано НЕ изменением инпута (а, например,
 вручную или закрытием модалки без сохранения), и в этом случае следует установить
 соответствующий инпут в новое положение

 5.1 Проверяется, есть ли соответствующая изменному свойству функция b2.set, и вызывается,
 если есть. Эти функции специфичны для каждого свойства и потому описываются отдельно, см.
 файл settingsHelpers.js. Здесь прописываются дополнительные действия, которые необходимо
 выполнить при изменении конкретно этого свойства.
 5.2 Проверяется, существует ли соответствующий свойству массив в b2.rel, и если да, то
 выполняется проход по массиву с вызовом setRelated. Массивы задаются в settingsRelated.js.
 Это обработка зависимостей - в массивах задаются зависимые от текущего свойства элементы
 и действия с ними в зависимости от нового значения.

 При открытии модалки создается снэпшот текущих настроек - b2.initialS, а также обнуляется
 объект измененных настроек - b2.changedS, и флаг saving сбрасывается в false.
 При попытке отправить форму флаг saving ставится в true ("значения сохраняются"), чтобы
 при закрытии модалки не произошло сброса настроек.
 При закрытии модалки, соответственно, проверяется этот флаг, и если false, то происходит
 сброс настроек к сохраненным в b2.initialS с возвращением исходных состояний инпутов
 На всякий случай при закрытии модалки также сбрасывается флаг saving (после его проверки,
 разумеется).


 // SETTINGS MAP
 структура:
 {<name> <camelCase name>
 [список значений] - дефолтное ставится первым. Если это инпут - чекбокс, то у параметра
 значения всегда false / true, а у самого чекбокса обязательно ставится value="checkbox"

 связанный DOM-элемент. Если иное не указано, то DOM-элемент ищется по атрибуту
 'data-<name> и значение этого атрибута устанавливается
 в текущее значение настройки}

 ==== MAP START ====
 {footmenu-visible-items footmenuVisibleItems
 5 6 7 8 9 10 все}
 {menu-visible-items menuVisibleItems
 5 6 7 8 9 10
 при изменении вызывается menu.updateVisible(this.val)}
 {action-on-buy actionOnBuy
 'animation-n-popup' 'open-modal-basket' 'go-to-big-basket'
 -}
 {backnav-enabled backnavEnabled
 false true
 b2.el.$bcrumbs = $('.breadcrumbs')}
 {top-line-position topLinePosition
 'fixed-top' 'fixed-bottom' 'fixed-left' 'fixed-right' 'not-fixed'
 $body = $('body')}
 {quick-view-enabled quickViewEnabled
 false true
 $('.catalog')}
 {socials-type socialsType
 visible hidden
 $('.product-page .socials')}
 {lang-switch-enabled langSwitchEnabled
 false true
 $('#lang-switch')}
 {currency-switch-enabled currencySwitchEnabled
 false true
 $('#currency-switch')}
 {categories-enabled categoriesEnabled
 true false
 $('#categories')}
 {categories-view categoriesView
 list: рубрикатор категорий на главной с скроллом
 blocks: рубрикатор категорий на главной в виде сетки }
 {categories-with-sub categoriesWithSub
 false: рубрикатор категорий с скроллом и списком категорий
 true: рубрикатор категорий с скроллом и списком категорий + подкатегорий }
 {categories-with-img categoriesWithImg
 false: рубрикатор категорий в виде сетки с списком категорий + подкатегорий
 true: рубрикатор категорий в виде сетки фотографий категорий }
 {styling-type stylingType
 'flat' 'skew'
 $('#theme-demos-wrap')}
 {additional-prices-enabled additionalPricesEnabled
 false true
 $body = $('body')}
 {color-theme-button colorThemeButton
 'white' 'black'	}
 {mobile-phone-action mobilePhoneAction
 callback : (для мобильных) при нажатии на иконку телефона вызывается форма "заказать звонок"
 calling : (для мобильных) при нажатии на иконку телефона происходит набор номера для звонка }
 {container-width containerWidth
 fixed : все .container-fluid меняются на .container
 fluid : все .container меняются на .container-fluid	}
 {big-slider-width bigSliderWidth
 'full' 'normal' 'narrow'
 $('#big-slider-wrap')
 особенность: эта настройка должна быть ПЕРЕД catalog-placement}
 {catalog-banner-pos catalogBannerPos
 'middle-to-top':
 если количество строк не четное, то баннер в каталоге помещается на 1 строку ближе к началу каталога товаров
 'middle-to-bottom':
 если количество строк не четное, то баннер в каталоге помещается на 1 строку ближе к концу каталога товаров}
 {catalog-placement catalogPlacement
 'top' 'side'
 b2.el.$menu = $('#mainmenu') переносится в '#catalog-at-'+this.val
 после переноса вызывается метод обновления меню - b2.el.menu.updateState()
 особенность: эта настройка должна быть ПОСЛЕ big-slider-width
 'top':
 радиобаттон ширины слайдера "narrow" -> disabled
 если bigSliderWidth === "narrow", установить в normal
 радиобаттоны ширины слайдера "normal" и "full" -> enabled
 'side':
 радиобаттоны ширины слайдера "normal" и "full" -> disabled
 радиобаттон ширины слайдера "narrow" -> enabled
 если bigSliderWidth !== "narrow", сделать его narrow}
 {catalog-darken catalogDarken
 'no' 'yes'
 'no':
 не использовать затемнение области вокруг меню при наведении на подпункты меню
 или при нажатии на пункты меню для тачевых устройств
 'yes':
 использовать затемнение области вокруг меню при наведении на подпункты меню
 или при нажатии на пункты меню для тачевых устройств}
 {filter-placement filterPlacement
 'side' 'top'
 $('#form-filter') переносится в '#filter-at-'+this.val}
 {site-background siteBackground
 'color' 'pattern' 'gradient' 'image'
 прячутся все .site-background, затем отображается [data-option="'+this.val+'"] из них}
 {header-version headerVersion
 'v1' 'v2' 'v3' 'v4'
 $('header.page-header')
 перед сменой значения проверяется - если новое значение v3, то меняются классы
 у главного меню и меняется catalog-placement. А если новое не v3, но предыдущее
 было v3, то производится обратная процедура. Да, такой у нас вредный v3. После обоих
 процедур вызывается метод обновления меню}
 {sitenav-type sitenavType
 $('#sitenav .sitenav-menu')
 дополнительное меню в шапке
 'all' - вывести все пункты меню вне зависимости от количества получаемых строк
 'collapse' - вывести пункты влазящие в 1 строку, остальные поместить в свернутый список}
 {big-slider-type bigSliderType
 'pro' 'normal' 'disabled'
 тут куча зависимостей по настройкам слайдера, а поскольку слайдер еще нужно рефакторить,
 то не стал документировать.}
 {hover-effect hoverEffect
 'border-n-shadow' 'detailed-expand'
 $('.catalog')}
 {brands-view-type brandsViewType
 'carousel' 'tags'
 $('.brands-wrap')}
 {coolslider-enabled coolsliderEnabled
 true false
 b2.el.$coolSlider = $('#cool-slider')}
 {coolslider-names-enabled coolsliderNamesEnabled
 true false
 b2.el.$coolSlider = $('#cool-slider')}
 {sb-mode sbMode
 'tabs' 'full'
 b2.el.$specialBlocks = $('#special-blocks')
 вызывается метод switchMode(this.val) у b2.el.specialBlocks}
 {sb-mode-def-expanded sbModeDefExpanded
 false true <чекбокс!>}
 {product-info-mode productInfoMode
 'full' 'tabs'
 b2.el.$productInfoSections = $('#product-info-sections')
 вызывается метод switchMode(this.val) у b2.el.productInfoSections}
 {product-info-mode-def-expanded productInfoModeDefExpanded
 false true <чекбокс!>}
 {product-availability productAvailability
 информация по наличию на складах выводится:
 - 'status': "в плашке статуса" в фикс. блоке "buy-block"
 - 'expanded': "развернуто" в фикс. блоке "buy-block"
 - 'tabs': "в отдельной вкладке" в блоке "product-info-sections"}
 {availability-view-type availabilityViewType
 вид отображения остатка по складам:
 - 'graphic': "графически"
 - 'text': "текстом"
 - 'numeric': "цифрами"
 }
 {big-slider-height bigSliderHeight
 float% - проценты от ширины
 DOM: устанавливаются проценты padding-bottom у большого слайдера и dummy-слайдера}
 {bigimg-desc bigimgDesc
 'disabled' 'top' 'bottom'
 $('.bigimg-wrap')}
 {photo-view-type photoViewType
 'modal 'zoom'
 серьезная зависимость по объектам и событиям, смотреть helper-функцию
 b2.set.photoViewType()}
 ===== MAP END =====
 */