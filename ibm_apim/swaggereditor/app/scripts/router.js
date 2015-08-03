'use strict';

SwaggerEditor.config(function Router($compileProvider, $stateProvider,
  $urlRouterProvider) {
  $urlRouterProvider.otherwise('/');
  var rootPath = Drupal.settings.basePath + 'sites/all/modules/ibm_apim/swaggereditor/app/';
  $stateProvider
  .state('home', {
    url: '/?import&tags&no-proxy',
    views: {
      '': {
        templateUrl: rootPath + 'views/main.html',
        controller: 'MainCtrl'
      },
      'header@home': {
        templateUrl: rootPath + 'views/header/header.html',
        controller: 'HeaderCtrl'
      },
      'preview@home': {
        templateUrl: rootPath + 'views/preview/preview.html',
        controller: 'PreviewCtrl'
      }
    }
  });

  $compileProvider.aHrefSanitizationWhitelist('.');
});
