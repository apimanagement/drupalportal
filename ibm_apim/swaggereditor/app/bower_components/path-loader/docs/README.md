path-loader is a small library that exposes a consistent API for loading files locally and remotely over http/https
URLs.

## Installation

path-loader is available for both Node.js and the browser.  Installation instructions for each environment are below.

### Browser

Installation for browser applications can be done via [Bower][bower] or by downloading a standalone binary.

#### Using Bower

```
bower install path-loader --save
```

#### Standalone Binaries

The standalone binaries come in two flavors:

* [path-loader.js](https://raw.github.com/whitlockjc/path-loader/master/browser/path-loader.js): _112kb_, full source source maps
* [path-loader-min.js](https://raw.github.com/whitlockjc/path-loader/master/browser/path-loader-min.js): _16kb_, minified, compressed
and no sourcemap

### Node.js

Installation for Node.js applications can be done via [NPM][npm].

```
npm install path-loader --save
```

## API Documentation

The path-loader project's API documentation can be found here: https://github.com/whitlockjc/path-loader/blob/master/docs/API.md

## Dependencies

Below is the list of projects being used by path-loader and the purpose(s) they are used for:

* [native-promise-only][native-promise-only]: Used to shim in [Promises][promises] support
* [superagent][superagent]: AJAX for the browser and Node.js

[bower]: http://bower.io/
[native-promise-only]: https://www.npmjs.com/package/native-promise-only
[npm]: https://www.npmjs.org/
[promises]: https://www.promisejs.org/
[superagent]: https://github.com/visionmedia/superagent
