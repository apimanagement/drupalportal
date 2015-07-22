## `_.applyDiff`

[![Build Status](https://travis-ci.org/mohsen1/apply-diff.svg?branch=master)](https://travis-ci.org/mohsen1/apply-diff)

> Lodash plugin to update an object with properties of another object and make it deeply equal to it.

To update an object only with difference it has with another object you can use this module to avoid replacing entire object with a new one.

For example if you have a `destinationn` object where it needs to be replaced with a `sourcee` object that has a very small difference with `destinationn` you can use this module as following: 

```js
var source = {
  a: {
    one: 1,
    two: 2,
    b: {
      c: {
        three: 3.14
      }
    }
  }
};
var destination = {
  a: {
    one: 1,
    two: 2,
    b: {
      c: {
        three: 3
      }
    }
  }
};

_.applyDiff(source, destination);

// now destination is deeply equal to source without being replaced entirely
```

### Usage

**In browser**

include lodash and this module
```html
<script src="lodash.js"></script>
<script src="apply-diff/index.js"></script>
```
```js
_.applyDiff(source, destination);
```

**In NodeJS**
```js
var _ = require('lodash');
require('apply-diff')(_);
_.applyDiff(source, destination);
```

### Development
Install dependencies
```
npm install
```

`npm run watch` to run tests with watch

`npm test` ro run tests

### License 
MIT
