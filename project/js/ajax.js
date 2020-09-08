var namespace = namespace || {};

namespace.ajax = (function(){

	/**
	* Sends an XMLHttpRequest and returns the response in a callback.
	* @method send
	* @param {Function} options.callback 		This function will be triggers upon success
	* 											it will pass in the response as a param
	* @param {string} options.url				The url to request
	* @param {Object} options.method			The request method
	* @param {Object} [options.headers]			An object (key/value pairs) where the key is the header to set, 
	*											and the value is what the header should be set to. For example:
	* 											{"Content-Type":"text/html", "Accept":"application/json"} 
	* @param {Object} [options.requestBody]
	* @param {Object} [options.errorCallback]
	*/
	function send(options){

			var callback = options.callback || null;
			var url = options.url || null;
			var method = options.method || "GET";
			var headers = options.headers || null;
			var requestBody = options.requestBody || null;
			var errorCallback = options.errorCallback || defaultErrorHandler;

			function defaultErrorHandler(statusCode, statusText){
				console.log(statusCode, statusText);
			}

			// validate that the required options are present and valid
			if(!callback){
				console.log("A callback function must be provided")
			}

			if(!url){
				console.log("A url must be provided");
			}
			// TODO: more validation (for example, check to make sure that callback is a function, etc.)


			// initialize the xhr object and 'open' the request
			var http = new XMLHttpRequest();
		    http.open(method, url);

		    // configure the event handler for when the status of the request changes
			http.onreadystatechange = function() {
		        if(http.readyState == 4 && http.status == 200) {
		            callback(http.responseText);
		        }else if(http.readyState == 4){
		        	if(errorCallback){
		        		errorCallback(http.status, http.statusText);
		        	}
		        }
		    };

		    // set the request headers...
		    if(headers){
				for(key in headers){
					http.setRequestHeader(key, headers[key]);
				}
			}

			// send the request...
		    http.send(requestBody); 
	}

	return{
		send: send
	}

})();