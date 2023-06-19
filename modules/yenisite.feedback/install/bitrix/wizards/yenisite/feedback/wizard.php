<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule("iblock");

class Step1 extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("Step1");

        $this->SetTitle(GetMessage("ENTER_DATA"));

        $this->SetNextStep("Step2");

        $this->SetCancelStep("Cancel");
    }

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();

		if(!$wizard->IsCancelButtonClick())
		{
			$install_type = $wizard->GetVar("install_type");
			if(empty($install_type))
				$this->SetError(GetMessage("SELECT_INSTALL_TYPE_ERR"), "install_type");

			//$site = $wizard->GetVar("site");
			//if(empty($site))
			//	$this->SetError(GetMessage("SELECT_SITE_ERR"), "site[]");
                        
			$site_for_install = $wizard->GetVar("site_for_install");
			if(empty($site_for_install))
				$this->SetError(GetMessage("SELECT_SITE_FOR_INSTALL_ERR"), "site_for_install");
		}
	}

	function ShowStep() 
	{
            
            $this->content .= '<table class="wizard-data-table">';

            /*$this->content .= '<tr><th align="right">'.GetMessage("SELECT_SITE").':</th><td>';
            $rsSites = CSite::GetList($by="sort", $order="desc",Array());
            while ($arSite = $rsSites->Fetch())
            {
                    $this->content .=  "[".$arSite['LID']."]". $this->ShowCheckboxField("site[]", $arSite['LID']). " ". $arSite['NAME']. "<br/>";
            }
            $this->content .= '</td></tr>';
            */
            
            $this->content .= '<tr><th align="right">'.GetMessage("SELECT_SITE_FOR_INSTALL").':</th><td>';
            $rsSites = CSite::GetList($by="sort", $order="desc",Array());
            while ($arSite = $rsSites->Fetch())
            {
                    $this->content .=  "[".$arSite['LID']."]". $this->ShowRadioField("site_for_install", $arSite['LID']). " ". $arSite['NAME']. "<br/>";
            }
            $this->content .= '</td></tr>';
            
            
            $this->content .= '<tr><th align="right">'.GetMessage("ENTER_DIR").':</th><td>';
            $this->content .= $this->ShowInputField("text", "folder", Array("size" => 25));
            $this->content .= '</td></tr>';

            $this->content .= '<tr><th align="right">'.GetMessage("SELECT_INSTALL_TYPE").':</th><td>';
            $this->content .= $this->ShowRadioField("install_type", "guestbook"). GetMessage("GUESTBOOK") . "<br />";
            $this->content .= $this->ShowRadioField("install_type", "question-answer"). GetMessage("QA") . "<br />";
            $this->content .= $this->ShowRadioField("install_type", "feedback"). GetMessage("FEEDBACK") . "<br />";
            $this->content .='</td></tr>';

            $this->content .= '<tr><th align="right">'.GetMessage("ENTER_EVENT_NAME").':</th><td>';
            $this->content .= $this->ShowInputField("text", "event_type", Array("size" => 25));
            $this->content .= '</td></tr>';

            $this->content .= '</table>';


            $wizard =& $this->GetWizard();
            $formName = $wizard->GetFormName();
            $nextButton = $wizard->GetNextButtonID();

            $folder = $wizard->GetRealName("folder");
            $event_type = $wizard->GetRealName("event_type");
//		$install_type = $wizard->GetRealName("install_type");
		                
            $INVALID_DIR = GetMessage('INVALID_DIR');
            $INVALID_EVENT_NAME = GetMessage('INVALID_EVENT_NAME');
		$this->content .= <<<JS
		<script type="text/javascript">
		
		function CheckMainFields()
		{
                	var form = document.forms["{$formName}"];
			if (!form)
				return;
		
			var folder = form.elements["{$folder}"].value;
			var event_type = form.elements["{$event_type}"].value;
				
                	var folderTest = "^[_0-9a-zA-Z-]";
                	var regex = new RegExp(folderTest);
                	if(!regex.test(folder) || folder.length < 1)
			{
				alert("{$INVALID_DIR}");
				return false;
                        }                

                	var eventTest = "^[_0-9a-zA-Z]";
                	var regex = new RegExp(eventTest);
                	if(!regex.test(event_type) || event_type.length < 1)
			{
				alert("{$INVALID_EVENT_NAME}");
				return false;
                        }                
		}
		

		function AttachEvent()
		{
			var form = document.forms["{$formName}"];
			if (!form)
                		return;
		
			var nextButton = form.elements["{$nextButton}"];
                	if (!nextButton)
				return;
		
			nextButton.onclick = CheckMainFields;
		}
		
                if (window.addEventListener) 
			window.addEventListener("load", AttachEvent, false);
		else if (window.attachEvent) 
			window.attachEvent("onload", AttachEvent);
		</script>
JS;
		
	}
}


class Step2 extends CWizardStep
{
	function InitStep()
	{
	        //Step ID
		$this->SetTitle(GetMessage("FINISH"));
		$this->SetStepID("Step2");

	        //Nav
	        $this->SetCancelStep("Cancel");
        	$this->SetCancelCaption(GetMessage("COMPLETE"));
	}

	function OnPostForm()
	{
/*		$wizard = &$this->GetWizard();
		$install_type = $wizard->GetVar("install_type");
		$wizard->SetCurrentStep($install_type);
*/
	}

	function ShowStep()
	{

		$wizard = &$this->GetWizard();
		$folder = $wizard->GetVar("folder");
		$install_type = $wizard->GetVar("install_type");
		//$site = $wizard->GetVar("site");
		$event_type = $wizard->GetVar("event_type");
                
                $site_for_install = $wizard->GetVar("site_for_install");
                $rsSites = CSite::GetByID($site_for_install);
                $arSite = $rsSites->Fetch();
                $site_dir = $arSite['DIR'];
                
		switch($install_type)
		{
			case "guestbook";
				$iblock_name = GetMessage("GUESTBOOK");
				$element_name = GetMessage("GB_ELEMENT_NAME");
				$element_name2 = GetMessage("GB_ELEMENT_NAME2");
				$elements_name = GetMessage("GB_ELEMENTS_NAME");

				$iblock_name_en = "Guest book";
				$element_name_en = "Record";
				$elements_name_en = "Records";

				$type_event_name = GetMessage("GB_EVENT_NAME");
                                
                                $public_folder = 'guestbook';
			break;
			case "question-answer";
				$iblock_name = GetMessage("QA");
				$element_name = GetMessage("QA_ELEMENT_NAME");
				$element_name2 = GetMessage("QA_ELEMENT_NAME2");
				$elements_name = GetMessage("QA_ELEMENTS_NAME");

				$iblock_name_en = "Question-answer";
				$element_name_en = "Answer";
				$elements_name_en = "Answers";

				$type_event_name = GetMessage("QA_EVENT_NAME");
                                
                                $public_folder = 'qa';
			break;
			case "feedback";
				$iblock_name = GetMessage("FEEDBACK");
				$element_name = GetMessage("FB_ELEMENT_NAME");
				$element_name2 = GetMessage("FB_ELEMENT_NAME2");
				$elements_name = GetMessage("FB_ELEMENTS_NAME");

				$iblock_name_en = "Feedbacks";
				$element_name_en = "Message";
				$elements_name_en = "Messages";

				$type_event_name = GetMessage("FB_EVENT_NAME");
                                
                                $public_folder = 'feedback';
			break;
		}

		//Create IBlock Type
                $arFields = Array(
				'ID'=>'yenisite_feedback',
				'SECTIONS'=>'Y',
				'IN_RSS'=>'N',
				'SORT'=>100,
				'LANG'=>Array(
					'en'=>Array(
					'NAME'=>'Feedback',
					'SECTION_NAME'=>'Section',
					'ELEMENT_NAME'=>'Message'),
					'ru'=>Array(
					'NAME'=> GetMessage("FEEDBACK"),
					'SECTION_NAME'=>GetMessage("SECTION_NAME"),
					'ELEMENT_NAME'=>GetMessage("ELEMENT_NAME")
					)
                )
            );

                $obBlocktype = new CIBlockType;
                $res_tib = $obBlocktype->Add($arFields);

                if($res_tib)
                    $this->content = GetMessage("IB_TYPE_CREATED", Array('#IBLOCK_TYPE#' => $res_tib));
                else
                {
                    $res_tib = "yenisite_feedback";
                    $this->content = GetMessage("IB_TYPE_FOUND", Array('#IBLOCK_TYPE#' => $res_tib));
                }
                
                
                $test_ib = CIBlock::GetList(Array(), Array('TYPE' => $res_tib, 'CODE' => $install_type . "%"), false);
                
                //var_dump($test_ib);
                $i = 0;
                while($ar_res = $test_ib->Fetch())
                    $i++;
                
                $_iblock_name = $iblock_name;
		if($i != 0)
		{
                    $install_type = $install_type . "_" . $i;
                    $_iblock_name = $iblock_name . "_" . $i;
		}

		//Create IBlock
		$ib = new CIBlock;
		$arFields = Array(
				  "ACTIVE" => "Y",
				  "NAME" => $_iblock_name,
				  "CODE" => $install_type,
				  "LIST_PAGE_URL" => $site_dir . $folder. "/",
				  "DETAIL_PAGE_URL" => $site_dir . $folder. "/",
				  "IBLOCK_TYPE_ID" => "yenisite_feedback",
				  "INDEX_ELEMENT" => "Y",
				  "SITE_ID" => $site_for_install,
				  "SORT" => "500",
				  "PICTURE" => "",
				  "DESCRIPTION" => "",
				  "DESCRIPTION_TYPE" => "text",
				  "GROUP_ID" => Array("2"=>"R"),
				  "ELEMENT_NAME" => $element_name,
				  "ELEMENTS_NAME" => $elements_name,
				  "ELEMENT_ADD" => GetMessage("ELEMENT_ADD") . " ". $element_name2,
				  "ELEMENT_EDIT" => GetMessage("ELEMENT_EDIT") . " ". $element_name2,
				  "ELEMENT_DELETE" => GetMessage("ELEMENT_DELETE") . " ". $element_name2,
				  );
                
                //var_dump($arFields);
		$res_ib = $ib->Add($arFields);

		if($res_ib)
                {
                    $this->content .= '<br />'. GetMessage("IB_CREATED", array('#IBLOCK#' => $res_ib));
                }
		else
                    $this->SetError(GetMessage("IB_CREATED_ERROR"), "res_ib");



		//Create IBlock properties 
                $arFields1 = Array(
                                "NAME" => GetMessage("NAME_FIELD"),
                                "ACTIVE" => "Y",
                                "SORT" => "100",
                                "CODE" => "name",
                                "PROPERTY_TYPE" => "S",
                                "IBLOCK_ID" => $res_ib,
                                "IS_REQUIRED" => "Y",
                            );
                /*$arFields2 = Array(
                                "NAME" => "",
                                "ACTIVE" => "Y",
                                "SORT" => "101",
                                "CODE" => "element",
                                "PROPERTY_TYPE" => "E",
                                "IBLOCK_ID" => $res_ib
                            );*/
                $arFields3 = Array(
                                "NAME" => GetMessage("PHONE_FIELD"),
                                "ACTIVE" => "Y",
                                "SORT" => "102",
                                "CODE" => "phone",
                                "PROPERTY_TYPE" => "S",
                                "IBLOCK_ID" => $res_ib,
                                "IS_REQUIRED" => "Y",
                            );
                $arFields4 = Array(
                                "NAME" => "E-Mail",
                                "ACTIVE" => "Y",
                                "SORT" => "103",
                                "CODE" => "email",
                                "PROPERTY_TYPE" => "S",
                                "IBLOCK_ID" => $res_ib,
                                "IS_REQUIRED" => "Y",
                            );
                $arFields5 = Array(
                                "NAME" => GetMessage("BIRTHDAY_FIELD"),
                                "ACTIVE" => "Y",
                                "SORT" => "104",
                                "CODE" => "birthday",
                                "PROPERTY_TYPE" => "S",
                                "USER_TYPE" => "DateTime",
                                "IBLOCK_ID" => $res_ib
                            );
                $arFields6 = Array(
                                "NAME" => GetMessage("FILE_FIELD"),
                                "ACTIVE" => "Y",
                                "SORT" => "106",
                                "CODE" => "file",
                                "PROPERTY_TYPE" => "F",
                                "IBLOCK_ID" => $res_ib
                            );
                $arFields7 = Array(
                                "NAME" => GetMessage("IP_FIELD"),
                                "ACTIVE" => "Y",
                                "SORT" => "107",
                                "CODE" => "ip",
                                "PROPERTY_TYPE" => "S",
                                "IBLOCK_ID" => $res_ib,
                                "IS_REQUIRED" => "N",
                            );


                $ibp = new CIBlockProperty;
                $ibp->Add($arFields1);
                //$ibp->Add($arFields2);
                $ibp->Add($arFields3);
                $ibp->Add($arFields4);
                //if ($install_type != 'feedback')
                    //$ibp->Add($arFields5);
                $ibp->Add($arFields6);
                $ibp->Add($arFields7);

                if ($res_ib)
                {
                    if (CModule::IncludeModule("yenisite.feedback"))
                    {
                        if ($install_type == 'question-answer')
                            $print_fields = array('ID', 'NAME', 'email', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'ip', 'timestamp_x', 'ACTIVE');
                        else
                            $print_fields = array('ID', 'NAME', 'email', 'PREVIEW_TEXT', 'ip', 'timestamp_x', 'ACTIVE');
                        
                        $settings = CYSFeedBack::SetIBlockAdminListDisplaySettings($res_ib, $print_fields, 'ID', 'DESC', 20, TRUE);
                    }
                }
                
		//Create EventType
		foreach(Array("en","ru") as $key => $value)
		{
			$et = new CEventType;
			$res_et = $et->Add(array(
					"LID"       	=> $value,
					"EVENT_NAME"    => $event_type,
					"NAME"          => $type_event_name,
					"DESCRIPTION"   => GetMessage("EVENT_TYPE_TEXT"),                                            
                                        )
                                );

			if($res_et)
				$this->content .= "<br />". GetMessage("EVENT_TYPE_CREATED", array('#EVENT_TYPE#' => $event_type, '#SITE#' => $value));
			else
				$this->SetError(GetMessage("EVENT_TYPE_CREATED_ERROR", array('#EVENT_TYPE#' => $event_type, '#SITE#' => $value)), "res_et");
		}


		//Create Message
		$arr = Array(
			"ACTIVE" => "Y",
			"EVENT_NAME" => $event_type,
			"LID" => $site_for_install,
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
			"BCC" => "",
			"SUBJECT" => "#SITE_NAME#: ". $type_event_name,
			"BODY_TYPE" => "text",
			"MESSAGE" => GetMessage("EVENT_MESSAGE_TEXT")
                    );

		$emess = new CEventMessage;
		$res_etsh = $emess->Add($arr);


		if($res_etsh)
			$this->content .= '<br />'. GetMessage("EVENT_MESSAGE_CREATED", array('#EVENT_MESSAGE#' => $res_etsh));
		else
			$this->SetError(GetMessage("EVENT_MESSAGE_CREATED_ERROR"), "res_etsh");

		//Copy public files
		$CopyPublic = CopyDirFiles(
			      $_SERVER['DOCUMENT_ROOT'].$wizard->GetPath().'/public/' . $public_folder,
			      $_SERVER['DOCUMENT_ROOT']. $site_dir . $folder,
			      $rewrite = true,
			      $recursive = true
			    );

                $data = file_get_contents($_SERVER['DOCUMENT_ROOT']. $site_dir . $folder . "/index.php");
                $data = str_replace(array('#IBLOCK_TYPE#', '#IBLOCK_ID#', '#EVENT_NAME#'), array($res_tib, $res_ib, $event_type), $data);
                file_put_contents($_SERVER['DOCUMENT_ROOT']. $site_dir . $folder . "/index.php", $data);
                
                $data = file_get_contents($_SERVER['DOCUMENT_ROOT']. $site_dir . $folder . "/.section.php");
                $data = str_replace('#SECTION_NAME#', $iblock_name, $data);
                file_put_contents($_SERVER['DOCUMENT_ROOT']. $site_dir . $folder . "/.section.php", $data);

                if($CopyPublic)
			$this->content .= '<br />' . GetMessage("PUBLIC_COPY_OK");
		else
			$this->SetError(GetMessage("PUBLIC_COPY_ERROR"), "CopyPublic");

                $obStep =& $wizard->GetCurrentStep();
		$arErrors = $obStep->GetErrors();

	        if (count($arErrors) > 0)	        		
			$this->content .= '<br /><br /><span style="font-weight: bold; color: RED;">' . GetMessage("FEEDBACK_INSTALL_ERROR") . '</span>';
		else
			$this->content .= '<br /><br /><span style="font-weight: bold; color: GREEN;">' . GetMessage("FEEDBACK_INSTALL_OK", array('#FOLDER#' => $site_dir . $folder)) . '</span>';
	}
}


class CancelStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("Cancel");
        $this->SetTitle(GetMessage("MASTER_CANCELED"));
        $this->SetCancelStep("Cancel");
        $this->SetCancelCaption(GetMessage("CLOSE"));
    }

    function ShowStep()
    {
        $this->content .= GetMessage("MASTER_CANCEL");
    }
}
?>