
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
//			, "UIButtonCSS"		: "CSS"
//			, "UIListCSS"		: "CSS"
//			, "UITextMaxLength"	: int
//			, "UIIsBorder"		: bool
//
//			// Mixed
//			, "IsReadOnly" 		: bool
//			, "IsEmptyAllow"	: bool
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 	: function(Control)
//		}
// }
//

function ComboBoxControlCreate(Main, Internal, Events) {

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
	Main.DefaultText		= Main.DefaultText				|| 'Выбрать';
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
	Main.GetHTML			= function() { return ComboBoxControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	Internal.ContentContainerID	= Internal.ContentContainerID	|| GetUniqJSVar();
	Internal.UIContainerCSS		= Internal.UIContainerCSS		|| '';
	Internal.UIButtonCSS		= Internal.UIButtonCSS			|| '';
	Internal.UIListCSS			= Internal.UIListCSS			|| '';
	Internal.UITextMaxLength	= Internal.UITextMaxLength		|| 10;
	Internal.UIIsBorder			= !("UIIsBorder" in Internal)	|| Internal.UIIsBorder;		// true  default
	
	Internal.IsReadOnly			= "IsReadOnly" in Internal 		&& Internal.IsReadOnly;		// false default
	Internal.IsEmptyAllow		= !("IsEmptyAllow" in Internal) || Internal.IsEmptyAllow;	// true  default
	
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

function ComboBoxControlGetHTML(Control) {
	var html = '\
		<div id="' + Control.Internal.RootElemID + '" class="w3-dropdown-click combobox-control-root-elm" ' 
				+ (Control.Internal.IsReadOnly ? ' ' : ' onclick="ComboBoxControlItemSelect(event);"') 
				+ ' style="' + Control.Internal.UIContainerCSS + '">\
			<button onclick="DropDownExpandCollapse(\'' + Control.Internal.ContentContainerID + '\')" class="w3-btn w3-white header-button combobox-control ' 
				+ (Control.Internal.UIIsBorder ? 'w3-border' : '') + '" ' 
				+ ' style="overflow: hidden; width: 200px;' 
				+ Control.Internal.UIButtonCSS + '">' 
				+ ComboBoxControlPrivateGetShortText(Control.SelectedText, Control.Internal.UITextMaxLength)
		    	+ ' <i class="fa fa-caret-down header-button combobox-control"></i>\
			</button>\
	';
	html += '\
	    	<div id="' + Control.Internal.ContentContainerID + '" onclick="DropDownExpandCollapse(\'' + Control.Internal.ContentContainerID + '\')" class="w3-dropdown-content w3-bar-block w3-border w3-card-4" \
		    	style="width: 200px; min-width: inherit; max-height: 400px; overflow-y: auto;' 
				+ Control.Internal.UIListCSS + '">\
	';
	for (var i = 0; i < Control.Collection.length; i++) {
		html += '<button class="w3-bar-item w3-button combobox-control ' 
				+ (Control.SelectedID === Control.Collection[i].ID ? 'w3-theme-l3' : '')
				+ '">' + Control.Collection[i].Text + '</button>';
	}
	html += '\
			</div>\
		</div>\
	';
	
	//
	// Static JS
	//
	
	if (!window.ComboBoxControlJS) {
		
		document.body.addEventListener("click", function(event) {
			var Target = event.target;
			if (Target.className.indexOf('combobox-control') == -1) {
				var RootsOfComboboxs = document.getElementsByClassName('combobox-control-root-elm');
				for (var i = 0; i < RootsOfComboboxs.length; i++) {
					DropDownExpandCollapse(RootsOfComboboxs[i].children[1].id, true);
				}
			}
		});
		
		window.ComboBoxControlJS = true;
	}
	
	return html;
}

function ComboBoxControlItemSelect(event) {
	
	var RootElement		= event.currentTarget;
	var ClickedElement	= event.target;
	var HeaderButton	= RootElement.children[0];
	var ListContainer	= RootElement.children[1];
	var ControlID		= RootElement.id;
	var Control			= window[ControlID];
	
	var OldID			= Control.SelectedID;
	
	//
	// Нажатие по верхней кнопке
	//
	
	if (ClickedElement.className.indexOf("header-button") != -1) {
		
		if (Control.Internal.IsEmptyAllow) {
			
			//
			// Data
			//
			
			Control.SelectedID 		= null;
			Control.SelectedText	= Control.DefaultText;
			
			//
			// UI
			//
			
			HeaderButton.firstChild.data = ComboBoxControlPrivateGetShortText(Control.SelectedText, Control.Internal.UITextMaxLength);
			for (var i = 0; i < ListContainer.children.length; i++) {
				ListContainer.children[i].className = ListContainer.children[i].className.replace(' w3-theme-l3', "");
			}
			
		}
		
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
		
		HeaderButton.firstChild.data = ComboBoxControlPrivateGetShortText(Control.SelectedText, Control.Internal.UITextMaxLength);
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

function ComboBoxControlPrivateGetShortText(String, MaxLength) {
	var ShortValue	= String.substr(0, MaxLength);
	ShortValue 		= String.length * 1 > ShortValue.length * 1 ? ShortValue + '... ' : ShortValue + ' ';
	return ShortValue;
}

