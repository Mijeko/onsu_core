<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/lang/ru/admin/button.php");
header("Content-Type: content=text/html; charset=".strtolower(SITE_CHARSET));?>
/*
Developer: Andrey Shilov
ICQ:	361577459
SKYPE:	zgatsouz
E-MAIL:	andrey@yenisite.ru
*/

/*      */
arButtons['r2_FancyBox'] = ['BXButton',
   {
      id : 'r2_FancyBox', 
      title : 'Resizer 2.0',
      src: '/bitrix/images/fileman/htmledit2/r2pic.jpg',
      disableOnCodeView : true,
      handler: function(){
         this.bNotFocus = true;
         var p = this.pMainObj.GetSelectionObject();
         if (!p || p.tagName != 'IMG')
            p = false;
        this.pMainObj.OpenEditorDialog("r2_FancyBox", p, 500);

      }
   }
];

/*      */
if(window.lightMode)
{
	//arGlobalToolbar[arGlobalToolbar.length] = 'new_line';
	
	arGlobalToolbar.unshift(arButtons['r2_FancyBox']);
}
else{
	arToolbars['standart'][1].unshift(arButtons['r2_FancyBox']);
	
	//oBXEditorUtils.appendButton("r2_FancyBox", arButtons['r2_FancyBox'], "standart");  
	//arParams['property'] = 'new property';
	//oBXEditorUtils.setCustomNodeParams(node,arParams); 
}



/*  -    */
arEditorFastDialogs['r2_FancyBox'] = function(pObj)
{
  <?
    
    /*    2.0 */
    CModule::IncludeModule('yenisite.resizer2');
    /*      */
    $res = CResizer2Set::GetList();
    
    /*      */ 
    $str1 = '<select id="r2small" name="set_small">';
    $str2 = '<select id="r2big" name="set_big">';
    $str = '';
    $str_in = '';
    while($set = $res->GetNext())
    {
	
		//if( SITE_CHARSET == "UTF-8" )
			//$set['NAME'] = iconv("utf-8", "windows-1251", $set['NAME']);
	
	
        if($set['id'] == '1')
            $selected1 = 'selected="selected"';
        else $selected1 = "";
        
        if($set['id'] == '3')
            $selected2 = 'selected="selected"';
        else $selected2 = "";
        

		
        $str .= '<option '.$selected1.' value="'.$set['id'].'">'.$set['NAME'].'</option>';
        $str_in .= '<option '.$selected2.' value="'.$set['id'].'">'.$set['NAME'].'</option>';
    }
        
    $str2 .= $str.'</select>';
    $str1 .= $str_in.'</select>';    
  
  ?>
  
    var OnClose = function(){};
    var OnSave = function(){
       
        if(!BX('r2file').value){
                alert('<?=GetMessage('ALERT')?>');
                return false;
            }
            pObj.pMainObj.insertHTML('<a class="'+BX('r2class').value+'" rel="'+BX('r2rel').value+'" title="'+BX('r2title').value+'" href="/yenisite.resizer2/resizer2GD.php?url='+el.value+'&set='+BX('r2big').value+'"><img alt="'+BX('r2title').value+'" src="/yenisite.resizer2/resizer2GD.php?url='+BX('r2file').value+'&set='+BX('r2small').value+'" /></a>');
            OnClose(pObj);
        }
   
   return {
      title: "<?=GetMessage('MODULE')?>",
      innerHTML : '<table width="100%">'+
                    '<tr><td width="20%"><?=GetMessage('SMALL')?></td><td width="80%"><?=$str1;?></td></tr>'+
                    '<tr><td><?=GetMessage('BIG')?></td><td><?=$str2;?></td></tr>'+
                    '<tr><td><?=GetMessage('IMAGE')?></td><td><input style="width: 200px;" disabled="disabled" type="text" value="" id="r2file" /><input style="float: right;" type="button" value="<?=GetMessage('OPEN')?>" onclick="OpenImageResizer2();" /><input style="float: right;" type="button" value="<?=GetMessage('OPEN_M')?>" onclick="OpenImageResizer3();" /></td></tr>'+
                    '<tr><td><?=GetMessage('TITLE')?></td><td><input style="width: 300px;" type="text" value="" id="r2title" /></td></tr>'+
                    '<tr><td><?=GetMessage('CLASS')?></td><td><input style="width: 300px;" type="text" value="resizer2fancy" id="r2class" /></td></tr>'+
                    '<tr><td><?=GetMessage('REL')?></td><td><input style="width: 300px;" type="text" value="group" id="r2rel" /></td></tr>'+
                    '</table>',
    OnLoad: function(){    
    	window.oBXEditorDialog.SetButtons([
		new BX.CWindowButton(
		{
			title: '<?=GetMessage('SAVE')?>',
			id: 'save',
			name: 'save',
			action: function()
			{
				
				
				OnSave();
				 window.oBXEditorDialog.Close();
                
			}
		}),		
		window.oBXEditorDialog.btnClose
	]);

    }

   };
   
}

function getImageUrl(filename,path,site)
{
    el = document.getElementById('r2file');
    el.value = path + '/' + filename;
    return path + '/' + filename;
}

function getImageUrlMediaLibrary(obj){
	console.log(obj['src']);
	el = document.getElementById('r2file');
    el.value = obj['src'];
    return obj['src'];
}