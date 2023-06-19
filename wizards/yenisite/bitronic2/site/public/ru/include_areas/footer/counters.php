<?$URL = htmlspecialchars($APPLICATION->GetCurPage());?>
<div>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/counters/counter_blank.png" alt="Пример счетчика">
</div>
<div>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/counters/counter_blank.png" alt="Пример счетчика">
</div>
<? if ($USER->IsAdmin()): ?>
<div>
	<a href="http://validator.w3.org/check?uri=<?=urlencode(SITE_SERVER_NAME.$URL)?>" target="_blank">
		<img src="<?=SITE_TEMPLATE_PATH?>/img/counters/html5.png" alt="HTML5">
	</a>
</div>
<div>
	<a href="http://www.cssportal.com/css-validator/validate.htm?url=<?=urlencode(SITE_SERVER_NAME.$URL)?>" target="_blank">
		<img src="<?=SITE_TEMPLATE_PATH?>/img/counters/css3.png" alt="CSS3">
	</a>
</div>
<div>
	<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?=urlencode(SITE_SERVER_NAME.$URL)?>" target="_blank">
		<img src="<?=SITE_TEMPLATE_PATH?>/img/counters/pagespeed-64.png" alt="PageSpeed Insights">
	</a>
</div>
<? endif ?>