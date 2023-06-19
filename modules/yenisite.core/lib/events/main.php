<?php
namespace Yenisite\Core\Events;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;

class Main
{
	public static function Redirect404($path404 = null)
	{
		if (empty($path404)) {
			$path404 = SITE_DIR . "404.php";
		}
		define("PATH_TO_404", $path404);
		if (
				!defined('ADMIN_SECTION') &&
				!defined('RZ_404_REDIRECT') &&
				defined("ERROR_404") &&
				defined("PATH_TO_404") &&
				file_exists($_SERVER["DOCUMENT_ROOT"] . PATH_TO_404)
		) {
			global $APPLICATION;
			define('RZ_404_REDIRECT', true);
			$APPLICATION->RestartBuffer();
			\CHTTP::SetStatus("404 Not Found");
			include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
			include($_SERVER["DOCUMENT_ROOT"] . PATH_TO_404);
			include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
		}
	}

	public static function initRefreshCaptcha()
	{
		if (defined("ADMIN_SECTION") && ADMIN_SECTION === true) {
			return;
		}
		if (\COption::GetOptionString('yenisite.core', 'captcha_refresh', 'Y') != 'Y') {
			return;
		}
		Loc::loadMessages(__FILE__);
		$Asset = Asset::getInstance();
		$Asset->addString('<style>img[src*="captcha.php"] {cursor: pointer;}</style>');
		ob_start();
		?>
		<script type="text/javascript">
			var rz_matchSelector = function (el, selector) {
				return (el.matches || el.matchesSelector || el.msMatchesSelector || el.mozMatchesSelector || el.webkitMatchesSelector || el.oMatchesSelector).call(el, selector);
			};
			document.addEventListener('click', function (e) {
				if (rz_matchSelector(e.target, "img[src*=\"captcha.php\"]")) {
					var src = e.target.src;
					src = (src.indexOf("&") > -1) ? src.substr(0, src.indexOf("&")) : src;
					src += '&' + Math.floor(Math.random() * 10000);
					e.target.src = src;
				}
			});
		</script>
		<?
		$script = ob_get_clean();
		$Asset->addString($script, true, AssetLocation::AFTER_JS);
	}

	public static function UserEmailAsLoginRegisterHandler(&$arFields)
	{
		if (empty($arFields['EMAIL'])) {
			global $APPLICATION;
			Loc::loadMessages(__FILE__);
			$APPLICATION->ThrowException(GetMessage('RZ_USER_REG_ERROR_EMAIL_EMPTY'));
			return false;
		}
		if ($arFields['LOGIN'] != $arFields['EMAIL']) {
			$arFields['LOGIN'] = $arFields['EMAIL'];
		}
		return true;
	}

	public static function UserEmailAsLoginUpdateHandler(&$arFields)
	{
		if (isset($arFields['LOGIN']) && empty($arFields['EMAIL'])) {
			unset($arFields['LOGIN']);
		} else {
			if (!empty($arFields['EMAIL'])) {
				$arFields['LOGIN'] = $arFields['EMAIL'];
			}
		}
		return true;
	}
}