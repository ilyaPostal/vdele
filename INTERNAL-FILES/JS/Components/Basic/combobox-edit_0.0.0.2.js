
//
// Require w3.css
// Require main.js
// Require main-defer.js
//

//
// Control : {
//
//		"Collection" : [
//			{ 
//				"ID"			: int
//				, "Text"		: "Text"
//			}
//		]
//		, "DefaultText"			: "Text"
//		, "SelectedID"			: int?
//		, "SelectedText"		: "Text"
//
//		// Calls only
//		, GetHTML()				: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"			: ID
//			, "ParentControlID"		: ID
//			, "ContentContainerID"	: ID
//
//			// UI
//			, "UIContainerCSS"	: "CSS"
//			, "UIInputCSS"		: "CSS"
//			, "UIListCSS"		: "CSS"
//			, "UITextMaxLength"	: int
//			, "UIIsBorder"		: bool
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 	: function(Control)
//		}
// }
//

function ComboBoxEditControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main					= Main						|| {};
	Main.Collection			= Main.Collection			|| [];
	for (var i = 0; i < Main.Collection.length; i++) {
		var mci = Main.Collection[i];
		mci.ID		= 'ID' in mci 	? mci.ID : null;
		mci.Text	= mci.Text		|| '';
	}
	Main.DefaultText		= Main.DefaultText				|| '';
	Main.SelectedID			= 'SelectedID' in Main			? Main.SelectedID		: null; //Main.SelectedID			|| null;
	Main.SelectedID			= Main.Collection.length == 1	? Main.Collection[0].ID	: Main.SelectedID;
	Main.SelectedText		= Main.SelectedText				|| Main.DefaultText;
	if (Main.SelectedID !== null) {
		for (var i = 0; i < Main.Collection.length; i++)
			if (Main.Collection[i].ID == Main.SelectedID) {
				Main.SelectedText	= Main.Collection[i].Text;
				break;
			}
	}
	Main.GetHTML			= function() { return ComboBoxEditControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	Internal.ContentContainerID	= Internal.ContentContainerID	|| GetUniqJSVar();
	Internal.UIContainerCSS		= Internal.UIContainerCSS		|| '';
	Internal.UIInputCSS			= Internal.UIInputCSS			|| '';
	Internal.UIListCSS			= Internal.UIListCSS			|| '';
	Internal.UITextMaxLength	= Internal.UITextMaxLength		|| 10;
	Internal.UIIsBorder			= !("UIIsBorder" in Internal)	|| Internal.UIIsBorder;		// true  default
	
	//
	// Events
	//
	
	Events					= Events					|| {};
	Events.ChangeCallBack	= Events.ChangeCallBack		|| null;	// funciton(Control)
	Events.OnKeyUpCallBack	= Events.OnKeyUpCallBack	|| null;	// function(Control, event)
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]	= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function ComboBoxEditControlGetHTML(Control) {
	var html = '\
		<div id="' + Control.Internal.RootElemID + '" class="w3-dropdown-click combobox-control-root-elm" ' 
				+ ' onclick="ComboBoxEditControlItemSelect(event);"'
				+ ' style="' + Control.Internal.UIContainerCSS + '">\
			<input type="text" ' + ( Control.Collection.length > 1 ? (' onclick="DropDownExpandCollapse(\'' + Control.Internal.ContentContainerID + '\')"') : '')  
				+ ' class="w3-input input-text combobox-control ' 
				+ (Control.Internal.UIIsBorder ? 'w3-border' : '') + '" ' 
				+ ' style="overflow: hidden; width: 200px;' 
				+ Control.Internal.UIInputCSS + '" value="' + Control.SelectedText + '" '
				+ ' onkeyup="ComboBoxEditControlInputOnKeyUp(event)" oninput="ComboBoxEditControlOnInput(event)"'
				+ ' spellcheck="false"'
				+ '>' 
		    	+ '\
			</input>\
	';
	html += '\
	    	<div id="' + Control.Internal.ContentContainerID + '" onclick="DropDownExpandCollapse(\'' + Control.Internal.ContentContainerID + '\')" class="w3-dropdown-content w3-bar-block w3-border w3-card-4" \
		    	style="width: 200px; min-width: inherit; max-height: 400px; overflow-y: auto;' 
				+ Control.Internal.UIListCSS + '">\
	';
	for (var i = 0; i < Control.Collection.length; i++) {
		html += '<button class="w3-bar-item w3-button combobox-control ' 
				+ ((Control.SelectedID && Control.SelectedID === Control.Collection[i].ID || Control.SelectedText === Control.Collection[i].Text) ? 'w3-theme-l3' : '')
				+ '">' + Control.Collection[i].Text + '</button>';
	}
	html += '\
			</div>\
		</div>\
	';
	
	//
	// Static CSS
	//
	
	if (!window.ComboBoxEditControlCSS) {
		
		html += '<style>';
		html += '.combobox-control-root-elm:hover { background-color: inherit; }';
		html += '</style>';
		
		window.ComboBoxEditControlCSS = true;
	}
	
	//
	// Static JS
	//
	
	if (!window.ComboBoxEditControlJS) {
		
		document.body.addEventListener("click", function(event) {
			var Target = event.target;
			if (Target.className.indexOf('combobox-control') == -1) {
				var RootsOfComboboxs = document.getElementsByClassName('combobox-control-root-elm');
				for (var i = 0; i < RootsOfComboboxs.length; i++) {
					DropDownExpandCollapse(RootsOfComboboxs[i].children[1].id, true);
				}
			}
		});
		
		window.ComboBoxEditControlJS = true;
	}
	
	return html;
}

function ComboBoxEditControlInputOnKeyUp(event) {
	
	var InputElement	= event.currentTarget;
	var RootElement		= InputElement.parentElement;
	var ControlID		= RootElement.id;
	var Control			= window[ControlID];
	
	if (event.keyCode == 13) {

		//
		// UI
		//
		
		DropDownExpandCollapse(Control.Internal.ContentContainerID, true);
	}
	
	//
	// Raise OnKeyUp Event
	//
	
	if (Control.Events.OnKeyUpCallBack !== null)
		setTimeout(function() { Control.Events.OnKeyUpCallBack(Control, event); }, 0);
	
}

function ComboBoxEditControlOnInput(event) {
	var InputElement	= event.currentTarget;
	var RootElement		= InputElement.parentElement;
	var ControlID		= RootElement.id;
	var Control			= window[ControlID];
	
	//
	// Data
	//
	
	var OldSelectedText		= Control.SelectedText;
	Control.SelectedText 	= InputElement.value.trim();
	Control.SelectedID		= null;
	for (var i = 0; i < Control.Collection.length; i++) {
		var cci = Control.Collection[i];
		if (cci.Text == Control.SelectedText) {
			Control.SelectedID = cci.ID;
		}
	}
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null && OldSelectedText != Control.SelectedText)
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
}

function ComboBoxEditControlItemSelect(event) {
	
	var RootElement		= event.currentTarget;
	var ClickedElement	= event.target;
	var InputText		= RootElement.children[0];
	var ListContainer	= RootElement.children[1];
	var ControlID		= RootElement.id;
	var Control			= window[ControlID];
	
	var OldID			= Control.SelectedID;
	
	//
	// Нажатие по полю ввода
	//
	
	if (ClickedElement.className.indexOf("input-text") != -1) {
		
		// ...
		
	//
	// Нажатие по элементу списка
	//
		
	} else if (ClickedElement.nodeName == "BUTTON" && ClickedElement.className.indexOf("w3-bar-item") != -1) {
		
		//
		// Data
		//
		
		Control.SelectedText 	= ClickedElement.firstChild.data;
		Control.SelectedID		= null;
		for (var i = 0; i < Control.Collection.length; i++) {
			if (Control.Collection[i].Text === Control.SelectedText) {
				Control.SelectedID	= Control.Collection[i].ID;
				break;
			}
		}
			
		//
		// UI
		//
		
		InputText.value = Control.SelectedText;
		for (var i = 0; i < ListContainer.children.length; i++) {
			if (ListContainer.children[i].firstChild.data === Control.SelectedText)
				ListContainer.children[i].className = ListContainer.children[i].className.replace(' w3-theme-l3', "") + ' w3-theme-l3';
			else
				ListContainer.children[i].className = ListContainer.children[i].className.replace(' w3-theme-l3', "");
		}

	}
	
	//
	// Raise Change Event
	//
	
	if (Control.Events.ChangeCallBack !== null && OldID != Control.SelectedID)
		setTimeout(function() { Control.Events.ChangeCallBack(Control); }, 0);
	
}

