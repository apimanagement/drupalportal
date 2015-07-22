'use strict';

SwaggerEditor.directive('operation', function (defaults) {
  return {
    restrict: 'E',
    replace: true,
    templateUrl: 'templates/operation.html',
    scope: false,
    link: function ($scope) {
      $scope.isTryOpen = false;
      $scope.enableTryIt = defaults.enableTryIt;
      $scope.toggleTry = function toggleTry() {
        $scope.isTryOpen = !$scope.isTryOpen;
      };

      /*
       * Gets all available parameters
       *
       * @returns {array} - array of parameters
      */
      $scope.getParameters = function () {
        var hasPathParameter = Array.isArray($scope.path.pathParameters);
        var hasOperationParameter = Array.isArray($scope.operation.parameters);

        // if there is no operation and path parameter return empty array
        if (!hasOperationParameter && !hasPathParameter) {
          return [];
        }

        // if there is no operation parameter return only path parameters
        if (!hasOperationParameter) {
          return $scope.path.pathParameters || [];
        }

        // if there is no path parameter return operation parameters
        if (!hasPathParameter) {
          return $scope.operation.parameters || [];
        }

        // if there is both path and operation parameters return all of them
        return $scope.operation.parameters.concat($scope.path.pathParameters);
      };

      /*
       * TODO: Docs
      */
      $scope.getParameterSchema = function (parameter) {
        if (parameter.schema) {
          return parameter.schema;
        }

        if (parameter.type === 'array') {
          return _.pick(parameter, 'type', 'items');
        }

        var schema = {type: parameter.type};

        if (parameter.format) {
          schema.format = parameter.format;
        }

        return schema;
      };

      /*
       * Returns true if the operation responses has at least one response with
       * schema
       *
       * @param responses {array} - an array of responses
       * @returns boolean
      */
      $scope.hasAResponseWithSchema = function (responses) {
        return responses.some(function (response) {
          return response.schema;
        });
      };

      /*
       * Returns true if the operation responses has at least one response with
       * "headers" field
       *
       * @param responses {array} - an array of responses
       * @returns boolean
      */
      $scope.hasAResponseWithHeaders = function (responses) {
        return responses.some(function (response) {
          return response.headers;
        });
      };
    }
  };
});
