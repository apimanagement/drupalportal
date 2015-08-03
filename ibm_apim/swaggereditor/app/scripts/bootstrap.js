'use strict';

jQuery(function () {

  // Try bootstrapping the app with embedded defaults if it exists
  var embeddedDefaults = window.$$embeddedDefaults;

  if (embeddedDefaults) {
    window.SwaggerEditor.$defaults = embeddedDefaults;
    angular.bootstrap(window.document, ['SwaggerEditor']);
  } else {
    var rootPath = '';
    if (window.location.pathname.lastIndexOf('/') !==
        (window.location.pathname.length - 1)) {
      rootPath = Drupal.settings.basePath + 'sites/all/modules/ibm_apim/swaggereditor/app/';
    }
    jQuery.getJSON(rootPath + './config/defaults.json').done(function (resp) {
      window.SwaggerEditor.$defaults = resp;
      window.SwaggerEditor.$defaults.backendEndpoint = Drupal.settings.ibm_apim.url;
      //window.SwaggerEditor.$defaults.importProxyUrl = window.location.protocol + "//" + window.location.host + "/?url=";
      window.SwaggerEditor.$defaults.importProxyUrl = '';
      angular.bootstrap(window.document, ['SwaggerEditor']);
    }).fail(function () {
      console.error('Failed to load defaults.json at',
        rootPath + './config/defaults.json');
    });
  }
});
