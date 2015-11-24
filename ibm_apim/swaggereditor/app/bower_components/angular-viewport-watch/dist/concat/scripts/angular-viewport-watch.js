/* global window */
'use strict';

(function () {

  /* @ngInject */
  function viewportWatch(scrollMonitor, $timeout) {
    var viewportUpdateTimeout;

    function debouncedViewportUpdate() {
      $timeout.cancel(viewportUpdateTimeout);
      viewportUpdateTimeout = $timeout(function () {
        scrollMonitor.update();
      }, 10);
    }

    return {
      restrict: 'AE',
      link: function (scope, element, attr) {
        var elementWatcher = scrollMonitor.create(element, scope.$eval(attr.viewportWatch || '0'));

        function watchDuringDisable() {
          /*jshint validthis:true */
          this.$$watchersBackup = this.$$watchersBackup || [];
          this.$$watchers = this.$$watchersBackup;
          this.constructor.prototype.$watch.apply(this, arguments);
          this.$$watchers = null;
        }

        function toggleWatchers(scope, enable) {
          var digest, current, next = scope;

          do {
            current = next;

            if (enable) {
              if (current.hasOwnProperty('$$watchersBackup')) {
                current.$$watchers = current.$$watchersBackup;
                delete current.$$watchersBackup;
                delete current.$watch;
                digest = !scope.$root.$$phase;
              }
            } else {
              if (!current.hasOwnProperty('$$watchersBackup')) {
                current.$$watchersBackup = current.$$watchers;
                current.$$watchers = null;
                current.$watch = watchDuringDisable;
              }
            }

            //DFS
            next = current.$$childHead;
            while (!next && current !== scope) {
              if (current.$$nextSibling) {
                next = current.$$nextSibling;
              } else {
                current = current.$parent;
              }
            }
          } while (next);

          if (digest) {
            //local digest only for this scope subtree
            scope.$digest();
          }
        }

        function disableDigest() {
          toggleWatchers(scope, false);
        }

        function enableDigest() {
          toggleWatchers(scope, true);
        }

        if (!elementWatcher.isInViewport) {
          scope.$evalAsync(disableDigest);
          debouncedViewportUpdate();
        }

        elementWatcher.enterViewport(enableDigest);
        elementWatcher.exitViewport(disableDigest);
        scope.$on('toggleWatchers', function (event, enable) {
          toggleWatchers(scope, enable);
        });

        scope.$on('$destroy', function () {
          elementWatcher.destroy();
          debouncedViewportUpdate();
        });
      }
    };
  }
  viewportWatch.$inject = ["scrollMonitor", "$timeout"];

  angular
    .module('angularViewportWatch', [])
    .directive('viewportWatch', viewportWatch)
    .value('scrollMonitor', window.scrollMonitor);

})();
