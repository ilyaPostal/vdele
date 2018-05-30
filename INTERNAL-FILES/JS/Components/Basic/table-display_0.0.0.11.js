
//
// Require w3.css
// Require main.js
//

//
// Control : {
//
//		"Columns"	: [ { "ColumnName" : string, "DisplayName" : string, "style" : string } ]	// Имеются в виду отображаемые колонки
//		, "Data"		: [
//			{ "ColumnName"	: "Value", ... }
//		]
//		, "IsDisplayHeader"	: bool
//		, "IsDisplayEdit"	: bool
//
//		// Calls only
//		, GetHTML()			: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"		: ID
//			, "ParentControlID"	: ID
//
//			// UI
//			"TableCSS"			: string
//			, "TableStyle"		: string  // ['All', 'InnerBorders']
//			, "TableClass"		: string
//			, "TBodyCSS"		: string
//			, "SelectRow"		: { 'Class' : string, 'ColumnName' : string, 'ColumnValue' : string }
//			, "IsHover"			: bool
//			, "IsHandCursor"	: bool
//
//			, SetFixedHeader()	: function(Control)
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 		: function(Control)
//			, "SelectRowCallBack"	: function(Control, SelectRow { "ColumnName"	: "Value", ... })
//			, "EditRowCallBack"		: function(Control, SelectRow { "ColumnName"	: "Value", ... }, IsNewTab)
//		}
// }
// 
//

function DisplayTableControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main					= Main						|| {};
	Main.Columns			= Main.Columns				|| [];
	for (var i = 0; i < Main.Columns.length; i++) {
		Main.Columns[i].ColumnName 	= Main.Columns[i].ColumnName 	|| '';
		Main.Columns[i].DisplayName	= Main.Columns[i].DisplayName	|| '';
		Main.Columns[i].style		= Main.Columns[i].style			|| '';
	}
	Main.Data				= Main.Data					|| [];
	for (var i = 0; i < Main.Data.length; i++) {
		var DataRow = Main.Data[i];
		for (var Prop in DataRow) {
			DataRow[Prop]	= DataRow[Prop] || '';
		}
	}

	Main.IsDisplayHeader	= Main.IsDisplayHeader		|| false;
	Main.IsDisplayEdit		= Main.IsDisplayEdit		|| false;
	
	Main.GetHTML			= function() { return DisplayTableControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	Internal.TableCSS			= Internal.TableCSS				|| '';
	Internal.TableStyle			= Internal.TableStyle			|| 'All';
	var TableClassDefault		= (Internal.TableStyle == 'All' 			? 'w3-table-all'					: '');
	TableClassDefault			= (Internal.TableStyle == 'InnerBorders'	? 'w3-table w3-bordered w3-striped'	: TableClassDefault);
	Internal.TableClass			= Internal.TableClass			|| TableClassDefault;
	Internal.TBodyCSS			= Internal.TBodyCSS				|| '';
	Internal.SelectRow			= Internal.SelectRow			|| null;
	//Internal.IsHover			= !('IsHover'	   in Internal)	|| Internal.IsHover;
	//Internal.IsHandCursor		= !('IsHandCursor' in Internal)	|| Internal.IsHandCursor;
	Internal.IsHover			= Internal.IsHover				|| false;
	Internal.IsHandCursor		= Internal.IsHandCursor			|| false;
	
	
	//
	// Events
	//
	
	Events						= Events					|| {};
	Events.ChangeCallBack		= Events.ChangeCallBack		|| null;
	Events.SelectRowCallBack	= Events.SelectRowCallBack	|| null;
	Events.EditRowCallBack		= Events.EditRowCallBack	|| null;
	
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]	= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function DisplayTableControlGetHTML(Control) {

	var html = '';
	if (Control.Data.length) {
		html += '<div id="' + Control.Internal.RootElemID + '">\
			<table class="' + Control.Internal.TableClass + ' ' + (Control.Internal.IsHover ? ' w3-hoverable' : '') + '" style="' + Control.Internal.TableCSS 
					+ '"  onclick="DisplayTableControlPrivateOnClick(event)" onmouseup="DisplayTableControlPrivateOnMouseUp(event)" '
					+ '>\
		';
		
		if (Control.IsDisplayHeader) {
			html += '<thead>';
			html += '<tr>';
			for (var i = 0; i < Control.Columns.length; i++) {
				html += '<th' + (Control.Columns[i].style ? (' style="' + Control.Columns[i].style + '" ') : '') + '>' + Control.Columns[i].DisplayName + '</th>';
			}
			if (Control.IsDisplayEdit) {
				html += '<th>&nbsp;</th>';
			}
			html += '</tr>';
			html += '</thead>';
		}
		
		html += '<tbody style="' + (Control.Internal.IsHandCursor ? 'cursor: pointer;' : '') + Control.Internal.TBodyCSS + '">';
		
		for (var i = 0; i < Control.Data.length; i++) {
			var Row			= Control.Data[i];
			var RowClass	= '';
			if (Control.Internal.SelectRow) {
				var sr = Control.Internal.SelectRow;
				sr.Class = sr.Class || 'table-row-select';
				RowClass = Row[sr.ColumnName] == sr.ColumnValue ? sr.Class : '';
			}
			html += '<tr ' + (RowClass ? (' class="' + RowClass + '" ') : '') +  '>';
			for (var j = 0; j < Control.Columns.length; j++) {
				var ColumnName = Control.Columns[j].ColumnName;
				html += '<td>' + Row[ColumnName] + '</td>';
			}
			if (Control.IsDisplayEdit) { // fa-lg 
				html += '<td><a class="a-edit w3-hover-text-green" href="javascript:void(0)" oncontextmenu="return false;"><i class="a-edit far fa-edit w3-large"></i></a></td>';
			}
			html += '</tr>';
		}
		
		html += '</tbody>';
		
		html += '</table></div>';
	} else {
		html += '<div id="' + Control.Internal.RootElemID + '" class="w3-container"><h4>Данные отсутствуют</h4></div>';
	}

	//	
	// Static CSS
	//
	
	if (!window.DisplayTableControlCSS) {
		html += '<style>';
		html += '.table-row-select { -webkit-box-shadow: 0px 0.5px 5px 0px #A6A6A6; box-shadow: 0px 0.5px 5px 0px #A6A6A6;transform: scale(1); }' 
					+ ' @-moz-document url-prefix() { .table-row-select { box-shadow: 0px 0px 2px 1px #A6A6A6;transform: scale(1); border-bottom: none !important;} }';
		
		html += '</style>';
		
//		html += '<style>';
//		html += '.table-row-select { -webkit-box-shadow: 0px 0.5px 5px 0px #A6A6A6; box-shadow: 0px 0.5px 5px 0px #A6A6A6;/*transform: scale(1);*/ }' 
//					+ ' @-moz-document url-prefix() { .table-row-select { box-shadow: 0px 0px 2px 1px #A6A6A6; transform: scale(1); border-bottom: none !important;} }';
//		html += '.w3-table tr, .w3-table-all tr { position: relative; } .w3-table td, .w3-table-all td { position: relative; z-index: -1; }';
//		html += '</style>';
		
		window.DisplayTableControlCSS = true;
	}
	
	return html;
}

function DisplayTableControlOnChange(Control) {
	
//	var eventTarget		= event.target;
//	var rootElement		= event.currentTarget.parentElement;
//	var ControlID		= rootElement.id;
//	var Control			= window[ControlID];
	
	//
	// Data
	//
	
	//...
	
	//
	// UI
	//
	
	//...
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null)
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
}

function DisplayTableControlOnSelectRow(Control, CurrentRow) {
	
	//
	// Raise Event
	//
	
	if (Control.Events.SelectRowCallBack !== null)
		setTimeout(function() { Control.Events.SelectRowCallBack(Control, CurrentRow); }, 0);
}

function DisplayTableControlOnEditRow(Control, CurrentRow, IsNewTab) {
	
	//
	// Raise Event
	//
	
	if (Control.Events.EditRowCallBack !== null)
		setTimeout(function() { Control.Events.EditRowCallBack(Control, CurrentRow, IsNewTab); }, 0);
}


function DisplayTableControlPrivateOnClick(event) {
	
	var Table		= event.currentTarget;
	var Control		= window[Table.parentElement.id];
	var EventTarget = event.target;
	
	if (EventTarget.nodeName == "TD" && EventTarget.parentElement.parentElement.nodeName == "TBODY") {
		
		var DataIndex = Control.IsDisplayHeader ? EventTarget.parentElement.rowIndex - 1 : EventTarget.parentElement.rowIndex;
		DisplayTableControlOnSelectRow(Control, Control.Data[DataIndex]);
		
	} else if (EventTarget.className.indexOf('a-edit') != -1) { 
		
		var TableTD = EventTarget;
		while (TableTD.nodeName != 'TD')
			TableTD = TableTD.parentElement;
		var DataIndex = Control.IsDisplayHeader ? TableTD.parentElement.rowIndex - 1 : TableTD.parentElement.rowIndex;
		DisplayTableControlOnEditRow(Control, Control.Data[DataIndex], false);
	
	} else {
		DisplayTableControlOnSelectRow(Control, null);
	}

}

//
// Для обработки нажатия средней и правой кнопки мыши по ссылке
//

function DisplayTableControlPrivateOnMouseUp(event) {
	
	var Table		= event.currentTarget;
	var Control		= window[Table.parentElement.id];
	var EventTarget = event.target;
	
	if ((event.which == 2 || event.which == 3) && EventTarget.className.indexOf('a-edit') != -1) { 
		event.preventDefault();
		var TableTD = EventTarget;
		while (TableTD.nodeName != 'TD')
			TableTD = TableTD.parentElement;
		var DataIndex = Control.IsDisplayHeader ? TableTD.parentElement.rowIndex - 1 : TableTD.parentElement.rowIndex;
		DisplayTableControlOnEditRow(Control, Control.Data[DataIndex], true);
	}
	
}
