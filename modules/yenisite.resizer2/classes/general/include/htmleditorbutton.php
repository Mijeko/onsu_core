<script>
function getImageUrl(filename,path,site) {
	el = document.getElementById('r2file');
	el.value = path + '/' + filename;
	return path + '/' + filename;
}

function getImageUrlMediaLibrary(obj) {
	console.log(obj['src']);
	el = document.getElementById('r2file');
	el.value = obj['src'];
	return obj['src'];
}
(function() {
	var OnClose = function() {};
	var OnSave = function(editor) {

		if (!BX('r2file').value) {
			alert('<?=GetMessage('ALERT')?>');
			return false;
		}
		if (!editor) {
			return false;
		}
		var imgHref = '/yenisite.resizer2/resizer2GD.php?url='+el.value+'&set='+BX('r2big').value;
		var imgSrc = '/yenisite.resizer2/resizer2GD.php?url='+BX('r2file').value+'&set='+BX('r2small').value;
		if (BX('r2static').checked) {
			var data = {mode: 'path'};
			BX.showWait();
			BX.ajax.Setup({async: false}, true);
			BX.ajax.get(imgHref, data, function(path) {
				imgHref = path;
			});
			BX.ajax.Setup({async: false}, true);
			BX.ajax.get(imgSrc, data, function(path) {
				imgSrc = path;
			});
			BX.closeWait();
		}
		editor.InsertHtml('<a class="'+BX('r2class').value+'" rel="'+BX('r2rel').value+'" title="'+BX('r2title').value+'" href="'+imgHref+'"><img alt="'+BX('r2title').value+'" title="'+BX('r2title').value+'" src="'+imgSrc+'" /></a>');

		OnClose(editor);
	};

	var originalTextWnd = null;
	var buttonSave = new BX.CWindowButton({
		title: '<?=GetMessage('SAVE')?>',
		id: 'save',
		name: 'save',
		action: function() {
			OnSave(resizer_editor);
			originalTextWnd.Close();
		}
	});

	var originalTextHandler = function(editor) {
		if (!originalTextWnd) {
			resizer_editor = editor;
			<?
				CModule::IncludeModule('yenisite.resizer2');
				$res = CResizer2Set::GetList();

				$str1 = '<select id="r2small" name="set_small">';
				$str2 = '<select id="r2big" name="set_big">';

				while ($set = $res->GetNext()) {
					$selected1 = ($set['id'] == '1') ? 'selected="selected"' : '';
					$selected2 = ($set['id'] == '3') ? 'selected="selected"' : '';
					
					$str2 .= '<option '.$selected1.' value="'.$set['id'].'">'.str_replace("'","\'", $set['NAME']).'</option>';
					$str1 .= '<option '.$selected2.' value="'.$set['id'].'">'.str_replace("'","\'", $set['NAME']).'</option>';
				}

				$str2 .= '</select>';
				$str1 .= '</select>';
			?>

			var res = '<style>.r2_table td{padding-bottom: 10px;}</style>'+
				'<table width="100%" class="r2_table">'+
				'<tr><td width="20%"><?=GetMessage('SMALL')?></td><td width="80%"><?=$str1;?></td></tr>'+
				'<tr><td><?=GetMessage('BIG')?></td><td><?=$str2;?></td></tr>'+
				'<tr><td><?=GetMessage('IMAGE')?></td><td><input style="width: 200px;" disabled="disabled" type="text" value="" id="r2file" /><input style="float: right;" type="button" value="<?=GetMessage('OPEN')?>" onclick="OpenImageResizer2();" /><input style="float: right;" type="button" value="<?=GetMessage('OPEN_M')?>" onclick="OpenImageResizer3();" /></td></tr>'+
				'<tr><td><?=GetMessage('STATIC')?><span id="r2static-hint"></td><td><input type="checkbox" id="r2static" checked="checked"></span></td></tr>'+
				'<tr><td><?=GetMessage('TITLE')?></td><td><input style="width: 300px;" type="text" value="" id="r2title" /></td></tr>'+
				'<tr><td><?=GetMessage('CLASS')?></td><td><input style="width: 300px;" type="text" value="resizer2fancy" id="r2class" /></td></tr>'+
				'<tr><td><?=GetMessage('REL')?></td><td><input style="width: 300px;" type="text" value="group" id="r2rel" /></td></tr>'+
			'</table>';

			originalTextWnd = new BX.CDialog({
				content: res,
				resizable: false,
				width: 750,
				height: 550,
				title: '<?=GetMessageJS('RZ_RESIZER_HTML_EDITOR_BUTTON_NAME')?>',
				buttons: [
					buttonSave,
					BX.CDialog.btnClose
				]
			});
			originalTextHandler.apply(this, [editor]);
			BX.hint_replace(BX('r2static-hint'), '<?=GetMessage('STATIC_HINT')?>');
		} else {
			originalTextWnd.Show();
		}
		originalTextWnd.Get().style.zIndex = 3010;
	};

	function applyForEditor(editor) {
		ed = editor;
		editor.AddButton({
			id : 'r2_FancyBox',
			src : '/bitrix/images/fileman/htmledit2/r2pic.jpg',
			name : '<?=CUtil::JSEscape(GetMessage('RZ_RESIZER_HTML_EDITOR_BUTTON_NAME'))?>',
			codeEditorMode : true,
			handler : function() {originalTextHandler(editor);},
			toolbarSort: 1
		});
	}

	if (window.BXHtmlEditor && window.BXHtmlEditor.editors) {
		for (var id in window.BXHtmlEditor.editors) {
			if (window.BXHtmlEditor.editors.hasOwnProperty(id)) {
				applyForEditor(window.BXHtmlEditor.Get(id))
			}
		}
	}

	BX.addCustomEvent("OnEditorInitedBefore", applyForEditor);
})();
</script>