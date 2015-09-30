'use strict';

SwaggerEditor.config(function Router($compileProvider, $stateProvider,
  $urlRouterProvider, $logProvider) {
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

  // Disable debug info in production. To detect the "production" mode we are
  // examining location.host to see if it matches localhost
  var isProduction = !/localhost/.test(window.location.host);

  $compileProvider.debugInfoEnabled(!isProduction);
  $logProvider.debugEnabled(!isProduction);
});
