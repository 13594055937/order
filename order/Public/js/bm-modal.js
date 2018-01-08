	var rowCount = 0;
	//添加行
	function addRow(){
		rowCount++;
		var t = '<tr id="option'+rowCount+'"><td><input type=\"text\" size=\"12\"></td><td><input type=\"text\" size=\"12\"></td><td><input type=\"text\" size=\"12\"></td><td><span class=\"green\">&#165;20</span></td><td><input name=\"#\" type=\"checkbox\" value=\"\"></td><td><span class=\"cutdown\" onclick="delRow('+rowCount+')">-</span></td></tr>'; 
		$("#bm-table tbody").append(t);
	}

	function delRow(rowIndex){
		$("#option"+ rowIndex).remove();
		alert("删除了一行");
	}
