<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("vote"))
	return;

// VOTE CHANNEL
$vcName = 'BITRONIC2';
$arVoted = CVoteChannel::GetList($by='ID', $order='ASC', Array('SYMBOLIC_NAME'=>$vcName), $is_filtered=true)->Fetch() ;
if (is_array($arVoted))
	return;

$arFields = array(
	'SITE'   => Array(WIZARD_SITE_ID),
	'VOTES'  => 0, 
	'C_SORT' => 100,
	'ACTIVE'      => 'Y',
	'VOTE_SINGLE' => 'Y',
	'USE_CAPTCHA' => 'N',
	'HIDDEN'      => 'N',
	'SYMBOLIC_NAME' => $vcName,
	'TITLE'         => $vcName,
);
$vcID = CVoteChannel::Add($arFields);

// SET PERMISSIONS
CVoteChannel::SetAccessPermissions(
	$vcID,
	$arGroups = array(
		1 => 4, //Administrators (EDIT)
		2 => 2, //All users (PARTICIPATE)
	)
);

// VOTE
$arFields = array(
	"CHANNEL_ID"       => $vcID,
	"C_SORT"           => 100,
	"ACTIVE"           => "Y",
	"DATE_START"       => date("01.01.Y 00:00:00"),
	"DATE_END"         => "01.01.2032 00:00:00",
	"TITLE"            => GetMessage('VOTE_TITLE'),
	"DESCRIPTION"      => "",
	"DESCRIPTION_TYPE" => "html",
	"IMAGE_ID"         => NULL,
	"EVENT1"          => "",
	"EVENT2"          => "",
	"UNIQUE_TYPE"     => 11, // IP & cookie
	"KEEP_IP_SEC"     => 600,
	"DELAY"           => 10,
	"DELAY_TYPE"      => "M",
	"TEMPLATE"        => NULL,
	"RESULT_TEMPLATE" => NULL,
	"NOTIFY"          => "N"
);
$voteID = CVote::Add($arFields);

// QUESTION
$arFields = array(
	"ACTIVE"        => "Y",
	"VOTE_ID"       => $voteID,
	"C_SORT"        => CVoteQuestion::GetNextSort($voteID),
	"QUESTION"      => GetMessage('VOTE_QUESTION'),
	"QUESTION_TYPE" => "html",
	"IMAGE_ID"      => NULL, 
	"DIAGRAM"       => "Y",
	"REQUIRED"      => "Y",
	"DIAGRAM_TYPE"  => 'histogram', 
	"TEMPLATE"      => NULL,
	"TEMPLATE_NEW"  => NULL
);
$questionID = CVoteQuestion::Add($arFields);

// ANSWERS
$arAnswers = array('Google', 'Microsoft', 'Apple', 'Facebook', GetMessage('YANDEX'));

for ($i = 0; $i < 5; $i++) {
	$arAnswer = array(
		"MESSAGE" => $arAnswers[$i],
		"QUESTION_ID" => $questionID,
		"ACTIVE" => 'Y',
		"C_SORT" => "".$i*100+100,
		"FIELD_TYPE" => "",
		"FIELD_WIDTH" => "",
		"FIELD_HEIGHT" => "",
		"FIELD_PARAM" => "",
		"COLOR" => ""
	);
	CVoteAnswer::Add($arAnswer);
}

?>
