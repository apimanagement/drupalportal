'use strict';

SwaggerEditor.directive('path', function () {
  var rootPath = Drupal.settings.basePath + 'sites/all/modules/ibm_apim/swaggereditor/app/';
  return {
    restrict: 'E',
    replace: true,
    templateUrl: rootPath + 'templates/path.html',
    scope: false
  };
});
