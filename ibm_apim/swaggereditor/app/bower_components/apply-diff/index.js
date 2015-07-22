(function(root){

    'use strict';

    var lodash = (typeof require === 'function') ? require('lodash') : root._;

    /*
     * Apply difference between two objects without without rewriting
     * destination object
     * @param source {object} - the source object. Destination should be exactly
     *   equal to this object after using applyDiff
     * @param destination {object} - the object that will get modified to be
     *   equal to source object.
    */
    function applyDiff(source, destination) {

      // type check
      if (!lodash.isObject(source)) {
        throw new Error('source should be an object');
      }
      if (!lodash.isObject(destination)) {
        throw new Error('destination should be an object');
      }

      var sourceKeys = Object.keys(source);
      var destinationKeys = Object.keys(destination);
      var extraKeys;

      // if there are fewer keys in source, remove the extra keys from
      // destination
      if (sourceKeys.length < destinationKeys.length) {
        extraKeys = lodash.difference(destinationKeys, sourceKeys);

        extraKeys.forEach(function (key) {

          if (lodash.isArray(destination)) {
            lodash.remove(destination, function (item, index){
                return index == key;
            });
          } else {
            delete destination[key];
          }
        });
      }

      // if there are more keys in source, add the extra keys to destination
      if (sourceKeys.length > destinationKeys.length) {
        extraKeys = lodash.difference(sourceKeys, destinationKeys);

        extraKeys.forEach(function (key) {
          destination[key] = source[key];
        });
      }

      // if sourceKeys and destinationKeys are not equal, but have the same
      // length then find the diff and apply it.
      //
      // example: source: ['one', 'two'] and destination: ['one', 'three']
      // remove 'three' from destination and add 'two' to source
      if (!lodash.isEqual(sourceKeys, destinationKeys)) {
        var newKeys = lodash.difference(sourceKeys, destinationKeys);

        newKeys.forEach(function (key) {
          destination[key] = source[key];
        });

        extraKeys = lodash.difference(destinationKeys, sourceKeys);
        extraKeys.forEach(function (key) {
          delete destination[key];
        });
      }

      // for each property in source check if the value of that property is
      // deeply equal to destination:
      //  if the value is deeply equal do nothing
      //  if the value is not deeply equal, call applyDiff with source value to
      //  destination value
      for (var property in source) {

        // if there is a difference between source and destination
        if (!lodash.isEqual(source[property], destination[property])) {

          // if destination[property] or source[property] is not an object,
          // there is no need for applying the diff, we can override entire
          // destination[property] with source[property]
          if (!lodash.isObject(destination[property]) ||
              !lodash.isObject(source[property])) {
            destination[property] = source[property];
          } else {
            applyDiff(source[property], destination[property]);
          }
        }
      }
    }

    if (typeof root._ === 'function') {
      root._.mixin({
        applyDiff: applyDiff
      });
    }

    if (typeof module !== 'undefined') {
      module.exports = function(lodash) {
        lodash.mixin({
          applyDiff: applyDiff
        });
      };
    }
})(this);
