
//
// Require w3.css
// Require main.js
// Require main-defer.js
//

//
// Control : {
//
//		"Value" 				: "Text"
//		, "MaxLength" 			: int
//
//		// Calls only
//		, GetHTML()				: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"		: ID
//			, "InputElementID"	: ID
//			, "ParentControlID"	: ID
//
//			// UI
//			, "UIContainerCSS"	: "CSS"
//			, "UIInputCSS"		: "CSS"
//			, "UIIsBorder"		: bool
//
//			// Mixed
//			, "IsReadOnly"		: bool
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 			: function(Control)
//		}
// }
//

function TextEditControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main					= Main						|| {};
	Main.Value				= Main.Value				|| null;
	Main.MaxLength			= Main.MaxLength			|| null;
	Main.GetHTML			= function() { return TextEditControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.InputElementID		= Internal.InputElementID		|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	Internal.UIContainerCSS		= Internal.UIContainerCSS		|| '';
	Internal.UIInputCSS			= Internal.UIInputCSS			|| '';
	Internal.UIIsBorder			= !("UIIsBorder" in Internal)	|| Internal.UIIsBorder;		// true  default
	Internal.IsReadOnly			= 'IsReadOnly' in Internal 		&& Internal.IsReadOnly;
	
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

function TextEditControlGetHTML(Control) {
	var html = '\
		<div id="' + Control.Internal.RootElemID + '" style="' + Control.Internal.UIContainerCSS + '">\
			<input id="' + Control.Internal.InputElementID + '" type="text" class="w3-input ' + (Control.Internal.UIIsBorder ? 'w3-border' : '') + '"\
	        	style="' + Control.Internal.UIInputCSS + '"\
				onchange="TextEditControlOnChange(event)"\
	        	value="' + Control.Value + '"\
	        	' + (Control.MaxLength ? (' maxlength="' + Control.MaxLength + '"') : '') + '\
	        	' + (Control.Internal.IsReadOnly ? ' readonly' : '') + '/>\
		</div>\
	';
	return html;
}

function TextEditControlOnChange(event) {
	
	var eventTarget		= event.target;
	var rootElement		= event.currentTarget.parentElement;
	var ControlID		= rootElement.id;
	var Control			= window[ControlID];
	
	var InputElement	= rootElement.children[0];
	var OldValue		= Control.value;
	
	//
	// Data
	//
	
	Control.Value		= InputElement.value;
	
	//
	// UI
	//
	
	//...
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null && OldValue != Control.Value)
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
}
