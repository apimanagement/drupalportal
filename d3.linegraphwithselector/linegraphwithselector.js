/**
 * @file
 * D3 Line Graph library js file.
 */

(function($) {

  Drupal.d3.linegraphwithselector = function (select, settings) {
    var id = settings.id;
    var rows = settings.rows;
    var key = settings.legend || [];
    var datacols = settings.datacolumns || ['avg', 'max', 'min'];
    var fullwidth = 1075;
    var fullheight = 380;
    var clipid = id + '-clip';
    var defenderclipid = id + '-defender-clip';
    var circleradius = 3.5;
    var expandedradius = 9;
    var sensitiveradius = 50;
    var date_format = "L";
    var libpath = Drupal.settings.basePath + Drupal.settings.d3.inventory[id].library['library path'];
    var yAxisLabel = 'Latency (ms)';
    if (id == 'datausagegraph') {
      yAxisLabel = 'Data used (kb)';
    }
    
    var margin = {
      top: 25,
      right: 270,
      bottom: 200,
      left: 70
    },
    margin2 = {
      top: 250,
      right: 10,
      bottom: 70,
      left: 70
    },
    legend = {w: 250, h: fullheight},
    width = fullwidth - margin.left - margin.right,
    height = fullheight - margin.top - margin.bottom,
    height2 = fullheight - margin2.top - margin2.bottom;
    
    var parseDate = d3.time.format("%Y-%m-%dT%H:%M:%s.%LZ").parse;
    
    var vis = d3.select('#' + id);
    var visDefender = d3.select('#' + id +'-defender');

    var x = d3.time.scale().range([0, width]),
      x2 = d3.time.scale().range([0, width]),
      y = d3.scale.linear().nice().range([height, 0]),
      y2 = d3.scale.linear().nice().range([height2, 0]);
    
    // Setup data
    var data = rows;
    data.map(function (t) {
      t.datetime = new Date(t.datetime);
    });
    
    var initialBrushEnd = 0;
    var initialBrushStart = 168;
    if (data.length < 168) {
      initialBrushStart = data.length - 1;
    }
    
    x.domain(d3.extent(data.map(function (d) {
      return d.datetime;
    })));
    y.domain([0, d3.max(data, function (d) {
      return +d.max;
    })]);

    x2.domain(x.domain());
    y2.domain(y.domain());

    // Set up axes
    var xAxis = d3.svg.axis().scale(x).orient("bottom"),
      xAxis2 = d3.svg.axis().scale(x2).orient("bottom"),
      yAxis = d3.svg.axis().scale(y).orient("left").ticks(Math.min(d3.range(y.domain()[0], y.domain()[1]).length, 12)),
      yAxis2 = d3.svg.axis().scale(y2).orient("left").tickFormat(function(value){return ""});

    var brush = d3.svg.brush()
      .x(x2)
      .on("brush", brush);
    
    // Setup Lines
    var line = function (field) {
      return d3.svg.line()
        .interpolate("monotone")
        .x(function (d) {
          return x(d.datetime);
      })
        .y(function (d) {
          return y(d[field]);
      });
    };
    
    var line2 = function (field) {
      return d3.svg.line()
        .interpolate("monotone")
        .x(function (d) {
          return x2(d.datetime);
      })
        .y(function (d) {
          return y2(d[field]);
      });
    };
    
    // setup SVG
  var svg = d3.select('#' + id)
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom);

  svg.append("defs").append("clipPath")
    .attr("id", clipid)
    .append("rect")
    .attr("width", width)
    .attr("height", height);
  svg.append("defs").append("clipPath")
    .attr("id", defenderclipid)
    .append("rect")
    .attr("width", width)
    .attr("height", height2);
  svg.append("defs").append("clipPath")
    .attr("id", clipid+'-circle')
    .append("rect")
    .attr("width", width + (circleradius * 4))
    .attr("height", height + (circleradius * 4));
  
  var focus = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  var context = svg.append("g")
    .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

  focus.selectAll('path')
    .data(datacols)
    .enter()
    .append('path')
    .attr('clip-path', 'url(#'+clipid+')')
    .attr('d', function (col) {
       return line(col)(data);
    })
    .attr('class', function (col) {
       return "linepath path_" + col + " data";
    });

  focus.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis)
    .selectAll("text")
    .style("text-anchor", "end")
    .attr("dx", "-.8em")
    .attr("dy", ".15em")
    .attr("transform", function (d) {
       return "rotate(-45)";
    });

  focus.append("g")
    .attr("class", "y axis")
    .call(yAxis);
  
  //Add the text label for the Y axis
  svg.append("text")
    .attr("transform", function (d) {
       return "rotate(-90)";
    })
    .attr("y", -2)
    .attr("x",0 - (height / 2) - 20)
    .attr("dy", "1em")
    .style("text-anchor", "middle")
    .text(yAxisLabel);
  
  context.selectAll('path')
    .data(datacols)
    .enter()
    .append('path')
    .attr('clip-path', 'url(#'+defenderclipid+')')
    .attr('d', function (col) {
       return line2(col)(data);
    })
    .attr('class', function (col) {
       return "linepath path_" + col;
    });
  
  var leftHandle = context.append("image")
    .attr("width", 15)
    .attr("height", height2-2)
    .attr("y", 1)
    .attr("x",x2(data[initialBrushStart].datetime)-6)
    .attr("xlink:href",libpath+'/left-handle.png');

  var rightHandle = context.append("image")
    .attr("width", 15)
    .attr("height", height2-2)
    .attr("y", 1)
    .attr("x",x2(data[initialBrushEnd].datetime)-14)
    .attr("xlink:href",libpath+'/right-handle.png');

  context.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height2 + ")")
    .call(xAxis2)
    .selectAll("text")
    .style("text-anchor", "end")
    .attr("dx", "-.8em")
    .attr("dy", ".15em")
    .attr("transform", function (d) {
       return "rotate(-45)";
    });
  
  context.append("g")
    .attr("class", "y axis")
    .call(yAxis2);

  context.append("g")
    .attr("class", "x brush")
    .call(brush.extent([data[initialBrushStart].datetime,data[initialBrushEnd].datetime]))
    .selectAll("rect")
    .attr("y", 0)
    .attr("height", height2);
    
  brush.event(context.select('g.x.brush'));
   
  /* LEGEND */
  var legend = svg.append("g")
    .attr("class", "legend")
    .attr('width', legend.w)
    .attr("transform", "translate(" + (fullwidth - legend.w) + "," + 0 + ")");

  var keys = legend.selectAll("g")
    .data(datacols)
    .enter().append("g")
    .attr("transform", function(d,i) { return "translate(0," + i*25 + ")"});

  keys.append("rect")
    .attr('class', function (col) {
      return "fill_" + col;
    })
    .attr("width", 16)
    .attr("height", 16)
    .attr("y", 0)
    .attr("x", 0)
    .on('mouseover', function(col) {
      var group = focus.select('g.circle-group-' + col);
      group.selectAll('g').select('.circle-outer').attr('r', expandedradius);
    })
    .on('mouseout', function(col) {
      var group = focus.select('g.circle-group-' + col);
      group.selectAll('g').select('.circle-outer').attr('r', circleradius);
    });

  var labelWrapper = keys.append("g");

  labelWrapper.selectAll("text")
    .data(function(d,i) { return d3.splitString(key[i], 15); })
    .enter().append("text")
    .text(function(d,i) { return d})
    .attr("x", 20)
    .attr("y", function(d,i) { return i * 20})
    .attr("dy", "1em");
  
  // tooltips
  var mouseover = function (d, i) {
    if ($.isArray(d)) d = d[i];
    // Find the sibling circle, expand radius.

    var circle = d3.select(this.parentNode).select('.circle-outer');
    circle.attr('r', expandedradius);
    var fieldname = '';
    var cx = 0;
    var cy = 0;
    Array.prototype.forEach.call(this.attributes, function(attr) {
      if (attr.name == 'field') {
        fieldname = attr.value;
      } else if (attr.name == 'cx') {
        cx = attr.value;
      } else if (attr.name == 'cy') {
        cy = attr.value;
      } 
    });
    
    var tip = focus.select('.tooltip')
      .attr('visibility', 'visible')
      .attr('transform', function(d,i) { return 'translate(' + cx + ',' + cy + ')'})
      .select('.text text:last-child').text(d[fieldname]);

    var textWidth = focus.select('.tooltip .text :last-child')[0][0].getComputedTextLength();
    textWidth = textWidth < 55 ? 55 : textWidth;
    focus.select('.tooltip .text').attr('transform', 'translate(' + (10 - 5 - textWidth / 2) + ",-40)");
    focus.select('.tooltip').selectAll('path').attr("d", d3.tooltip.tooltipPath({ w: textWidth + 10, h: 40}));
  };
  var mouseout = function (d, i) {
    if ($.isArray(d)) d = d[i];
    // Find the sibling circle and reset its radius.
    d3.select(this.parentNode).select('.circle-outer')
      .attr('r', circleradius);

    focus.select('.tooltip').attr('visibility', 'hidden');
  };
  
  // Container for each circle to have a outer circle, a main one, and a rollover circle.
  var avgcircles = focus.append("g")
    .attr('class', 'circle-group-avg line-point');
   
  var avgpoint = avgcircles.selectAll('g')
    .data(data)
    .enter().append('g');
  
  // outer circle
  avgpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.avg) })
    .attr("field", 'avg')
    .attr("r", circleradius)
    .attr('fill-opacity', 0.2)
    .attr("class", function(d,i) { return 'circle-outer circle-' + i + '-outer path_avg'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  // inner circle
  avgpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.avg) })
    .attr("field", 'avg')
    .attr("r", circleradius)
    .style("fill", "white")
    .attr("class", function(d,i) { return 'circle circle-' + i + '-outer path_avg'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  avgpoint.append('clipPath').attr("id", function (d, i) { 
      return "clip-avg-" + i;
    }).append('circle')
    .attr("class", function(d,i) { return 'circle-mouse circle-' + i + '-mouse'; })
    .attr("fill", "#000000")
    .attr("color", "#000000")
    .attr("cx", function(d,i) { return x(d.datetime); })
    .attr("cy", function(d,i) { return y(d.avg); })
    .attr("r", sensitiveradius)
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  
  var maxcircles = focus.append("g")
    .attr('class', 'circle-group-max line-point');
  var maxpoint = maxcircles.selectAll('g')
    .data(data)
    .enter().append('g');
  // outer circle
  maxpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.max) })
    .attr("r", circleradius)
    .attr("field", 'max')
    .attr('fill-opacity', 0.2)
    .attr("class", function(d,i) { return 'circle-outer circle-' + i + '-outer path_max'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  // inner circle
  maxpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.max) })
    .attr("field", 'max')
    .attr("r", circleradius)
    .style("fill", "white")
    .attr("class", function(d,i) { return 'circle circle-' + i + '-outer path_max'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  maxpoint.append('clipPath').attr("id", function (d, i) { return "clip-max-" + i;}).append('circle')
    .attr("class", function(d,i) { return 'circle-mouse circle-' + i + '-mouse'; })
    .attr("fill", "#000000")
    .attr("color", "#000000")
    .attr("cx", function(d,i) { return x(d.datetime); })
    .attr("cy", function(d,i) { return y(d.max); })
    .attr("r", sensitiveradius)
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  
  var mincircles = focus.append("g")
    .attr('class', 'circle-group-min line-point');
  var minpoint = mincircles.selectAll('g')
    .data(data)
    .enter().append('g');
  //outer circle
  minpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.min) })
    .attr("r", circleradius)
    .attr("field", 'min')
    .attr('fill-opacity', 0.2)
    .attr("class", function(d,i) { return 'circle-outer circle-' + i + '-outer path_min'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  // inner circle
  minpoint.append('circle')
    .attr('clip-path', 'url(#'+clipid+'-circle'+')')
    .attr("cx", function(d) { return x(d.datetime) })
    .attr("cy", function(d) { return y(d.min) })
    .attr("field", 'min')
    .attr("r", circleradius)
    .style("fill", "white")
    .attr("class", function(d,i) { return 'circle circle-' + i + '-outer path_min'; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  minpoint.append('clipPath').attr("id", function (d, i) { return "clip-min-" + i;}).append('circle')
    .attr("class", function(d,i) { return 'circle-mouse circle-' + i + '-mouse'; })
    .attr("fill", "#000000")
    .attr("color", "#000000")
    .attr("cx", function(d,i) { return x(d.datetime); })
    .attr("cy", function(d,i) { return y(d.min); })
    .attr("r", sensitiveradius)
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  
  d3.tooltip(focus.append('g')
    .attr('class', 'tooltip')
    .attr('visibility', 'hidden'), "");
   
  // Brush
  function brush() {
    x.domain(brush.empty() ? x2.domain() : brush.extent());
    var ext = brush.extent();
    leftHandle.attr("x",x2(ext[0])-6);
    rightHandle.attr("x",x2(ext[1])-14);
    focus.selectAll("path.data")
      .attr("d", function (col) { 
        return line(col)(data); 
      });
    focus.select(".x.axis")
      .call(xAxis)
      .selectAll("text")
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", ".15em")
      .attr("transform", function (d) {
        return "rotate(-45)";
      });
    focus.selectAll(".line-point").selectAll("circle").attr("cx", function(d) { return x(d.datetime); });
  }
   
  }

})(jQuery);
