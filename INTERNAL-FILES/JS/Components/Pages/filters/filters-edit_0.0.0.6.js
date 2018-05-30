
//
// Require main.js
// Require main-defer.js
// Require Modules/url-params.js
// Require combobox.js
// Require text-edit.js
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
//		, FilterUrlParam		: object
//		, GetHTML()				: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"		: ID
//			, "ParentControlID"	: ID
//			, "Containers"		: [
//				{ "ID" : ID, "CheckBoxControl" : control, "ExpandControl" : control}
//			]
//
//			// UI
//			, "UIContainerCSS"	: "CSS"
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 	: function(Control)
//			, "Submit"			: function(Control, FilterUrlParam : object)
//		}
// }
//

function FiltersEditControlCreate(Main, Internal, Events) {

	
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
	Main.GetHTML = function() { return FiltersEditControlGetHTML(Control); };

	
	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	Internal.Containers			= [];
	for (var FilterName in Main.Filters) {
		var mfi = Main.Filters[FilterName];
		if (mfi.EditType == "bool") {
			var FilterContainer	= {};
			FilterContainer.ID						= GetUniqJSVar();
			FilterContainer.SubContainerID			= GetUniqJSVar();
			FilterContainer.IsSubContainerVisible	= ('IsDisable' in mfi && mfi.IsDisable) ? false : mfi.WhereValue;
			FilterContainer.FilterName				= 'FilterName' in mfi ? mfi.FilterName : FilterName;
			FilterContainer.Iterator				= FilterName;
			var FilterValue	= null;
			for (var WhereValueName in mfi.WhereValue) {
				FilterValue = mfi.WhereValue[WhereValueName];
			}
			FilterContainer.CheckBoxControl	= CheckBoxControlCreate({ "Value" : FilterContainer.IsSubContainerVisible, "Text" : mfi.Caption }
												, { "ParentControlID" : Internal.RootElemID, "ParentControlContainerID" : FilterContainer.ID }
												, { "ChangeCallBack" : FiltersEditControlOnChange });
												//, { "ChangeCallBack" : function(EventControl) { FiltersEditControlOnChange(Control, FilterContainer.ID, EventControl); } });
			if (mfi.DisplayType == "bool") {
				FilterContainer.ComboBoxControl	= ComboBoxControlCreate({ "Collection" : [{ "ID" : 1, "Text" : "Да"}, { "ID" : 0, "Text" : "Нет"}], "SelectedID" : FilterValue * 1}
													, { "ParentControlID" : Internal.RootElemID, "ParentControlContainerID" : FilterContainer.ID, IsEmptyAllow : false }
													, { "ChangeCallBack" : FiltersEditControlOnChange });
													//, { "ChangeCallBack" : function(EventControl) { FiltersEditControlOnChange(Control, FilterContainer.ID, EventControl); } });
			} else if  (mfi.DisplayType == "!bool") {
				FilterContainer.ComboBoxControl	= ComboBoxControlCreate({ "Collection" : [{ "ID" : 0, "Text" : "Да"}, { "ID" : 1, "Text" : "Нет"}], "SelectedID" : FilterValue * 1}
													, { "ParentControlID" : Internal.RootElemID, "ParentControlContainerID" : FilterContainer.ID, IsEmptyAllow : false }
													, { "ChangeCallBack" : FiltersEditControlOnChange });
			}
												
			Internal.Containers.push(FilterContainer);
		} else {
			Internal.Containers.push(null);
		}
	}
	
	Internal.UIContainerCSS	= Internal.UIContainerCSS		|| '';
	
	
	//
	// Events
	//
	
	Events					= Events					|| {};
	Events.ChangeCallBack	= Events.ChangeCallBack		|| null;
	Events.Submit			= Events.Submit				|| null;
	
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]	= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function FiltersEditControlGetHTML(Control) {
	
	var html = '<div id="' + Control.Internal.RootElemID + '" style="' + Control.Internal.UIContainerCSS + '">';
	
	html += '<h4 style="margin-bottom:28px;">Фильтры</h4>';
	
	for (var i = 0; i < Control.Internal.Containers.length; i++) {
		var cici = Control.Internal.Containers[i];
		
		if (i != 0) {
			html += '<hr>';
		}
		
		html += '<div id="' + cici.ID + '">';
		
		html += cici.CheckBoxControl.GetHTML();
		
		html += '<div id="' + cici.SubContainerID + '" class="filters-edit-control-sub-container" ' + (!cici.IsSubContainerVisible ? 'style="display : none;"' : '') + '>';
		
		html += cici.ComboBoxControl.GetHTML();
		
		html += '</div>';
		
		html += '</div>';
	}
	
	html += '<hr>';
	html += '<button class="filters-edit-control-apply w3-btn w3-while w3-hoverable w3-border" onclick="FiltersEditControlOnClickSubmit(event)">';
	html += '<i class="fa fa-bolt"></i> &nbsp;Применить';
	html += '</button> ';
	html += '<button class="filters-edit-control-apply w3-btn w3-while w3-hoverable w3-border" onclick="FiltersEditControlOnClickReset(event)">';
	html += '<i class="fa fa-undo"></i> &nbsp;По умолчанию';
	html += '</button>';
	
	html += '</div>';
	
	//
	// Static CSS
	//
	
	if (!window.FiltersEditControlCSS) {
		
		html += '<style> .filters-edit-control-sub-container { ';
		html += ' padding: 10px 5px 5px 40px; ';
		html += '} .filters-edit-control-apply {'; 
		html += ' margin-top: 10px; width: 12em; height: 3em;';
		html += '} .filters-edit-control-apply i {'; 
		html += ' transform: scale(1.5); color:#333;';
		html += '} </style>';
		window.FiltersEditControlCSS = true;
	}
	
	return html;
}

function FiltersEditControlOnChange(EventControl) {
	
	var Control 		= window[EventControl.Internal.ParentControlID];
	var ContainerID 	= EventControl.Internal.ParentControlContainerID;
	
	for (var i = 0; i < Control.Internal.Containers.length; i++) {
		var cici = Control.Internal.Containers[i];
		if (cici.ID == ContainerID) {
			
			//var Filter = Control.Filters[cici.FilterName];
			var Filter = Control.Filters[cici.Iterator];
			
			if (Filter.EditType === 'bool') {
				
				var IsVisible = cici.CheckBoxControl.Value;
				document.getElementById(cici.SubContainerID).style.display = IsVisible ? 'block' : 'none';
				
				for (var Prop in Filter.WhereValue) {
					Filter.WhereValue[Prop] = cici.ComboBoxControl.SelectedID;
					break;
				}
			}
			
			break;
		}
	}
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null)
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
}

function FiltersEditControlOnClickSubmit(event) {
	//var eventTarget		= event.target;
	var rootElement		= event.currentTarget.parentElement;
	var ControlID		= rootElement.id;
	var Control			= window[ControlID];
	
	var FiltersObj		= {};
	for (var i = 0; i < Control.Internal.Containers.length; i++) {
		
		var cici		= Control.Internal.Containers[i];
		//var Filter		= Control.Filters[cici.FilterName];
		var Filter		= Control.Filters[cici.Iterator];
		var IsVisible	= cici.CheckBoxControl.Value;
		
		if (Filter.EditType === 'bool') {
			FiltersObj[cici.FilterName]	= {};
			if (IsVisible) {
				var WhereValue				= FiltersObj[cici.FilterName]['WhereValue'] = {};
				for (var SubProp in Filter.WhereValue) {
					WhereValue[SubProp] = Filter.WhereValue[SubProp];
				}
			} else {
				FiltersObj[cici.FilterName].IsDisable = true;
			}
		}
	}
	
	var IsEmptyObj = true;
	for (var x in FiltersObj) { 
		IsEmptyObj = false;
		break;
	}
	
	//var NewUrl = GetUrlWithParams(GetCurrentUrlWithoutParams(), { "Filters" : (IsEmptyObj ? null : FiltersObj) });
	//window.location.href = NewUrl;
	Control.FilterUrlParam = { "Filters" : (IsEmptyObj ? null : FiltersObj) };
	
	//
	// Raise Submit Event
	//
	
	if (Control.Events.Submit !== null)
		setTimeout(function() { Control.Events.Submit(Control, Control.FilterUrlParam); }, 0);
	
}

function FiltersEditControlOnClickReset(event) {
	var rootElement			= event.currentTarget.parentElement;
	var ControlID			= rootElement.id;
	var Control				= window[ControlID];
	Control.FilterUrlParam = { "Filters" : null };
	//window.location.href = GetCurrentUrlWithoutParams();
	
	//
	// Raise Submit Event
	//
	
	if (Control.Events.Submit !== null)
		setTimeout(function() { Control.Events.Submit(Control, Control.FilterUrlParam); }, 0);
}
