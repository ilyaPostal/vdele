
//
// Require w3.css
// Require main.js
//

//	// Не доделан
// Control : {
//
//		"Columns"	: [ { "ColumnName" : string, "DisplayName" : string, "Type" : string, "style" : string } ]	
//		, "Data"		: [
//			{ "ColumnName"	: "Value", ... }
//		]
//		, "IsDisplayHeader"	: bool
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
//			, "TableClass"		: string
//			, "TBodyCSS"		: string
//
//			, SetFixedHeader()	: function(Control)
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 		: function(Control)
//		}
// }
// 
//

function EditTableControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main			= Main			|| {};
	Main.Columns	= Main.Columns	|| [];
	for (var i = 0; i < Main.Columns.length; i++) {
		var Ci			= Main.Columns[i];
		Ci.ColumnName 	= Ci.ColumnName 	|| '';
		Ci.DisplayName	= Ci.DisplayName	|| '';
		Ci.Type			= Ci.Type			|| 'Text';
		Ci.style		= Ci.style			|| '';
	}
	Main.Data = Main.Data || [];
	for (var i = 0; i < Main.Data.length; i++) {
		var DataRow = Main.Data[i];
		for (var Prop in DataRow) {
			DataRow[Prop] = DataRow[Prop] || '';
		}
	}

	Main.IsDisplayHeader	= Main.IsDisplayHeader		|| false;
	
	Main.GetHTML			= function() { return EditTableControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	
	Internal.TableCSS			= Internal.TableCSS				|| '';
	Internal.TableClass			= Internal.TableClass			|| 'w3-table-all';
	Internal.TBodyCSS			= Internal.TBodyCSS				|| '';
	
	
	//
	// Events
	//
	
	Events						= Events					|| {};
	Events.ChangeCallBack		= Events.ChangeCallBack		|| null;
	
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]		= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function EditTableControlGetHTML(Control) {

	var html = '';
	html += '<div id="' + Control.Internal.RootElemID + '">\
		<table class="' + Control.Internal.TableClass + '" style="' + Control.Internal.TableCSS + '">';
	
	if (Control.IsDisplayHeader) {
		html += '<thead>';
		html += '<tr>';
		for (var i = 0; i < Control.Columns.length; i++) {
			html += '<th' + (Control.Columns[i].style ? (' style="' + Control.Columns[i].style + '" ') : '') + '>' + Control.Columns[i].DisplayName + '</th>';
		}
		html += '<th>&nbsp;</th>';
		html += '</tr>';
		html += '</thead>';
	}
	
	html += '<tbody style="' + Control.Internal.TBodyCSS + '">';
	
	for (var i = 0; i < Control.Data.length; i++) {
		var Row			= Control.Data[i];
		html += '<tr>';
		for (var j = 0; j < Control.Columns.length; j++) {
			var ColumnName = Control.Columns[j].ColumnName;
			html += '<td>' + Row[ColumnName] + '</td>';
		}
		html += '<td><a class="a-edit w3-hover-text-green" href="javascript:void(0)" oncontextmenu="return false;"><i class="a-edit far fa-edit w3-large"></i></a></td>';
		html += '</tr>';
	}
	
	html += '</tbody>';
	
	html += '</table></div>';
	
	return html;
}

function EditTableControlOnChange(Control) {
	
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