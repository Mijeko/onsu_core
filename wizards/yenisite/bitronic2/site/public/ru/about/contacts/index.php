<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
    <main class="container about-page">
        <div class="row">
            <div class="col-xs-12">
                <h1><?$APPLICATION->ShowTitle()?></h1>


                <div>
                    <b>Москва</b>
                    <div><b>ул. Пушкина 19</b></div>
                    <div><b>E-mail: sale@test.loc</b></div>
                    <div><b>тел. 8-800-777-9999</b></div>
                </div>

                <div>
                    <h2>Банковские реквизиты</h2>

                    <div><b>ИНН: 1234567890</b></div>
                    <div><b>КПП: 123456789</b></div>
                    <div><b>Расчетный счет: 0000 0000 0000 0000 0000</b></div>
                    <div><b>Банк: ОАО "Сбербанк России", г. Москва</b></div>
                    <div><b>Банковские реквизиты: БИК 044525225</b></div>
                    <div><b>Корреспондентский счет: 30101 810 4 0000 0000225</b></div>
                </div>

                <p>В нашем магазине всегда только самые лучшие товары по низким ценам. Каталог продаваемых товаров постоянно пополняется и обновляется.</p>

                <div>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:map.google.view",
                        "",
                        Array(
                            "OPTIONS" => array(
                                // 0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                // 2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_WIDTH" => 'AUTO',
                        )
                    );?>
                </div>
                <div>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:map.yandex.view",
                        "",
                        Array(
                            "OPTIONS" => array(
                                // 0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                // 2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_WIDTH" => 'AUTO',
                        ),
                        false
                    );?>
                </div>
                <?if (CModule::IncludeModule('simai.maps2gis')): ?>
                    <div>
                        <?$APPLICATION->IncludeComponent(
                            "simai:maps.2gis.simple",
                            "",
                            Array(
                                "MAP_WIDTH" => 'AUTO',
                            )
                        );?>
                    </div>
                <?endif?>
            </div>
        </div>
    </main>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>