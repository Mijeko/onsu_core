<?

namespace Yenisite\Core;

use Bitrix\Main\Data\Cache as BXCache;

class Cache extends \CPHPCache
{
	const BASE_DIR = 'romza_settings';
	/**
	 * @var bool
	 */
	protected $clearCache = false;
	/**
	 * @var bool
	 */
	protected $clearCacheSession = false;

	/**
	 * Wrapper for \Bitrix\Main\Data\Cache
	 * exec offClearCache method before InitCache
	 * @param $TTL
	 * @param $uniq_str
	 * @param bool $initdir
	 * @param string $basedir
	 * @return bool
	 */
	public function InitCache($TTL, $uniq_str, $initdir = false, $basedir = self::BASE_DIR)
	{
		$this->offClearCache();
		$return = parent::InitCache($TTL, $uniq_str, $initdir, $basedir);
		$this->onClearCache();
		return $return;
	}

	/**
	 * remove clearCache behavior if it was switched on
	 */
	public function offClearCache()
	{
		$this->setClearCache(BXCache::shouldClearCache());
		if ($this->getClearCache()) BXCache::setClearCache(false);
		$this->setClearCacheSession((isset($_SESSION["SESS_CLEAR_CACHE"]) && $_SESSION["SESS_CLEAR_CACHE"] === "Y"));
		if ($this->getClearCacheSession()) {
			unset($_SESSION["SESS_CLEAR_CACHE"]);
			BXCache::setClearCacheSession(false);
		};
	}

	/**
	 * set clearCache behavior if it was switched off
	 */
	public function onClearCache()
	{
		if ($this->getClearCache()) BXCache::setClearCache(true);
		if ($this->getClearCacheSession()) {
			BXCache::setClearCacheSession(true);
			$_SESSION["SESS_CLEAR_CACHE"] = "Y";
		}
	}

	/**
	 * @return boolean
	 */
	public function getClearCache()
	{
		return $this->clearCache;
	}

	/**
	 * @param boolean $clearCache
	 */
	public function setClearCache($clearCache)
	{
		$this->clearCache = $clearCache;
	}

	/**
	 * @return boolean
	 */
	public function getClearCacheSession()
	{
		return $this->clearCacheSession;
	}

	/**
	 * @param boolean $clearCacheSession
	 */
	public function setClearCacheSession($clearCacheSession)
	{
		$this->clearCacheSession = $clearCacheSession;
	}



}