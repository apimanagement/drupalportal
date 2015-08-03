'use strict';

SwaggerEditor.controller('SecurityCtrl', function SecurityCtrl($scope, $modal,
  AuthManager) {
  $scope.getHumanSecurityType = function (type) {
    var types = {
      basic: 'HTTP Basic Authentication',
      oauth2: 'OAuth 2.0',
      apiKey: 'API Key'
    };

    return types[type];
  };

  $scope.isAuthenticated = AuthManager.securityIsAuthenticated;

  $scope.authenticate = function (securityName, security) {
    var rootPath = Drupal.settings.basePath + 'sites/all/modules/ibm_apim/swaggereditor/app/';
    var specs = $scope.specs;
    if (security.type === 'basic') {
      $modal.open({
        templateUrl: rootPath + 'templates/auth/basic.html',
        controller: function BasicAuthAuthenticateCtrl($scope, $modalInstance) {
          $scope.cancel = $modalInstance.close;
          $scope.authenticate = function (username, password) {
            AuthManager.basicAuth(securityName, security, {
              username: username,
              password: password
            });
            $modalInstance.close();
          };
        },
        size: 'large'
      });
    } else if (security.type === 'oauth2') {
      $modal.open({
        templateUrl: rootPath + 'templates/auth/oauth2.html',
        controller: function OAuth2AuthenticateCtrl($scope, $modalInstance) {
          $scope.cancel = $modalInstance.close;
          $scope.authenticate = function (accessToken) {
            if (!accessToken) {
              return;
            }
            AuthManager.oAuth2(securityName, security, {
              accessToken: accessToken
            });
            $modalInstance.close();
          };
          $scope.oauthconfig = Drupal.settings.ibm_apim.oauthconfig;
        },
        size: 'large'
      });
    } else if (security.type === 'apiKey' && specs['x-ibm-configuration'] && specs['x-ibm-configuration'].enforced == true && (security.name === 'client_id' || security.name === 'X-IBM-Client-Id')) {
      $modal.open({
        templateUrl: rootPath + 'templates/auth/clientid.html',
        controller: function APIKeyAuthenticateCtrl($scope, $modalInstance) {
          $scope.cancel = $modalInstance.close;
          var clientids = [];
          Drupal.settings.ibm_apim.apps.forEach(function(app) {
            app.appCredentials.forEach(function(cred) {
              var descr = '';
              if (cred.description) {
                descr = ' - ' + cred.description;
              }
              clientids.push({clientid: cred.clientID, descr: app.name + descr});
            });
          }); 
          $scope.clientids = clientids;
          $scope.authenticate = function (apiKey) {
            if (!apiKey) {
              return;
            }
            AuthManager.apiKey(securityName, security, {
              apiKey: apiKey
            });
            $modalInstance.close();
          };
        },
        size: 'large'
      });
    } else if (security.type === 'apiKey') {
        $modal.open({
          templateUrl: rootPath + 'templates/auth/api-key.html',
          controller: function APIKeyAuthenticateCtrl($scope, $modalInstance) {
            $scope.cancel = $modalInstance.close;
            $scope.authenticate = function (apiKey) {
              if (!apiKey) {
                return;
              }
              AuthManager.apiKey(securityName, security, {
                apiKey: apiKey
              });
              $modalInstance.close();
            };
          },
          size: 'large'
        });   
    } else {
      window.alert('Not yet supported');
    }
  };
});
