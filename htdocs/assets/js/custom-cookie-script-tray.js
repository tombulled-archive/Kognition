!function(o) {
    var e = !1;
    if ('function' == typeof define && define.amd && (define(o),
    e = !0),
    'object' == typeof exports && (module.exports = o(),
    e = !0),
    !e) {
        var c = window.Cookies
          , i = window.Cookies = o();
        i.noConflict = function() {
            return window.Cookies = c,
            i
        }
    }
}(function() {
    function o() {
        for (var o = 0, c = {}; o < arguments.length; o++) {
            var i = arguments[o];
            for (var e in i)
                c[e] = i[e]
        }
        ;return c
    }
    ;return function e(i) {
        function c(e, n, t) {
            var s;
            if ('undefined' != typeof document) {
                if (1 < arguments.length) {
                    if ('number' == typeof (t = o({
                        path: '/'
                    }, c.defaults, t)).expires) {
                        var l = new Date;
                        l.setMilliseconds(l.getMilliseconds() + 864e5 * t.expires),
                        t.expires = l
                    }
                    ;t.expires = t.expires ? t.expires.toUTCString() : '';
                    try {
                        s = JSON.stringify(n),
                        /^[\{\[]/.test(s) && (n = s)
                    } catch (p) {}
                    ;n = i.write ? i.write(n, e) : encodeURIComponent(String(n)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent),
                    e = (e = (e = encodeURIComponent(String(e))).replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)).replace(/[\(\)]/g, escape);
                    var u = '';
                    for (var d in t)
                        t[d] && (u += '; ' + d,
                        !0 !== t[d] && (u += '=' + t[d]));
                    return document.cookie = e + '=' + n + u
                }
                ;e || (s = {});
                for (var S = document.cookie ? document.cookie.split('; ') : [], h = /(%[0-9A-Z]{2})+/g, k = 0; k < S.length; k++) {
                    var f = S[k].split('=')
                      , r = f.slice(1).join('=');
                    this.json || '"' !== r.charAt(0) || (r = r.slice(1, -1));
                    try {
                        var a = f[0].replace(h, decodeURIComponent);
                        if (r = i.read ? i.read(r, a) : i(r, a) || r.replace(h, decodeURIComponent),
                        this.json)
                            try {
                                r = JSON.parse(r)
                            } catch (p) {}
                        ;if (e === a) {
                            s = r;
                            break
                        }
                        ;e || (s[a] = r)
                    } catch (p) {}
                }
                ;return s
            }
        }
        ;return (c.set = c).get = function(o) {
            return c.call(c, o)
        }
        ,
        c.getJSON = function() {
            return c.apply({
                json: !0
            }, [].slice.call(arguments))
        }
        ,
        c.defaults = {},
        c.remove = function(e, i) {
            c(e, '', o(i, {
                expires: -1
            }))
        }
        ,
        c.withConverter = e,
        c
    }(function() {})
});
var csCookies = Cookies.noConflict()
  , cookieScriptWindow = window.document
  , cookieScripts = document.getElementsByTagName('script')
  , cookieScriptSrc = cookieScripts[cookieScripts.length - 1].src
  , cookieQuery = null
  , cookieScriptPosition = 'bottom'
  , cookieScriptSource = 'cookie-script.com'
  , cookieScriptDomain = ''
  , cookieScriptReadMore = '/cookie-policy.html'
  , cookieId = 'fdc450abe426d36ecade676de2ee74c7'
  , cookieScriptDebug = 0
  , cookieScriptShowBadge = !0
  , cookieScriptCurrentUrl = window.location.href
  , cookieScriptTitle = '<h4 id="cookiescript_header">This website uses cookies</h4>'
  , cookieScriptDesc = 'This website uses cookies to improve user experience. By using our website you consent to all cookies in accordance with our Cookie Policy.<br \/>'
  , cookieScriptAccept = 'I agree'
  , cookieScriptMore = 'Read more'
  , cookieScriptCopyrights = 'I agree'
  , cookieBackground = '#111';
cookieScriptReject = '<div id="cookiescript_reject">I disagree</div>',
cookieScriptLoadJavaScript = function(e, i) {
    var c = document.getElementsByTagName('head')[0]
      , o = document.createElement('script');
    o.type = 'text/javascript',
    o.src = e,
    i != undefined && (o.onload = o.onreadystatechange = function() {
        (!o.readyState || /loaded|complete/.test(o.readyState)) && (o.onload = o.onreadystatechange = null,
        c && o.parentNode && c.removeChild(o),
        o = undefined,
        i())
    }
    ),
    c.insertBefore(o, c.firstChild)
}
,
InjectCookieScript = function() {
    cookieScriptDropfromFlag = 0;
    /*if (cookieScriptSrc != 'http://' + cookieScriptSource + '/s/' + cookieId + '.js' && cookieScriptSrc != 'http://chs02.' + cookieScriptSource + '/s/' + cookieId + '.js' && cookieScriptSrc != 'http://chs03.' + cookieScriptSource + '/s/' + cookieId + '.js' && cookieScriptSrc != 'https://' + cookieScriptSource + '/s/' + cookieId + '.js' && '' != cookieScriptSrc && cookieScriptSrc != 'https://eu.' + cookieScriptSource + '/s/' + cookieId + '.js' && cookieScriptSrc != 'http://eu.' + cookieScriptSource + '/s/' + cookieId + '.js' && cookieScriptSrc != 'https://' + cookieScriptSource + '/item?h=' + cookieId) {
        console.log('not allowed ' + cookieScriptSrc);
        return !1
    }*/
    ;cookieScriptDroptoFlag = 0;
    if (window.location.protocol == 'https:')
        cookieScriptStatsDomain = '';
    else
        cookieScriptStatsDomain = '';
    cookieScriptCreateCookie = function(o, e, c) {
        if (window.location.protocol == 'https:')
            var n = ';secure';
        else
            var n = '';
        var r = '', i, t;
        c && (i = new Date,
        i.setTime(i.getTime() + c * 864e5),
        r = '; expires=' + i.toGMTString()),
        t = '',
        cookieScriptDomain != '' && (t = '; domain=' + cookieScriptDomain),
        document.cookie = o + '=' + e + r + t + '; path=/' + n
    }
    ,
    cookieScriptReadCookie = function(o) {
        for (var c = o + '=', t = document.cookie.split(';'), e, i = 0; i < t.length; i++) {
            for (e = t[i]; e.charAt(0) == ' '; )
                e = e.substring(1, e.length);
            if (e.indexOf(c) == 0)
                return e.substring(c.length, e.length)
        }
        ;return null
    }
    ;
    cookieQuery(function() {
        cookieQuery('#cookiescript_injected', cookieScriptWindow).remove();
        cookieScriptAddBox = function() {
            cookieQuery('body', cookieScriptWindow).append('<div id="cookiescript_injected"><div id="cookiescript_wrapper">' + cookieScriptTitle + cookieScriptDesc + '<div id="cookiescript_buttons"><div id="cookiescript_accept">' + cookieScriptAccept + '</div>' + cookieScriptReject + '<div id="cookiescript_readmore">' + cookieScriptMore + '</div></div><a href="//' + cookieScriptSource + '" target="_blank" id="cookiescript_link" style="display:block !important">Free cookie consent by cookie-script.com</a><div id="cookiescript_pixel"></div></div>');
            if (cookieScriptPosition == 'top') {
                cookieQuery('#cookiescript_injected', cookieScriptWindow).css('top', 0)
            } else {
                cookieQuery('#cookiescript_injected', cookieScriptWindow).css('bottom', 0)
            }
            ;cookieQuery('#cookiescript_injected', cookieScriptWindow).css({
                'background-color': '#111111',
                'z-index': '999999',
                'opacity': '1',
                'position': 'fixed',
                'padding': '15px 0px 5px 0',
                'width': '100%',
                'left': '0',
                'font-size': '13px',
                'font-weight': 'normal',
                'text-align': 'left',
                'letter-spacing': 'normal',
                'color': '#FFFFFF',
                'font-family': 'Arial, sans-serif',
                'display': 'none',
                '-moz-box-shadow': '0px 0px 8px #000000',
                '-webkit-box-shadow': '0px 0px 8px #000000',
                'box-shadow': '0px 0px 8px #000000'
            });
            cookieQuery('#cookiescript_buttons', cookieScriptWindow).css({
                'margin': '10px auto 5px auto',
                'font-size': '13px',
                'font-weight': 'normal',
                'text-align': 'center',
                'font-family': 'Arial, sans-serif'
            });
            cookieQuery('#cookiescript_wrapper', cookieScriptWindow).css({
                'width': '100%',
                'margin': '0 auto',
                'font-size': '13px',
                'font-weight': 'normal',
                'text-align': 'center',
                'color': '#FFFFFF',
                'font-family': 'Arial, sans-serif',
                'line-height': '18px',
                'letter-spacing': 'normal',
                'padding': '0 5px'
            });
            cookieQuery('#cookiescript_injected h4#cookiescript_header', cookieScriptWindow).css({
                'background-color': '#111111',
                'z-index': '999999',
                'padding': '0 0 7px 0',
                'text-align': 'center',
                'color': '#FFFFFF',
                'font-family': 'Arial, sans-serif',
                'display': 'block',
                'font-size': '15px',
                'font-weight': 'bold',
                'margin': '0'
            });
            cookieQuery('#cookiescript_injected span', cookieScriptWindow).css({
                'display': 'block',
                'font-size': '100%',
                'margin': '5px 0'
            });
            cookieQuery('#cookiescript_injected a', cookieScriptWindow).css({
                'text-decoration': 'underline',
                'color': '#FFFFFF'
            });
            cookieQuery('#cookiescript_injected a#cookiescript_link', cookieScriptWindow).css({
                'text-decoration': 'none',
                'color': '#FFFFFF',
                'font-size': '85%',
                'padding': '0px 10px 0 0',
                'float': 'right'
            });
            cookieQuery('#cookiescript_injected div#cookiescript_accept,#cookiescript_injected div#cookiescript_readmore, #cookiescript_injected div#cookiescript_reject', cookieScriptWindow).css({
                '-webkit-border-radius': '5px',
                '-khtml-border-radius': '5px',
                '-moz-border-radius': '5px',
                'border-radius': '5px',
                'border': '0',
                'padding': '6px 10px',
                'font-weight': 'bold',
                'font-size': '13px',
                'cursor': 'pointer',
                'margin': '0 15px 0 0',
                '-webkit-transition': '0.25s',
                '-moz-transition': '0.25s',
                'transition': '0.25s',
                'text-shadow': 'rgb(0, 0, 0) 0px 0px 2px',
                'display': 'inline-block'
            });
            cookieQuery('#cookiescript_injected div#cookiescript_readmore', cookieScriptWindow).css({
                'background-color': '#697677',
                'color': '#FFFFFF'
            });
            cookieQuery('#cookiescript_injected div#cookiescript_reject', cookieScriptWindow).css({
                'background-color': '#B75B5B',
                'color': '#FFFFFF'
            });
            cookieQuery('#cookiescript_injected div#cookiescript_accept', cookieScriptWindow).css({
                'background-color': '#5BB75B',
                'color': '#FFFFFF'
            });
            cookieQuery('#cookiescript_injected div#cookiescript_pixel', cookieScriptWindow).css({
                'width': '1px',
                'height': '1px',
                'float': 'left'
            });
            cookieQuery('#cookiescript_injected', cookieScriptWindow).fadeIn(1000);
            cookieQuery('#cookiescript_injected div#cookiescript_accept', cookieScriptWindow).click(function() {
                i()
            });
            cookieQuery('#cookiescript_injected div#cookiescript_reject', cookieScriptWindow).click(function() {
                c()
            });
            cookieQuery('#cookiescript_injected div#cookiescript_readmore', cookieScriptWindow).click(function() {
                window.open(cookieScriptReadMore, '_blank');
                return !1
            })
        }
        ;
        cookieScriptCurrentValue = o('action');
        if (cookieScriptCurrentValue == 'accept')
            s();
        if (cookieScriptCurrentValue == 'accept' || cookieScriptCurrentValue == 'reject') {
            e();
            return !1
        }
        ;cookieScriptAddBox();
        p()
    });
    function p() {
        cookieQuery('iframe[data-cookiescript="accepted"]').not(':has([src])').each(function() {
            var o = this;
            o = (o.contentWindow) ? o.contentWindow : (o.contentDocument.document) ? o.contentDocument.document : o.contentDocument;
            o.document.open();
            o.document.write(cookieQuery(this).attr('alt'));
            o.document.close()
        })
    }
    ;function i() {
        cookieQuery('#cookiescript_injected', cookieScriptWindow).fadeOut(200);
        t('action', 'accept');
        s();
        cookieQuery('#csconsentcheckbox').prop('checked', !0);
        if (typeof cookieScriptShowBadge === 'undefined')
            cookieScriptShowBadge = !0;
        if (!cookieScriptShowBadge)
            return !1;
        e()
    }
    ;function c() {
        t('action', 'reject');
        n();
        cookieQuery('#cookiescript_injected', cookieScriptWindow).fadeOut(200);
        cookieQuery('#csconsentcheckbox').prop('checked', !1);
        if (typeof cookieScriptShowBadge === 'undefined')
            cookieScriptShowBadge = !0;
        if (!cookieScriptShowBadge)
            return !1;
        e()
    }
    ;function t(o, i) {
        var t = 'CookieScriptConsent';
        try {
            var c = JSON.parse(cookieScriptReadCookie(t))
        } catch (e) {
            console.log(e);
            return !1
        }
        ;if (c == null) {
            var c = {};
            c[o] = i
        } else {
            c[o] = i
        }
        ;try {
            var r = JSON.stringify(c)
        } catch (e) {
            console.log(e);
            return !1
        }
        ;cookieScriptCreateCookie(t, r, 30)
    }
    ;function o(o) {
        var c = 'CookieScriptConsent';
        try {
            var i = JSON.parse(cookieScriptReadCookie(c))
        } catch (e) {
            console.log(e);
            return null
        }
        ;if (i == null) {
            return null
        } else {
            return i[o]
        }
    }
    ;function e() {
        return !1
    }
    ;function a() {
        if (cookieQuery('#cookiescript_badge').length) {
            cookieQuery('#cookiescript_badge', cookieScriptWindow).fadeOut(200)
        }
        ;if (cookieQuery('#cookiescript_injected').length) {
            cookieQuery('#cookiescript_injected', cookieScriptWindow).fadeIn(200)
        } else {
            cookieScriptAddBox()
        }
    }
    ;function r(o) {
        if (r)
            console.log(o)
    }
    ;function d() {
        if (o('action') == 'accept') {
            cookieQuery('#csconsentcheckbox').prop('checked', !0)
        }
        ;cookieQuery('#csconsentcheckbox').change(function() {
            if (this.checked) {
                i()
            } else {
                c()
            }
        });
        cookieQuery('#csconsentlink').click(function() {
            a()
        })
    }
    ;d();
    function n() {
        var s = csCookies.get();
        for (var e in s) {
            if (e == 'CookieScriptConsent') {
                continue
            }
            ;if (!csCookies.remove(e)) {
                var o = [window.location.host, '.' + window.location.host]
                  , t = new RegExp('[a-z\-0-9]{2,63}\.[a-z\.]{2,6}$')
                  , n = t.exec(window.location.host)
                  , c = window.location.host.replace(n[0], '').slice(0, -1);
                if (c != '')
                    o.push(window.location.host.substr(c.length));
                for (var i in o) {
                    if (csCookies.remove(e, {
                        path: '/',
                        domain: o[i]
                    }) && r)
                        console.log('deleting cookie:' + e + '| domain:' + o[i])
                }
            }
        }
    }
    ;if (o('action') != 'accept')
        setTimeout(n, 500);
    function s() {
        cookieQuery('img[data-cookiescript="accepted"]').each(function() {
            cookieQuery(this).attr('src', cookieQuery(this).attr('data-src'))
        });
        cookieQuery('script[type="text/plain"][data-cookiescript="accepted"]').each(function() {
            if (cookieQuery(this).attr('src')) {
                cookieQuery(this).after('<script type="text/javascript" src="' + cookieQuery(this).attr('src') + '"><\/script>')
            } else {
                cookieQuery(this).after('<script type="text/javascript">' + cookieQuery(this).html() + '<\/script>')
            }
            ;cookieQuery(this).empty()
        });
        cookieQuery('iframe[data-cookiescript="accepted"]').each(function() {
            cookieQuery(this).attr('src', cookieQuery(this).attr('data-src'))
        });
        cookieQuery('embed[data-cookiescript="accepted"]').each(function() {
            cookieQuery(this).replaceWith(cookieQuery(this).attr('src', cookieQuery(this).attr('data-src'))[0].outerHTML)
        });
        cookieQuery('object[data-cookiescript="accepted"]').each(function() {
            cookieQuery(this).replaceWith(cookieQuery(this).attr('data', cookieQuery(this).attr('data-data'))[0].outerHTML)
        });
        cookieQuery('link[data-cookiescript="accepted"]').each(function() {
            cookieQuery(this).attr('href', cookieQuery(this).attr('data-href'))
        })
    }
}
;
window.jQuery && jQuery.fn && /^(1\.[8-9]|2\.[0-9]|1\.1[0-2]|3\.[0-9])/.test(jQuery.fn.jquery) ? (cookieScriptDebug && window.console && console.log('Using existing jQuery version ' + jQuery.fn.jquery),
cookieQuery = window.jQuery,
InjectCookieScript()) : (cookieScriptDebug && window.console && console.log('Loading jQuery 1.8.1 from ajax.googleapis.com'),
cookieScriptLoadJavaScript(('https:' == document.location.protocol ? 'https://' : 'http://') + 'ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', function() {
    cookieQuery = jQuery.noConflict(!0),
    InjectCookieScript()
}));
