<?
if(IsModuleInstalled('rest'))
{
	$updater->CopyFiles("install/components", "components");
	$updater->CopyFiles("install/js", "js");
}

if ($updater->CanUpdateDatabase() && $DB->type == "MYSQL")
{
	if(IsModuleInstalled('rest'))
	{
		\CAgent::AddAgent("\\Bitrix\\Rest\\Configuration\\Helper::sendStatisticAgent();", "rest", "N", 86400, "", "Y", \ConvertTimeStamp(time()+\CTimeZone::GetOffset()+600, "FULL"));
		\CAgent::AddAgent("\\Bitrix\\Rest\\Configuration\\Structure::clearContentAgent();", "rest", "N", 86400, "", "Y", \ConvertTimeStamp(time()+\CTimeZone::GetOffset()+900, "FULL"));
	}

	$app = \Bitrix\Main\Config\Option::get('rest', 'uses_configuration_app', '');
	if(!empty($app))
	{
		$basicApp = [
			'vertical_crm' => $app
		];
		$data = \Bitrix\Main\Web\Json::encode($basicApp);
		\Bitrix\Main\Config\Option::set('rest', 'uses_basic_app_list', $data);
		\Bitrix\Main\Config\Option::delete('rest', ['name' => 'uses_configuration_app']);
	}
}

if ($updater->CanUpdateDatabase() && $updater->TableExists('b_rest_event'))
{
	if ($DB->type == "MYSQL")
	{
		if (!$updater->TableExists("b_rest_owner_entity"))
		{
			$DB->Query("
				CREATE TABLE b_rest_owner_entity(
					ID INT(11) NOT NULL AUTO_INCREMENT,
					OWNER_TYPE CHAR(1) NOT NULL,
					OWNER INT(11) NOT NULL,
					ENTITY_TYPE VARCHAR(32) NOT NULL,
					ENTITY VARCHAR(32) NOT NULL,
					PRIMARY KEY (ID)
				);
			");
		}
		if ($updater->TableExists("b_rest_owner_entity"))
		{
			if (!$DB->IndexExists("b_rest_owner_entity", array("ENTITY_TYPE", "ENTITY", ), true))
			{
				$DB->Query("CREATE UNIQUE INDEX ix_b_rest_owner_entity ON b_rest_owner_entity(ENTITY_TYPE, ENTITY)");
			}
		}
	}
}

if ($updater->CanUpdateDatabase() && $updater->TableExists('b_rest_event'))
{
	if ($DB->type == "MYSQL")
	{
		$DB->Query("
			update b_rest_usage_entity
			inner join b_rest_app on b_rest_app.CODE = b_rest_usage_entity.ENTITY_CODE
			set b_rest_usage_entity.ENTITY_CODE = b_rest_app.CLIENT_ID
			where b_rest_usage_entity.ENTITY_CODE <> b_rest_app.CLIENT_ID
			and b_rest_usage_entity.ENTITY_TYPE = 'A'
		");
	}
}
