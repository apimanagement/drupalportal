var popupMask;
var popupDialog;
var oauth2KeyName;

function handleLogin() {

  parentDiv = jQuery('div.swagger-section');
  if (authType == 'basic') {
    popupDialog = jQuery([
      '<div class="api-popup-dialog">',
      '<div class="api-popup-title">Basic Authentication</div>',
      '<div class="api-popup-content">',
      '<p>Please enter your userid and password.</p>',
      '<p><label for="api-popup-username-input">Username: </label><input id="api-popup-username-input" class="api-popup-username-input" type="text" name="username"></p>',
      '<p><label for="api-popup-password-input">Password: </label><input id="api-popup-password-input" class="api-popup-password-input" type="password" name="password"></p>',
      '<p class="error-msg"></p>',
      '<div class="api-popup-actions"><button class="api-popup-authbtn api-button green" type="button">Authenticate</button><button class="api-popup-cancel api-button gray" type="button">Cancel</button></div>',
      '</div>',
      '</div>'
    ].join(''));
  } else if (authType == 'oauth') {
    popupDialog = jQuery([
      '<div class="api-popup-dialog">',
      '<div class="api-popup-title">OAuth2.0 Token</div>',
      '<div class="api-popup-content">',
      '<p>Please follow the OAuth flow, copy access the token from OAuth and paste it here.</p>',
      '<input class="api-popup-token-input" type="text" name="token">',
      '<p class="error-msg"></p>',
      '<div class="api-popup-actions"><button class="api-popup-authbtn api-button green" type="button">Authenticate</button><button class="api-popup-cancel api-button gray" type="button">Cancel</button></div>',
      '</div>',
      '</div>'
    ].join(''));
  }
  jQuery('.api-popup-dialog', parentDiv).remove(); /* Discard any previous dialog instance. */
  jQuery(parentDiv).append(popupDialog);

  var $win = jQuery(window), dw = $win.width(), dh = $win.height(), st = $win.scrollTop(), dlgWd = popupDialog.outerWidth(), dlgHt = popupDialog
    .outerHeight(), top = (dh - dlgHt) / 2 + st, left = (dw - dlgWd) / 2;

  popupDialog.css({
    top: (top < 0 ? 0 : top) + 'px',
    left: (left < 0 ? 0 : left) + 'px'
  });

  popupDialog.find('button.api-popup-cancel').click(function() {
    popupMask.hide();
    popupDialog.hide();
    popupDialog.empty();
    popupDialog = [];
  });

  jQuery('button.api-popup-authbtn').unbind();
  popupDialog.find('button.api-popup-authbtn').click(function() {
    popupMask.hide();
    popupDialog.hide();

    jQuery.each(jQuery('.auth #api_information_panel'), function(k, v) {
      var children = v;
      if (children && children.childNodes) {
        o = v.parentNode;
        jQuery(o.parentNode).find('.api-ic.ic-off').addClass('ic-on');
        jQuery(o.parentNode).find('.api-ic.ic-off').removeClass('ic-off');
        jQuery(o).find('.api-ic').addClass('ic-info');
        jQuery(o).find('.api-ic').removeClass('ic-warning');
        jQuery(o).find('.api-ic').removeClass('ic-error');
      }
    });
    if (authType == 'basic') {
      var username = jQuery('input:text[name=username]').val();
      var password = jQuery('input:password[name=password]').val();
      var encodedString = Base64.encode(username + ':' + password);
      window.swaggerUi.api.clientAuthorizations.remove("basicauth");
      var basicauthkey = new SwaggerClient.ApiKeyAuthorization("Authorization", 'Basic ' + encodedString, "header");
      window.swaggerUi.api.clientAuthorizations.add("basicauth", basicauthkey);
    } else if (authType == 'oauth') {
      var tokentxt = jQuery('input:text[name=token]').val();
      window.swaggerUi.api.clientAuthorizations.remove("oauth");
      var oauthkey = new SwaggerClient.ApiKeyAuthorization("Authorization", 'Bearer ' + tokentxt, "header");
      window.swaggerUi.api.clientAuthorizations.add("oauth", oauthkey);
    }
  });

  popupMask.show();
  popupDialog.show();
  return;
}

function handleLogout() {
  window.authorizations.remove('oauth');
  window.authorizations.remove('basicauth');
  window.enabledScopes = null;
  jQuery('.api-ic.ic-on').addClass('ic-off');
  jQuery('.api-ic.ic-on').removeClass('ic-on');

  // set the info box
  jQuery('.api-ic.ic-warning').addClass('ic-error');
  jQuery('.api-ic.ic-warning').removeClass('ic-warning');
}

function initOAuth(opts) {
  var o = (opts || {});
  var errors = [];

  popupMask = (o.popupMask || jQuery('#api-common-mask'));
  popupDialog = (o.popupDialog || jQuery('.api-popup-dialog'));
  authType = (o.authType || errors.push('missing authType'));

  if (errors.length > 0) {
    log('auth unable initialize oauth: ' + errors);
    return;
  }

  jQuery('pre code').each(function(i, e) {
    hljs.highlightBlock(e)
  });
  jQuery('.api-ic').unbind();
  jQuery('.api-ic').click(function(s) {
    if (jQuery(s.target).hasClass('ic-off'))
      handleLogin();
    else {
      handleLogout();
    }
    false;
  });
}

// Create Base64 Object
var Base64 = {
  _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
  encode: function(e) {
    var t = "";
    var n, r, i, s, o, u, a;
    var f = 0;
    e = Base64._utf8_encode(e);
    while (f < e.length) {
      n = e.charCodeAt(f++);
      r = e.charCodeAt(f++);
      i = e.charCodeAt(f++);
      s = n >> 2;
      o = (n & 3) << 4 | r >> 4;
      u = (r & 15) << 2 | i >> 6;
      a = i & 63;
      if (isNaN(r)) {
        u = a = 64
      } else if (isNaN(i)) {
        a = 64
      }
      t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
    }
    return t
  },
  decode: function(e) {
    var t = "";
    var n, r, i;
    var s, o, u, a;
    var f = 0;
    e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
    while (f < e.length) {
      s = this._keyStr.indexOf(e.charAt(f++));
      o = this._keyStr.indexOf(e.charAt(f++));
      u = this._keyStr.indexOf(e.charAt(f++));
      a = this._keyStr.indexOf(e.charAt(f++));
      n = s << 2 | o >> 4;
      r = (o & 15) << 4 | u >> 2;
      i = (u & 3) << 6 | a;
      t = t + String.fromCharCode(n);
      if (u != 64) {
        t = t + String.fromCharCode(r)
      }
      if (a != 64) {
        t = t + String.fromCharCode(i)
      }
    }
    t = Base64._utf8_decode(t);
    return t
  },
  _utf8_encode: function(e) {
    e = e.replace(/\r\n/g, "\n");
    var t = "";
    for (var n = 0; n < e.length; n++) {
      var r = e.charCodeAt(n);
      if (r < 128) {
        t += String.fromCharCode(r)
      } else if (r > 127 && r < 2048) {
        t += String.fromCharCode(r >> 6 | 192);
        t += String.fromCharCode(r & 63 | 128)
      } else {
        t += String.fromCharCode(r >> 12 | 224);
        t += String.fromCharCode(r >> 6 & 63 | 128);
        t += String.fromCharCode(r & 63 | 128)
      }
    }
    return t
  },
  _utf8_decode: function(e) {
    var t = "";
    var n = 0;
    var r = c1 = c2 = 0;
    while (n < e.length) {
      r = e.charCodeAt(n);
      if (r < 128) {
        t += String.fromCharCode(r);
        n++
      } else if (r > 191 && r < 224) {
        c2 = e.charCodeAt(n + 1);
        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
        n += 2
      } else {
        c2 = e.charCodeAt(n + 1);
        c3 = e.charCodeAt(n + 2);
        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
        n += 3
      }
    }
    return t
  }
}