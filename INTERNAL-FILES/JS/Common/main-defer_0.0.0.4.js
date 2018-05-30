
//////////////////////////////////////
//			w3.css support			//
//////////////////////////////////////

function sidebar_switch_visible() {
	var IsDisplay = null;
	for (var i = 0; i < arguments.length; i++) {
		var Elem = document.getElementById(arguments[i]);
		if (Elem && 'style' in Elem && 'display' in Elem.style) {
			IsDisplay = IsDisplay == null ? Elem.style.display == "block" : IsDisplay;
			Elem.style.display = !IsDisplay ? "block" : "none";
			if (Elem.id == 'Sidebar' && Elem.className.indexOf("animate-left-quick") == -1)
				Elem.className += ' animate-left-quick';
		}
	}
}

//function DropDownExpandCollapse(id, IsCollapse) {
//    var x		= document.getElementById(id);
//    IsCollapse	= IsCollapse === undefined ? null : IsCollapse;
//    if (IsCollapse === false || x.className.indexOf("w3-show") == -1 && IsCollapse === null) {
//        x.className += " w3-show";
//        x.previousElementSibling.className += " w3-theme-l4";
//    } else if (IsCollapse === true || IsCollapse === null) {
//        x.className = x.className.replace("w3-show", "");
//        x.previousElementSibling.className = x.previousElementSibling.className.replace(" w3-theme-l4", "");
//    }
//}

function DropDownExpandCollapse(id, IsCollapse, ButtonSelectedClass) {
    var x				= document.getElementById(id);
    IsCollapse			= IsCollapse === undefined ? null : IsCollapse;
    //ButtonSelectedClass	= ButtonSelectedClass	|| 'w3-theme-l4';
    ButtonSelectedClass	= ButtonSelectedClass	|| '';
    if (IsCollapse === false || x.className.indexOf("w3-show") == -1 && IsCollapse === null) {
        x.className += " w3-show";
        if (ButtonSelectedClass) {
        	x.previousElementSibling.className += " " + ButtonSelectedClass;
        }
        
    } else if (IsCollapse === true || IsCollapse === null) {
        x.className = x.className.replace("w3-show", "");
        if (ButtonSelectedClass) {
        	x.previousElementSibling.className = x.previousElementSibling.className.replace(" " + ButtonSelectedClass, "");
        }
        
    }
}
