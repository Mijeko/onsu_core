<?
global $MODULE_ID;
if (!strlen($MODULE_ID) > 0) return;

// *******************************************************************************************************
// Install new right system: operation and tasks
// *******************************************************************************************************
// ############ MODULE OPERATION ###########
$arFOp = Array();
$arFOp[] = Array($MODULE_ID . '_settings', $MODULE_ID, '', 'module');

// ############ MODULE TASKS ###########
$arTasksF = Array();
$arTasksF[] = Array($MODULE_ID . '_denied', 'D', $MODULE_ID, 'Y', '', 'module');
$arTasksF[] = Array($MODULE_ID . '_edit', 'F', $MODULE_ID, 'Y', '', 'module');
$arTasksF[] = Array($MODULE_ID . '_full_access', 'W', $MODULE_ID, 'Y', '', 'module');


//Operations in Tasks
$arOInT = Array();
$arOInT[$MODULE_ID . '_denied'] = array();
$arOInT[$MODULE_ID . '_edit'] = array(
	$MODULE_ID . '_edit',
);

$arOInT[$MODULE_ID . '_full_access'] = Array(
	$MODULE_ID . '_edit',
	$MODULE_ID . '_settings'
);

foreach ($arFOp as $ar)
	$DB->Query("
		INSERT INTO b_operation
		(NAME,MODULE_ID,DESCRIPTION,BINDING)
		VALUES
		('" . $ar[0] . "','" . $ar[1] . "','" . $ar[2] . "','" . $ar[3] . "')
	", false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

foreach ($arTasksF as $ar)
	$DB->Query("
		INSERT INTO b_task
		(NAME,LETTER,MODULE_ID,SYS,DESCRIPTION,BINDING)
		VALUES
		('" . $ar[0] . "','" . $ar[1] . "','" . $ar[2] . "','" . $ar[3] . "','" . $ar[4] . "','" . $ar[5] . "')
	", false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

// ############ b_group_task ###########
$sql_str = "
	INSERT INTO b_group_task
	(GROUP_ID,TASK_ID)
	SELECT MG.GROUP_ID, T.ID
	FROM
		b_task T
		INNER JOIN b_module_group MG ON MG.G_ACCESS = T.LETTER
	WHERE
		T.SYS = 'Y'
		AND T.BINDING = 'module'
		AND MG.MODULE_ID = '$MODULE_ID'
		AND T.MODULE_ID = MG.MODULE_ID
";
$z = $DB->Query($sql_str, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

// ############ b_task_operation ###########
foreach ($arOInT as $tname => $arOp) {
	$sql_str = "
		INSERT INTO b_task_operation
		(TASK_ID,OPERATION_ID)
		SELECT T.ID, O.ID
		FROM
			b_task T
			,b_operation O
		WHERE
			T.SYS='Y'
			AND T.NAME='" . $tname . "'
			AND O.NAME in ('" . implode("','", $arOp) . "')
	";
	$z = $DB->Query($sql_str, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);
}

global $CACHE_MANAGER;
if (is_object($CACHE_MANAGER)) {
	$CACHE_MANAGER->CleanDir("b_task");
	$CACHE_MANAGER->CleanDir("b_task_operation");
}