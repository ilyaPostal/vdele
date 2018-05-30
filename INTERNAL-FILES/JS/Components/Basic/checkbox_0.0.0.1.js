
//
// Require w3.css
// Require main.js
//

//
// Control : {
//
//		"Value" 				: bool
//		, "Text" 				: string
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
//			"CheckIconClass"	: string
//			"UnCheckIconClass"	: string
//			"IsHandCursor"		: bool
//
//			// Mixed
//			, "IsReadOnly"		: bool
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 	: function(Control)
//		}
// }
//

function CheckBoxControlCreate(Main, Internal, Events) {

	
	//
	// Main
	//
	
	Main					= Main						|| {};
	Main.Value				= Main.Value				|| false;
	Main.Text				= Main.Text					|| 'Флажок';
	Main.GetHTML			= function() { return CheckBoxControlGetHTML(Control); };

	
	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	
	Internal.CheckIconClass		= Internal.CheckIconClass		|| 'fa fa-check-square-o';
	Internal.UnCheckIconClass	= Internal.UnCheckIconClass		|| 'fa fa-square-o';
	Internal.IsHandCursor		= !('IsHandCursor' in Internal) || Internal.IsHandCursor;
	
	
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

function CheckBoxControlGetHTML(Control) {
	
	var html = '\
	<a href="javascript:void(0)" id="' + Control.Internal.RootElemID + '" ' 
			+ 'style="' + (Control.Internal.IsHandCursor ? 'cursor: pointer' : '') + '" '
			+ 'class="check-box-control"'
			+ 'onclick="CheckBoxControlOnChange(event)">\
		&nbsp;&nbsp;<i class=" check-box-control-icon ' + (Control.Value ? Control.Internal.CheckIconClass : Control.Internal.UnCheckIconClass) + '"></i>\
		<span>' + Control.Text + '</span>\
	</a>\
	';
	
	//
	// Static CSS
	//
	
	if (!window.CheckBoxControlCSS) {
		
		html += '<style> .check-box-control { ';
		html += ' display: inline-block; text-decoration: none; outline-offset: 3px;';
		html += '} .check-box-control-icon { ';
		html += ' color: #333; transform: scale(1.7); display: inline-block; width: 1.15em;';
		html +=	'} </style>';
		window.CheckBoxControlCSS = true;
	}

	return html;
}

function CheckBoxControlOnChange(event) {
	
	var rootElement		= event.currentTarget;
	var ControlID		= rootElement.id;
	var Control			= window[ControlID];
	
	var IconElement		= rootElement.children[0];
	
	//
	// Data
	//
	
	Control.Value		= !Control.Value;
	
	//
	// UI
	//
	
	IconElement.className	= 'check-box-control-icon ' + (Control.Value ? Control.Internal.CheckIconClass : Control.Internal.UnCheckIconClass);
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null)	{
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
	}
		
}
