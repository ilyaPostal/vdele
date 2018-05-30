
//
// Require w3.css
// Require main.css
// Require main.js
// Require main-defer.js
// Require Modules/url-params.js
//

//
// Control : {
//
//		"Filters"	: {
//			"DynamicName" : { 
//				"Caption"				: string
//				, "IsEditAllow" 		: bool
//				, "EditType"			: string
//				, "DisplayType"			: string
//				, "WhereValueDisplay"	: string
//				, "WhereValue"			: {"DynamicName" : "Value", ...}
//			}
//		}

//
//		// Calls only
//		, "ResultDisplay"		: string
//		, "ResultFiltersArr"	: [string]
//		, GetHTML()				: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"		: "ID"
//			, "ParentControl"	: Control
//
//			// UI
//			// ...
//
//		}
//		, "Events" : {
//			"ChangeCallBack"	: function(Control)
//		}
// }
//
//

function DisplayFiltersControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main			= Main			|| {};
	Main.Filters 	= Main.Filters	|| {};
	for (var FilterName in Main.Filters) {
		var Filter = Main.Filters[FilterName];
		Filter.Caption				= Filter.Caption			|| '';
		Filter.EditType				= Filter.EditType			|| '';
		Filter.IsEditAllow			= Filter.IsEditAllow		|| 0;
		Filter.DisplayType			= Filter.DisplayType		|| '';
		Filter.WhereValueDisplay	= Filter.WhereValueDisplay	|| '';
		Filter.WhereValue			= Filter.WhereValue			|| {};
	}
	Main.ResultDisplay		= '';
	Main.ResultFiltersArr	= [];
	
	for (var FilterName in Main.Filters) {
		var Filter = Main.Filters[FilterName];
		if (!('IsDisable' in Filter && Filter.IsDisable)) {
			if (Filter.EditType == 'bool') {		
				var Value = null;
				for ( var Prop in Filter.WhereValue) {
					Value = Filter.WhereValue[Prop];
					break;
				}
				if (Filter.DisplayType == 'bool') {
					//Main.ResultFiltersArr.push((Value ? '' : 'не ') + Filter.Caption);
					//Main.ResultFiltersArr.push(Filter.Caption + '(' + (Value ? 'Да' : 'Нет') + ')');
					Main.ResultFiltersArr.push(Filter.Caption + '(' + (Value ? 'да' : 'нет') + ')');
				} else if (Filter.DisplayType == '!bool') {
					//Main.ResultFiltersArr.push((!Value ? '' : 'не ') + Filter.Caption);
					//Main.ResultFiltersArr.push(Filter.Caption + '(' + (!Value ? 'Да' : 'Нет') + ')');
					Main.ResultFiltersArr.push(Filter.Caption + '(' + (!Value ? 'да' : 'нет') + ')');
				}
			}
		}
	}
	Main.ResultDisplay		= Main.ResultFiltersArr.length ? (Main.ResultFiltersArr.join(', ') + '.') : 'отсутствуют.';
	
	Main.GetHTML			= function() { return DisplayFiltersControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal				= Internal					|| {};
	Internal.RootElemID		= Internal.RootElemID		|| GetUniqJSVar();
	Internal.ParentControl	= Internal.ParentControl	|| null;
	
	//
	// Events
	//
	
	Events					= Events					|| {};
	Events.ChangeCallBack	= Events.ChangeCallBack		|| null;
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]	= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function DisplayFiltersControlGetHTML(Control) {

	var html = '';
	html += '<span id="' + Control.Internal.RootElemID + '">';
	html += '<span>: ';
	html += Control.ResultDisplay;
//	if (Control.ResultFiltersArr.length) {
//		html += Control.ResultDisplay;
//	} else {
//		html += 'Отсутствуют.';
//	}
	html += '</span>';
	html += '</span>'; // control

	return html;
}

//function DisplayFiltersControlOnChange(event) {
//	
//	var eventTarget		= event.target;
//	var rootElement		= event.currentTarget.parentElement;
//	var ControlID		= rootElement.id;
//	var Control			= window[ControlID];
//	
//	//
//	// Data
//	//
//	
//	//...
//	
//	//
//	// UI
//	//
//	
//	//...
//	
//	//
//	// Raise Change Event
//	//
//	
//	//if (Control.Events.ChangeCallBack !== null)
//	//	setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
//}
