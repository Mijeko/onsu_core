function JCSmartFilter(ajaxURL, form_id, manual, hide_props) {
    this.ajaxURL = ajaxURL;
    this.form = BX(form_id);
    this.timer = null;
    this.isFlying = false;
    this.manual = manual;
    this.hideProps = hide_props;
    this.$form = $(this.form);
    this.templateChoosenFilter = '<div class="filter-chosen"><div class="title-h2">' + BX.message('BITRONIC2_YOU_CHOOSE') + '</div><div class="filter-section"><ul class="chosen-list">#DATA_LI#</ul></div></div>';

    // save set filter params for catalog AJAX
    var values = [], that = this;
    this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': 'input'}, true));
    values = this.values2post(values);
    if (typeof values.set_filter != "undefined" && typeof values.del_filter == "undefined") {
        RZB2.ajax.CatalogSection.filterParams = values;
    }

    // clear Button
    this.$form.on('click', '#del_filter', function (e) {
        e.preventDefault();
        var $this = $(this),
            bAllelements = false;
        smartFilter.form = BX.findParent(e.target, {'tag': 'form'});

        // hide filter results
        $('#modef, #flying-results-wrap').hide();

        // clear sliders
        $(smartFilter.form).find('[id^=slider_]').each(function () {
            var min = $(this).siblings('.range-slider-inputs').find('input.range-input-lower').data('range-min');
            var max = $(this).siblings('.range-slider-inputs').find('input.range-input-upper').data('range-max');
            setRangeSliderLimits($(this), {left: min, right: max});
            this.noUiSlider.set([min, max]);
        });
        // clear slider input
        $(smartFilter.form).find('input[type=text]:enabled').val('');
        // clear checkbox
        $(smartFilter.form).find('input[type=checkbox],input[type=radio]').prop('checked', false).prop('disabled', false).parent().removeClass('disabled');
        // clear selects
        $(smartFilter.form).find('select').each(function () {
            var optionCount = $(this).find('option').length;
            if (optionCount <= 1) {
                $(this).find('option').prop('disabled', true);
                b2.init.selects($(this).parent());
            }
            $(this).find('option').eq(0).attr('selected', true);
        });
        // clear brands
        $('.brands-catalog .brand').removeClass('active disabled');

        // clear filter params
        RZB2.ajax.CatalogSection.filterParams = {};

        $this.addClass('disabled');
        YSFilterRemoveDisable = false;
        var sefDel = $this.data('sef-del');
        if (typeof sefDel != 'undefined' && sefDel.length > 0) {
            RZB2.ajax.CatalogSection.SefSetUrl = sefDel;
            RZB2.ajax.params.REQUEST_URI = sefDel;
        }
        // start ajax
        smartFilter.reload($(smartFilter.form).find('input:first')[0]);
        RZB2.ajax.CatalogSection.Start();
    });

    //delete choosen value
    this.$form.on('click', '.choose-delete', function (e) {
        var $btn = $(this),
            idControlInput = $btn.data('control-input'),
            idControlSelect = $btn.data('control-select'),
            idControlRadio = $btn.data('control-radio'),
            $filter = that.$form,
            $choosenBlock = $filter.find('.filter-chosen');

        $('.brands-catalog .brand[data-checkbox=' + idControlInput + ']').removeClass('active');
        var $input = $filter.find('input[name="' + idControlInput + '"],input[name="' + idControlRadio + '"]'),
            $select = $filter.find('select[name="' + idControlSelect + '"]');
        if (!!$input.length) {
            if ($input.is(':radio, :checked')) {
                $input.click();
            } else if ($input.is(':text')) {
                if ($input.hasClass('range-input-lower') || $input.hasClass('range-input-upper')) {
                    var $range = $input.closest('.range-slider-inputs').siblings('.range-slider'),
                        from = $range.data('from'),
                        to = $range.data('to'),
                        limits = {left: from, right: to},
                        right = 0, left = 0, bLower = false, bEmpty = false;
                    if ($range.length){
                        if ($input.hasClass('range-input-lower')) {
                            bEmpty = $input.parent().siblings('.end').find('input').val() == '';
                            limits.right = bEmpty  ? to : '';
                            bLower = true;
                        } else {
                            bEmpty = $input.parent().siblings('.start').find('input').val() == '';
                            limits.left = bEmpty ? from : '';
                        }
                        $range.get(0).noUiSlider.updateTopLimiter([String(limits.left),String(limits.right)]);
                        $range.get(0).noUiSlider.set([String(limits.left),String(limits.right)]);
                        $input.val('');
                        bEmpty ? bLower ? $input.parent().siblings('.end').find('input').val('') : $input.parent().siblings('.start').find('input').val('') : '';
                    } else{
                        $input.val('');
                    }
                } else {
                    $input.val('');
                }
                smartFilter.keyup(BX($input.attr('id')));
            }
        }
        else if (!!$select.length) {
            $select.find('option[selected]').attr('selected', false);
            $select.find('option').eq(0).attr('selected', true).change();
        }

        $btn.parent().remove();
        $choosenBlock.find('li').length ? '' : $choosenBlock.remove();
    });

    //delete choosen in line filter
    this.$form.on('click', '.btn-clear-section', function (e) {
        var $this = $(this).closest('.filter-section'),
            $checkBoxes = $this.find('input[type=checkbox]:checked'),
            $radio = $this.find('input[type=radio]').eq(0),
            $select = $this.find('select'),
            $textInput = $this.find('input[type=text]');

        $this.removeClass('--checked');
        if (!$this.hasClass('expanded')) {
            $this.addClass('not-expand');
        }
        $this.find('header .text').eq(0).text($this.data('name-prop'));
        $this.find('header .text.choosen-values').remove();
        if ($checkBoxes.length) {
            $checkBoxes.click();
        }
        if ($radio.length) {
            $radio.click();
        }
        if ($textInput.length && $textInput.val().length) {
            $textInput.val('');
            smartFilter.keyup(BX($textInput.attr('id')));
        }
        if ($select.length) {
            $select.find('option[selected]').attr('selected', false);
            $select.find('option').eq(0).attr('selected', true).change();
        }
    });

    // full-filter Button
    this.$form.off('click', '.btn-toggle-full-filter').on('click', '.btn-toggle-full-filter', function (e) {
        var _ = $(this);
        _.toggleClass('toggled');
        var filterFull = _.closest('.form_filter').find('.filter-full');
        var filterShort = _.closest('.form_filter').find('.filter-short');
        if ($('[data-filter-placement]').data('filter-placement') === 'line') {

            if (filterFull.hasClass('filter-opened') ){
                filterFull.removeClass('filter-opened');
                filterShort.removeClass('filter-opened');
                filterFull.attr('style','');
                filterShort.attr('style','');
            } else {
                filterFull.addClass('filter-opened');
                filterShort.addClass('filter-opened');
            }

        } else {
            if (filterFull.hasClass('filter-opened')) {

                filterFull.removeClass('filter-opened');
                filterShort.removeClass('filter-opened');
                filterFull.velocity('slideUp');
            } else {
                filterFull.velocity('slideDown', {
                    complete: function () {
                        filterFull.addClass('filter-opened');
                        filterShort.addClass('filter-opened');
                    }
                });
            }
        }

        return false;
    })
        .on('submit', function (e) {
            //smartFilter.reload($(this).children('input:first')[0]);
            return false;
        });
    if (this.manual) {
        $(document).on('click', '#flying-results-wrap .btn-show-results, #' + form_id + ' .show-results', function (e) {
            e.preventDefault();
            RZB2.ajax.CatalogSection.Start();
            RZB2.ajax.scrollPage($('.sort-n-view'), true);
        });
    }

}

var YSFilterRemoveDisable = false;

JCSmartFilter.prototype.keyup = function (input) {
    if (this.timer)
        clearTimeout(this.timer);
    this.timer = setTimeout(BX.delegate(function () {
        YSFilterRemoveDisable = true;
        this.reload(input);
    }, this), 1000);
};

JCSmartFilter.prototype.click = function (checkbox) {
    if (this.timer)
        clearTimeout(this.timer);
    this.timer = setTimeout(BX.delegate(function () {
        YSFilterRemoveDisable = true;
        this.reload(checkbox);
    }, this), 1000);
};

JCSmartFilter.prototype.reload = function (input) {
    var $parent = $(input).parent();
    if ($parent.is(':visible')) {
        this.position = $parent.offset();
    }
    this.form = BX.findParent(input, {'tag': 'form'});
    if (this.form) {
        var values = [];
        values[0] = {name: 'ajax', value: 'y'};
        this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

        this.updateFilterTagsList(values);

        this.values = this.values2post(values);
        // update filter block
        BX.ajax.loadJSON(
            this.ajaxURL,
            this.values,
            BX.delegate(this.postHandler, this)
        );
    }
};

JCSmartFilter.prototype.postHandler = function (result) {
    if ('SEO_FILTER' in result) {
        this.setSeoFilter(result);
    }

    this.setChoosenValues(result.VALUES_CHECKED);
    var $nameInput = this.$form.find('#arrFilter_FIELD_NAME'),
        bLineFilter = $('[data-filter-placement]').attr('filter-placement') === 'line';

    if (!!result && !!result.ITEMS) {
        for (var PID in result.ITEMS) {
            var arItem = result.ITEMS[PID],
                bChecked = false,
                $container = '';

            if (arItem.PROPERTY_TYPE == 'N' || arItem.PRICE) {
                var sliderId = '#slider_' + (arItem.PRICE ? 'price_' : '') + arItem.ID;
                var slider = $(sliderId),
                    selectedValues,
                    $container = slider,
                    minInInput = $('input[name="' + arItem.VALUES.MIN.CONTROL_NAME + '"]').length ? $('input[name="' + arItem.VALUES.MIN.CONTROL_NAME + '"]').val() : '',
                    maxInInput =  $('input[name="' + arItem.VALUES.MAX.CONTROL_NAME + '"]').length ? $('input[name="' + arItem.VALUES.MAX.CONTROL_NAME + '"]').val() : '',
                    bChecked = (minInInput.length &&  minInInput.replace(/ /g,'') != arItem.VALUES.MIN.VALUE) || (maxInInput.length &&  maxInInput.replace(/ /g,'') != arItem.VALUES.MAX.VALUE),
                    bChecked = minInInput.length == 0 && maxInInput.length == 0 ? false : bChecked;

                if (bChecked && bLineFilter){
                    selectedValues = minInInput.length && minInInput.replace(/ /g,'') ? minInInput.replace(/ /g,'') : '';
                    selectedValues += maxInInput.length && maxInInput.replace(/ /g,'') ? (selectedValues.length ? ', ' + maxInInput.replace(/ /g,'') : maxInInput.replace(/ /g,'')) : '';
                }
                if (slider.length < 1){
                    if (!!arItem.VALUES && !!arItem.VALUES.MIN) {
                        var $containerOfInputs = $('input[name="' + arItem.VALUES.MIN.CONTROL_NAME + '"]').closest('.range-slider-inputs'),
                            $container = $('input[name="' + arItem.VALUES.MIN.CONTROL_NAME + '"]').closest('.range-slider-inputs');
                        if (!!arItem.DISABLED && arItem.DISABLED){
                            $containerOfInputs.addClass('disabled');
                        } else{
                            $containerOfInputs.removeClass('disabled');
                        }
                    }
                    this.setCheckedFilterSection(bChecked,$container);
                    if (bLineFilter) {
                        this.setTextForChoosenValues(selectedValues, $container, arItem.NAME,bChecked);
                    }
                    continue;
                }

                var limits = {};
                if (arItem.VALUES.MIN && arItem.VALUES.MIN.FILTERED_VALUE) {
                    limits.left = arItem.VALUES.MIN.FILTERED_VALUE;
                }
                if (arItem.VALUES.MAX && arItem.VALUES.MAX.FILTERED_VALUE) {
                    limits.right = arItem.VALUES.MAX.FILTERED_VALUE;
                }
                setRangeSliderLimits(slider, limits);

                if (!!arItem.DISABLED && arItem.DISABLED){
                    slider.addClass('disabled');
                    slider.siblings('.range-slider-inputs').addClass('disabled');
                } else{
                    slider.removeClass('disabled');
                    slider.siblings('.range-slider-inputs').removeClass('disabled');
                }
            } else if (arItem.VALUES) {
                if (arItem.DISPLAY_TYPE == 'R' || arItem.DISPLAY_TYPE == 'P') {
                    var $select = false,
                        $container = '',
                        selectedValues = '';
                    for (var i in arItem.VALUES) {
                        var ar = arItem.VALUES[i],
                            bChecked = bChecked ? bChecked : ar.CHECKED,
                             $select = $('select[name="' + ar.CONTROL_NAME_ALT + '"]'),
                            $container = $select,
                            $option = $select.find('option[value="' + ar.HTML_VALUE_ALT + '"]');

                        if (ar.CHECKED && bLineFilter){
                            selectedValues += selectedValues.length ? ', ' + ar.VALUE : ar.VALUE;
                        }
                        if (ar.hasOwnProperty('ELEMENT_COUNT')) {
                            $option.data('sup',ar.ELEMENT_COUNT);
                        }
                        $option.prop('disabled', !!(ar.DISABLED && !ar.CHECKED));
                    }
                    if ($select) {
                        b2.init.selects($select.parent());
                        if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($select.parent());
                    }
                }
                else {
                    var selectedValues = '';
                    for (var i in arItem.VALUES) {
                        var ar = arItem.VALUES[i],
                            bChecked = bChecked ? bChecked : ar.CHECKED;
                        var control = BX(ar.CONTROL_ID),
                            $container = $('input#' + ar.CONTROL_ID);

                        if (ar.CHECKED && bLineFilter){
                            selectedValues += selectedValues.length ? ', ' + ar.VALUE : ar.VALUE;
                        }
                        if (control && $(control).is('select')) {
                        }
                        else if (control) {
                            var $brand;
                            if (arItem.CODE == this.brandPropCode) {
                                $brand = $('div[data-checkbox="' + ar.CONTROL_ID + '"]');
                            }
                            var className = 'disabled' + (this.hideProps ? ' hidden' : '');
                            $(control.parentNode).toggleClass(className, (!!ar.DISABLED && !ar.CHECKED));
                            if (ar.DISABLED && !control.checked) {
                                control.setAttribute("disabled", "disabled");
                                if ($brand) $brand.addClass(className);
                            } else {
                                control.removeAttribute("disabled");
                                if ($brand) $brand.removeClass(className);
                            }
                            if (ar.hasOwnProperty('ELEMENT_COUNT')) {
                                label = document.querySelector('[data-role="count_' + ar.CONTROL_ID + '"]');
                                if (label)
                                    label.innerHTML = ar.ELEMENT_COUNT;
                            }
                            $brand = undefined;
                        }
                    }
                }
            }
            this.setCheckedFilterSection(bChecked,$container);
            if (bLineFilter) {
                this.setTextForChoosenValues(selectedValues, $container, arItem.NAME,bChecked);
            }
        }
        if (!YSFilterRemoveDisable)
            return;

        var modef = BX('modef');
        var modef_num = BX('modef_num');
        var sideFilterWrap = $('#filter-at-side');
        var modef_flight = $('#flying-results-wrap');
        var modef_flight_num = BX('modef_flight_num');
        var _ = this;

        if (modef && modef_num) {
            modef_num.innerHTML = result.ELEMENT_COUNT;

            if (modef.style.display == 'none')
                modef.style.display = 'block';
        }
        if (modef_flight.length && modef_flight_num) {
            modef_flight_num.innerHTML = result.ELEMENT_COUNT;
            modef_flight.css('top', this.position.top - sideFilterWrap.offset().top - 10);
            if (!this.isFlying) {
                modef_flight.velocity('fadeIn');
            }
            clearTimeout(this.isFlying);
            this.isFlying = setTimeout(function () {
                modef_flight.velocity('fadeOut');
                _.isFlying = false;
            }, 4000);
        }
    }

    if ($nameInput.length && $nameInput.val().length){
        $nameInput.closest('.filter-section').addClass('--checked');
        if (bLineFilter) {
            var spunTextValueOfChoosen = '<span class="text choosen-values">' + ' ' + $nameInput.val() + '</span>';
            $nameInput.closest('.filter-section').find('header .text').eq(0).text($nameInput.closest('.filter-section').data('name-prop') + ': ');
            if ($nameInput.closest('.filter-section').find('header .choosen-values').length){
                $nameInput.closest('.filter-section').find('header .choosen-values').text(' ' + $nameInput.val());
            } else if ($nameInput.closest('.filter-section').find('header .help').length){
                $nameInput.closest('.filter-section').find('header .help').after(spunTextValueOfChoosen);
            } else{
                $nameInput.closest('.filter-section').find('header .text').text(nameProp + ': '  + $nameInput.val());
            }
        }
    } else{
        $nameInput.closest('.filter-section').removeClass('--checked');
        if (bLineFilter) {
            $nameInput.closest('.filter-section').find('header .text').eq(0).text($nameInput.closest('.filter-section').data('name-prop'));
            $nameInput.closest('.filter-section').find('header .choosen-values').text('');
        }
    }

    RZB2.ajax.CatalogSection.filterParams = this.values;
    if (RZB2.ajax.CatalogSection.ID > 0 && 'SEF_SET_FILTER_URL' in result && result['SEF_SET_FILTER_URL'].length > 0) {
        RZB2.ajax.CatalogSection.SefSetUrl = result['SEF_SET_FILTER_URL'];
    }

    //delete service var
    delete RZB2.ajax.CatalogSection.filterParams.ajax;

    RZB2.ajax.CatalogSection.filterParams.set_filter = 'y';
    if (!this.manual) {
        RZB2.ajax.CatalogSection.Start();
    }

    if (YSFilterRemoveDisable) {
        $('#del_filter').removeClass('disabled');
        this.$form.find('footer .show-results').removeClass('disabled');
    }

    if (this.hideProps) {
        var $filterSections = $('.filter-section').has('.checkbox-styled, .radio-styled, select');
        $filterSections = $filterSections.not($filterSections.has('input:not([disabled]):not([value=""])').removeClass('hidden'));
        $filterSections = $filterSections.not($filterSections.has('option:not([disabled]):not([value=""])').removeClass('hidden'));
        $filterSections.addClass('hidden');
    }
};

JCSmartFilter.prototype.setCheckedFilterSection = function(bChecked,$container){
    if (bChecked){
        $container.length ? $container.closest('.filter-section').addClass('--checked') : '';
    }else{
        $container.length ? $container.closest('.filter-section').removeClass('--checked') : '';
    }
};

JCSmartFilter.prototype.setTextForChoosenValues = function(choosenValues,$container, nameProp, bChecked){
    var spunTextValueOfChoosen = '<span class="text choosen-values">' + ' ' + choosenValues + '</span>';
    if (bChecked) {
        if($container.length){
            $container.closest('.filter-section').find('header .text').eq(0).text(nameProp + ': ' );
            if ($container.closest('.filter-section').find('header .choosen-values').length){
                $container.closest('.filter-section').find('header .choosen-values').text(choosenValues);
            } else if ($container.closest('.filter-section').find('header .help').length){
                $container.closest('.filter-section').find('header .help').after(spunTextValueOfChoosen);
            } else{
                $container.closest('.filter-section').find('header .text').text(nameProp + ': '  + choosenValues);
            }
        }
    } else{
        $container.length ? $container.closest('.filter-section').find('header .text').eq(0).text(nameProp) : '';
        $container.length ? $container.closest('.filter-section').find('header .choosen-values').text('') : '';
    }
};

JCSmartFilter.prototype.setChoosenValues = function (arChossens) {
   var $nameInput = this.$form.find('#arrFilter_FIELD_NAME'),
       bHasFilterByName = $nameInput.length && $nameInput.val().length;
    if (arChossens || bHasFilterByName) {
        var tmpLi = ' <li class="chosen-item"> <span>#VALUE#</span> <i data-control-radio="#CONTROL_ALT#" data-control-select="#CONTROL_ALT#" data-control-input="#CONTROL_ID#" class="choose-delete btn-close-chosen flaticon-close47"></i></li>',
            allLi = bHasFilterByName ? tmpLi.replace('#VALUE#',$nameInput.val()) : '',
            allLi = bHasFilterByName ? allLi.replace('#CONTROL_ID#','arrFilter_FIELD_NAME') : '',
            tmpLiInFor = '',
            blockOfChoosend = this.templateChoosenFilter;

        if (arChossens) {
            $.each(arChossens, function (index, value) {
                $.each(value.VALUES, function () {
                    tmpLiInFor = tmpLi.replace('#VALUE#', this.VALUE);
                    tmpLiInFor = tmpLiInFor.replace('#CONTROL_ALT#', this.CONTROL_NAME_ALT);
                    tmpLiInFor = tmpLiInFor.replace('#CONTROL_ALT#', this.CONTROL_NAME_ALT);
                    tmpLiInFor = tmpLiInFor.replace('#CONTROL_ID#', this.CONTROL_ID);
                    allLi += tmpLiInFor;
                });
            });
        }
        blockOfChoosend = blockOfChoosend.replace('#DATA_LI#', allLi);
        this.$form.find('.filter-chosen').remove();
        this.$form.find('.filter-content .title-h2').before(blockOfChoosend);
    } else {
        this.$form.find('.filter-chosen').remove();
    }
}

JCSmartFilter.prototype.setSeoFilter = function (result) {
    if ('SEO_FILTER' in result && typeof window.rzSeoFilter != 'undefined') {
        window.rzSeoFilter(result.SEO_FILTER);
        if ('SEO_TEXT' in result.SEO_FILTER) {
            if ('RZ_SEO_TOP' in result.SEO_FILTER.SEO_TEXT) {
                var $desc = $('.rz_category_desc_top');
                if ($desc.length) {
                    $desc.find('.desc').html(result.SEO_FILTER.SEO_TEXT.RZ_SEO_TOP);
                }
            }
            if ('RZ_SEO_BOT' in result.SEO_FILTER.SEO_TEXT) {
                var $desc = $('.rz_category_desc_bottom');
                if ($desc.length) {
                    $desc.find('.desc').html(result.SEO_FILTER.SEO_TEXT.RZ_SEO_BOT);
                }
            }
        }
    }
}

JCSmartFilter.prototype.gatherInputsValues = function (values, elements) {
    if (elements) {
        for (var i = 0; i < elements.length; i++) {
            var el = elements[i];
            if (el.disabled || !el.type)
                continue;

            switch (el.type.toLowerCase()) {
                case 'number':
                case 'text':
                case 'textarea':
                case 'password':
                case 'hidden':
                case 'select-one':
                    if (el.value.length) {
                        values[values.length] = {name: el.name, value: el.value, id: el.id, type: el.type};
                        var $el = $(el);
                        if ($el.hasClass('range-input-lower') || $el.hasClass('range-input-upper')) {
                            values[values.length - 1]['value'] = el.value.replace(/\s+/g, '');
                        }
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    if (el.checked)
                        values[values.length] = {name: el.name, value: el.value, id: el.id, type: el.type};
                    break;
                case 'select-multiple':
                    for (var j = 0; j < el.options.length; j++) {
                        if (el.options[j].selected)
                            values[values.length] = {
                                name: el.name,
                                value: el.options[j].value,
                                id: el.id,
                                type: el.type
                            };
                    }
                    break;
                default:
                    break;
            }
        }
    }
};

JCSmartFilter.prototype.values2post = function (values) {
    var post = [];
    var current = post;
    var i = 0;
    while (i < values.length) {
        var p = values[i].name.indexOf('[');
        if (p == -1) {
            current[values[i].name] = values[i].value;
            current = post;
            i++;
        } else {
            var name = values[i].name.substring(0, p);
            var rest = values[i].name.substring(p + 1);
            if (!current[name])
                current[name] = [];

            var pp = rest.indexOf(']');
            if (pp == -1) {
                //Error - not balanced brackets
                current = post;
                i++;
            }
            else if (pp == 0) {
                //No index specified - so take the next integer
                current = current[name];
                values[i].name = '' + current.length;
            }
            else {
                //Now index name becomes and name and we go deeper into the array
                current = current[name];
                values[i].name = rest.substring(0, pp) + rest.substring(pp + 1);
            }
        }
    }
    return post;
};
JCSmartFilter.prototype.updateFilterTagsList = function (values) {
    var checkboxValues = [];
    var checkboxIdList = [];
    var obTagsList = $('.tags-list');
    for (var key in values) {
        if (typeof values[key].id != 'undefined' && typeof values[key].type != 'undefined' && values[key].type == 'checkbox') {
            checkboxValues[checkboxValues.length] = values[key];
            checkboxIdList[checkboxIdList.length] = values[key].id;
        }
    }

    // delete tag in tags-list
    obTagsList.find('.tag button').each(function () {
        if ($.inArray($(this).attr('data-input-id'), checkboxIdList) < 0) {
            $(this).parent('.tag').remove();
        }
    });
    if (!obTagsList.find('.tag button').length) {
        obTagsList.parent('.tags').addClass('hide');
    }

    // add tag in tags-list
    for (var key in checkboxValues) {
        if (!$('.tags-list .tag button[data-input-id=' + checkboxValues[key].id + ']').length) {
            var html = '<div class="tag">' +
                '<span class="tag-text">' + $('#catalog-filter-form #' + checkboxValues[key].id).siblings('span').text() + '</span>' +
                '<button class="btn-closebtn" data-input-id="' + checkboxValues[key].id + '"><span class="btn-text"></span></button>' +
                '</div> ';
            obTagsList.append(html).parent('.tags').removeClass('hide');
        }
    }
}

function createSlider(params) {
    // PRICE SLIDER STARTS HERE
    var minPriceLimit = Number(params.VALUES.MIN);
    var maxPriceLimit = Number(params.VALUES.MAX);

    var currentMin = Number((params.HTML_VALUES.MIN.length) ? params.HTML_VALUES.MIN : params.VALUES.MIN);
    var currentMax = Number((params.HTML_VALUES.MAX.length) ? params.HTML_VALUES.MAX : params.VALUES.MAX);

    var minFilterLimit = Number(params.FILTERED_VALUES.MIN.length ? params.FILTERED_VALUES.MIN : currentMin);
    var maxFilterLimit = Number(params.FILTERED_VALUES.MAX.length ? params.FILTERED_VALUES.MAX : currentMax);

    var numPipsDef = 5;
    var numPips = ((maxPriceLimit - minPriceLimit) > numPipsDef) ? numPipsDef : (maxPriceLimit - minPriceLimit) + 1;

    var $sliderObj = $('#' + params.SLIDER_ID);
    if (typeof $sliderObj.data('noUiSlider') != "undefined") return;

    initRangeSlider($sliderObj[0], {
        minValue: Math.floor(minPriceLimit),
        maxValue: Math.ceil(maxPriceLimit),
        step: 1,
        startLower: currentMin,
        startUpper: currentMax,
        limits: {
            left: minFilterLimit,
            right: maxFilterLimit
        },
        format: {
            decimals: 0,
            thousand: ' '
        },
        pips: {
            mode: 'count',
            values: numPips,
            density: 50
        }
    });
    $sliderObj.data(
        'noUiSlider', 'noUiSlider'
    );
    $sliderObj.data(
        'from', minPriceLimit
    );
    $sliderObj.data(
        'to', maxPriceLimit
    );

    // this str for not filter by this slider before we change this slider
    if (!params.HTML_VALUES.MIN.length)
        $('#' + params.INPUT_ID.MIN).val('');
    if (!params.HTML_VALUES.MAX.length)
        $('#' + params.INPUT_ID.MAX).val('');

    $sliderObj[0].noUiSlider.on('change', function () {
        smartFilter.keyup(BX(params.INPUT_ID.MIN));
    });
}

function initRangeSlider(slider, options) {
    var $slider = $(slider),
        minValue = options.minValue,
        maxValue = options.maxValue,
        step = ( options.step === undefined ) ? 1 : options.step,
        $inputLower = $slider.parent().find('.range-input-lower'),
        $inputUpper = $slider.parent().find('.range-input-upper'),
        startLower = ( options.startLower === undefined ) ? minValue : options.startLower,
        startUpper = ( options.startUpper === undefined ) ? maxValue : options.startUpper;

    function filterPips(value) {
        if (value === minValue || value === maxValue) return 1;
        return 2;
    }

    noUiSlider.create(slider, {
        start: [startLower, startUpper],
        connect: true,
        behaviour: 'snap',
        step: step,
        range: {
            'min': minValue,
            'max': maxValue
        },
        format: wNumb(options.format),
        tooltips: true,
        pips: {
            mode: options.pips.mode,
            values: options.pips.values,
            density: options.pips.density,
            filter: filterPips
        }
    }).on('update', function (values, handle) {
        if (handle) {
            // handle is 1 == upper
            $inputUpper.val(values[handle]);
        } else {
            // handle is 0 == lower
            $inputLower.val(values[handle]);
        }
    });

    $inputLower.on('change', function (e) {
        slider.noUiSlider.set([this.value, null]);
    });
    $inputUpper.on('change', function (e) {
        slider.noUiSlider.set([null, this.value]);
    });

    $slider.find('.noUi-handle').append('<div class="noUi-arrow"></div>');

    $slider.data('range', {min: minValue, max: maxValue})
        .data('format', options.format)
        .find('.noUi-base').append('<div class="noUi-limiter base hidden"></div><div class="noUi-limiter top hidden"></div>');

    if (options.limits) {
        var $limiter = $slider.find(".noUi-limiter.top"),
            format = wNumb(options.format),
            delta = maxValue - minValue;

        slider.noUiSlider.updateTopLimiter = function (val) {
            var limits = $slider.data('noUi-limits'),
                curLeft = format.from(val[0]),
                curRight = format.from(val[1]),
                leftLimitPercent = limits.leftPercent,
                rightLimitPercent = limits.rightPercent,
                newLeft = 100 * ((curLeft - minValue) / delta),
                newRight = 100 * ((maxValue - curRight) / delta);

            // console.log('current',curLeft, leftPercent, curRight, rightPercent);
            if (newLeft < leftLimitPercent) newLeft = leftLimitPercent;
            if (newRight < rightLimitPercent) newRight = rightLimitPercent;
            if (newLeft >= (99.5 - rightLimitPercent)) {
                return $limiter.addClass('hidden');
            }
            if (newRight >= (100 - leftLimitPercent)) {
                return $limiter.addClass('hidden');
            }

            $limiter.removeClass('hidden').css({
                left: newLeft + '%',
                right: newRight + '%',
            })
        }
        setRangeSliderLimits($slider, options.limits);

        slider.noUiSlider.on('update', function (values, handle) {
            slider.noUiSlider.updateTopLimiter(values);
        });
    }
}

function setRangeSliderLimits($slider, limits) {
    // limits is object with left and right
    var min = $slider.data('range').min,
        max = $slider.data('range').max,
        left = parseFloat(limits.left) || min,
        right = parseFloat(limits.right) || max;

    // edge cases
    if (left > right) right = left;
    if (left < min) left = min;
    if (right > max) right = max;
    if (left >= max) {
        left = max;
        right = max;
    } else if (right <= min) {
        right = min;
        left = min;
    }

    var leftPercent = 100 * ((left - min) / (max - min)),
        rightPercent = 100 * ((max - right) / (max - min)),
        $limiter = $slider.find('.noUi-limiter.base');

    (leftPercent === 0 && rightPercent === 0) ?
        $limiter.addClass('hidden') : $limiter.removeClass('hidden');
    // console.log(leftPercent, rightPercent);
    $slider.data('noUi-limits', {left: left, right: right, leftPercent: leftPercent, rightPercent: rightPercent});
    $limiter.css({
        left: leftPercent + '%',
        right: rightPercent + '%'
    });

    var s = $slider.get(0).noUiSlider, v = s.get();

    s.updateTopLimiter(v);
}