/*!
 * Drupal_SwaggerUI JavaScript v1.0
 * https://github.com/tpanagos/Drupal_SwaggerUI
 *
 */
 
 (function ($) {

    Drupal.behaviors.SwaggerSetup = {
    attach: function (context, settings) {
	    window.swaggerUi = new SwaggerUi({
                url: Drupal.settings.ibm_apim.url,
               // apiKey:"special-key",
                dom_id:"swagger-ui-container",
                validatorUrl: null,
                sorter: 'alpha',
                supportHeaderParams: true,
                supportedSubmitMethods: ['get', 'post', 'put', 'delete'],
                onComplete: function(swaggerApi, swaggerUi){
                	if(console) {
                        console.log("Loaded SwaggerUI")
                        console.log(swaggerApi);
                        console.log(swaggerUi);
                    }
                  $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
                },
                onFailure: function(data) {
                	if(console) {
                        console.log("Unable to Load SwaggerUI");
                        console.log(data);
                    }
                },
                docExpansion: "list"
            });
	    $('#input_secretKey').change(function() {
	    	var key = $('#input_secretKey')[0].value;
	    	if(key && key.trim() != "") {
	    		window.authorizations.add("key", new ApiKeyAuthorization("X-IBM-Client-Secret", key, "header"));
	    	}
	    }) 
	    $('#input_clientKey').change(function() {
	    	var client = $('#input_clientKey')[0].value;
	    	if (client && client.trim() != "") {
	    		window.authorizations.add("client", new ApiKeyAuthorization("X-IBM-Client-Id", client, "header"));
	    	}
	    }) 
	    
	    window.authorizations.add("context", new ApiKeyAuthorization("X-IBM-APIManagement-Context", Drupal.settings.ibm_apim.context_header, "header"));
	    window.authorizations.add("basicauth", new ApiKeyAuthorization("Authorization", Drupal.settings.ibm_apim.basic_auth, "header"));
        window.swaggerUi.load();
    }
  };

})(jQuery);