<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

$arDaysB = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY');
$arDaysH = array('SATURDAY', 'SUNDAY');
?>
    <div class="time with-icon">
<? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php'; ?>
    <i id="switch-time-content" class="flaticon-clock4" data-popup="^.time>.time-content"></i>
    <div class="time-content">
		<span class="bars">
			<? foreach ($arDaysB as $day): ?>
                <span class="bar
				    <? if (in_array($day, $arParams['HALF_DAYS'])): ?>
				    halfday
                   <?
                elseif ($arParams[$day] == 'Y'): ?>
                    fullday
                    <?
                else: ?>
                       holiday
                    <?endif ?>"></span>
            <?endforeach ?>
            <? foreach ($arDaysH as $day): ?>
                <span class="bar
                <? if (in_array($day, $arParams['HALF_DAYS'])): ?>
				    halfday
                   <?
                elseif ($arParams[$day] == 'Y'): ?>
                    fullday
                    <?
                else: ?>
                       holiday
                    <?endif ?>"></span>
            <?endforeach ?>
		</span>

        <div class="time-interval">
			<span class="working-time" data-popup=">.notification-popup" data-position="centered bottom">
				<span class="bar fullday demo"></span>
				<span class="work-from"><?= $arParams['TIME_WORK_FROM'] ?></span>
				<span class="work-to"><?= $arParams['TIME_WORK_TO'] ?></span>
				<span class="notification-popup">
					<span class="content">
						<?= $arParams['LUNCH'] ?>
					</span>
				</span>
			</span>
        </div><!-- /.time-interval -->
        <? if ($arParams['TIME_WEEKEND_FROM'] != null): ?>
            <div class="time-interval">
			<span class="working-time" data-popup=">.notification-popup" data-position="centered bottom">
				<span class="bar <?= ($arParams[$day] == 'Y') ? 'fullday' : 'holiday' ?> demo"></span>
				<span class="work-from"><?= $arParams['TIME_WEEKEND_FROM'] ?></span>
				<span class="work-to"><?= $arParams['TIME_WEEKEND_TO'] ?></span>
				<span class="notification-popup">
					<span class="content">
						<?= $arParams['LUNCH_WEEKEND'] ?>
					</span>
				</span>
			</span>
            </div><!-- /.time-interval -->
        <?endif; ?>
        <? if ($arParams['TIME_NOT_FULL_DAY_FROM'] != null): ?>
            <div class="time-interval">
                <span class="working-time" data-popup=">.notification-popup" data-position="centered bottom">
                    <span class="bar halfday demo"></span>
                    <span class="work-from"><?= $arParams['TIME_NOT_FULL_DAY_FROM'] ?></span>
                    <span class="work-to"><?= $arParams['TIME_NOT_FULL_DAY_TO'] ?></span>
                    <span class="notification-popup">
                        <span class="content">
                            <?= $arParams['LUNCH_HALF_DAY'] ?>
                        </span>
                    </span>
                </span>
            </div><!-- /.time-interval -->
        <?endif; ?>
    </div><!-- /.time-content -->
    </div><?
// echo "<pre style='text-align:left;'>";print_r($arParams);echo "</pre>";