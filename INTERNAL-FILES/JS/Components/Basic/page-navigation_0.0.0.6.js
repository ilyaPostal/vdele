
//
// Require w3.css
// Require main.js
// //////Require font-awesome_4_7_0.min.css 
// Require Modules/url-params.js
//

//
// Control : {
//
//		"PageNumber"				: int
//		, "IsLastPage"				: bool
//		, "MaxPageNumber"			: int
//		, "PagesPerRange"			: int
//		, "DefaultClientID"			: int
//		, "DefaultClientIDOrderNum"	: int
//		, "Filters"					: string
//
//		// Calls only
//		, GetHTML()			: function(Control) : "HTML"
//
//		, "Internal" : {
//
//			// Data
//			"RootElemID"		: ID
//			, "ParentControlID"	: ID
//			// read only
//			"RangesCount"		: int
//			, "RangeOffset"		: int
//
//			// UI
//			// ...
//
//		}
//		, "Events" : {
//			"ChangeCallBack" 		: function(Control)
//		}
// }
// 
//

function PageNavigationControlCreate(Main, Internal, Events) {

	//
	// Main
	//
	
	Main							= Main							|| {};
	Main.PageNumber					= Main.PageNumber				|| 1;
	Main.IsLastPage					= Main.IsLastPage				|| false;
	Main.MaxPageNumber				= Main.MaxPageNumber			|| (Main.IsLastPage ? Main.PageNumber : 99999);
	Main.PagesPerRange				= Main.PagesPerRange			|| 5;
	Main.DefaultClientID			= Main.DefaultClientID			|| null;
	Main.DefaultClientIDOrderNum	= Main.DefaultClientIDOrderNum	|| null;
	Main.Filters					= Main.Filters					|| '';
	
	Main.GetHTML			= function() { return PageNavigationControlGetHTML(Control); };

	//
	// Internal
	//
	
	Internal					= Internal						|| {};
	Internal.RootElemID			= Internal.RootElemID			|| GetUniqJSVar();
	Internal.ParentControlID	= Internal.ParentControlID		|| null;
	var TempFloat				= Main.MaxPageNumber / Main.PagesPerRange;
	var TempInt					= Math.floor(TempFloat);
	Internal.RangesCount		= TempFloat == TempInt ? TempFloat : TempInt + 1;
	TempInt						= Main.PageNumber % Main.PagesPerRange
	Internal.RangeOffset		= TempInt != 0 ? TempInt - 1 : Main.PagesPerRange - 1;
	
	//
	// Events
	//
	
	Events						= Events					|| {};
	Events.ChangeCallBack		= Events.ChangeCallBack		|| null;
	
	
	//
	// Create object
	//
	
	var Control			= window[Internal.RootElemID]	= Main;
	Control.Internal	= Internal;
	Control.Events		= Events;
	
	return Control;
	
}

function PageNavigationControlGetHTML(Control) {
	
	var html			= '';
	var EmptyUrl		= 'javascript:void(0)';
	var DisableClass	= ' w3-hover-light-grey';
	html				+= '<div id="' + Control.Internal.RootElemID + '" class="w3-bar w3-border w3-round page-navigation-control">';
	var BeginNumber 	= Control.PageNumber - Control.Internal.RangeOffset;
	var EndNumber		= BeginNumber + Control.PagesPerRange - 1;
	var BackUrl			= Control.PageNumber * 1 > 1 ? PageNavigationControlPrivateGetPageUrl(Control.PageNumber * 1 - 1, Control) : EmptyUrl;
	var AdditionClass	= BackUrl == EmptyUrl ? DisableClass : '';
	html += '<a href="' + BackUrl + '" class="w3-bar-item w3-button w3-border-right w3-mobile ' + AdditionClass + '">&#10094; Назад</a>';
	for (var i = BeginNumber; i <= EndNumber; i++) {
		var Url			= i <= Control.MaxPageNumber && i != Control.PageNumber ? PageNavigationControlPrivateGetPageUrl(i, Control) : EmptyUrl;
		AdditionClass	= Control.PageNumber == i ? ' w3-green' : '';
		AdditionClass	+= Url == EmptyUrl && Control.PageNumber != i ? DisableClass : '';
		html			+= '<a href="' + Url + '" class="w3-bar-item w3-button w3-border-right w3-mobile ' + AdditionClass + '">' + i + '</a>';
	}
	var NextUrl 	= (Control.PageNumber * 1 < Control.MaxPageNumber * 1) && !Control.IsLastPage ? PageNavigationControlPrivateGetPageUrl(Control.PageNumber * 1 + 1, Control) : EmptyUrl;
	AdditionClass	= NextUrl == EmptyUrl ? DisableClass : '';
	html 			+= '<a href="' + NextUrl + '" class="w3-bar-item w3-button w3-mobile ' + AdditionClass + '">Далее &#10095;</a>';
	html 			+= '</div>';
	
	//	
	// Static CSS
	//
	
	if (!window.PageNavigationControlCSS) {
		html += '<style>';
		html += '@media (max-width: 600px) { .page-navigation-control .w3-border-right { border-right: none!important; border-bottom: 1px solid #ccc!important; }';
		html += '.page-navigation-control a { padding: 0.75em 2em!important } }';
		html += '</style>';
		window.PageNavigationControlCSS = true;
	}
	
//	var html = '';
//	html += '<div id="' + Control.Internal.RootElemID + '" class="w3-bar w3-border w3-round">';
//	//html += '<a href="#" class="w3-bar-item w3-button w3-border-right">&#10094;&#10094;</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right">&#10094; Назад</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right w3-green ">1</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right">2</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right">3</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right">4</a>';
//	html += '<a href="#" class="w3-bar-item w3-button w3-border-right">5</a>';
//	html += '<a href="#" class="w3-bar-item w3-button">Далее &#10095;</a>';	// <i class="fa fa-chevron-right"></i>
//	//html += '<a href="#" class="w3-bar-item w3-button">&#10095;&#10095;</a>';
//	  
//	html += '</div>';
	
	return html;
}

function PageNavigationControlOnChange(Control) {
	
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

function PageNavigationControlPrivateGetPageUrl(PageNumber, Control) {
	var Params 				= GetCurrentUrlParams() || {};
	Params.Page 			= PageNumber; 
	Params.ClientID			= Params.ClientID		|| Control.DefaultClientID;
	Params.ClientOrderNum	= Params.ClientOrderNum	|| Control.DefaultClientIDOrderNum;
	Params.Filters			= Control.Filters;
	return AddUrlParams(GetCurrentUrlWithoutParams(), Params); 
}
