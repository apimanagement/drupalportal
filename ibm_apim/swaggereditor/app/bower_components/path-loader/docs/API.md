## Functions
<dl>
<dt><a href="#getLoader">getLoader(location)</a> ⇒ <code>object</code></dt>
<dd><p>Returns the loader for the given location.</p>
</dd>
<dt><a href="#load">load(location, [options], done)</a> ⇒ <code>Promise</code></dt>
<dd><p>Loads a document at the provided location and returns a JavaScript object representation.</p>
</dd>
</dl>
## Typedefs
<dl>
<dt><a href="#resultCallback">resultCallback</a> : <code>function</code></dt>
<dd><p>Error-first callback.</p>
</dd>
<dt><a href="#prepareRequestCallback">prepareRequestCallback</a> : <code>function</code></dt>
<dd><p>Callback used to provide access to altering a remote request prior to the request being made.</p>
</dd>
</dl>
<a name="getLoader"></a>
## getLoader(location) ⇒ <code>object</code>
Returns the loader for the given location.

**Kind**: global function  
**Returns**: <code>object</code> - The loader to use  

| Param | Type | Description |
| --- | --- | --- |
| location | <code>string</code> | The location to load |

<a name="load"></a>
## load(location, [options], done) ⇒ <code>Promise</code>
Loads a document at the provided location and returns a JavaScript object representation.

**Kind**: global function  
**Returns**: <code>Promise</code> - Always returns a promise even if there is a callback provided  

| Param | Type | Description |
| --- | --- | --- |
| location | <code>object</code> | The location to the document |
| [options] | <code>object</code> | The options |
| done | <code>[resultCallback](#resultCallback)</code> | The result callback |

**Example**  
```js
// Example using callbacks

PathLoader
  .load('./package.json', function (err, document) {
    if (err) {
      console.error(err.stack);
    } else {
      try {
        document = JSON.parse(document)
        console.log(document.name + ' (' + document.version + '): ' + document.description);
      } catch (err2) {
        callback(err2);
      }
    });
```
**Example**  
```js
// Example using Promises

PathLoader
  .load('./package.json')
  .then(JSON.parse)
  .then(function (document) {
    console.log(document.name + ' (' + document.version + '): ' + document.description);
  }, function (err) {
    console.error(err.stack);
  });
```
**Example**  
```js
// Example using options.prepareRequest to provide authentication details for a remotely secure URL

PathLoader
  .load('https://api.github.com/repos/whitlockjc/path-loader', {
    prepareRequest: function (req) {
      req.auth('my-username', 'my-password')
    }
  })
  .then(JSON.parse)
  .then(function (document) {
    console.log(document.full_name + ': ' + document.description);
  }, function (err) {
    console.error(err.stack);
  });
```
**Example**  
```js
// Example using options.processContent to load a YAML file

PathLoader
  .load('/Users/not-you/projects/path-loader/.travis.yml')
  .then(YAML.safeLoad)
  .then(function (document) {
    console.log('path-loader uses the', document.language, 'language.');
  }, function (err) {
    console.error(err.stack);
  });
```
<a name="resultCallback"></a>
## resultCallback : <code>function</code>
Error-first callback.

**Kind**: global typedef  

| Param | Type | Description |
| --- | --- | --- |
| [err] | <code>error</code> | The error if there is a problem |
| [result] | <code>string</code> | The result of the function |

<a name="prepareRequestCallback"></a>
## prepareRequestCallback : <code>function</code>
Callback used to provide access to altering a remote request prior to the request being made.

**Kind**: global typedef  

| Param | Type | Description |
| --- | --- | --- |
| req | <code>object</code> | The Superagent request object |
| location | <code>string</code> | The location being retrieved |

