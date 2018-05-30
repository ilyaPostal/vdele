
//
// GET
//

function GetCurrentUrlParam(ParamName) {
   var Query = window.location.search.substring(1);
   if (Query) {
	   var Vars = Query.split("&");
	   for (var i = 0; i < Vars.length; i++) {
	       var KeyValue = Vars[i].split("=");
	       if(KeyValue[0] == ParamName)
	    	   return KeyValue[1];
	   }
   }
   return null;
}

function GetCurrentUrlParams() {
	var Result	= {};
    var Query	= window.location.search.substring(1);
    if (Query) {
        var Vars	= Query.split("&");
        for (var i = 0; i < Vars.length; i++) {
            var KeyValue 	= Vars[i].split("=");
            Result[KeyValue[0]] = KeyValue[1];
        }
    }
    return Result == {} ? null : Result;
}

function GetCurrentUrlWithoutParams() {
	return location.protocol + '//' + location.host + location.pathname;
}


function GetUrlWithParams(Url, Params, IsEncodeParams) {
	return AddUrlParams(Url, Params, IsEncodeParams);
}

//
// ADD
//

function AddUrlParams(Url, Params, IsEncodeParams) {
	var EncodedParams = [];
	for (var p in Params) {
		if (Params[p] && IsEncodeParams !== false) {
			//EncodedParams.push(encodeURIComponent(p) + '=' + encodeURIComponent(Params[p]));
			EncodedParams.push(encodeURIComponent(p) + '=' + encodeURIComponent((typeof Params[p] === 'object' ? JSON.stringify(Params[p]) : Params[p])));
		} else if (Params[p] && IsEncodeParams === false) {
			EncodedParams.push(p + '=' + Params[p]);
		}
	}			
			
	var Query = EncodedParams.join('&');
	if (Query) {
		Url = Url[Url.length - 1] == '/' ? (Url + '?') : (Url + '&');
	}
	return Url + Query;
}
