function GetUniqJSVar() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return 'V' + s4() + s4() + s4() + s4() +
    s4() + s4() + s4() + s4();
}

function AjaxSendObject(Link, Object, CallBackSuccessful, CallBackFail, CallBackError) {
	
	//
	// ResponseObject { IsSuccessful : bool, Message : string, Data : any data}
	//
	
	try {
		
		var RequestObject = new XMLHttpRequest();
		RequestObject.open("POST", Link, true);
		RequestObject.onreadystatechange = function() {
			
			//
			// После отправки.
			//
			
			if (RequestObject.readyState != 4) 
				return;
			var ResponseObject;
			try {
				try {
					ResponseObject = JSON.parse(RequestObject.responseText);
				} catch (ex) {
					if (CallBackFail != null)
						CallBackFail('непредвиденная ошибка сервера');
					return;
			    }
				if (ResponseObject.IsSuccessful) {
					if (CallBackSuccessful != null)
						CallBackSuccessful(ResponseObject);
				} else {
					if (CallBackFail != null) {
						CallBackFail(ResponseObject.Message	.replace(/&lt;br \/&gt;/g	, "<br />" )
															.replace(/&lt;b&gt;/g		, "<b>")
															.replace(/&lt;\/b&gt;/g		, "</b>")
															.replace(/&#13;/g			, "<br />")
															.replace(/<br \/><br \/>/g	, "<br />"));
					}
				}
			} catch (ex) {
				if (CallBackError != null)
					CallBackError(ex.message);
		    }
		};
		
		//
		// Отправка.
		//
		
		RequestObject.send(JSON.stringify(Object));
		
	} catch(ex) {
		if (CallBackError != null)
			CallBackError(ex.message);
	}
}
