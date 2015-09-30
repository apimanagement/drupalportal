'use strict';

var fs = require('fs');
var _ = require('lodash');

var workerCode = fs.readFileSync('./browser-worker.min.js').toString();
var templateFile = fs.readFileSync('./index.template.js').toString();

var result = _.template(templateFile)({
  workerCode: workerCode
});

fs.writeFileSync('index.js', result);
