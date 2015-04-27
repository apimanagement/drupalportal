/*!
 * Drupal_SwaggerUI JavaScript v1.0
 * https://github.com/tpanagos/Drupal_SwaggerUI
 *
 */

(function($) {

  Drupal.behaviors.SwaggerSetup = {
    attach: function(context, settings) {
      window.swaggerUi = new SwaggerUi({
        url: Drupal.settings.ibm_apim.url,
        // apiKey:"special-key",
        dom_id: "swagger-ui-container",
        validatorUrl: null,
        sorter: 'alpha',
        supportHeaderParams: true,
        supportedSubmitMethods: [
          'get',
          'post',
          'put',
          'delete',
          'patch',
          'options',
          'head'
        ],
        onComplete: function(swaggerApi, swaggerUi) {
          if (typeof initOAuth == "function") {
            initOAuth({
              authType: Drupal.settings.ibm_apim.authtype
            });
          }
          $('pre code').each(function(i, e) {
            hljs.highlightBlock(e)
          });
        },
        onFailure: function(data) {
        },
        docExpansion: "list"
      });
      $('#input_secretKey').change(function() {
        var key = $('#input_secretKey')[0].value;
        if (key && key.trim() != "") {
        	var apikey = new SwaggerClient.ApiKeyAuthorization("X-IBM-Client-Secret", key, "header");
        	window.swaggerUi.api.clientAuthorizations.add("X-IBM-Client-Secret", apikey);
            //window.authorizations.add("key", new ApiKeyAuthorization("X-IBM-Client-Secret", key, "header"));
        }
      })
      $('#input_clientKey').change(function() {
        var client = $('#input_clientKey')[0].value;
        if (client && client.trim() != "") {
          var clientkey = new SwaggerClient.ApiKeyAuthorization("X-IBM-Client-Id", client, "header");
          window.swaggerUi.api.clientAuthorizations.add("X-IBM-Client-Id", clientkey);
          //window.authorizations.add("client", new ApiKeyAuthorization("X-IBM-Client-Id", client, "header"));
          if (typeof initOAuth == "function") {
            initOAuth({
              authType: Drupal.settings.ibm_apim.authtype
            });
          }
        }
      })
      
      window.swaggerUi.load();

      var context = new SwaggerClient.ApiKeyAuthorization("X-IBM-APIManagement-Context", Drupal.settings.ibm_apim.context_header, "header");
      window.swaggerUi.api.clientAuthorizations.add("X-IBM-APIManagement-Context", context);
      //window.authorizations.add("context", new ApiKeyAuthorization("X-IBM-APIManagement-Context", Drupal.settings.ibm_apim.context_header, "header"));
    }
  };

})(jQuery);