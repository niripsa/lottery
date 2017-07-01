var APP_ROOT        = '';
var AJAX_LOGIN_URL  = '/user/ajax_login';
var AJAX_URL        = '/ajax';

//关于图片上传的定义
var LOADER_IMG      = 'http://qijian.techgou.com/app/Tpl/main/techgou/images/loader_img.gif';
var UPLOAD_SWF      = 'http://qijian.techgou.com/app/Tpl/main/techgou/js/utils/Moxie.swf';
var UPLOAD_XAP      = 'http://qijian.techgou.com/app/Tpl/main/techgou/js/utils/Moxie.xap';
var MAX_IMAGE_SIZE  = '2000000';
var ALLOW_IMAGE_EXT = 'jpg,gif,png,jpeg';
var UPLOAD_URL      = '/file/upload';
var QRCODE_ON       = '';
var cart_url        = '/cart';


/*!
 * jQuery JavaScript Library v1.4.1
 * http://jquery.com/
 *
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 * Copyright 2010, The Dojo Foundation
 * Released under the MIT, BSD, and GPL Licenses.
 *
 * Date: Mon Jan 25 19:43:33 2010 -0500
 */
(function(z, v) {
    function la() {
        if (!c.isReady) {
            try {
                r.documentElement.doScroll("left")
            } catch (a) {
                setTimeout(la, 1);
                return
            }
            c.ready()
        }
    }

    function Ma(a, b) {
        b.src ? c.ajax({
            url: b.src,
            async: false,
            dataType: "script"
        }) : c.globalEval(b.text || b.textContent || b.innerHTML || "");
        b.parentNode && b.parentNode.removeChild(b)
    }

    function X(a, b, d, f, e, i) {
        var j = a.length;
        if (typeof b === "object") {
            for (var n in b) X(a, n, b[n], f, e, d);
            return a
        }
        if (d !== v) {
            f = !i && f && c.isFunction(d);
            for (n = 0; n < j; n++) e(a[n], b, f ? d.call(a[n], n, e(a[n], b)) : d, i);
            return a
        }
        return j ?
            e(a[0], b) : null
    }

    function J() {
        return (new Date).getTime()
    }

    function Y() {
        return false
    }

    function Z() {
        return true
    }

    function ma(a, b, d) {
        d[0].type = a;
        return c.event.handle.apply(b, d)
    }

    function na(a) {
        var b, d = [],
            f = [],
            e = arguments,
            i, j, n, navo, m, s, x = c.extend({}, c.data(this, "events").live);
        if (!(a.button && a.type === "click")) {
            for (navo in x) {
                j = x[navo];
                if (j.live === a.type || j.altLive && c.inArray(a.type, j.altLive) > -1) {
                    i = j.data;
                    i.beforeFilter && i.beforeFilter[a.type] && !i.beforeFilter[a.type](a) || f.push(j.selector)
                } else delete x[navo]
            }
            i = c(a.target).closest(f,
                a.currentTarget);
            m = 0;
            for (s = i.length; m < s; m++)
                for (navo in x) {
                    j = x[navo];
                    n = i[m].elem;
                    f = null;
                    if (i[m].selector === j.selector) {
                        if (j.live === "mouseenter" || j.live === "mouseleave") f = c(a.relatedTarget).closest(j.selector)[0];
                        if (!f || f !== n) d.push({
                            elem: n,
                            fn: j
                        })
                    }
                }
            m = 0;
            for (s = d.length; m < s; m++) {
                i = d[m];
                a.currentTarget = i.elem;
                a.data = i.fn.data;
                if (i.fn.apply(i.elem, e) === false) {
                    b = false;
                    break
                }
            }
            return b
        }
    }

    function oa(a, b) {
        return "live." + (a ? a + "." : "") + b.replace(/\./g, "`").replace(/ /g, "&")
    }

    function pa(a) {
        return !a || !a.parentNode || a.parentNode.nodeType ===
            11
    }

    function qa(a, b) {
        var d = 0;
        b.each(function() {
            if (this.nodeName === (a[d] && a[d].nodeName)) {
                var f = c.data(a[d++]),
                    e = c.data(this, f);
                if (f = f && f.events) {
                    delete e.handle;
                    e.events = {};
                    for (var i in f)
                        for (var j in f[i]) c.event.add(this, i, f[i][j], f[i][j].data)
                }
            }
        })
    }

    function ra(a, b, d) {
        var f, e, i;
        if (a.length === 1 && typeof a[0] === "string" && a[0].length < 512 && a[0].indexOf("<option") < 0 && (c.support.checkClone || !sa.test(a[0]))) {
            e = true;
            if (i = c.fragments[a[0]])
                if (i !== 1) f = i
        }
        if (!f) {
            b = b && b[0] ? b[0].ownerDocument || b[0] : r;
            f = b.createDocumentFragment();
            c.clean(a, b, f, d)
        }
        if (e) c.fragments[a[0]] = i ? f : 1;
        return {
            fragment: f,
            cacheable: e
        }
    }

    function K(a, b) {
        var d = {};
        c.each(ta.concat.apply([], ta.slice(0, b)), function() {
            d[this] = a
        });
        return d
    }

    function ua(a) {
        return "scrollTo" in a && a.document ? a : a.nodeType === 9 ? a.defaultView || a.parentWindow : false
    }
    var c = function(a, b) {
            return new c.fn.init(a, b)
        },
        Na = z.jQuery,
        Oa = z.$,
        r = z.document,
        S, Pa = /^[^<]*(<[\w\W]+>)[^>]*$|^#([\w-]+)$/,
        Qa = /^.[^:#\[\.,]*$/,
        Ra = /\S/,
        Sa = /^(\s|\u00A0)+|(\s|\u00A0)+$/g,
        Ta = /^<(\w+)\s*\/?>(?:<\/\1>)?$/,
        O = navigator.userAgent,
        va = false,
        P = [],
        L, $ = Object.prototype.toString,
        aa = Object.prototype.hasOwnProperty,
        ba = Array.prototype.push,
        Q = Array.prototype.slice,
        wa = Array.prototype.indexOf;
    c.fn = c.prototype = {
        init: function(a, b) {
            var d, f;
            if (!a) return this;
            if (a.nodeType) {
                this.context = this[0] = a;
                this.length = 1;
                return this
            }
            if (typeof a === "string")
                if ((d = Pa.exec(a)) && (d[1] || !b))
                    if (d[1]) {
                        f = b ? b.ownerDocument || b : r;
                        if (a = Ta.exec(a))
                            if (c.isPlainObject(b)) {
                                a = [r.createElement(a[1])];
                                c.fn.attr.call(a, b, true)
                            } else a = [f.createElement(a[1])];
                        else {
                            a = ra([d[1]], [f]);
                            a = (a.cacheable ? a.fragment.cloneNode(true) : a.fragment).childNodes
                        }
                    } else {
                        if (b = r.getElementById(d[2])) {
                            if (b.id !== d[2]) return S.find(a);
                            this.length = 1;
                            this[0] = b
                        }
                        this.context = r;
                        this.selector = a;
                        return this
                    } else if (!b && /^\w+$/.test(a)) {
                this.selector = a;
                this.context = r;
                a = r.getElementsByTagName(a)
            } else return !b || b.jquery ? (b || S).find(a) : c(b).find(a);
            else if (c.isFunction(a)) return S.ready(a);
            if (a.selector !== v) {
                this.selector = a.selector;
                this.context = a.context
            }
            return c.isArray(a) ? this.setArray(a) : c.makeArray(a,
                this)
        },
        selector: "",
        jquery: "1.4.1",
        length: 0,
        size: function() {
            return this.length
        },
        toArray: function() {
            return Q.call(this, 0)
        },
        get: function(a) {
            return a == null ? this.toArray() : a < 0 ? this.slice(a)[0] : this[a]
        },
        pushStack: function(a, b, d) {
            a = c(a || null);
            a.prevObject = this;
            a.context = this.context;
            if (b === "find") a.selector = this.selector + (this.selector ? " " : "") + d;
            else if (b) a.selector = this.selector + "." + b + "(" + d + ")";
            return a
        },
        setArray: function(a) {
            this.length = 0;
            ba.apply(this, a);
            return this
        },
        each: function(a, b) {
            return c.each(this,
                a, b)
        },
        ready: function(a) {
            c.bindReady();
            if (c.isReady) a.call(r, c);
            else P && P.push(a);
            return this
        },
        eq: function(a) {
            return a === -1 ? this.slice(a) : this.slice(a, +a + 1)
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        slice: function() {
            return this.pushStack(Q.apply(this, arguments), "slice", Q.call(arguments).join(","))
        },
        map: function(a) {
            return this.pushStack(c.map(this, function(b, d) {
                return a.call(b, d, b)
            }))
        },
        end: function() {
            return this.prevObject || c(null)
        },
        push: ba,
        sort: [].sort,
        splice: [].splice
    };
    c.fn.init.prototype = c.fn;
    c.extend = c.fn.extend = function() {
        var a = arguments[0] || {},
            b = 1,
            d = arguments.length,
            f = false,
            e, i, j, n;
        if (typeof a === "boolean") {
            f = a;
            a = arguments[1] || {};
            b = 2
        }
        if (typeof a !== "object" && !c.isFunction(a)) a = {};
        if (d === b) {
            a = this;
            --b
        }
        for (; b < d; b++)
            if ((e = arguments[b]) != null)
                for (i in e) {
                    j = a[i];
                    n = e[i];
                    if (a !== n)
                        if (f && n && (c.isPlainObject(n) || c.isArray(n))) {
                            j = j && (c.isPlainObject(j) || c.isArray(j)) ? j : c.isArray(n) ? [] : {};
                            a[i] = c.extend(f, j, n)
                        } else if (n !== v) a[i] = n
                }
            return a
    };
    c.extend({
        noConflict: function(a) {
            z.$ =
                Oa;
            if (a) z.jQuery = Na;
            return c
        },
        isReady: false,
        ready: function() {
            if (!c.isReady) {
                if (!r.body) return setTimeout(c.ready, 13);
                c.isReady = true;
                if (P) {
                    for (var a, b = 0; a = P[b++];) a.call(r, c);
                    P = null
                }
                c.fn.triggerHandler && c(r).triggerHandler("ready")
            }
        },
        bindReady: function() {
            if (!va) {
                va = true;
                if (r.readyState === "complete") return c.ready();
                if (r.addEventListener) {
                    r.addEventListener("DOMContentLoaded", L, false);
                    z.addEventListener("load", c.ready, false)
                } else if (r.attachEvent) {
                    r.attachEvent("onreadystatechange", L);
                    z.attachEvent("onload",
                        c.ready);
                    var a = false;
                    try {
                        a = z.frameElement == null
                    } catch (b) {}
                    r.documentElement.doScroll && a && la()
                }
            }
        },
        isFunction: function(a) {
            return $.call(a) === "[object Function]"
        },
        isArray: function(a) {
            return $.call(a) === "[object Array]"
        },
        isPlainObject: function(a) {
            if (!a || $.call(a) !== "[object Object]" || a.nodeType || a.setInterval) return false;
            if (a.constructor && !aa.call(a, "constructor") && !aa.call(a.constructor.prototype, "isPrototypeOf")) return false;
            var b;
            for (b in a);
            return b === v || aa.call(a, b)
        },
        isEmptyObject: function(a) {
            for (var b in a) return false;
            return true
        },
        error: function(a) {
            throw a;
        },
        parseJSON: function(a) {
            if (typeof a !== "string" || !a) return null;
            if (/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return z.JSON && z.JSON.parse ? z.JSON.parse(a) : (new Function("return " + a))();
            else c.error("Invalid JSON: " + a)
        },
        noop: function() {},
        globalEval: function(a) {
            if (a && Ra.test(a)) {
                var b = r.getElementsByTagName("head")[0] ||
                    r.documentElement,
                    d = r.createElement("script");
                d.type = "text/javascript";
                if (c.support.scriptEval) d.appendChild(r.createTextNode(a));
                else d.text = a;
                b.insertBefore(d, b.firstChild);
                b.removeChild(d)
            }
        },
        nodeName: function(a, b) {
            return a.nodeName && a.nodeName.toUpperCase() === b.toUpperCase()
        },
        each: function(a, b, d) {
            var f, e = 0,
                i = a.length,
                j = i === v || c.isFunction(a);
            if (d)
                if (j)
                    for (f in a) {
                        if (b.apply(a[f], d) === false) break
                    } else
                        for (; e < i;) {
                            if (b.apply(a[e++], d) === false) break
                        } else if (j)
                            for (f in a) {
                                if (b.call(a[f], f, a[f]) === false) break
                            } else
                                for (d =
                                    a[0]; e < i && b.call(d, e, d) !== false; d = a[++e]);
            return a
        },
        trim: function(a) {
            return (a || "").replace(Sa, "")
        },
        makeArray: function(a, b) {
            b = b || [];
            if (a != null) a.length == null || typeof a === "string" || c.isFunction(a) || typeof a !== "function" && a.setInterval ? ba.call(b, a) : c.merge(b, a);
            return b
        },
        inArray: function(a, b) {
            if (b.indexOf) return b.indexOf(a);
            for (var d = 0, f = b.length; d < f; d++)
                if (b[d] === a) return d;
            return -1
        },
        merge: function(a, b) {
            var d = a.length,
                f = 0;
            if (typeof b.length === "number")
                for (var e = b.length; f < e; f++) a[d++] = b[f];
            else
                for (; b[f] !==
                    v;) a[d++] = b[f++];
            a.length = d;
            return a
        },
        grep: function(a, b, d) {
            for (var f = [], e = 0, i = a.length; e < i; e++)!d !== !b(a[e], e) && f.push(a[e]);
            return f
        },
        map: function(a, b, d) {
            for (var f = [], e, i = 0, j = a.length; i < j; i++) {
                e = b(a[i], i, d);
                if (e != null) f[f.length] = e
            }
            return f.concat.apply([], f)
        },
        guid: 1,
        proxy: function(a, b, d) {
            if (arguments.length === 2)
                if (typeof b === "string") {
                    d = a;
                    a = d[b];
                    b = v
                } else if (b && !c.isFunction(b)) {
                d = b;
                b = v
            }
            if (!b && a) b = function() {
                return a.apply(d || this, arguments)
            };
            if (a) b.guid = a.guid = a.guid || b.guid || c.guid++;
            return b
        },
        uaMatch: function(a) {
            a = a.toLowerCase();
            a = /(webkit)[ \/]([\w.]+)/.exec(a) || /(opera)(?:.*version)?[ \/]([\w.]+)/.exec(a) || /(msie) ([\w.]+)/.exec(a) || !/compatible/.test(a) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(a) || [];
            return {
                browser: a[1] || "",
                version: a[2] || "0"
            }
        },
        browser: {}
    });
    O = c.uaMatch(O);
    if (O.browser) {
        c.browser[O.browser] = true;
        c.browser.version = O.version
    }
    if (c.browser.webkit) c.browser.safari = true;
    if (wa) c.inArray = function(a, b) {
        return wa.call(b, a)
    };
    S = c(r);
    if (r.addEventListener) L = function() {
        r.removeEventListener("DOMContentLoaded",
            L, false);
        c.ready()
    };
    else if (r.attachEvent) L = function() {
        if (r.readyState === "complete") {
            r.detachEvent("onreadystatechange", L);
            c.ready()
        }
    };
    (function() {
        c.support = {};
        var a = r.documentElement,
            b = r.createElement("script"),
            d = r.createElement("div"),
            f = "script" + J();
        d.style.display = "none";
        d.innerHTML = "   <link/><table></table><a href='/a' style='color:red;float:left;opacity:.55;'>a</a><input type='checkbox'/>";
        var e = d.getElementsByTagName("*"),
            i = d.getElementsByTagName("a")[0];
        if (!(!e || !e.length || !i)) {
            c.support = {
                leadingWhitespace: d.firstChild.nodeType === 3,
                tbody: !d.getElementsByTagName("tbody").length,
                htmlSerialize: !!d.getElementsByTagName("link").length,
                style: /red/.test(i.getAttribute("style")),
                hrefNormalized: i.getAttribute("href") === "/a",
                opacity: /^0.55$/.test(i.style.opacity),
                cssFloat: !!i.style.cssFloat,
                checkOn: d.getElementsByTagName("input")[0].value === "on",
                optSelected: r.createElement("select").appendChild(r.createElement("option")).selected,
                checkClone: false,
                scriptEval: false,
                noCloneEvent: true,
                boxModel: null
            };
            b.type = "text/javascript";
            try {
                b.appendChild(r.createTextNode("window." + f + "=1;"))
            } catch (j) {}
            a.insertBefore(b, a.firstChild);
            if (z[f]) {
                c.support.scriptEval = true;
                delete z[f]
            }
            a.removeChild(b);
            if (d.attachEvent && d.fireEvent) {
                d.attachEvent("onclick", function n() {
                    c.support.noCloneEvent = false;
                    d.detachEvent("onclick", n)
                });
                d.cloneNode(true).fireEvent("onclick")
            }
            d = r.createElement("div");
            d.innerHTML = "<input type='radio' name='radiotest' checked='checked'/>";
            a = r.createDocumentFragment();
            a.appendChild(d.firstChild);
            c.support.checkClone = a.cloneNode(true).cloneNode(true).lastChild.checked;
            c(function() {
                var n = r.createElement("div");
                n.style.width = n.style.paddingLeft = "1px";
                r.body.appendChild(n);
                c.boxModel = c.support.boxModel = n.offsetWidth === 2;
                r.body.removeChild(n).style.display = "none"
            });
            a = function(n) {
                var navo = r.createElement("div");
                n = "on" + n;
                var m = n in navo;
                if (!m) {
                    navo.setAttribute(n, "return;");
                    m = typeof o[n] === "function"
                }
                return m
            };
            c.support.submitBubbles = a("submit");
            c.support.changeBubbles = a("change");
            a = b = d = e = i = null
        }
    })();
    c.props = {
        "for": "htmlFor",
        "class": "className",
        readonly: "readOnly",
        maxlength: "maxLength",
        cellspacing: "cellSpacing",
        rowspan: "rowSpan",
        colspan: "colSpan",
        tabindex: "tabIndex",
        usemap: "useMap",
        frameborder: "frameBorder"
    };
    var G = "jQuery" + J(),
        Ua = 0,
        xa = {},
        Va = {};
    c.extend({
        cache: {},
        expando: G,
        noData: {
            embed: true,
            object: true,
            applet: true
        },
        data: function(a, b, d) {
            if (!(a.nodeName && c.noData[a.nodeName.toLowerCase()])) {
                a = a == z ? xa : a;
                var f = a[G],
                    e = c.cache;
                if (!b && !f) return null;
                f || (f = ++Ua);
                if (typeof b === "object") {
                    a[G] = f;
                    e = e[f] = c.extend(true, {}, b)
                } else e = e[f] ? e[f] : typeof d === "undefined" ? Va : (e[f] = {}); if (d !== v) {
                    a[G] = f;
                    e[b] = d
                }
                return typeof b === "string" ? e[b] : e
            }
        },
        removeData: function(a, b) {
            if (!(a.nodeName && c.noData[a.nodeName.toLowerCase()])) {
                a = a == z ? xa : a;
                var d = a[G],
                    f = c.cache,
                    e = f[d];
                if (b) {
                    if (e) {
                        delete e[b];
                        c.isEmptyObject(e) && c.removeData(a)
                    }
                } else {
                    try {
                        delete a[G]
                    } catch (i) {
                        a.removeAttribute && a.removeAttribute(G)
                    }
                    delete f[d]
                }
            }
        }
    });
    c.fn.extend({
        data: function(a, b) {
            if (typeof a === "undefined" && this.length) return c.data(this[0]);
            else if (typeof a === "object") return this.each(function() {
                c.data(this,
                    a)
            });
            var d = a.split(".");
            d[1] = d[1] ? "." + d[1] : "";
            if (b === v) {
                var f = this.triggerHandler("getData" + d[1] + "!", [d[0]]);
                if (f === v && this.length) f = c.data(this[0], a);
                return f === v && d[1] ? this.data(d[0]) : f
            } else return this.trigger("setData" + d[1] + "!", [d[0], b]).each(function() {
                c.data(this, a, b)
            })
        },
        removeData: function(a) {
            return this.each(function() {
                c.removeData(this, a)
            })
        }
    });
    c.extend({
        queue: function(a, b, d) {
            if (a) {
                b = (b || "fx") + "queue";
                var f = c.data(a, b);
                if (!d) return f || [];
                if (!f || c.isArray(d)) f = c.data(a, b, c.makeArray(d));
                else f.push(d);
                return f
            }
        },
        dequeue: function(a, b) {
            b = b || "fx";
            var d = c.queue(a, b),
                f = d.shift();
            if (f === "inprogress") f = d.shift();
            if (f) {
                b === "fx" && d.unshift("inprogress");
                f.call(a, function() {
                    c.dequeue(a, b)
                })
            }
        }
    });
    c.fn.extend({
        queue: function(a, b) {
            if (typeof a !== "string") {
                b = a;
                a = "fx"
            }
            if (b === v) return c.queue(this[0], a);
            return this.each(function() {
                var d = c.queue(this, a, b);
                a === "fx" && d[0] !== "inprogress" && c.dequeue(this, a)
            })
        },
        dequeue: function(a) {
            return this.each(function() {
                c.dequeue(this, a)
            })
        },
        delay: function(a, b) {
            a = c.fx ? c.fx.speeds[a] ||
                a : a;
            b = b || "fx";
            return this.queue(b, function() {
                var d = this;
                setTimeout(function() {
                    c.dequeue(d, b)
                }, a)
            })
        },
        clearQueue: function(a) {
            return this.queue(a || "fx", [])
        }
    });
    var ya = /[\n\t]/g,
        ca = /\s+/,
        Wa = /\r/g,
        Xa = /href|src|style/,
        Ya = /(button|input)/i,
        Za = /(button|input|object|select|textarea)/i,
        $a = /^(a|area)$/i,
        za = /radio|checkbox/;
    c.fn.extend({
        attr: function(a, b) {
            return X(this, a, b, true, c.attr)
        },
        removeAttr: function(a) {
            return this.each(function() {
                c.attr(this, a, "");
                this.nodeType === 1 && this.removeAttribute(a)
            })
        },
        addClass: function(a) {
            if (c.isFunction(a)) return this.each(function(navo) {
                var m =
                    c(this);
                m.addClass(a.call(this, navo, m.attr("class")))
            });
            if (a && typeof a === "string")
                for (var b = (a || "").split(ca), d = 0, f = this.length; d < f; d++) {
                    var e = this[d];
                    if (e.nodeType === 1)
                        if (e.className)
                            for (var i = " " + e.className + " ", j = 0, n = b.length; j < n; j++) {
                                if (i.indexOf(" " + b[j] + " ") < 0) e.className += " " + b[j]
                            } else e.className = a
                }
            return this
        },
        removeClass: function(a) {
            if (c.isFunction(a)) return this.each(function(navo) {
                var m = c(this);
                m.removeClass(a.call(this, navo, m.attr("class")))
            });
            if (a && typeof a === "string" || a === v)
                for (var b = (a || "").split(ca),
                    d = 0, f = this.length; d < f; d++) {
                    var e = this[d];
                    if (e.nodeType === 1 && e.className)
                        if (a) {
                            for (var i = (" " + e.className + " ").replace(ya, " "), j = 0, n = b.length; j < n; j++) i = i.replace(" " + b[j] + " ", " ");
                            e.className = i.substring(1, i.length - 1)
                        } else e.className = ""
                }
            return this
        },
        toggleClass: function(a, b) {
            var d = typeof a,
                f = typeof b === "boolean";
            if (c.isFunction(a)) return this.each(function(e) {
                var i = c(this);
                i.toggleClass(a.call(this, e, i.attr("class"), b), b)
            });
            return this.each(function() {
                if (d === "string")
                    for (var e, i = 0, j = c(this), n = b, navo =
                        a.split(ca); e = navo[i++];) {
                        n = f ? n : !j.hasClass(e);
                        j[n ? "addClass" : "removeClass"](e)
                    } else if (d === "undefined" || d === "boolean") {
                        this.className && c.data(this, "__className__", this.className);
                        this.className = this.className || a === false ? "" : c.data(this, "__className__") || ""
                    }
            })
        },
        hasClass: function(a) {
            a = " " + a + " ";
            for (var b = 0, d = this.length; b < d; b++)
                if ((" " + this[b].className + " ").replace(ya, " ").indexOf(a) > -1) return true;
            return false
        },
        val: function(a) {
            if (a === v) {
                var b = this[0];
                if (b) {
                    if (c.nodeName(b, "option")) return (b.attributes.value || {}).specified ? b.value : b.text;
                    if (c.nodeName(b, "select")) {
                        var d = b.selectedIndex,
                            f = [],
                            e = b.options;
                        b = b.type === "select-one";
                        if (d < 0) return null;
                        var i = b ? d : 0;
                        for (d = b ? d + 1 : e.length; i < d; i++) {
                            var j = e[i];
                            if (j.selected) {
                                a = c(j).val();
                                if (b) return a;
                                f.push(a)
                            }
                        }
                        return f
                    }
                    if (za.test(b.type) && !c.support.checkOn) return b.getAttribute("value") === null ? "on" : b.value;
                    return (b.value || "").replace(Wa, "")
                }
                return v
            }
            var n = c.isFunction(a);
            return this.each(function(navo) {
                var m = c(this),
                    s = a;
                if (this.nodeType === 1) {
                    if (n) s = a.call(this, navo, m.val());
                    if (typeof s === "number") s += "";
                    if (c.isArray(s) && za.test(this.type)) this.checked = c.inArray(m.val(), s) >= 0;
                    else if (c.nodeName(this, "select")) {
                        var x = c.makeArray(s);
                        c("option", this).each(function() {
                            this.selected = c.inArray(c(this).val(), x) >= 0
                        });
                        if (!x.length) this.selectedIndex = -1
                    } else this.value = s
                }
            })
        }
    });
    c.extend({
        attrFn: {
            val: true,
            css: true,
            html: true,
            text: true,
            data: true,
            width: true,
            height: true,
            offset: true
        },
        attr: function(a, b, d, f) {
            if (!a || a.nodeType === 3 || a.nodeType === 8) return v;
            if (f && b in c.attrFn) return c(a)[b](d);
            f = a.nodeType !== 1 || !c.isXMLDoc(a);
            var e = d !== v;
            b = f && c.props[b] || b;
            if (a.nodeType === 1) {
                var i = Xa.test(b);
                if (b in a && f && !i) {
                    if (e) {
                        b === "type" && Ya.test(a.nodeName) && a.parentNode && c.error("type property can't be changed");
                        a[b] = d
                    }
                    if (c.nodeName(a, "form") && a.getAttributeNode(b)) return a.getAttributeNode(b).nodeValue;
                    if (b === "tabIndex") return (b = a.getAttributeNode("tabIndex")) && b.specified ? b.value : Za.test(a.nodeName) || $a.test(a.nodeName) && a.href ? 0 : v;
                    return a[b]
                }
                if (!c.support.style && f && b === "style") {
                    if (e) a.style.cssText =
                        "" + d;
                    return a.style.cssText
                }
                e && a.setAttribute(b, "" + d);
                a = !c.support.hrefNormalized && f && i ? a.getAttribute(b, 2) : a.getAttribute(b);
                return a === null ? v : a
            }
            return c.style(a, b, d)
        }
    });
    var ab = function(a) {
        return a.replace(/[^\w\s\.\|`]/g, function(b) {
            return "\\" + b
        })
    };
    c.event = {
        add: function(a, b, d, f) {
            if (!(a.nodeType === 3 || a.nodeType === 8)) {
                if (a.setInterval && a !== z && !a.frameElement) a = z;
                if (!d.guid) d.guid = c.guid++;
                if (f !== v) {
                    d = c.proxy(d);
                    d.data = f
                }
                var e = c.data(a, "events") || c.data(a, "events", {}),
                    i = c.data(a, "handle"),
                    j;
                if (!i) {
                    j =
                        function() {
                            return typeof c !== "undefined" && !c.event.triggered ? c.event.handle.apply(j.elem, arguments) : v
                    };
                    i = c.data(a, "handle", j)
                }
                if (i) {
                    i.elem = a;
                    b = b.split(/\s+/);
                    for (var n, navo = 0; n = b[navo++];) {
                        var m = n.split(".");
                        n = m.shift();
                        if (navo > 1) {
                            d = c.proxy(d);
                            if (f !== v) d.data = f
                        }
                        d.type = m.slice(0).sort().join(".");
                        var s = e[n],
                            x = this.special[n] || {};
                        if (!s) {
                            s = e[n] = {};
                            if (!x.setup || x.setup.call(a, f, m, d) === false)
                                if (a.addEventListener) a.addEventListener(n, i, false);
                                else a.attachEvent && a.attachEvent("on" + n, i)
                        }
                        if (x.add)
                            if ((m = x.add.call(a,
                                d, f, m, s)) && c.isFunction(m)) {
                                m.guid = m.guid || d.guid;
                                m.data = m.data || d.data;
                                m.type = m.type || d.type;
                                d = m
                            }
                        s[d.guid] = d;
                        this.global[n] = true
                    }
                    a = null
                }
            }
        },
        global: {},
        remove: function(a, b, d) {
            if (!(a.nodeType === 3 || a.nodeType === 8)) {
                var f = c.data(a, "events"),
                    e, i, j;
                if (f) {
                    if (b === v || typeof b === "string" && b.charAt(0) === ".")
                        for (i in f) this.remove(a, i + (b || ""));
                    else {
                        if (b.type) {
                            d = b.handler;
                            b = b.type
                        }
                        b = b.split(/\s+/);
                        for (var n = 0; i = b[n++];) {
                            var navo = i.split(".");
                            i = navo.shift();
                            var m = !navo.length,
                                s = c.map(navo.slice(0).sort(), ab);
                            s = new RegExp("(^|\\.)" +
                                s.join("\\.(?:.*\\.)?") + "(\\.|$)");
                            var x = this.special[i] || {};
                            if (f[i]) {
                                if (d) {
                                    j = f[i][d.guid];
                                    delete f[i][d.guid]
                                } else
                                    for (var A in f[i])
                                        if (m || s.test(f[i][A].type)) delete f[i][A];
                                x.remove && x.remove.call(a, navo, j);
                                for (e in f[i]) break;
                                if (!e) {
                                    if (!x.teardown || x.teardown.call(a, navo) === false)
                                        if (a.removeEventListener) a.removeEventListener(i, c.data(a, "handle"), false);
                                        else a.detachEvent && a.detachEvent("on" + i, c.data(a, "handle"));
                                    e = null;
                                    delete f[i]
                                }
                            }
                        }
                    }
                    for (e in f) break;
                    if (!e) {
                        if (A = c.data(a, "handle")) A.elem = null;
                        c.removeData(a,
                            "events");
                        c.removeData(a, "handle")
                    }
                }
            }
        },
        trigger: function(a, b, d, f) {
            var e = a.type || a;
            if (!f) {
                a = typeof a === "object" ? a[G] ? a : c.extend(c.Event(e), a) : c.Event(e);
                if (e.indexOf("!") >= 0) {
                    a.type = e = e.slice(0, -1);
                    a.exclusive = true
                }
                if (!d) {
                    a.stopPropagation();
                    this.global[e] && c.each(c.cache, function() {
                        this.events && this.events[e] && c.event.trigger(a, b, this.handle.elem)
                    })
                }
                if (!d || d.nodeType === 3 || d.nodeType === 8) return v;
                a.result = v;
                a.target = d;
                b = c.makeArray(b);
                b.unshift(a)
            }
            a.currentTarget = d;
            (f = c.data(d, "handle")) && f.apply(d,
                b);
            f = d.parentNode || d.ownerDocument;
            try {
                if (!(d && d.nodeName && c.noData[d.nodeName.toLowerCase()]))
                    if (d["on" + e] && d["on" + e].apply(d, b) === false) a.result = false
            } catch (i) {}
            if (!a.isPropagationStopped() && f) c.event.trigger(a, b, f, true);
            else if (!a.isDefaultPrevented()) {
                d = a.target;
                var j;
                if (!(c.nodeName(d, "a") && e === "click") && !(d && d.nodeName && c.noData[d.nodeName.toLowerCase()])) {
                    try {
                        if (d[e]) {
                            if (j = d["on" + e]) d["on" + e] = null;
                            this.triggered = true;
                            d[e]()
                        }
                    } catch (n) {}
                    if (j) d["on" + e] = j;
                    this.triggered = false
                }
            }
        },
        handle: function(a) {
            var b,
                d;
            a = arguments[0] = c.event.fix(a || z.event);
            a.currentTarget = this;
            d = a.type.split(".");
            a.type = d.shift();
            b = !d.length && !a.exclusive;
            var f = new RegExp("(^|\\.)" + d.slice(0).sort().join("\\.(?:.*\\.)?") + "(\\.|$)");
            d = (c.data(this, "events") || {})[a.type];
            for (var e in d) {
                var i = d[e];
                if (b || f.test(i.type)) {
                    a.handler = i;
                    a.data = i.data;
                    i = i.apply(this, arguments);
                    if (i !== v) {
                        a.result = i;
                        if (i === false) {
                            a.preventDefault();
                            a.stopPropagation()
                        }
                    }
                    if (a.isImmediatePropagationStopped()) break
                }
            }
            return a.result
        },
        props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),
        fix: function(a) {
            if (a[G]) return a;
            var b = a;
            a = c.Event(b);
            for (var d = this.props.length, f; d;) {
                f = this.props[--d];
                a[f] = b[f]
            }
            if (!a.target) a.target = a.srcElement || r;
            if (a.target.nodeType === 3) a.target = a.target.parentNode;
            if (!a.relatedTarget && a.fromElement) a.relatedTarget = a.fromElement === a.target ? a.toElement : a.fromElement;
            if (a.pageX == null && a.clientX != null) {
                b = r.documentElement;
                d = r.body;
                a.pageX = a.clientX + (b && b.scrollLeft || d && d.scrollLeft || 0) - (b && b.clientLeft || d && d.clientLeft || 0);
                a.pageY = a.clientY + (b && b.scrollTop ||
                    d && d.scrollTop || 0) - (b && b.clientTop || d && d.clientTop || 0)
            }
            if (!a.which && (a.charCode || a.charCode === 0 ? a.charCode : a.keyCode)) a.which = a.charCode || a.keyCode;
            if (!a.metaKey && a.ctrlKey) a.metaKey = a.ctrlKey;
            if (!a.which && a.button !== v) a.which = a.button & 1 ? 1 : a.button & 2 ? 3 : a.button & 4 ? 2 : 0;
            return a
        },
        guid: 1E8,
        proxy: c.proxy,
        special: {
            ready: {
                setup: c.bindReady,
                teardown: c.noop
            },
            live: {
                add: function(a, b) {
                    c.extend(a, b || {});
                    a.guid += b.selector + b.live;
                    b.liveProxy = a;
                    c.event.add(this, b.live, na, b)
                },
                remove: function(a) {
                    if (a.length) {
                        var b =
                            0,
                            d = new RegExp("(^|\\.)" + a[0] + "(\\.|$)");
                        c.each(c.data(this, "events").live || {}, function() {
                            d.test(this.type) && b++
                        });
                        b < 1 && c.event.remove(this, a[0], na)
                    }
                },
                special: {}
            },
            beforeunload: {
                setup: function(a, b, d) {
                    if (this.setInterval) this.onbeforeunload = d;
                    return false
                },
                teardown: function(a, b) {
                    if (this.onbeforeunload === b) this.onbeforeunload = null
                }
            }
        }
    };
    c.Event = function(a) {
        if (!this.preventDefault) return new c.Event(a);
        if (a && a.type) {
            this.originalEvent = a;
            this.type = a.type
        } else this.type = a;
        this.timeStamp = J();
        this[G] = true
    };
    c.Event.prototype = {
        preventDefault: function() {
            this.isDefaultPrevented = Z;
            var a = this.originalEvent;
            if (a) {
                a.preventDefault && a.preventDefault();
                a.returnValue = false
            }
        },
        stopPropagation: function() {
            this.isPropagationStopped = Z;
            var a = this.originalEvent;
            if (a) {
                a.stopPropagation && a.stopPropagation();
                a.cancelBubble = true
            }
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = Z;
            this.stopPropagation()
        },
        isDefaultPrevented: Y,
        isPropagationStopped: Y,
        isImmediatePropagationStopped: Y
    };
    var Aa = function(a) {
            for (var b =
                a.relatedTarget; b && b !== this;) try {
                b = b.parentNode
            } catch (d) {
                break
            }
            if (b !== this) {
                a.type = a.data;
                c.event.handle.apply(this, arguments)
            }
        },
        Ba = function(a) {
            a.type = a.data;
            c.event.handle.apply(this, arguments)
        };
    c.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(a, b) {
        c.event.special[a] = {
            setup: function(d) {
                c.event.add(this, b, d && d.selector ? Ba : Aa, a)
            },
            teardown: function(d) {
                c.event.remove(this, b, d && d.selector ? Ba : Aa)
            }
        }
    });
    if (!c.support.submitBubbles) c.event.special.submit = {
        setup: function(a, b, d) {
            if (this.nodeName.toLowerCase() !==
                "form") {
                c.event.add(this, "click.specialSubmit." + d.guid, function(f) {
                    var e = f.target,
                        i = e.type;
                    if ((i === "submit" || i === "image") && c(e).closest("form").length) return ma("submit", this, arguments)
                });
                c.event.add(this, "keypress.specialSubmit." + d.guid, function(f) {
                    var e = f.target,
                        i = e.type;
                    if ((i === "text" || i === "password") && c(e).closest("form").length && f.keyCode === 13) return ma("submit", this, arguments)
                })
            } else return false
        },
        remove: function(a, b) {
            c.event.remove(this, "click.specialSubmit" + (b ? "." + b.guid : ""));
            c.event.remove(this,
                "keypress.specialSubmit" + (b ? "." + b.guid : ""))
        }
    };
    if (!c.support.changeBubbles) {
        var da = /textarea|input|select/i;

        function Ca(a) {
            var b = a.type,
                d = a.value;
            if (b === "radio" || b === "checkbox") d = a.checked;
            else if (b === "select-multiple") d = a.selectedIndex > -1 ? c.map(a.options, function(f) {
                return f.selected
            }).join("-") : "";
            else if (a.nodeName.toLowerCase() === "select") d = a.selectedIndex;
            return d
        }

        function ea(a, b) {
            var d = a.target,
                f, e;
            if (!(!da.test(d.nodeName) || d.readOnly)) {
                f = c.data(d, "_change_data");
                e = Ca(d);
                if (a.type !== "focusout" ||
                    d.type !== "radio") c.data(d, "_change_data", e);
                if (!(f === v || e === f))
                    if (f != null || e) {
                        a.type = "change";
                        return c.event.trigger(a, b, d)
                    }
            }
        }
        c.event.special.change = {
            filters: {
                focusout: ea,
                click: function(a) {
                    var b = a.target,
                        d = b.type;
                    if (d === "radio" || d === "checkbox" || b.nodeName.toLowerCase() === "select") return ea.call(this, a)
                },
                keydown: function(a) {
                    var b = a.target,
                        d = b.type;
                    if (a.keyCode === 13 && b.nodeName.toLowerCase() !== "textarea" || a.keyCode === 32 && (d === "checkbox" || d === "radio") || d === "select-multiple") return ea.call(this, a)
                },
                beforeactivate: function(a) {
                    a =
                        a.target;
                    a.nodeName.toLowerCase() === "input" && a.type === "radio" && c.data(a, "_change_data", Ca(a))
                }
            },
            setup: function(a, b, d) {
                for (var f in T) c.event.add(this, f + ".specialChange." + d.guid, T[f]);
                return da.test(this.nodeName)
            },
            remove: function(a, b) {
                for (var d in T) c.event.remove(this, d + ".specialChange" + (b ? "." + b.guid : ""), T[d]);
                return da.test(this.nodeName)
            }
        };
        var T = c.event.special.change.filters
    }
    r.addEventListener && c.each({
        focus: "focusin",
        blur: "focusout"
    }, function(a, b) {
        function d(f) {
            f = c.event.fix(f);
            f.type = b;
            return c.event.handle.call(this,
                f)
        }
        c.event.special[b] = {
            setup: function() {
                this.addEventListener(a, d, true)
            },
            teardown: function() {
                this.removeEventListener(a, d, true)
            }
        }
    });
    c.each(["bind", "one"], function(a, b) {
        c.fn[b] = function(d, f, e) {
            if (typeof d === "object") {
                for (var i in d) this[b](i, f, d[i], e);
                return this
            }
            if (c.isFunction(f)) {
                e = f;
                f = v
            }
            var j = b === "one" ? c.proxy(e, function(n) {
                c(this).unbind(n, j);
                return e.apply(this, arguments)
            }) : e;
            return d === "unload" && b !== "one" ? this.one(d, f, e) : this.each(function() {
                c.event.add(this, d, j, f)
            })
        }
    });
    c.fn.extend({
        unbind: function(a,
            b) {
            if (typeof a === "object" && !a.preventDefault) {
                for (var d in a) this.unbind(d, a[d]);
                return this
            }
            return this.each(function() {
                c.event.remove(this, a, b)
            })
        },
        trigger: function(a, b) {
            return this.each(function() {
                c.event.trigger(a, b, this)
            })
        },
        triggerHandler: function(a, b) {
            if (this[0]) {
                a = c.Event(a);
                a.preventDefault();
                a.stopPropagation();
                c.event.trigger(a, b, this[0]);
                return a.result
            }
        },
        toggle: function(a) {
            for (var b = arguments, d = 1; d < b.length;) c.proxy(a, b[d++]);
            return this.click(c.proxy(a, function(f) {
                var e = (c.data(this, "lastToggle" +
                    a.guid) || 0) % d;
                c.data(this, "lastToggle" + a.guid, e + 1);
                f.preventDefault();
                return b[e].apply(this, arguments) || false
            }))
        },
        hover: function(a, b) {
            return this.mouseenter(a).mouseleave(b || a)
        }
    });
    c.each(["live", "die"], function(a, b) {
        c.fn[b] = function(d, f, e) {
            var i, j = 0;
            if (c.isFunction(f)) {
                e = f;
                f = v
            }
            for (d = (d || "").split(/\s+/);
                (i = d[j++]) != null;) {
                i = i === "focus" ? "focusin" : i === "blur" ? "focusout" : i === "hover" ? d.push("mouseleave") && "mouseenter" : i;
                b === "live" ? c(this.context).bind(oa(i, this.selector), {
                    data: f,
                    selector: this.selector,
                    live: i
                }, e) : c(this.context).unbind(oa(i, this.selector), e ? {
                    guid: e.guid + this.selector + i
                } : null)
            }
            return this
        }
    });
    c.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error".split(" "), function(a, b) {
        c.fn[b] = function(d) {
            return d ? this.bind(b, d) : this.trigger(b)
        };
        if (c.attrFn) c.attrFn[b] = true
    });
    z.attachEvent && !z.addEventListener && z.attachEvent("onunload", function() {
        for (var a in c.cache)
            if (c.cache[a].handle) try {
                c.event.remove(c.cache[a].handle.elem)
            } catch (b) {}
    });
    (function() {
        function a(g) {
            for (var h = "", k, l = 0; g[l]; l++) {
                k = g[l];
                if (k.nodeType === 3 || k.nodeType === 4) h += k.nodeValue;
                else if (k.nodeType !== 8) h += a(k.childNodes)
            }
            return h
        }

        function b(g, h, k, l, q, p) {
            q = 0;
            for (var u = l.length; q < u; q++) {
                var t = l[q];
                if (t) {
                    t = t[g];
                    for (var y = false; t;) {
                        if (t.sizcache === k) {
                            y = l[t.sizset];
                            break
                        }
                        if (t.nodeType === 1 && !p) {
                            t.sizcache = k;
                            t.sizset = q
                        }
                        if (t.nodeName.toLowerCase() === h) {
                            y = t;
                            break
                        }
                        t = t[g]
                    }
                    l[q] = y
                }
            }
        }

        function d(g, h, k, l, q, p) {
            q = 0;
            for (var u = l.length; q < u; q++) {
                var t = l[q];
                if (t) {
                    t = t[g];
                    for (var y = false; t;) {
                        if (t.sizcache ===
                            k) {
                            y = l[t.sizset];
                            break
                        }
                        if (t.nodeType === 1) {
                            if (!p) {
                                t.sizcache = k;
                                t.sizset = q
                            }
                            if (typeof h !== "string") {
                                if (t === h) {
                                    y = true;
                                    break
                                }
                            } else if (navo.filter(h, [t]).length > 0) {
                                y = t;
                                break
                            }
                        }
                        t = t[g]
                    }
                    l[q] = y
                }
            }
        }
        var f = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]*['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,
            e = 0,
            i = Object.prototype.toString,
            j = false,
            n = true;
        [0, 0].sort(function() {
            n = false;
            return 0
        });
        var navo = function(g, h, k, l) {
            k = k || [];
            var q = h = h || r;
            if (h.nodeType !== 1 && h.nodeType !== 9) return [];
            if (!g ||
                typeof g !== "string") return k;
            for (var p = [], u, t, y, R, H = true, M = w(h), I = g;
                (f.exec(""), u = f.exec(I)) !== null;) {
                I = u[3];
                p.push(u[1]);
                if (u[2]) {
                    R = u[3];
                    break
                }
            }
            if (p.length > 1 && s.exec(g))
                if (p.length === 2 && m.relative[p[0]]) t = fa(p[0] + p[1], h);
                else
                    for (t = m.relative[p[0]] ? [h] : navo(p.shift(), h); p.length;) {
                        g = p.shift();
                        if (m.relative[g]) g += p.shift();
                        t = fa(g, t)
                    } else {
                        if (!l && p.length > 1 && h.nodeType === 9 && !M && m.match.ID.test(p[0]) && !m.match.ID.test(p[p.length - 1])) {
                            u = navo.find(p.shift(), h, M);
                            h = u.expr ? navo.filter(u.expr, u.set)[0] : u.set[0]
                        }
                        if (h) {
                            u =
                                l ? {
                                    expr: p.pop(),
                                    set: A(l)
                            } : navo.find(p.pop(), p.length === 1 && (p[0] === "~" || p[0] === "+") && h.parentNode ? h.parentNode : h, M);
                            t = u.expr ? navo.filter(u.expr, u.set) : u.set;
                            if (p.length > 0) y = A(t);
                            else H = false;
                            for (; p.length;) {
                                var D = p.pop();
                                u = D;
                                if (m.relative[D]) u = p.pop();
                                else D = ""; if (u == null) u = h;
                                m.relative[D](y, u, M)
                            }
                        } else y = []
                    }
                y || (y = t);
            y || navo.error(D || g);
            if (i.call(y) === "[object Array]")
                if (H)
                    if (h && h.nodeType === 1)
                        for (g = 0; y[g] != null; g++) {
                            if (y[g] && (y[g] === true || y[g].nodeType === 1 && E(h, y[g]))) k.push(t[g])
                        } else
                            for (g = 0; y[g] != null; g++) y[g] &&
                                y[g].nodeType === 1 && k.push(t[g]);
                    else k.push.apply(k, y);
            else A(y, k); if (R) {
                navo(R, q, k, l);
                navo.uniqueSort(k)
            }
            return k
        };
        navo.uniqueSort = function(g) {
            if (C) {
                j = n;
                g.sort(C);
                if (j)
                    for (var h = 1; h < g.length; h++) g[h] === g[h - 1] && g.splice(h--, 1)
            }
            return g
        };
        navo.matches = function(g, h) {
            return navo(g, null, null, h)
        };
        navo.find = function(g, h, k) {
            var l, q;
            if (!g) return [];
            for (var p = 0, u = m.order.length; p < u; p++) {
                var t = m.order[p];
                if (q = m.leftMatch[t].exec(g)) {
                    var y = q[1];
                    q.splice(1, 1);
                    if (y.substr(y.length - 1) !== "\\") {
                        q[1] = (q[1] || "").replace(/\\/g, "");
                        l = m.find[t](q,
                            h, k);
                        if (l != null) {
                            g = g.replace(m.match[t], "");
                            break
                        }
                    }
                }
            }
            l || (l = h.getElementsByTagName("*"));
            return {
                set: l,
                expr: g
            }
        };
        navo.filter = function(g, h, k, l) {
            for (var q = g, p = [], u = h, t, y, R = h && h[0] && w(h[0]); g && h.length;) {
                for (var H in m.filter)
                    if ((t = m.leftMatch[H].exec(g)) != null && t[2]) {
                        var M = m.filter[H],
                            I, D;
                        D = t[1];
                        y = false;
                        t.splice(1, 1);
                        if (D.substr(D.length - 1) !== "\\") {
                            if (u === p) p = [];
                            if (m.preFilter[H])
                                if (t = m.preFilter[H](t, u, k, p, l, R)) {
                                    if (t === true) continue
                                } else y = I = true;
                            if (t)
                                for (var U = 0;
                                    (D = u[U]) != null; U++)
                                    if (D) {
                                        I = M(D, t, U, u);
                                        var Da =
                                            l ^ !!I;
                                        if (k && I != null)
                                            if (Da) y = true;
                                            else u[U] = false;
                                        else if (Da) {
                                            p.push(D);
                                            y = true
                                        }
                                    }
                            if (I !== v) {
                                k || (u = p);
                                g = g.replace(m.match[H], "");
                                if (!y) return [];
                                break
                            }
                        }
                    }
                if (g === q)
                    if (y == null) navo.error(g);
                    else break;
                q = g
            }
            return u
        };
        navo.error = function(g) {
            throw "Syntax error, unrecognized expression: " + g;
        };
        var m = navo.selectors = {
                order: ["ID", "NAME", "TAG"],
                match: {
                    ID: /#((?:[\w\u00c0-\uFFFF-]|\\.)+)/,
                    CLASS: /\.((?:[\w\u00c0-\uFFFF-]|\\.)+)/,
                    NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF-]|\\.)+)['"]*\]/,
                    ATTR: /\[\s*((?:[\w\u00c0-\uFFFF-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/,
                    TAG: /^((?:[\w\u00c0-\uFFFF\*-]|\\.)+)/,
                    CHILD: /:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/,
                    POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/,
                    PSEUDO: /:((?:[\w\u00c0-\uFFFF-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
                },
                leftMatch: {},
                attrMap: {
                    "class": "className",
                    "for": "htmlFor"
                },
                attrHandle: {
                    href: function(g) {
                        return g.getAttribute("href")
                    }
                },
                relative: {
                    "+": function(g, h) {
                        var k = typeof h === "string",
                            l = k && !/\W/.test(h);
                        k = k && !l;
                        if (l) h = h.toLowerCase();
                        l = 0;
                        for (var q = g.length,
                            p; l < q; l++)
                            if (p = g[l]) {
                                for (;
                                    (p = p.previousSibling) && p.nodeType !== 1;);
                                g[l] = k || p && p.nodeName.toLowerCase() === h ? p || false : p === h
                            }
                        k && navo.filter(h, g, true)
                    },
                    ">": function(g, h) {
                        var k = typeof h === "string";
                        if (k && !/\W/.test(h)) {
                            h = h.toLowerCase();
                            for (var l = 0, q = g.length; l < q; l++) {
                                var p = g[l];
                                if (p) {
                                    k = p.parentNode;
                                    g[l] = k.nodeName.toLowerCase() === h ? k : false
                                }
                            }
                        } else {
                            l = 0;
                            for (q = g.length; l < q; l++)
                                if (p = g[l]) g[l] = k ? p.parentNode : p.parentNode === h;
                            k && navo.filter(h, g, true)
                        }
                    },
                    "": function(g, h, k) {
                        var l = e++,
                            q = d;
                        if (typeof h === "string" && !/\W/.test(h)) {
                            var p =
                                h = h.toLowerCase();
                            q = b
                        }
                        q("parentNode", h, l, g, p, k)
                    },
                    "~": function(g, h, k) {
                        var l = e++,
                            q = d;
                        if (typeof h === "string" && !/\W/.test(h)) {
                            var p = h = h.toLowerCase();
                            q = b
                        }
                        q("previousSibling", h, l, g, p, k)
                    }
                },
                find: {
                    ID: function(g, h, k) {
                        if (typeof h.getElementById !== "undefined" && !k) return (g = h.getElementById(g[1])) ? [g] : []
                    },
                    NAME: function(g, h) {
                        if (typeof h.getElementsByName !== "undefined") {
                            var k = [];
                            h = h.getElementsByName(g[1]);
                            for (var l = 0, q = h.length; l < q; l++) h[l].getAttribute("name") === g[1] && k.push(h[l]);
                            return k.length === 0 ? null : k
                        }
                    },
                    TAG: function(g, h) {
                        return h.getElementsByTagName(g[1])
                    }
                },
                preFilter: {
                    CLASS: function(g, h, k, l, q, p) {
                        g = " " + g[1].replace(/\\/g, "") + " ";
                        if (p) return g;
                        p = 0;
                        for (var u;
                            (u = h[p]) != null; p++)
                            if (u)
                                if (q ^ (u.className && (" " + u.className + " ").replace(/[\t\n]/g, " ").indexOf(g) >= 0)) k || l.push(u);
                                else if (k) h[p] = false;
                        return false
                    },
                    ID: function(g) {
                        return g[1].replace(/\\/g, "")
                    },
                    TAG: function(g) {
                        return g[1].toLowerCase()
                    },
                    CHILD: function(g) {
                        if (g[1] === "nth") {
                            var h = /(-?)(\d*)n((?:\+|-)?\d*)/.exec(g[2] === "even" && "2n" || g[2] === "odd" &&
                                "2n+1" || !/\D/.test(g[2]) && "0n+" + g[2] || g[2]);
                            g[2] = h[1] + (h[2] || 1) - 0;
                            g[3] = h[3] - 0
                        }
                        g[0] = e++;
                        return g
                    },
                    ATTR: function(g, h, k, l, q, p) {
                        h = g[1].replace(/\\/g, "");
                        if (!p && m.attrMap[h]) g[1] = m.attrMap[h];
                        if (g[2] === "~=") g[4] = " " + g[4] + " ";
                        return g
                    },
                    PSEUDO: function(g, h, k, l, q) {
                        if (g[1] === "not")
                            if ((f.exec(g[3]) || "").length > 1 || /^\w/.test(g[3])) g[3] = o(g[3], null, null, h);
                            else {
                                g = navo.filter(g[3], h, k, true ^ q);
                                k || l.push.apply(l, g);
                                return false
                            } else if (m.match.POS.test(g[0]) || m.match.CHILD.test(g[0])) return true;
                        return g
                    },
                    POS: function(g) {
                        g.unshift(true);
                        return g
                    }
                },
                filters: {
                    enabled: function(g) {
                        return g.disabled === false && g.type !== "hidden"
                    },
                    disabled: function(g) {
                        return g.disabled === true
                    },
                    checked: function(g) {
                        return g.checked === true
                    },
                    selected: function(g) {
                        return g.selected === true
                    },
                    parent: function(g) {
                        return !!g.firstChild
                    },
                    empty: function(g) {
                        return !g.firstChild
                    },
                    has: function(g, h, k) {
                        return !!o(k[3], g).length
                    },
                    header: function(g) {
                        return /h\d/i.test(g.nodeName)
                    },
                    text: function(g) {
                        return "text" === g.type
                    },
                    radio: function(g) {
                        return "radio" === g.type
                    },
                    checkbox: function(g) {
                        return "checkbox" ===
                            g.type
                    },
                    file: function(g) {
                        return "file" === g.type
                    },
                    password: function(g) {
                        return "password" === g.type
                    },
                    submit: function(g) {
                        return "submit" === g.type
                    },
                    image: function(g) {
                        return "image" === g.type
                    },
                    reset: function(g) {
                        return "reset" === g.type
                    },
                    button: function(g) {
                        return "button" === g.type || g.nodeName.toLowerCase() === "button"
                    },
                    input: function(g) {
                        return /input|select|textarea|button/i.test(g.nodeName)
                    }
                },
                setFilters: {
                    first: function(g, h) {
                        return h === 0
                    },
                    last: function(g, h, k, l) {
                        return h === l.length - 1
                    },
                    even: function(g, h) {
                        return h % 2 ===
                            0
                    },
                    odd: function(g, h) {
                        return h % 2 === 1
                    },
                    lt: function(g, h, k) {
                        return h < k[3] - 0
                    },
                    gt: function(g, h, k) {
                        return h > k[3] - 0
                    },
                    nth: function(g, h, k) {
                        return k[3] - 0 === h
                    },
                    eq: function(g, h, k) {
                        return k[3] - 0 === h
                    }
                },
                filter: {
                    PSEUDO: function(g, h, k, l) {
                        var q = h[1],
                            p = m.filters[q];
                        if (p) return p(g, k, h, l);
                        else if (q === "contains") return (g.textContent || g.innerText || a([g]) || "").indexOf(h[3]) >= 0;
                        else if (q === "not") {
                            h = h[3];
                            k = 0;
                            for (l = h.length; k < l; k++)
                                if (h[k] === g) return false;
                            return true
                        } else navo.error("Syntax error, unrecognized expression: " +
                            q)
                    },
                    CHILD: function(g, h) {
                        var k = h[1],
                            l = g;
                        switch (k) {
                            case "only":
                            case "first":
                                for (; l = l.previousSibling;)
                                    if (l.nodeType === 1) return false;
                                if (k === "first") return true;
                                l = g;
                            case "last":
                                for (; l = l.nextSibling;)
                                    if (l.nodeType === 1) return false;
                                return true;
                            case "nth":
                                k = h[2];
                                var q = h[3];
                                if (k === 1 && q === 0) return true;
                                h = h[0];
                                var p = g.parentNode;
                                if (p && (p.sizcache !== h || !g.nodeIndex)) {
                                    var u = 0;
                                    for (l = p.firstChild; l; l = l.nextSibling)
                                        if (l.nodeType === 1) l.nodeIndex = ++u;
                                    p.sizcache = h
                                }
                                g = g.nodeIndex - q;
                                return k === 0 ? g === 0 : g % k === 0 && g / k >=
                                    0
                        }
                    },
                    ID: function(g, h) {
                        return g.nodeType === 1 && g.getAttribute("id") === h
                    },
                    TAG: function(g, h) {
                        return h === "*" && g.nodeType === 1 || g.nodeName.toLowerCase() === h
                    },
                    CLASS: function(g, h) {
                        return (" " + (g.className || g.getAttribute("class")) + " ").indexOf(h) > -1
                    },
                    ATTR: function(g, h) {
                        var k = h[1];
                        g = m.attrHandle[k] ? m.attrHandle[k](g) : g[k] != null ? g[k] : g.getAttribute(k);
                        k = g + "";
                        var l = h[2];
                        h = h[4];
                        return g == null ? l === "!=" : l === "=" ? k === h : l === "*=" ? k.indexOf(h) >= 0 : l === "~=" ? (" " + k + " ").indexOf(h) >= 0 : !h ? k && g !== false : l === "!=" ? k !== h : l === "^=" ?
                            k.indexOf(h) === 0 : l === "$=" ? k.substr(k.length - h.length) === h : l === "|=" ? k === h || k.substr(0, h.length + 1) === h + "-" : false
                    },
                    POS: function(g, h, k, l) {
                        var q = m.setFilters[h[2]];
                        if (q) return q(g, k, h, l)
                    }
                }
            },
            s = m.match.POS;
        for (var x in m.match) {
            m.match[x] = new RegExp(m.match[x].source + /(?![^\[]*\])(?![^\(]*\))/.source);
            m.leftMatch[x] = new RegExp(/(^(?:.|\r|\n)*?)/.source + m.match[x].source.replace(/\\(\d+)/g, function(g, h) {
                return "\\" + (h - 0 + 1)
            }))
        }
        var A = function(g, h) {
            g = Array.prototype.slice.call(g, 0);
            if (h) {
                h.push.apply(h, g);
                return h
            }
            return g
        };
        try {
            Array.prototype.slice.call(r.documentElement.childNodes, 0)
        } catch (B) {
            A = function(g, h) {
                h = h || [];
                if (i.call(g) === "[object Array]") Array.prototype.push.apply(h, g);
                else if (typeof g.length === "number")
                    for (var k = 0, l = g.length; k < l; k++) h.push(g[k]);
                else
                    for (k = 0; g[k]; k++) h.push(g[k]);
                return h
            }
        }
        var C;
        if (r.documentElement.compareDocumentPosition) C = function(g, h) {
            if (!g.compareDocumentPosition || !h.compareDocumentPosition) {
                if (g == h) j = true;
                return g.compareDocumentPosition ? -1 : 1
            }
            g = g.compareDocumentPosition(h) & 4 ? -1 : g ===
                h ? 0 : 1;
            if (g === 0) j = true;
            return g
        };
        else if ("sourceIndex" in r.documentElement) C = function(g, h) {
            if (!g.sourceIndex || !h.sourceIndex) {
                if (g == h) j = true;
                return g.sourceIndex ? -1 : 1
            }
            g = g.sourceIndex - h.sourceIndex;
            if (g === 0) j = true;
            return g
        };
        else if (r.createRange) C = function(g, h) {
            if (!g.ownerDocument || !h.ownerDocument) {
                if (g == h) j = true;
                return g.ownerDocument ? -1 : 1
            }
            var k = g.ownerDocument.createRange(),
                l = h.ownerDocument.createRange();
            k.setStart(g, 0);
            k.setEnd(g, 0);
            l.setStart(h, 0);
            l.setEnd(h, 0);
            g = k.compareBoundaryPoints(Range.START_TO_END,
                l);
            if (g === 0) j = true;
            return g
        };
        (function() {
            var g = r.createElement("div"),
                h = "script" + (new Date).getTime();
            g.innerHTML = "<a name='" + h + "'/>";
            var k = r.documentElement;
            k.insertBefore(g, k.firstChild);
            if (r.getElementById(h)) {
                m.find.ID = function(l, q, p) {
                    if (typeof q.getElementById !== "undefined" && !p) return (q = q.getElementById(l[1])) ? q.id === l[1] || typeof q.getAttributeNode !== "undefined" && q.getAttributeNode("id").nodeValue === l[1] ? [q] : v : []
                };
                m.filter.ID = function(l, q) {
                    var p = typeof l.getAttributeNode !== "undefined" && l.getAttributeNode("id");
                    return l.nodeType === 1 && p && p.nodeValue === q
                }
            }
            k.removeChild(g);
            k = g = null
        })();
        (function() {
            var g = r.createElement("div");
            g.appendChild(r.createComment(""));
            if (g.getElementsByTagName("*").length > 0) m.find.TAG = function(h, k) {
                k = k.getElementsByTagName(h[1]);
                if (h[1] === "*") {
                    h = [];
                    for (var l = 0; k[l]; l++) k[l].nodeType === 1 && h.push(k[l]);
                    k = h
                }
                return k
            };
            g.innerHTML = "<a href='#'></a>";
            if (g.firstChild && typeof g.firstChild.getAttribute !== "undefined" && g.firstChild.getAttribute("href") !== "#") m.attrHandle.href = function(h) {
                return h.getAttribute("href",
                    2)
            };
            g = null
        })();
        r.querySelectorAll && function() {
            var g = navo,
                h = r.createElement("div");
            h.innerHTML = "<p class='TEST'></p>";
            if (!(h.querySelectorAll && h.querySelectorAll(".TEST").length === 0)) {
                navo = function(l, q, p, u) {
                    q = q || r;
                    if (!u && q.nodeType === 9 && !w(q)) try {
                        return A(q.querySelectorAll(l), p)
                    } catch (t) {}
                    return g(l, q, p, u)
                };
                for (var k in g) navo[k] = g[k];
                h = null
            }
        }();
        (function() {
            var g = r.createElement("div");
            g.innerHTML = "<div class='test e'></div><div class='test'></div>";
            if (!(!g.getElementsByClassName || g.getElementsByClassName("e").length ===
                0)) {
                g.lastChild.className = "e";
                if (g.getElementsByClassName("e").length !== 1) {
                    m.order.splice(1, 0, "CLASS");
                    m.find.CLASS = function(h, k, l) {
                        if (typeof k.getElementsByClassName !== "undefined" && !l) return k.getElementsByClassName(h[1])
                    };
                    g = null
                }
            }
        })();
        var E = r.compareDocumentPosition ? function(g, h) {
                return g.compareDocumentPosition(h) & 16
            } : function(g, h) {
                return g !== h && (g.contains ? g.contains(h) : true)
            },
            w = function(g) {
                return (g = (g ? g.ownerDocument || g : 0).documentElement) ? g.nodeName !== "HTML" : false
            },
            fa = function(g, h) {
                var k = [],
                    l = "",
                    q;
                for (h = h.nodeType ? [h] : h; q = m.match.PSEUDO.exec(g);) {
                    l += q[0];
                    g = g.replace(m.match.PSEUDO, "")
                }
                g = m.relative[g] ? g + "*" : g;
                q = 0;
                for (var p = h.length; q < p; q++) o(g, h[q], k);
                return navo.filter(l, k)
            };
        c.find = navo;
        c.expr = navo.selectors;
        c.expr[":"] = c.expr.filters;
        c.unique = navo.uniqueSort;
        c.getText = a;
        c.isXMLDoc = w;
        c.contains = E
    })();
    var bb = /Until$/,
        cb = /^(?:parents|prevUntil|prevAll)/,
        db = /,/;
    Q = Array.prototype.slice;
    var Ea = function(a, b, d) {
        if (c.isFunction(b)) return c.grep(a, function(e, i) {
            return !!b.call(e, i, e) === d
        });
        else if (b.nodeType) return c.grep(a,
            function(e) {
                return e === b === d
            });
        else if (typeof b === "string") {
            var f = c.grep(a, function(e) {
                return e.nodeType === 1
            });
            if (Qa.test(b)) return c.filter(b, f, !d);
            else b = c.filter(b, f)
        }
        return c.grep(a, function(e) {
            return c.inArray(e, b) >= 0 === d
        })
    };
    c.fn.extend({
        find: function(a) {
            for (var b = this.pushStack("", "find", a), d = 0, f = 0, e = this.length; f < e; f++) {
                d = b.length;
                c.find(a, this[f], b);
                if (f > 0)
                    for (var i = d; i < b.length; i++)
                        for (var j = 0; j < d; j++)
                            if (b[j] === b[i]) {
                                b.splice(i--, 1);
                                break
                            }
            }
            return b
        },
        has: function(a) {
            var b = c(a);
            return this.filter(function() {
                for (var d =
                    0, f = b.length; d < f; d++)
                    if (c.contains(this, b[d])) return true
            })
        },
        not: function(a) {
            return this.pushStack(Ea(this, a, false), "not", a)
        },
        filter: function(a) {
            return this.pushStack(Ea(this, a, true), "filter", a)
        },
        is: function(a) {
            return !!a && c.filter(a, this).length > 0
        },
        closest: function(a, b) {
            if (c.isArray(a)) {
                var d = [],
                    f = this[0],
                    e, i = {},
                    j;
                if (f && a.length) {
                    e = 0;
                    for (var n = a.length; e < n; e++) {
                        j = a[e];
                        i[j] || (i[j] = c.expr.match.POS.test(j) ? c(j, b || this.context) : j)
                    }
                    for (; f && f.ownerDocument && f !== b;) {
                        for (j in i) {
                            e = i[j];
                            if (e.jquery ? e.index(f) >
                                -1 : c(f).is(e)) {
                                d.push({
                                    selector: j,
                                    elem: f
                                });
                                delete i[j]
                            }
                        }
                        f = f.parentNode
                    }
                }
                return d
            }
            var navo = c.expr.match.POS.test(a) ? c(a, b || this.context) : null;
            return this.map(function(m, s) {
                for (; s && s.ownerDocument && s !== b;) {
                    if (navo ? navo.index(s) > -1 : c(s).is(a)) return s;
                    s = s.parentNode
                }
                return null
            })
        },
        index: function(a) {
            if (!a || typeof a === "string") return c.inArray(this[0], a ? c(a) : this.parent().children());
            return c.inArray(a.jquery ? a[0] : a, this)
        },
        add: function(a, b) {
            a = typeof a === "string" ? c(a, b || this.context) : c.makeArray(a);
            b = c.merge(this.get(),
                a);
            return this.pushStack(pa(a[0]) || pa(b[0]) ? b : c.unique(b))
        },
        andSelf: function() {
            return this.add(this.prevObject)
        }
    });
    c.each({
        parent: function(a) {
            return (a = a.parentNode) && a.nodeType !== 11 ? a : null
        },
        parents: function(a) {
            return c.dir(a, "parentNode")
        },
        parentsUntil: function(a, b, d) {
            return c.dir(a, "parentNode", d)
        },
        next: function(a) {
            return c.nth(a, 2, "nextSibling")
        },
        prev: function(a) {
            return c.nth(a, 2, "previousSibling")
        },
        nextAll: function(a) {
            return c.dir(a, "nextSibling")
        },
        prevAll: function(a) {
            return c.dir(a, "previousSibling")
        },
        nextUntil: function(a, b, d) {
            return c.dir(a, "nextSibling", d)
        },
        prevUntil: function(a, b, d) {
            return c.dir(a, "previousSibling", d)
        },
        siblings: function(a) {
            return c.sibling(a.parentNode.firstChild, a)
        },
        children: function(a) {
            return c.sibling(a.firstChild)
        },
        contents: function(a) {
            return c.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document : c.makeArray(a.childNodes)
        }
    }, function(a, b) {
        c.fn[a] = function(d, f) {
            var e = c.map(this, b, d);
            bb.test(a) || (f = d);
            if (f && typeof f === "string") e = c.filter(f, e);
            e = this.length > 1 ? c.unique(e) :
                e;
            if ((this.length > 1 || db.test(f)) && cb.test(a)) e = e.reverse();
            return this.pushStack(e, a, Q.call(arguments).join(","))
        }
    });
    c.extend({
        filter: function(a, b, d) {
            if (d) a = ":not(" + a + ")";
            return c.find.matches(a, b)
        },
        dir: function(a, b, d) {
            var f = [];
            for (a = a[b]; a && a.nodeType !== 9 && (d === v || a.nodeType !== 1 || !c(a).is(d));) {
                a.nodeType === 1 && f.push(a);
                a = a[b]
            }
            return f
        },
        nth: function(a, b, d) {
            b = b || 1;
            for (var f = 0; a; a = a[d])
                if (a.nodeType === 1 && ++f === b) break;
            return a
        },
        sibling: function(a, b) {
            for (var d = []; a; a = a.nextSibling) a.nodeType === 1 && a !==
                b && d.push(a);
            return d
        }
    });
    var Fa = / jQuery\d+="(?:\d+|null)"/g,
        V = /^\s+/,
        Ga = /(<([\w:]+)[^>]*?)\/>/g,
        eb = /^(?:area|br|col|embed|hr|img|input|link|meta|param)$/i,
        Ha = /<([\w:]+)/,
        fb = /<tbody/i,
        gb = /<|&\w+;/,
        sa = /checked\s*(?:[^=]|=\s*.checked.)/i,
        Ia = function(a, b, d) {
            return eb.test(d) ? a : b + "></" + d + ">"
        },
        F = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            area: [1, "<map>", "</map>"],
            _default: [0, "", ""]
        };
    F.optgroup = F.option;
    F.tbody = F.tfoot = F.colgroup = F.caption = F.thead;
    F.th = F.td;
    if (!c.support.htmlSerialize) F._default = [1, "div<div>", "</div>"];
    c.fn.extend({
        text: function(a) {
            if (c.isFunction(a)) return this.each(function(b) {
                var d = c(this);
                d.text(a.call(this, b, d.text()))
            });
            if (typeof a !== "object" && a !== v) return this.empty().append((this[0] && this[0].ownerDocument || r).createTextNode(a));
            return c.getText(this)
        },
        wrapAll: function(a) {
            if (c.isFunction(a)) return this.each(function(d) {
                c(this).wrapAll(a.call(this, d))
            });
            if (this[0]) {
                var b = c(a, this[0].ownerDocument).eq(0).clone(true);
                this[0].parentNode && b.insertBefore(this[0]);
                b.map(function() {
                    for (var d = this; d.firstChild && d.firstChild.nodeType === 1;) d = d.firstChild;
                    return d
                }).append(this)
            }
            return this
        },
        wrapInner: function(a) {
            if (c.isFunction(a)) return this.each(function(b) {
                c(this).wrapInner(a.call(this, b))
            });
            return this.each(function() {
                var b = c(this),
                    d = b.contents();
                d.length ?
                    d.wrapAll(a) : b.append(a)
            })
        },
        wrap: function(a) {
            return this.each(function() {
                c(this).wrapAll(a)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                c.nodeName(this, "body") || c(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, true, function(a) {
                this.nodeType === 1 && this.appendChild(a)
            })
        },
        prepend: function() {
            return this.domManip(arguments, true, function(a) {
                this.nodeType === 1 && this.insertBefore(a, this.firstChild)
            })
        },
        before: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments,
                false, function(b) {
                    this.parentNode.insertBefore(b, this)
                });
            else if (arguments.length) {
                var a = c(arguments[0]);
                a.push.apply(a, this.toArray());
                return this.pushStack(a, "before", arguments)
            }
        },
        after: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments, false, function(b) {
                this.parentNode.insertBefore(b, this.nextSibling)
            });
            else if (arguments.length) {
                var a = this.pushStack(this, "after", arguments);
                a.push.apply(a, c(arguments[0]).toArray());
                return a
            }
        },
        clone: function(a) {
            var b = this.map(function() {
                if (!c.support.noCloneEvent &&
                    !c.isXMLDoc(this)) {
                    var d = this.outerHTML,
                        f = this.ownerDocument;
                    if (!d) {
                        d = f.createElement("div");
                        d.appendChild(this.cloneNode(true));
                        d = d.innerHTML
                    }
                    return c.clean([d.replace(Fa, "").replace(V, "")], f)[0]
                } else return this.cloneNode(true)
            });
            if (a === true) {
                qa(this, b);
                qa(this.find("*"), b.find("*"))
            }
            return b
        },
        html: function(a) {
            if (a === v) return this[0] && this[0].nodeType === 1 ? this[0].innerHTML.replace(Fa, "") : null;
            else if (typeof a === "string" && !/<script/i.test(a) && (c.support.leadingWhitespace || !V.test(a)) && !F[(Ha.exec(a) ||
                ["", ""])[1].toLowerCase()]) {
                a = a.replace(Ga, Ia);
                try {
                    for (var b = 0, d = this.length; b < d; b++)
                        if (this[b].nodeType === 1) {
                            c.cleanData(this[b].getElementsByTagName("*"));
                            this[b].innerHTML = a
                        }
                } catch (f) {
                    this.empty().append(a)
                }
            } else c.isFunction(a) ? this.each(function(e) {
                var i = c(this),
                    j = i.html();
                i.empty().append(function() {
                    return a.call(this, e, j)
                })
            }) : this.empty().append(a);
            return this
        },
        replaceWith: function(a) {
            if (this[0] && this[0].parentNode) {
                if (c.isFunction(a)) return this.each(function(b) {
                    var d = c(this),
                        f = d.html();
                    d.replaceWith(a.call(this,
                        b, f))
                });
                else a = c(a).detach();
                return this.each(function() {
                    var b = this.nextSibling,
                        d = this.parentNode;
                    c(this).remove();
                    b ? c(b).before(a) : c(d).append(a)
                })
            } else return this.pushStack(c(c.isFunction(a) ? a() : a), "replaceWith", a)
        },
        detach: function(a) {
            return this.remove(a, true)
        },
        domManip: function(a, b, d) {
            function f(s) {
                return c.nodeName(s, "table") ? s.getElementsByTagName("tbody")[0] || s.appendChild(s.ownerDocument.createElement("tbody")) : s
            }
            var e, i, j = a[0],
                n = [];
            if (!c.support.checkClone && arguments.length === 3 && typeof j ===
                "string" && sa.test(j)) return this.each(function() {
                c(this).domManip(a, b, d, true)
            });
            if (c.isFunction(j)) return this.each(function(s) {
                var x = c(this);
                a[0] = j.call(this, s, b ? x.html() : v);
                x.domManip(a, b, d)
            });
            if (this[0]) {
                e = a[0] && a[0].parentNode && a[0].parentNode.nodeType === 11 ? {
                    fragment: a[0].parentNode
                } : ra(a, this, n);
                if (i = e.fragment.firstChild) {
                    b = b && c.nodeName(i, "tr");
                    for (var navo = 0, m = this.length; navo < m; navo++) d.call(b ? f(this[navo], i) : this[navo], e.cacheable || this.length > 1 || navo > 0 ? e.fragment.cloneNode(true) : e.fragment)
                }
                n && c.each(n,
                    Ma)
            }
            return this
        }
    });
    c.fragments = {};
    c.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(a, b) {
        c.fn[a] = function(d) {
            var f = [];
            d = c(d);
            for (var e = 0, i = d.length; e < i; e++) {
                var j = (e > 0 ? this.clone(true) : this).get();
                c.fn[b].apply(c(d[e]), j);
                f = f.concat(j)
            }
            return this.pushStack(f, a, d.selector)
        }
    });
    c.each({
        remove: function(a, b) {
            if (!a || c.filter(a, [this]).length) {
                if (!b && this.nodeType === 1) {
                    c.cleanData(this.getElementsByTagName("*"));
                    c.cleanData([this])
                }
                this.parentNode &&
                    this.parentNode.removeChild(this)
            }
        },
        empty: function() {
            for (this.nodeType === 1 && c.cleanData(this.getElementsByTagName("*")); this.firstChild;) this.removeChild(this.firstChild)
        }
    }, function(a, b) {
        c.fn[a] = function() {
            return this.each(b, arguments)
        }
    });
    c.extend({
        clean: function(a, b, d, f) {
            b = b || r;
            if (typeof b.createElement === "undefined") b = b.ownerDocument || b[0] && b[0].ownerDocument || r;
            var e = [];
            c.each(a, function(i, j) {
                if (typeof j === "number") j += "";
                if (j) {
                    if (typeof j === "string" && !gb.test(j)) j = b.createTextNode(j);
                    else if (typeof j ===
                        "string") {
                        j = j.replace(Ga, Ia);
                        var n = (Ha.exec(j) || ["", ""])[1].toLowerCase(),
                            navo = F[n] || F._default,
                            m = navo[0];
                        i = b.createElement("div");
                        for (i.innerHTML = navo[1] + j + navo[2]; m--;) i = i.lastChild;
                        if (!c.support.tbody) {
                            m = fb.test(j);
                            n = n === "table" && !m ? i.firstChild && i.firstChild.childNodes : navo[1] === "<table>" && !m ? i.childNodes : [];
                            for (navo = n.length - 1; navo >= 0; --navo) c.nodeName(n[navo], "tbody") && !n[navo].childNodes.length && n[navo].parentNode.removeChild(n[navo])
                        }!c.support.leadingWhitespace && V.test(j) && i.insertBefore(b.createTextNode(V.exec(j)[0]), i.firstChild);
                        j = c.makeArray(i.childNodes)
                    }
                    if (j.nodeType) e.push(j);
                    else e = c.merge(e, j)
                }
            });
            if (d)
                for (a = 0; e[a]; a++)
                    if (f && c.nodeName(e[a], "script") && (!e[a].type || e[a].type.toLowerCase() === "text/javascript")) f.push(e[a].parentNode ? e[a].parentNode.removeChild(e[a]) : e[a]);
                    else {
                        e[a].nodeType === 1 && e.splice.apply(e, [a + 1, 0].concat(c.makeArray(e[a].getElementsByTagName("script"))));
                        d.appendChild(e[a])
                    }
            return e
        },
        cleanData: function(a) {
            for (var b = 0, d;
                (d = a[b]) != null; b++) {
                c.event.remove(d);
                c.removeData(d)
            }
        }
    });
    var hb = /z-?index|font-?weight|opacity|zoom|line-?height/i,
        Ja = /alpha\([^)]*\)/,
        Ka = /opacity=([^)]*)/,
        ga = /float/i,
        ha = /-([a-z])/ig,
        ib = /([A-Z])/g,
        jb = /^-?\d+(?:px)?$/i,
        kb = /^-?\d/,
        lb = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        mb = ["Left", "Right"],
        nb = ["Top", "Bottom"],
        ob = r.defaultView && r.defaultView.getComputedStyle,
        La = c.support.cssFloat ? "cssFloat" : "styleFloat",
        ia = function(a, b) {
            return b.toUpperCase()
        };
    c.fn.css = function(a, b) {
        return X(this, a, b, true, function(d, f, e) {
            if (e === v) return c.curCSS(d, f);
            if (typeof e === "number" && !hb.test(f)) e += "px";
            c.style(d, f, e)
        })
    };
    c.extend({
        style: function(a, b, d) {
            if (!a || a.nodeType === 3 || a.nodeType === 8) return v;
            if ((b === "width" || b === "height") && parseFloat(d) < 0) d = v;
            var f = a.style || a,
                e = d !== v;
            if (!c.support.opacity && b === "opacity") {
                if (e) {
                    f.zoom = 1;
                    b = parseInt(d, 10) + "" === "NaN" ? "" : "alpha(opacity=" + d * 100 + ")";
                    a = f.filter || c.curCSS(a, "filter") || "";
                    f.filter = Ja.test(a) ? a.replace(Ja, b) : b
                }
                return f.filter && f.filter.indexOf("opacity=") >= 0 ? parseFloat(Ka.exec(f.filter)[1]) / 100 + "" : ""
            }
            if (ga.test(b)) b = La;
            b = b.replace(ha, ia);
            if (e) f[b] = d;
            return f[b]
        },
        css: function(a,
            b, d, f) {
            if (b === "width" || b === "height") {
                var e, i = b === "width" ? mb : nb;

                function j() {
                    e = b === "width" ? a.offsetWidth : a.offsetHeight;
                    f !== "border" && c.each(i, function() {
                        f || (e -= parseFloat(c.curCSS(a, "padding" + this, true)) || 0);
                        if (f === "margin") e += parseFloat(c.curCSS(a, "margin" + this, true)) || 0;
                        else e -= parseFloat(c.curCSS(a, "border" + this + "Width", true)) || 0
                    })
                }
                a.offsetWidth !== 0 ? j() : c.swap(a, lb, j);
                return Math.max(0, Math.round(e))
            }
            return c.curCSS(a, b, d)
        },
        curCSS: function(a, b, d) {
            var f, e = a.style;
            if (!c.support.opacity && b === "opacity" &&
                a.currentStyle) {
                f = Ka.test(a.currentStyle.filter || "") ? parseFloat(RegExp.$1) / 100 + "" : "";
                return f === "" ? "1" : f
            }
            if (ga.test(b)) b = La;
            if (!d && e && e[b]) f = e[b];
            else if (ob) {
                if (ga.test(b)) b = "float";
                b = b.replace(ib, "-$1").toLowerCase();
                e = a.ownerDocument.defaultView;
                if (!e) return null;
                if (a = e.getComputedStyle(a, null)) f = a.getPropertyValue(b);
                if (b === "opacity" && f === "") f = "1"
            } else if (a.currentStyle) {
                d = b.replace(ha, ia);
                f = a.currentStyle[b] || a.currentStyle[d];
                if (!jb.test(f) && kb.test(f)) {
                    b = e.left;
                    var i = a.runtimeStyle.left;
                    a.runtimeStyle.left =
                        a.currentStyle.left;
                    e.left = d === "fontSize" ? "1em" : f || 0;
                    f = e.pixelLeft + "px";
                    e.left = b;
                    a.runtimeStyle.left = i
                }
            }
            return f
        },
        swap: function(a, b, d) {
            var f = {};
            for (var e in b) {
                f[e] = a.style[e];
                a.style[e] = b[e]
            }
            d.call(a);
            for (e in b) a.style[e] = f[e]
        }
    });
    if (c.expr && c.expr.filters) {
        c.expr.filters.hidden = function(a) {
            var b = a.offsetWidth,
                d = a.offsetHeight,
                f = a.nodeName.toLowerCase() === "tr";
            return b === 0 && d === 0 && !f ? true : b > 0 && d > 0 && !f ? false : c.curCSS(a, "display") === "none"
        };
        c.expr.filters.visible = function(a) {
            return !c.expr.filters.hidden(a)
        }
    }
    var pb =
        J(),
        qb = /<script(.|\s)*?\/script>/gi,
        rb = /select|textarea/i,
        sb = /color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week/i,
        N = /=\?(&|$)/,
        ja = /\?/,
        tb = /(\?|&)_=.*?(&|$)/,
        ub = /^(\w+:)?\/\/([^\/?#]+)/,
        vb = /%20/g;
    c.fn.extend({
        _load: c.fn.load,
        load: function(a, b, d) {
            if (typeof a !== "string") return this._load(a);
            else if (!this.length) return this;
            var f = a.indexOf(" ");
            if (f >= 0) {
                var e = a.slice(f, a.length);
                a = a.slice(0, f)
            }
            f = "GET";
            if (b)
                if (c.isFunction(b)) {
                    d = b;
                    b = null
                } else if (typeof b === "object") {
                b =
                    c.param(b, c.ajaxSettings.traditional);
                f = "POST"
            }
            var i = this;
            c.ajax({
                url: a,
                type: f,
                dataType: "html",
                data: b,
                complete: function(j, n) {
                    if (n === "success" || n === "notmodified") i.html(e ? c("<div />").append(j.responseText.replace(qb, "")).find(e) : j.responseText);
                    d && i.each(d, [j.responseText, n, j])
                }
            });
            return this
        },
        serialize: function() {
            return c.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                return this.elements ? c.makeArray(this.elements) : this
            }).filter(function() {
                return this.name && !this.disabled &&
                    (this.checked || rb.test(this.nodeName) || sb.test(this.type))
            }).map(function(a, b) {
                a = c(this).val();
                return a == null ? null : c.isArray(a) ? c.map(a, function(d) {
                    return {
                        name: b.name,
                        value: d
                    }
                }) : {
                    name: b.name,
                    value: a
                }
            }).get()
        }
    });
    c.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function(a, b) {
        c.fn[b] = function(d) {
            return this.bind(b, d)
        }
    });
    c.extend({
        get: function(a, b, d, f) {
            if (c.isFunction(b)) {
                f = f || d;
                d = b;
                b = null
            }
            return c.ajax({
                type: "GET",
                url: a,
                data: b,
                success: d,
                dataType: f
            })
        },
        getScript: function(a,
            b) {
            return c.get(a, null, b, "script")
        },
        getJSON: function(a, b, d) {
            return c.get(a, b, d, "json")
        },
        post: function(a, b, d, f) {
            if (c.isFunction(b)) {
                f = f || d;
                d = b;
                b = {}
            }
            return c.ajax({
                type: "POST",
                url: a,
                data: b,
                success: d,
                dataType: f
            })
        },
        ajaxSetup: function(a) {
            c.extend(c.ajaxSettings, a)
        },
        ajaxSettings: {
            url: location.href,
            global: true,
            type: "GET",
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            async: true,
            xhr: z.XMLHttpRequest && (z.location.protocol !== "file:" || !z.ActiveXObject) ? function() {
                return new z.XMLHttpRequest
            } : function() {
                try {
                    return new z.ActiveXObject("Microsoft.XMLHTTP")
                } catch (a) {}
            },
            accepts: {
                xml: "application/xml, text/xml",
                html: "text/html",
                script: "text/javascript, application/javascript",
                json: "application/json, text/javascript",
                text: "text/plain",
                _default: "*/*"
            }
        },
        lastModified: {},
        etag: {},
        ajax: function(a) {
            function b() {
                e.success && e.success.call(navo, n, j, w);
                e.global && f("ajaxSuccess", [w, e])
            }

            function d() {
                e.complete && e.complete.call(navo, w, j);
                e.global && f("ajaxComplete", [w, e]);
                e.global && !--c.active && c.event.trigger("ajaxStop")
            }

            function f(q, p) {
                (e.context ? c(e.context) : c.event).trigger(q, p)
            }
            var e = c.extend(true, {}, c.ajaxSettings, a),
                i, j, n, navo = a && a.context || e,
                m = e.type.toUpperCase();
            if (e.data && e.processData && typeof e.data !== "string") e.data = c.param(e.data, e.traditional);
            if (e.dataType === "jsonp") {
                if (m === "GET") N.test(e.url) || (e.url += (ja.test(e.url) ? "&" : "?") + (e.jsonp || "callback") + "=?");
                else if (!e.data || !N.test(e.data)) e.data = (e.data ? e.data + "&" : "") + (e.jsonp || "callback") + "=?";
                e.dataType = "json"
            }
            if (e.dataType === "json" && (e.data && N.test(e.data) ||
                N.test(e.url))) {
                i = e.jsonpCallback || "jsonp" + pb++;
                if (e.data) e.data = (e.data + "").replace(N, "=" + i + "$1");
                e.url = e.url.replace(N, "=" + i + "$1");
                e.dataType = "script";
                z[i] = z[i] || function(q) {
                    n = q;
                    b();
                    d();
                    z[i] = v;
                    try {
                        delete z[i]
                    } catch (p) {}
                    A && A.removeChild(B)
                }
            }
            if (e.dataType === "script" && e.cache === null) e.cache = false;
            if (e.cache === false && m === "GET") {
                var s = J(),
                    x = e.url.replace(tb, "$1_=" + s + "$2");
                e.url = x + (x === e.url ? (ja.test(e.url) ? "&" : "?") + "_=" + s : "")
            }
            if (e.data && m === "GET") e.url += (ja.test(e.url) ? "&" : "?") + e.data;
            e.global && !c.active++ &&
                c.event.trigger("ajaxStart");
            s = (s = ub.exec(e.url)) && (s[1] && s[1] !== location.protocol || s[2] !== location.host);
            if (e.dataType === "script" && m === "GET" && s) {
                var A = r.getElementsByTagName("head")[0] || r.documentElement,
                    B = r.createElement("script");
                B.src = e.url;
                if (e.scriptCharset) B.charset = e.scriptCharset;
                if (!i) {
                    var C = false;
                    B.onload = B.onreadystatechange = function() {
                        if (!C && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
                            C = true;
                            b();
                            d();
                            B.onload = B.onreadystatechange = null;
                            A && B.parentNode &&
                                A.removeChild(B)
                        }
                    }
                }
                A.insertBefore(B, A.firstChild);
                return v
            }
            var E = false,
                w = e.xhr();
            if (w) {
                e.username ? w.open(m, e.url, e.async, e.username, e.password) : w.open(m, e.url, e.async);
                try {
                    if (e.data || a && a.contentType) w.setRequestHeader("Content-Type", e.contentType);
                    if (e.ifModified) {
                        c.lastModified[e.url] && w.setRequestHeader("If-Modified-Since", c.lastModified[e.url]);
                        c.etag[e.url] && w.setRequestHeader("If-None-Match", c.etag[e.url])
                    }
                    s || w.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                    w.setRequestHeader("Accept",
                        e.dataType && e.accepts[e.dataType] ? e.accepts[e.dataType] + ", */*" : e.accepts._default)
                } catch (fa) {}
                if (e.beforeSend && e.beforeSend.call(navo, w, e) === false) {
                    e.global && !--c.active && c.event.trigger("ajaxStop");
                    w.abort();
                    return false
                }
                e.global && f("ajaxSend", [w, e]);
                var g = w.onreadystatechange = function(q) {
                    if (!w || w.readyState === 0 || q === "abort") {
                        E || d();
                        E = true;
                        if (w) w.onreadystatechange = c.noop
                    } else if (!E && w && (w.readyState === 4 || q === "timeout")) {
                        E = true;
                        w.onreadystatechange = c.noop;
                        j = q === "timeout" ? "timeout" : !c.httpSuccess(w) ?
                            "error" : e.ifModified && c.httpNotModified(w, e.url) ? "notmodified" : "success";
                        var p;
                        if (j === "success") try {
                            n = c.httpData(w, e.dataType, e)
                        } catch (u) {
                            j = "parsererror";
                            p = u
                        }
                        if (j === "success" || j === "notmodified") i || b();
                        else c.handleError(e, w, j, p);
                        d();
                        q === "timeout" && w.abort();
                        if (e.async) w = null
                    }
                };
                try {
                    var h = w.abort;
                    w.abort = function() {
                        w && h.call(w);
                        g("abort")
                    }
                } catch (k) {}
                e.async && e.timeout > 0 && setTimeout(function() {
                    w && !E && g("timeout")
                }, e.timeout);
                try {
                    w.send(m === "POST" || m === "PUT" || m === "DELETE" ? e.data : null)
                } catch (l) {
                    c.handleError(e,
                        w, null, l);
                    d()
                }
                e.async || g();
                return w
            }
        },
        handleError: function(a, b, d, f) {
            if (a.error) a.error.call(a.context || a, b, d, f);
            if (a.global)(a.context ? c(a.context) : c.event).trigger("ajaxError", [b, a, f])
        },
        active: 0,
        httpSuccess: function(a) {
            try {
                return !a.status && location.protocol === "file:" || a.status >= 200 && a.status < 300 || a.status === 304 || a.status === 1223 || a.status === 0
            } catch (b) {}
            return false
        },
        httpNotModified: function(a, b) {
            var d = a.getResponseHeader("Last-Modified"),
                f = a.getResponseHeader("Etag");
            if (d) c.lastModified[b] = d;
            if (f) c.etag[b] =
                f;
            return a.status === 304 || a.status === 0
        },
        httpData: function(a, b, d) {
            var f = a.getResponseHeader("content-type") || "",
                e = b === "xml" || !b && f.indexOf("xml") >= 0;
            a = e ? a.responseXML : a.responseText;
            e && a.documentElement.nodeName === "parsererror" && c.error("parsererror");
            if (d && d.dataFilter) a = d.dataFilter(a, b);
            if (typeof a === "string")
                if (b === "json" || !b && f.indexOf("json") >= 0) a = c.parseJSON(a);
                else if (b === "script" || !b && f.indexOf("javascript") >= 0) c.globalEval(a);
            return a
        },
        param: function(a, b) {
            function d(j, n) {
                if (c.isArray(n)) c.each(n,
                    function(navo, m) {
                        b ? f(j, m) : d(j + "[" + (typeof m === "object" || c.isArray(m) ? navo : "") + "]", m)
                    });
                else !b && n != null && typeof n === "object" ? c.each(n, function(o, m) {
                    d(j + "[" + navo + "]", m)
                }) : f(j, n)
            }

            function f(j, n) {
                n = c.isFunction(n) ? n() : n;
                e[e.length] = encodeURIComponent(j) + "=" + encodeURIComponent(n)
            }
            var e = [];
            if (b === v) b = c.ajaxSettings.traditional;
            if (c.isArray(a) || a.jquery) c.each(a, function() {
                f(this.name, this.value)
            });
            else
                for (var i in a) d(i, a[i]);
            return e.join("&").replace(vb, "+")
        }
    });
    var ka = {},
        wb = /toggle|show|hide/,
        xb = /^([+-]=)?([\d+-.]+)(.*)$/,
        W, ta = [
            ["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"],
            ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"],
            ["opacity"]
        ];
    c.fn.extend({
        show: function(a, b) {
            if (a || a === 0) return this.animate(K("show", 3), a, b);
            else {
                a = 0;
                for (b = this.length; a < b; a++) {
                    var d = c.data(this[a], "olddisplay");
                    this[a].style.display = d || "";
                    if (c.css(this[a], "display") === "none") {
                        d = this[a].nodeName;
                        var f;
                        if (ka[d]) f = ka[d];
                        else {
                            var e = c("<" + d + " />").appendTo("body");
                            f = e.css("display");
                            if (f === "none") f = "block";
                            e.remove();
                            ka[d] = f
                        }
                        c.data(this[a], "olddisplay", f)
                    }
                }
                a = 0;
                for (b = this.length; a < b; a++) this[a].style.display = c.data(this[a], "olddisplay") || "";
                return this
            }
        },
        hide: function(a, b) {
            if (a || a === 0) return this.animate(K("hide", 3), a, b);
            else {
                a = 0;
                for (b = this.length; a < b; a++) {
                    var d = c.data(this[a], "olddisplay");
                    !d && d !== "none" && c.data(this[a], "olddisplay", c.css(this[a], "display"))
                }
                a = 0;
                for (b = this.length; a < b; a++) this[a].style.display = "none";
                return this
            }
        },
        _toggle: c.fn.toggle,
        toggle: function(a, b) {
            var d = typeof a === "boolean";
            if (c.isFunction(a) &&
                c.isFunction(b)) this._toggle.apply(this, arguments);
            else a == null || d ? this.each(function() {
                var f = d ? a : c(this).is(":hidden");
                c(this)[f ? "show" : "hide"]()
            }) : this.animate(K("toggle", 3), a, b);
            return this
        },
        fadeTo: function(a, b, d) {
            return this.filter(":hidden").css("opacity", 0).show().end().animate({
                opacity: b
            }, a, d)
        },
        animate: function(a, b, d, f) {
            var e = c.speed(b, d, f);
            if (c.isEmptyObject(a)) return this.each(e.complete);
            return this[e.queue === false ? "each" : "queue"](function() {
                var i = c.extend({}, e),
                    j, n = this.nodeType === 1 && c(this).is(":hidden"),
                    navo = this;
                for (j in a) {
                    var m = j.replace(ha, ia);
                    if (j !== m) {
                        a[m] = a[j];
                        delete a[j];
                        j = m
                    }
                    if (a[j] === "hide" && n || a[j] === "show" && !n) return i.complete.call(this);
                    if ((j === "height" || j === "width") && this.style) {
                        i.display = c.css(this, "display");
                        i.overflow = this.style.overflow
                    }
                    if (c.isArray(a[j])) {
                        (i.specialEasing = i.specialEasing || {})[j] = a[j][1];
                        a[j] = a[j][0]
                    }
                }
                if (i.overflow != null) this.style.overflow = "hidden";
                i.curAnim = c.extend({}, a);
                c.each(a, function(s, x) {
                    var A = new c.fx(navo, i, s);
                    if (wb.test(x)) A[x === "toggle" ? n ? "show" : "hide" : x](a);
                    else {
                        var B = xb.exec(x),
                            C = A.cur(true) || 0;
                        if (B) {
                            x = parseFloat(B[2]);
                            var E = B[3] || "px";
                            if (E !== "px") {
                                navo.style[s] = (x || 1) + E;
                                C = (x || 1) / A.cur(true) * C;
                                navo.style[s] = C + E
                            }
                            if (B[1]) x = (B[1] === "-=" ? -1 : 1) * x + C;
                            A.custom(C, x, E)
                        } else A.custom(C, x, "")
                    }
                });
                return true
            })
        },
        stop: function(a, b) {
            var d = c.timers;
            a && this.queue([]);
            this.each(function() {
                for (var f = d.length - 1; f >= 0; f--)
                    if (d[f].elem === this) {
                        b && d[f](true);
                        d.splice(f, 1)
                    }
            });
            b || this.dequeue();
            return this
        }
    });
    c.each({
        slideDown: K("show", 1),
        slideUp: K("hide", 1),
        slideToggle: K("toggle",
            1),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        }
    }, function(a, b) {
        c.fn[a] = function(d, f) {
            return this.animate(b, d, f)
        }
    });
    c.extend({
        speed: function(a, b, d) {
            var f = a && typeof a === "object" ? a : {
                complete: d || !d && b || c.isFunction(a) && a,
                duration: a,
                easing: d && b || b && !c.isFunction(b) && b
            };
            f.duration = c.fx.off ? 0 : typeof f.duration === "number" ? f.duration : c.fx.speeds[f.duration] || c.fx.speeds._default;
            f.old = f.complete;
            f.complete = function() {
                f.queue !== false && c(this).dequeue();
                c.isFunction(f.old) && f.old.call(this)
            };
            return f
        },
        easing: {
            linear: function(a,
                b, d, f) {
                return d + f * a
            },
            swing: function(a, b, d, f) {
                return (-Math.cos(a * Math.PI) / 2 + 0.5) * f + d
            }
        },
        timers: [],
        fx: function(a, b, d) {
            this.options = b;
            this.elem = a;
            this.prop = d;
            if (!b.orig) b.orig = {}
        }
    });
    c.fx.prototype = {
        update: function() {
            this.options.step && this.options.step.call(this.elem, this.now, this);
            (c.fx.step[this.prop] || c.fx.step._default)(this);
            if ((this.prop === "height" || this.prop === "width") && this.elem.style) this.elem.style.display = "block"
        },
        cur: function(a) {
            if (this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] ==
                null)) return this.elem[this.prop];
            return (a = parseFloat(c.css(this.elem, this.prop, a))) && a > -10000 ? a : parseFloat(c.curCSS(this.elem, this.prop)) || 0
        },
        custom: function(a, b, d) {
            function f(i) {
                return e.step(i)
            }
            this.startTime = J();
            this.start = a;
            this.end = b;
            this.unit = d || this.unit || "px";
            this.now = this.start;
            this.pos = this.state = 0;
            var e = this;
            f.elem = this.elem;
            if (f() && c.timers.push(f) && !W) W = setInterval(c.fx.tick, 13)
        },
        show: function() {
            this.options.orig[this.prop] = c.style(this.elem, this.prop);
            this.options.show = true;
            this.custom(this.prop ===
                "width" || this.prop === "height" ? 1 : 0, this.cur());
            c(this.elem).show()
        },
        hide: function() {
            this.options.orig[this.prop] = c.style(this.elem, this.prop);
            this.options.hide = true;
            this.custom(this.cur(), 0)
        },
        step: function(a) {
            var b = J(),
                d = true;
            if (a || b >= this.options.duration + this.startTime) {
                this.now = this.end;
                this.pos = this.state = 1;
                this.update();
                this.options.curAnim[this.prop] = true;
                for (var f in this.options.curAnim)
                    if (this.options.curAnim[f] !== true) d = false;
                if (d) {
                    if (this.options.display != null) {
                        this.elem.style.overflow =
                            this.options.overflow;
                        a = c.data(this.elem, "olddisplay");
                        this.elem.style.display = a ? a : this.options.display;
                        if (c.css(this.elem, "display") === "none") this.elem.style.display = "block"
                    }
                    this.options.hide && c(this.elem).hide();
                    if (this.options.hide || this.options.show)
                        for (var e in this.options.curAnim) c.style(this.elem, e, this.options.orig[e]);
                    this.options.complete.call(this.elem)
                }
                return false
            } else {
                e = b - this.startTime;
                this.state = e / this.options.duration;
                a = this.options.easing || (c.easing.swing ? "swing" : "linear");
                this.pos =
                    c.easing[this.options.specialEasing && this.options.specialEasing[this.prop] || a](this.state, e, 0, 1, this.options.duration);
                this.now = this.start + (this.end - this.start) * this.pos;
                this.update()
            }
            return true
        }
    };
    c.extend(c.fx, {
        tick: function() {
            for (var a = c.timers, b = 0; b < a.length; b++) a[b]() || a.splice(b--, 1);
            a.length || c.fx.stop()
        },
        stop: function() {
            clearInterval(W);
            W = null
        },
        speeds: {
            slow: 600,
            fast: 200,
            _default: 400
        },
        step: {
            opacity: function(a) {
                c.style(a.elem, "opacity", a.now)
            },
            _default: function(a) {
                if (a.elem.style && a.elem.style[a.prop] !=
                    null) a.elem.style[a.prop] = (a.prop === "width" || a.prop === "height" ? Math.max(0, a.now) : a.now) + a.unit;
                else a.elem[a.prop] = a.now
            }
        }
    });
    if (c.expr && c.expr.filters) c.expr.filters.animated = function(a) {
        return c.grep(c.timers, function(b) {
            return a === b.elem
        }).length
    };
    c.fn.offset = "getBoundingClientRect" in r.documentElement ? function(a) {
        var b = this[0];
        if (a) return this.each(function(e) {
            c.offset.setOffset(this, a, e)
        });
        if (!b || !b.ownerDocument) return null;
        if (b === b.ownerDocument.body) return c.offset.bodyOffset(b);
        var d = b.getBoundingClientRect(),
            f = b.ownerDocument;
        b = f.body;
        f = f.documentElement;
        return {
            top: d.top + (self.pageYOffset || c.support.boxModel && f.scrollTop || b.scrollTop) - (f.clientTop || b.clientTop || 0),
            left: d.left + (self.pageXOffset || c.support.boxModel && f.scrollLeft || b.scrollLeft) - (f.clientLeft || b.clientLeft || 0)
        }
    } : function(a) {
        var b = this[0];
        if (a) return this.each(function(s) {
            c.offset.setOffset(this, a, s)
        });
        if (!b || !b.ownerDocument) return null;
        if (b === b.ownerDocument.body) return c.offset.bodyOffset(b);
        c.offset.initialize();
        var d = b.offsetParent,
            f =
            b,
            e = b.ownerDocument,
            i, j = e.documentElement,
            n = e.body;
        f = (e = e.defaultView) ? e.getComputedStyle(b, null) : b.currentStyle;
        for (var navo = b.offsetTop, m = b.offsetLeft;
            (b = b.parentNode) && b !== n && b !== j;) {
            if (c.offset.supportsFixedPosition && f.position === "fixed") break;
            i = e ? e.getComputedStyle(b, null) : b.currentStyle;
            navo -= b.scrollTop;
            m -= b.scrollLeft;
            if (b === d) {
                navo += b.offsetTop;
                m += b.offsetLeft;
                if (c.offset.doesNotAddBorder && !(c.offset.doesAddBorderForTableAndCells && /^t(able|d|h)$/i.test(b.nodeName))) {
                    navo += parseFloat(i.borderTopWidth) ||
                        0;
                    m += parseFloat(i.borderLeftWidth) || 0
                }
                f = d;
                d = b.offsetParent
            }
            if (c.offset.subtractsBorderForOverflowNotVisible && i.overflow !== "visible") {
                navo += parseFloat(i.borderTopWidth) || 0;
                m += parseFloat(i.borderLeftWidth) || 0
            }
            f = i
        }
        if (f.position === "relative" || f.position === "static") {
            navo += n.offsetTop;
            m += n.offsetLeft
        }
        if (c.offset.supportsFixedPosition && f.position === "fixed") {
            navo += Math.max(j.scrollTop, n.scrollTop);
            m += Math.max(j.scrollLeft, n.scrollLeft)
        }
        return {
            top: navo,
            left: m
        }
    };
    c.offset = {
        initialize: function() {
            var a = r.body,
                b = r.createElement("div"),
                d, f, e, i = parseFloat(c.curCSS(a, "marginTop", true)) || 0;
            c.extend(b.style, {
                position: "absolute",
                top: 0,
                left: 0,
                margin: 0,
                border: 0,
                width: "1px",
                height: "1px",
                visibility: "hidden"
            });
            b.innerHTML = "<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";
            a.insertBefore(b, a.firstChild);
            d = b.firstChild;
            f = d.firstChild;
            e = d.nextSibling.firstChild.firstChild;
            this.doesNotAddBorder = f.offsetTop !== 5;
            this.doesAddBorderForTableAndCells = e.offsetTop === 5;
            f.style.position = "fixed";
            f.style.top = "20px";
            this.supportsFixedPosition = f.offsetTop === 20 || f.offsetTop === 15;
            f.style.position = f.style.top = "";
            d.style.overflow = "hidden";
            d.style.position = "relative";
            this.subtractsBorderForOverflowNotVisible = f.offsetTop === -5;
            this.doesNotIncludeMarginInBodyOffset = a.offsetTop !== i;
            a.removeChild(b);
            c.offset.initialize = c.noop
        },
        bodyOffset: function(a) {
            var b = a.offsetTop,
                d = a.offsetLeft;
            c.offset.initialize();
            if (c.offset.doesNotIncludeMarginInBodyOffset) {
                b += parseFloat(c.curCSS(a, "marginTop", true)) || 0;
                d += parseFloat(c.curCSS(a, "marginLeft", true)) || 0
            }
            return {
                top: b,
                left: d
            }
        },
        setOffset: function(a, b, d) {
            if (/static/.test(c.curCSS(a, "position"))) a.style.position = "relative";
            var f = c(a),
                e = f.offset(),
                i = parseInt(c.curCSS(a, "top", true), 10) || 0,
                j = parseInt(c.curCSS(a, "left", true), 10) || 0;
            if (c.isFunction(b)) b = b.call(a, d, e);
            d = {
                top: b.top - e.top + i,
                left: b.left -
                    e.left + j
            };
            "using" in b ? b.using.call(a, d) : f.css(d)
        }
    };
    c.fn.extend({
        position: function() {
            if (!this[0]) return null;
            var a = this[0],
                b = this.offsetParent(),
                d = this.offset(),
                f = /^body|html$/i.test(b[0].nodeName) ? {
                    top: 0,
                    left: 0
                } : b.offset();
            d.top -= parseFloat(c.curCSS(a, "marginTop", true)) || 0;
            d.left -= parseFloat(c.curCSS(a, "marginLeft", true)) || 0;
            f.top += parseFloat(c.curCSS(b[0], "borderTopWidth", true)) || 0;
            f.left += parseFloat(c.curCSS(b[0], "borderLeftWidth", true)) || 0;
            return {
                top: d.top - f.top,
                left: d.left - f.left
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var a =
                    this.offsetParent || r.body; a && !/^body|html$/i.test(a.nodeName) && c.css(a, "position") === "static";) a = a.offsetParent;
                return a
            })
        }
    });
    c.each(["Left", "Top"], function(a, b) {
        var d = "scroll" + b;
        c.fn[d] = function(f) {
            var e = this[0],
                i;
            if (!e) return null;
            if (f !== v) return this.each(function() {
                if (i = ua(this)) i.scrollTo(!a ? f : c(i).scrollLeft(), a ? f : c(i).scrollTop());
                else this[d] = f
            });
            else return (i = ua(e)) ? "pageXOffset" in i ? i[a ? "pageYOffset" : "pageXOffset"] : c.support.boxModel && i.document.documentElement[d] || i.document.body[d] : e[d]
        }
    });
    c.each(["Height", "Width"], function(a, b) {
        var d = b.toLowerCase();
        c.fn["inner" + b] = function() {
            return this[0] ? c.css(this[0], d, false, "padding") : null
        };
        c.fn["outer" + b] = function(f) {
            return this[0] ? c.css(this[0], d, false, f ? "margin" : "border") : null
        };
        c.fn[d] = function(f) {
            var e = this[0];
            if (!e) return f == null ? null : this;
            if (c.isFunction(f)) return this.each(function(i) {
                var j = c(this);
                j[d](f.call(this, i, j[d]()))
            });
            return "scrollTo" in e && e.document ? e.document.compatMode === "CSS1Compat" && e.document.documentElement["client" + b] ||
                e.document.body["client" + b] : e.nodeType === 9 ? Math.max(e.documentElement["client" + b], e.body["scroll" + b], e.documentElement["scroll" + b], e.body["offset" + b], e.documentElement["offset" + b]) : f === v ? c.css(e, d) : this.css(d, typeof f === "string" ? f : f + "px")
        }
    });
    z.jQuery = z.$ = c
})(window);


/**
 * jQuery.timers - Timer abstractions for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/02/08
 *
 * @author Blair Mitchelmore
 * @version 1.1.2
 *
 **/

jQuery.fn.extend({
    everyTime: function(interval, label, fn, times, belay) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, times, belay);
        });
    },
    oneTime: function(interval, label, fn) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, 1);
        });
    },
    stopTime: function(label, fn) {
        return this.each(function() {
            jQuery.timer.remove(this, label, fn);
        });
    }
});

jQuery.event.special

jQuery.extend({
    timer: {
        global: [],
        guid: 1,
        dataKey: "jQuery.timer",
        regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
        powers: {
            // Yeah this is major overkill...
            'ms': 1,
            'cs': 10,
            'ds': 100,
            's': 1000,
            'das': 10000,
            'hs': 100000,
            'ks': 1000000
        },
        timeParse: function(value) {
            if (value == undefined || value == null)
                return null;
            var result = this.regex.exec(jQuery.trim(value.toString()));
            if (result[2]) {
                var num = parseFloat(result[1]);
                var mult = this.powers[result[2]] || 1;
                return num * mult;
            } else {
                return value;
            }
        },
        add: function(element, interval, label, fn, times, belay) {
            var counter = 0;

            if (jQuery.isFunction(label)) {
                if (!times)
                    times = fn;
                fn = label;
                label = interval;
            }

            interval = jQuery.timer.timeParse(interval);

            if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
                return;

            if (times && times.constructor != Number) {
                belay = !!times;
                times = 0;
            }

            times = times || 0;
            belay = belay || false;

            var timers = jQuery.data(element, this.dataKey) || jQuery.data(element, this.dataKey, {});

            if (!timers[label])
                timers[label] = {};

            fn.timerID = fn.timerID || this.guid++;

            var handler = function() {
                if (belay && this.inProgress)
                    return;
                this.inProgress = true;
                if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
                    jQuery.timer.remove(element, label, fn);
                this.inProgress = false;
            };

            handler.timerID = fn.timerID;

            if (!timers[label][fn.timerID])
                timers[label][fn.timerID] = window.setInterval(handler, interval);

            this.global.push(element);

        },
        remove: function(element, label, fn) {
            var timers = jQuery.data(element, this.dataKey),
                ret;

            if (timers) {

                if (!label) {
                    for (label in timers)
                        this.remove(element, label, fn);
                } else if (timers[label]) {
                    if (fn) {
                        if (fn.timerID) {
                            window.clearInterval(timers[label][fn.timerID]);
                            delete timers[label][fn.timerID];
                        }
                    } else {
                        for (var fn in timers[label]) {
                            window.clearInterval(timers[label][fn]);
                            delete timers[label][fn];
                        }
                    }

                    for (ret in timers[label]) break;
                    if (!ret) {
                        ret = null;
                        delete timers[label];
                    }
                }

                for (ret in timers) break;
                if (!ret)
                    jQuery.removeData(element, this.dataKey);
            }
        }
    }
});


/*!
 * SuperSlide v2.1.1
 * 轻松解决网站大部分特效展示问题
 * 详尽信息请看官网：http://www.SuperSlide2.com/
 *
 * Copyright 2011-2013, 大话主席
 *
 * 请尊重原创，保留头部版权
 * 在保留版权的前提下可应用于个人或商业用途
 
 * v2.1.1：修复当调用多个SuperSlide，并设置returnDefault:true 时返回defaultIndex索引错误
 
 */

! function(a) {
    a.fn.slide = function(b) {
        return a.fn.slide.defaults = {
            type: "slide",
            effect: "fade",
            autoPlay: !1,
            delayTime: 500,
            interTime: 2500,
            triggerTime: 150,
            defaultIndex: 0,
            titCell: ".hd li",
            mainCell: ".bd",
            targetCell: null,
            trigger: "mouseover",
            scroll: 1,
            vis: 1,
            titOnClassName: "on",
            autoPage: !1,
            prevCell: ".prev",
            nextCell: ".next",
            pageStateCell: ".pageState",
            opp: !1,
            pnLoop: !0,
            easing: "swing",
            startFun: null,
            endFun: null,
            switchLoad: null,
            playStateCell: ".playState",
            mouseOverStop: !0,
            defaultPlay: !0,
            returnDefault: !1
        }, this.each(function() {
            var c = a.extend({}, a.fn.slide.defaults, b),
                d = a(this),
                e = c.effect,
                f = a(c.prevCell, d),
                g = a(c.nextCell, d),
                h = a(c.pageStateCell, d),
                i = a(c.playStateCell, d),
                j = a(c.titCell, d),
                k = j.size(),
                l = a(c.mainCell, d),
                m = l.children().size(),
                n = c.switchLoad,
                navo = a(c.targetCell, d),
                p = parseInt(c.defaultIndex),
                q = parseInt(c.delayTime),
                r = parseInt(c.interTime);
            parseInt(c.triggerTime);
            var Q, t = parseInt(c.scroll),
                u = parseInt(c.vis),
                v = "false" == c.autoPlay || 0 == c.autoPlay ? !1 : !0,
                w = "false" == c.opp || 0 == c.opp ? !1 : !0,
                x = "false" == c.autoPage || 0 == c.autoPage ? !1 : !0,
                y = "false" == c.pnLoop || 0 == c.pnLoop ? !1 : !0,
                z = "false" == c.mouseOverStop || 0 == c.mouseOverStop ? !1 : !0,
                A = "false" == c.defaultPlay || 0 == c.defaultPlay ? !1 : !0,
                B = "false" == c.returnDefault || 0 == c.returnDefault ? !1 : !0,
                C = 0,
                D = 0,
                E = 0,
                F = 0,
                G = c.easing,
                H = null,
                I = null,
                J = null,
                K = c.titOnClassName,
                L = j.index(d.find("." + K)),
                M = p = -1 == L ? p : L,
                N = p,
                O = p,
                P = m >= u ? 0 != m % t ? m % t : t : 0,
                R = "leftMarquee" == e || "topMarquee" == e ? !0 : !1,
                S = function() {
                    a.isFunction(c.startFun) && c.startFun(p, k, d, a(c.titCell, d), l, navo, f, g)
                },
                T = function() {
                    a.isFunction(c.endFun) && c.endFun(p, k, d, a(c.titCell, d), l, navo, f, g)
                },
                U = function() {
                    j.removeClass(K), A && j.eq(N).addClass(K)
                };
            if ("menu" == c.type) return A && j.removeClass(K).eq(p).addClass(K), j.hover(function() {
                Q = a(this).find(c.targetCell);
                var b = j.index(a(this));
                I = setTimeout(function() {
                    switch (p = b, j.removeClass(K).eq(p).addClass(K), S(), e) {
                        case "fade":
                            Q.stop(!0, !0).animate({
                                opacity: "show"
                            }, q, G, T);
                            break;
                        case "slideDown":
                            Q.stop(!0, !0).animate({
                                height: "show"
                            }, q, G, T)
                    }
                }, c.triggerTime)
            }, function() {
                switch (clearTimeout(I), e) {
                    case "fade":
                        Q.animate({
                            opacity: "hide"
                        }, q, G);
                        break;
                    case "slideDown":
                        Q.animate({
                            height: "hide"
                        }, q, G)
                }
            }), B && d.hover(function() {
                clearTimeout(J)
            }, function() {
                J = setTimeout(U, q)
            }), void 0;
            if (0 == k && (k = m), R && (k = 2), x) {
                if (m >= u)
                    if ("leftLoop" == e || "topLoop" == e) k = 0 != m % t ? (0 ^ m / t) + 1 : m / t;
                    else {
                        var V = m - u;
                        k = 1 + parseInt(0 != V % t ? V / t + 1 : V / t), 0 >= k && (k = 1)
                    } else k = 1;
                j.html("");
                var W = "";
                if (1 == c.autoPage || "true" == c.autoPage)
                    for (var X = 0; k > X; X++) W += "<li>" + (X + 1) + "</li>";
                else
                    for (var X = 0; k > X; X++) W += c.autoPage.replace("$", X + 1);
                j.html(W);
                var j = j.children()
            }
            if (m >= u) {
                l.children().each(function() {
                    a(this).width() > E && (E = a(this).width(), D = a(this).outerWidth(!0)), a(this).height() > F && (F = a(this).height(), C = a(this).outerHeight(!0))
                });
                var Y = l.children(),
                    Z = function() {
                        for (var a = 0; u > a; a++) Y.eq(a).clone().addClass("clone").appendTo(l);
                        for (var a = 0; P > a; a++) Y.eq(m - a - 1).clone().addClass("clone").prependTo(l)
                    };
                switch (e) {
                    case "fold":
                        l.css({
                            position: "relative",
                            width: D,
                            height: C
                        }).children().css({
                            position: "absolute",
                            width: E,
                            left: 0,
                            top: 0,
                            display: "none"
                        });
                        break;
                    case "top":
                        l.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:' + u * C + 'px"></div>').css({
                            top: -(p * t) * C,
                            position: "relative",
                            padding: "0",
                            margin: "0"
                        }).children().css({
                            height: F
                        });
                        break;
                    case "left":
                        l.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:' + u * D + 'px"></div>').css({
                            width: m * D,
                            left: -(p * t) * D,
                            position: "relative",
                            overflow: "hidden",
                            padding: "0",
                            margin: "0"
                        }).children().css({
                            "float": "left",
                            width: E
                        });
                        break;
                    case "leftLoop":
                    case "leftMarquee":
                        Z(), l.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:' + u * D + 'px"></div>').css({
                            width: (m + u + P) * D,
                            position: "relative",
                            overflow: "hidden",
                            padding: "0",
                            margin: "0",
                            left: -(P + p * t) * D
                        }).children().css({
                            "float": "left",
                            width: E
                        });
                        break;
                    case "topLoop":
                    case "topMarquee":
                        Z(), l.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:' + u * C + 'px"></div>').css({
                            height: (m + u + P) * C,
                            position: "relative",
                            padding: "0",
                            margin: "0",
                            top: -(P + p * t) * C
                        }).children().css({
                            height: F
                        })
                }
            }
            var $ = function(a) {
                    var b = a * t;
                    return a == k ? b = m : -1 == a && 0 != m % t && (b = -m % t), b
                },
                _ = function(b) {
                    var c = function(c) {
                        for (var d = c; u + c > d; d++) b.eq(d).find("img[" + n + "]").each(function() {
                            var b = a(this);
                            if (b.attr("src", b.attr(n)).removeAttr(n), l.find(".clone")[0])
                                for (var c = l.children(), d = 0; d < c.size(); d++) c.eq(d).find("img[" + n + "]").each(function() {
                                    a(this).attr(n) == b.attr("src") && a(this).attr("src", a(this).attr(n)).removeAttr(n)
                                })
                        })
                    };
                    switch (e) {
                        case "fade":
                        case "fold":
                        case "top":
                        case "left":
                        case "slideDown":
                            c(p * t);
                            break;
                        case "leftLoop":
                        case "topLoop":
                            c(P + $(O));
                            break;
                        case "leftMarquee":
                        case "topMarquee":
                            var d = "leftMarquee" == e ? l.css("left").replace("px", "") : l.css("top").replace("px", ""),
                                f = "leftMarquee" == e ? D : C,
                                g = P;
                            if (0 != d % f) {
                                var h = Math.abs(0 ^ d / f);
                                g = 1 == p ? P + h : P + h - 1
                            }
                            c(g)
                    }
                },
                ab = function(a) {
                    if (!A || M != p || a || R) {
                        if (R ? p >= 1 ? p = 1 : 0 >= p && (p = 0) : (O = p, p >= k ? p = 0 : 0 > p && (p = k - 1)), S(), null != n && _(l.children()), o[0] && (Q = navo.eq(p), null != n && _(navo), "slideDown" == e ? (navo.not(Q).stop(!0, !0).slideUp(q), Q.slideDown(q, G, function() {
                            l[0] || T()
                        })) : (navo.not(Q).stop(!0, !0).hide(), Q.animate({
                            opacity: "show"
                        }, q, function() {
                            l[0] || T()
                        }))), m >= u) switch (e) {
                            case "fade":
                                l.children().stop(!0, !0).eq(p).animate({
                                    opacity: "show"
                                }, q, G, function() {
                                    T()
                                }).siblings().hide();
                                break;
                            case "fold":
                                l.children().stop(!0, !0).eq(p).animate({
                                    opacity: "show"
                                }, q, G, function() {
                                    T()
                                }).siblings().animate({
                                    opacity: "hide"
                                }, q, G);
                                break;
                            case "top":
                                l.stop(!0, !1).animate({
                                    top: -p * t * C
                                }, q, G, function() {
                                    T()
                                });
                                break;
                            case "left":
                                l.stop(!0, !1).animate({
                                    left: -p * t * D
                                }, q, G, function() {
                                    T()
                                });
                                break;
                            case "leftLoop":
                                var b = O;
                                l.stop(!0, !0).animate({
                                    left: -($(O) + P) * D
                                }, q, G, function() {
                                    -1 >= b ? l.css("left", -(P + (k - 1) * t) * D) : b >= k && l.css("left", -P * D), T()
                                });
                                break;
                            case "topLoop":
                                var b = O;
                                l.stop(!0, !0).animate({
                                    top: -($(O) + P) * C
                                }, q, G, function() {
                                    -1 >= b ? l.css("top", -(P + (k - 1) * t) * C) : b >= k && l.css("top", -P * C), T()
                                });
                                break;
                            case "leftMarquee":
                                var c = l.css("left").replace("px", "");
                                0 == p ? l.animate({
                                    left: ++c
                                }, 0, function() {
                                    l.css("left").replace("px", "") >= 0 && l.css("left", -m * D)
                                }) : l.animate({
                                    left: --c
                                }, 0, function() {
                                    l.css("left").replace("px", "") <= -(m + P) * D && l.css("left", -P * D)
                                });
                                break;
                            case "topMarquee":
                                var d = l.css("top").replace("px", "");
                                0 == p ? l.animate({
                                    top: ++d
                                }, 0, function() {
                                    l.css("top").replace("px", "") >= 0 && l.css("top", -m * C)
                                }) : l.animate({
                                    top: --d
                                }, 0, function() {
                                    l.css("top").replace("px", "") <= -(m + P) * C && l.css("top", -P * C)
                                })
                        }
                        j.removeClass(K).eq(p).addClass(K), M = p, y || (g.removeClass("nextStop"), f.removeClass("prevStop"), 0 == p && f.addClass("prevStop"), p == k - 1 && g.addClass("nextStop")), h.html("<span>" + (p + 1) + "</span>/" + k)
                    }
                };
            A && ab(!0), B && d.hover(function() {
                clearTimeout(J)
            }, function() {
                J = setTimeout(function() {
                    p = N, A ? ab() : "slideDown" == e ? Q.slideUp(q, U) : Q.animate({
                        opacity: "hide"
                    }, q, U), M = p
                }, 300)
            });
            var bb = function(a) {
                    H = setInterval(function() {
                        w ? p-- : p++, ab()
                    }, a ? a : r)
                },
                cb = function(a) {
                    H = setInterval(ab, a ? a : r)
                },
                db = function() {
                    z || (clearInterval(H), bb())
                },
                eb = function() {
                    (y || p != k - 1) && (p++, ab(), R || db())
                },
                fb = function() {
                    (y || 0 != p) && (p--, ab(), R || db())
                },
                gb = function() {
                    clearInterval(H), R ? cb() : bb(), i.removeClass("pauseState")
                },
                hb = function() {
                    clearInterval(H), i.addClass("pauseState")
                };
            if (v ? R ? (w ? p-- : p++, cb(), z && l.hover(hb, gb)) : (bb(), z && d.hover(hb, gb)) : (R && (w ? p-- : p++), i.addClass("pauseState")), i.click(function() {
                i.hasClass("pauseState") ? gb() : hb()
            }), "mouseover" == c.trigger ? j.hover(function() {
                var a = j.index(this);
                I = setTimeout(function() {
                    p = a, ab(), db()
                }, c.triggerTime)
            }, function() {
                clearTimeout(I)
            }) : j.click(function() {
                p = j.index(this), ab(), db()
            }), R) {
                if (g.mousedown(eb), f.mousedown(fb), y) {
                    var ib, jb = function() {
                            ib = setTimeout(function() {
                                clearInterval(H), cb(0 ^ r / 10)
                            }, 150)
                        },
                        kb = function() {
                            clearTimeout(ib), clearInterval(H), cb()
                        };
                    g.mousedown(jb), g.mouseup(kb), f.mousedown(jb), f.mouseup(kb)
                }
                "mouseover" == c.trigger && (g.hover(eb, function() {}), f.hover(fb, function() {}))
            } else g.click(eb), f.click(fb)
        })
    }
}(jQuery), jQuery.easing.jswing = jQuery.easing.swing, jQuery.extend(jQuery.easing, {
    def: "easeOutQuad",
    swing: function(a, b, c, d, e) {
        return jQuery.easing[jQuery.easing.def](a, b, c, d, e)
    },
    easeInQuad: function(a, b, c, d, e) {
        return d * (b /= e) * b + c
    },
    easeOutQuad: function(a, b, c, d, e) {
        return -d * (b /= e) * (b - 2) + c
    },
    easeInOutQuad: function(a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b + c : -d / 2 * (--b * (b - 2) - 1) + c
    },
    easeInCubic: function(a, b, c, d, e) {
        return d * (b /= e) * b * b + c
    },
    easeOutCubic: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b + 1) + c
    },
    easeInOutCubic: function(a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b + c : d / 2 * ((b -= 2) * b * b + 2) + c
    },
    easeInQuart: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b + c
    },
    easeOutQuart: function(a, b, c, d, e) {
        return -d * ((b = b / e - 1) * b * b * b - 1) + c
    },
    easeInOutQuart: function(a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b * b + c : -d / 2 * ((b -= 2) * b * b * b - 2) + c
    },
    easeInQuint: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b * b + c
    },
    easeOutQuint: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b * b * b + 1) + c
    },
    easeInOutQuint: function(a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b * b * b + c : d / 2 * ((b -= 2) * b * b * b * b + 2) + c
    },
    easeInSine: function(a, b, c, d, e) {
        return -d * Math.cos(b / e * (Math.PI / 2)) + d + c
    },
    easeOutSine: function(a, b, c, d, e) {
        return d * Math.sin(b / e * (Math.PI / 2)) + c
    },
    easeInOutSine: function(a, b, c, d, e) {
        return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c
    },
    easeInExpo: function(a, b, c, d, e) {
        return 0 == b ? c : d * Math.pow(2, 10 * (b / e - 1)) + c
    },
    easeOutExpo: function(a, b, c, d, e) {
        return b == e ? c + d : d * (-Math.pow(2, -10 * b / e) + 1) + c
    },
    easeInOutExpo: function(a, b, c, d, e) {
        return 0 == b ? c : b == e ? c + d : (b /= e / 2) < 1 ? d / 2 * Math.pow(2, 10 * (b - 1)) + c : d / 2 * (-Math.pow(2, -10 * --b) + 2) + c
    },
    easeInCirc: function(a, b, c, d, e) {
        return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c
    },
    easeOutCirc: function(a, b, c, d, e) {
        return d * Math.sqrt(1 - (b = b / e - 1) * b) + c
    },
    easeInOutCirc: function(a, b, c, d, e) {
        return (b /= e / 2) < 1 ? -d / 2 * (Math.sqrt(1 - b * b) - 1) + c : d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c
    },
    easeInElastic: function(a, b, c, d, e) {
        var f = 1.70158,
            g = 0,
            h = d;
        if (0 == b) return c;
        if (1 == (b /= e)) return c + d;
        if (g || (g = .3 * e), h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return -(h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g)) + c
    },
    easeOutElastic: function(a, b, c, d, e) {
        var f = 1.70158,
            g = 0,
            h = d;
        if (0 == b) return c;
        if (1 == (b /= e)) return c + d;
        if (g || (g = .3 * e), h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return h * Math.pow(2, -10 * b) * Math.sin((b * e - f) * 2 * Math.PI / g) + d + c
    },
    easeInOutElastic: function(a, b, c, d, e) {
        var f = 1.70158,
            g = 0,
            h = d;
        if (0 == b) return c;
        if (2 == (b /= e / 2)) return c + d;
        if (g || (g = e * .3 * 1.5), h < Math.abs(d)) {
            h = d;
            var f = g / 4
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return 1 > b ? -.5 * h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g) + c : .5 * h * Math.pow(2, -10 * (b -= 1)) * Math.sin((b * e - f) * 2 * Math.PI / g) + d + c
    },
    easeInBack: function(a, b, c, d, e, f) {
        return void 0 == f && (f = 1.70158), d * (b /= e) * b * ((f + 1) * b - f) + c
    },
    easeOutBack: function(a, b, c, d, e, f) {
        return void 0 == f && (f = 1.70158), d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c
    },
    easeInOutBack: function(a, b, c, d, e, f) {
        return void 0 == f && (f = 1.70158), (b /= e / 2) < 1 ? d / 2 * b * b * (((f *= 1.525) + 1) * b - f) + c : d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c
    },
    easeInBounce: function(a, b, c, d, e) {
        return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c
    },
    easeOutBounce: function(a, b, c, d, e) {
        return (b /= e) < 1 / 2.75 ? d * 7.5625 * b * b + c : 2 / 2.75 > b ? d * (7.5625 * (b -= 1.5 / 2.75) * b + .75) + c : 2.5 / 2.75 > b ? d * (7.5625 * (b -= 2.25 / 2.75) * b + .9375) + c : d * (7.5625 * (b -= 2.625 / 2.75) * b + .984375) + c
    },
    easeInOutBounce: function(a, b, c, d, e) {
        return e / 2 > b ? .5 * jQuery.easing.easeInBounce(a, 2 * b, 0, d, e) + c : .5 * jQuery.easing.easeOutBounce(a, 2 * b - e, 0, d, e) + .5 * d + c
    }
});

eval(function(p, a, c, k, e, d) {
    e = function(c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) {
            d[e(c)] = k[c] || e(c)
        }
        k = [
            function(e) {
                return d[e]
            }
        ];
        e = function() {
            return '\\w+'
        };
        c = 1
    };
    while (c--) {
        if (k[c]) {
            p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
        }
    }
    return p
}('(8($){$.13.43=8(7){3 x={1P:0,2d:"11",R:P};7=$.1g({},x,7);3 navo=$(b);3 c=$(navo).4("c");$(navo).1u();9(7.R){$(navo).3s();7.1P=$(navo).3m().4("1P");$(navo).3m().4i()}3 Z=$("<1m 1P=\'"+7.1P+"\'></1m>");$(Z).4("1p",$(navo).4("1p"));$(Z).4("1r",$(navo).4("1r"));$(Z).f({"1f":"2s-1T"});3 1W=$("<2c></2c>");$(Z).2k(1W);$(1W).4("1p","2y-3P-3y");3 2a=$(navo).k("3D:3y");$(1W).v("<t>"+2a.v()+"</t><i></i>");$(1W).4("1h",2a.4("1h"));3 1d=$("<1b></1b>");$(Z).2k(1d);$(navo).k("3D").2x(8(4e,2e){3 1B=$("<a 4d=\'4h:4c(0);\'></a>");$(1B).f({"1f":"1T"});$(1B).4("1h",$(2e).4("1h"));$(1B).v($(2e).v());9(2a.4("1h")==$(2e).4("1h"))1B.y("36");$(1d).2k(1B)});$(navo).3q(Z);$(1d).f({"15":"1R","z-4a":50});$(1d).y("2y-3P-49");3 n=$(Z).15().n+$(Z).c();3 m=$(Z).15().m;$(1d).f("m",m);$(1d).f("n",n);9(c&&$(1d).c()>3R(c)){$(1d).f("c",3R(c))}$(1d).1u();9(7.R)$(navo).1u();9(7.2d=="11"){$(Z).C("11",8(){3 n=$(b).15().n+$(b).c();3 m=$(b).15().m;$(b).k("1b").f("m",m);$(b).k("1b").f("n",n);$(b).k("1b").3S("34");$(b).y("37")})}E{$(Z).1F(8(){$(b).3V(35,8(){3 n=$(b).15().n+$(b).c();3 m=$(b).15().m;$(b).k("1b").f("m",m);$(b).k("1b").f("n",n);$(b).k("1b").3S("34");$(b).y("37")})},8(){$(b).3T();$(b).k("1b").4b("34");$(b).d("37")})}$(Z).k("1b a").C("11",8(){3 1m=$(b).V().V();3 t=$(b);$(1m).k("2c").v("<t>"+$(t).v()+"</t><i></i>");$(1m).k("2c").4("1h",$(t).4("1h"));$(1m).2g().N($(t).4("1h"));$(1m).2g().1z("42");$(1m).k("1b a").d("36");$(b).y("36")})},$.13.41=8(){3 Q=$(b);9(Q.f("1f")=="2j")1i;$(Q).1u();3 navo=$("<2i><2i><t></t></2i></2i>");$(Q).3q(navo);$(navo).4("1p",$(Q).4("1p"));$(navo).y($(Q).4("s"));$(navo).4("s",$(Q).4("s"));$(navo).k("t").v($(Q).v());$(navo).C("11",8(){9(Q.4("1a")=="3H"){3 V=Q.V();2D{4g(V.4j(0).3O.4l()!="3Z"){V=V.V()}V.3H()}2F(e){$(Q).11()}}E{9(Q.V()[0].3O=="A"){}E $(Q).11()}});$(navo).C("3l",8(){$(navo).d($(navo).4("s")+"U");$(navo).d($(navo).4("s")+"1X");$(navo).d($(navo).4("s"));$(navo).y($(navo).4("s")+"U")});$(navo).C("3f",8(){$(navo).d($(navo).4("s")+"U");$(navo).d($(navo).4("s")+"1X");$(navo).d($(navo).4("s"));$(navo).y($(navo).4("s"))});$(navo).C("44",8(){$(navo).d($(navo).4("s")+"U");$(navo).d($(navo).4("s")+"1X");$(navo).d($(navo).4("s"));$(navo).y($(navo).4("s")+"1X")});$(navo).C("3Y",8(){$(navo).d($(navo).4("s")+"U");$(navo).d($(navo).4("s")+"1X");$(navo).d($(navo).4("s"));$(navo).y($(navo).4("s")+"U")})},$.13.3U=8(7){3 x={R:P};7=$.1g({},x,7);3 l=$(b);9(7.R){3 B=$(l).2g();9($.2q($(l).N())!=""){$(B).f("1f","2j")}1i}$(l).C("2v",8(){$(l).d("1F");$(l).d("29");$(l).y("1F")});$(l).C("3r",8(){$(l).d("1F");$(l).d("29");$(l).y("29")});9($(l).4("B")==""||!$(l).4("B"))1i;9(\'28\'3X 3W.40(\'L\')){$(l).4("28",$(l).4("B"))}E{3 B=$(l).2g();9($(B).4("s")!="B"&&$(l).4("B")){B=$("<t 2o=\'15:1R; 4f:#45;\' 1p=\'4k-2y-B\' s=\'B\'>"+$(l).4("B")+"</t>");$(B).f({"2B-1K":$(l).f("2B-1K"),"3M-c":$(l).f("3M-c"),"1n-m":$(l).f("1n-m"),"1n-3F":$(l).f("1n-3F"),"1n-n":$(l).f("1n-n"),"1n-3G":$(l).f("1n-3G")});$(B).f("m",0);$(B).f("n",0);3 47=$(l).3c("<i 2o=\'2B-2o:29; 1f:1T;\'></i>");$(l).V().f("15","46");$(l).48(B)}9($.2q($(l).N())!=""){$(B).f("1f","2j")}$(B).11(8(){$(l).2v()});$(l).2v(8(){$(B).f("1f","2j")});$(l).3r(8(){9($.2q($(l).N())=="")$(B).3s()})}},$.13.4u=8(7){3 x={R:P};7=$.1g({},x,7);3 h=$(b);3 navo=$(h).k("L[1a=\'2p\']");$(navo).1u();3 j=$(navo).4("j");3 g=$(h).4("s");$(h).y(g);$(h).4("1r",$(navo).4("1r"));$(h).f({"1f":"2s-1T"});$(h).4("j",j?W:P);9(j){$(h).d(g);$(h).d(g+"U");$(h).d(g+"19");$(h).y(g+"19")}E{$(h).d(g);$(h).d(g+"U");$(h).d(g+"19");$(h).y(g)}9(7.R)1i;$(navo).C("11",8(){1i P});$(h).1F(8(){3 X=$(b).k("L[1a=\'2p\']");3 j=$(X).4("j");3 g=$(h).4("s");9(!j)$(b).y(g+"U")},8(){$(b).d(g+"U")});$(h).C("11",8(){3 r=$(b);3 X=$(r).k("L[1a=\'2p\']");3 j=$(X).4("j");3 g=$(h).4("s");j=j?P:W;$(X).4("j",j);$(r).4("j",j);$(r).d(g+"U");9(j){$(X).1z("59");$(r).d(g);$(r).d(g+"19");$(r).y(g+"19")}E{$(X).1z("5c");$(r).d(g);$(r).d(g+"19");$(r).y(g)}})},$.13.3B=8(7){3 x={R:P};7=$.1g({},x,7);3 h=$(b);3 o=$(h).k("L[1a=\'27\']");$(navo).1u();3 j=$(navo).4("j");3 g=$(h).4("s");$(h).y(g);$(h).4("1r",$(navo).4("1r"));$(h).f({"1f":"2s-1T"});$(h).4("j",j?W:P);9(j){$(h).d(g);$(h).d(g+"19");$(h).y(g+"19")}E{$(h).d(g);$(h).d(g+"19");$(h).y(g)}9(7.R)1i;$(navo).C("11",8(){1i P});$(h).1F(8(){3 X=$(b).k("L[1a=\'27\']");3 j=$(X).4("j");3 g=$(h).4("s");9(!j)$(b).y(g+"U")},8(){$(b).d(g+"U")});$(h).C("11",8(){3 r=$(b);3 X=$(r).k("L[1a=\'27\']");3 j=$(X).4("j");3 g=$(h).4("s");3 3C=j;j=W;$(X).4("j",j);$(r).4("j",j);$(r).d(g+"U");$("L[1r=\'"+r.4("1r")+"\'][1a=\'27\']").V().2x(8(i,3v){$(3v).3B({R:W})});9(!3C){$(X).1z("j");$(r).d(g);$(r).d(g+"19");$(r).y(g+"19")}})},$.13.5d=8(7){3 x={R:P,1e:5};7=$.1g({},x,7);3 1y=$(b);$(1y).1u();3 2u=$(1y).4("2u");3 N=$(1y).N();9(2E(N))N=0;9(N<0)N=0;9(N>7.1e)N=7.1e;9(!7.R)$(1y).3c("<t><t></t></t>");3 G=$(1y).V().V();G.4("1p",$(1y).4("1p"));$(G).k("t").f("I",(56(N)/7.1e*35)+"%");9(!7.R&&!2u){3 3e=$(G).I();3 2m=3e/7.1e;$(G).C("53 3l",8(2d){3 2r=2d.2r;3 m=$(G).3u().m;3 3k=2r-m;3 1L=K.52(3k/2m);3 21=(1L*2m)+"3E";$(G).k("L").4("1L",1L);$(G).k("t").f("I",21);$(G).k("L").1z("3x")});$(G).C("3f",8(){3 20=$(G).k("t").k("L").N();3 21=(20*2m)+"3E";$(G).k("t").f("I",21);$(G).k("L").4("1L",20);$(G).k("L").1z("3x")});$(G).C("11",8(){3 20=$(G).k("L").4("1L");$(G).k("t").k("L").N(20);$(G).k("L").1z("4Z")})}},$.13.4X=8(7){3 x={2A:4Y,3K:W,2n:u,25:u,26:u,22:u};7=$.1g({},x,7);3 Q=$(b);3 Y=12 4U.51({4W:Q[0],2A:7.2A,4V:4R,4Q:4S,4T:7.3K,54:{5f:5e,5a:[{2b:"5b 1V",55:57}]}});Y.58();Y.C(\'2n\',8(Y,1V){9(7.2n!=u){9(7.2n.1H(u,1V)!=P){Y.3I()}}E{Y.3I()}});Y.C(\'25\',8(Y,4O,3N){9(7.25!=u){3 2w=$.4w(3N.4v);7.25.1H(u,2w);9(2w.4x!=0){Y.4y()}}});Y.C(\'26\',8(Y,1V){9(7.26!=u)7.26.1H(u,1V)});Y.C(\'22\',8(Y,3d){9(7.22!=u)7.22.1H(u,3d)})};$.13.4m=8(7){3 x={28:"",1v:"",R:P};7=$.1g({},x,7);3 3h=b;3h.2x(8(){3 r=$(b);3 2z=$(3z).4z();3 3o=$(3z).c();3 2t=r.3u().n;9(!r.4("3Q")||7.R){$(r).4("1v",7.28);9(3o+2z>=2t&&2z<=2t+r.c()){9(7.1v!="")r.4("1v",7.1v);E r.4("1v",r.4("4P-1v"));r.4("3Q",W)}}})};$.13.4t=8(7){3 1E=8(1k,38){1k=4o(1k);3 1Z=1k.1Z;9(1k.1Z<38){1s(3 i=0;i<38-1Z;i++){1k="0"+1k}}1i 1k};3 x={3b:0,3p:0,3n:1,3J:"%D天 %H:%M:%S.%4n",33:u};7=$.1g({},x,7);3 o=$(b);$(navo).4p(7.3n,8(){3 32=12 4q().4s();32+=7.3p;1w=7.3b-32;9(1w<=0){$(navo).3T();9(7.33!=u)7.33.1H();E $(navo).v("")}E{3 1N=1E(K.4r(K.1l(1w%1M)/10),2);9(1N==35)1N="10";3 2J=1E(K.1l(1w/1M%2C),2); 3 2Z=1E(K.1l((1w/1M/2C)%2C),2);3 4A=1E(K.1l((1w/1M/3L)%24),2);3 4B=1E(K.1l((1w/1M/ 3L)/24),2); 3 2K=2Z.1J(0,1);3 2L=2Z.1J(1,1);3 31=2J.1J(0,1);3 2M=2J.1J(1,1);3 2I=1N.1J(0,1);3 2H=1N.1J(1,1);3 v=7.3J;v=v.1D("%2K",2K);v=v.1D("%2L",2L);v=v.1D("%31",31);v=v.1D("%2M",2M);v=v.1D("%2I",2I);v=v.1D("%2H",2H);$(navo).v(v)}})}})(3i);(8($){$.p={q:{I:4K,1Q:30,1t:10,1Y:P,1U:4J,1O:[],1I:0}},$.6={w:u,18:u,1x:u,T:u,1j:u,17:u,1q:[]},$.13.4L=8(7){3 x=$.1g({},$.p.q,7);$.p.q.I=x.I;$.p.q.1Q=x.1Q;$.p.q.1t=x.1t;$.p.q.1O=x.1O;$.p.q.1I=x.1I;$.p.q.1Y=x.1Y;$.p.q.1U=x.1U;$.6.18=$(b);$.6.1x=$.6.18.I();$.6.T=K.1l(($.6.1x-$.p.q.1I+$.p.q.1t)/($.p.q.I+$.p.q.1t));$.6.w=12 1c($.6.T);$.6.1j=12 1c($.6.T);$.6.17=$.p.q.1Q;3 t=$.p.q.1t;3 J=12 1c();1s(i=0;i<$.6.T;i++){9(i==0)$.6.1j[i]=0+$.p.q.1I;E $.6.1j[i]=($.p.q.I+t)*i+$.p.q.1I;3 16=0;2D{16=$.p.q.1O[i];9(2E(16))16=0}2F(3g){16=0}$.6.w[i]={c:16,1K:0,1q:[]};J[i]=$.6.w[i].c}3 14=K.1e.1C(u,J);3 2f=$.1A(14,J);$.6.18.c(14+$.6.17)},$.13.4M=8(v){3 O=$(v);$.6.18.2k(O);3 1o=12 1c();1s(i=0;i<$.6.T;i++){1o[i]=$.6.w[i].c}3 1S=K.3j.1C(u,1o);3 F=$.1A(1S,1o);3 m=$.6.1j[F];9($.6.w[F].c>0)3 n=$.6.w[F].c+$.6.17;E 3 n=0;9($.p.q.1Y){3 f={"15":"1R","n":1S,"m":0};O.f(f);O.3a({n:n,m:m},$.p.q.1U)}E{3 f={"15":"1R","n":n,"m":m};O.f(f)}9($.6.w[F].c>0)$.6.w[F].c+=(O.c()+$.6.17);E $.6.w[F].c+=O.c();$.6.w[F].1K++;$.6.w[F].1q.2G(O);$.6.1q.2G(O);3 J=12 1c();1s(i=0;i<$.6.T;i++){J[i]=$.6.w[i].c}3 14=K.1e.1C(u,J);3 2f=$.1A(14,J);$.6.18.c(14+$.6.17)},$.13.4N=8(){$.6.18=$(b);9($.6.1x==$.6.18.I())1i;$.6.1x=$.6.18.I();$.6.T=K.1l($.6.1x/($.p.q.I+$.p.q.1t));$.6.w=12 1c($.6.T);$.6.1j=12 1c($.6.T);$.6.17=$.p.q.1Q;3 t=K.1l(($.6.1x-$.p.q.I*$.6.T)/($.6.T-1));3 J=12 1c();1s(i=0;i<$.6.T;i++){9(i==0)$.6.1j[i]=0;E $.6.1j[i]=($.p.q.I+t)*i;3 16=0;2D{16=$.p.q.1O[i];9(2E(16))16=0}2F(3g){16=0}$.6.w[i]={c:16,1K:0,1q:[]};J[i]=$.6.w[i].c}3 14=K.1e.1C(u,J);3 2f=$.1A(14,J);$.6.18.c(14+$.6.17);1s(2h=0;2h<$.6.1q.1Z;2h++){3 O=$.6.1q[2h];3 1o=12 1c();1s(i=0;i<$.6.T;i++){1o[i]=$.6.w[i].c}3 1S=K.3j.1C(u,1o);3 F=$.1A(1S,1o);3 m=$.6.1j[F];9($.6.w[F].c>0)3 n=$.6.w[F].c+$.6.17;E 3 n=0;9($.p.q.1Y){O.3a({n:n,m:m},$.p.q.1U)}E{3 f={"15":"1R","n":n,"m":m};O.f(f)}9($.6.w[F].c>0)$.6.w[F].c+=(O.c()+$.6.17);E $.6.w[F].c+=O.c();$.6.w[F].1K++;$.6.w[F].1q.2G(O);3 J=12 1c();1s(i=0;i<$.6.T;i++){J[i]=$.6.w[i].c}3 14=K.1e.1C(u,J);3 2f=$.1A(14,J);$.6.18.c(14+$.6.17)}}})(3i);$.4I=8(1G,2l){$.23.2N(1G,{2O:\'4H\',2V:\'2W\',2X:W,2Y:P,2U:W,2b:\'错误\',I:2T,1a:\'2P\',2Q:8(){2R()},2S:2l})};$.4D=8(1G,2l){$.23.2N(1G,{2O:\'4C\',2V:\'2W\',2X:W,2Y:P,2U:W,2b:\'提示\',I:2T,1a:\'2P\',2Q:8(){2R()},2S:2l})};$.4E=8(1G,3w,3t){3 39=8(){$.23.4F("3A");3w.1H()};$.23.2N(1G,{2O:\'3A\',2V:\'2W\',2X:W,2Y:W,2U:W,2b:\'确认\',I:2T,1a:\'2P\',2Q:8(){2R()},2S:3t,4G:39})};', 62, 326, '|||var|attr||pin_info|options|function|if||this|height|removeClass||css|relClass|ImgCbo||checked|find|obj|left|top||pin_config|_op|img|rel|span|null|html|pin_col_array|op|addClass|||holder|bind||else|minHIndex|outBar||width|theightArray|Math|input||val|pin_item|false|btn|refresh||col_size|_hover|parent|true|cbo|uploader|DLselect||click|new|fn|maxH|position|init_height|height_span|pin_layout|_checked|type|dd|Array|DDselect|max|display|extend|value|return|left_array|number|floor|dl|padding|heightArray|class|items|name|for|wSpan|hide|src|timeless|layout_width|ipt|trigger|inArray|SPANselect|apply|replace|fillZero|hover|str|call|pin_col_init_width|substr|size|sector|1000|mS|pin_col_init_height|id|hSpan|absolute|minH|block|speed|files|DTselect|_active|isAnimate|length|current_sec|cssWidth|Error|weeboxs||FileUploaded|UploadComplete|radio|placeholder|normal|selectNode|title|dt|event|oo|maxHIndex|prev|idx|div|none|append|func|sec_width|FilesAdded|style|checkbox|trim|pageX|inline|imgoffset|disabled|focus|ajaxobj|each|ui|scrolltop|url|font|60|try|isNaN|catch|push|SMS|BMS|nS|BM|SM|SS|open|boxid|wee|onopen|init_ui_button|onclose|250|showOk|contentType|text|showButton|showCancel|nM||BS|nowtime|callback|fast|100|current|dropdown|digits|okfunc|animate|endtime|wrap|errObject|total_width|mouseout|ex|imgs|jQuery|min|move_left|mouseover|next|interval|windheight|timespan|after|blur|show|funcclose|offset|olb|funcok|uichange|selected|window|fanwe_confirm_box|ui_radiobox|ochecked|option|px|right|bottom|submit|start|format|multi|3600|line|responseObject|tagName|select|isload|parseInt|slideDown|stopTime|ui_textbox|oneTime|document|in|mouseup|form|createElement|ui_button|change|ui_select|mousedown|666|relative|outer|before|drop|index|fadeOut|void|href|ii|color|while|javascript|remove|get|fanwe|toLowerCase|ui_lazy|MS|String|everyTime|Date|round|getTime|count_down|ui_checkbox|response|parseJSON|error|stop|scrollTop|nH|nD|fanwe_success_box|showSuccess|showConfirm|close|onok|fanwe_error_box|showErr|300|225|init_pin|pin|reposition|file|data|silverlight_xap_url|UPLOAD_SWF|UPLOAD_XAP|multi_selection|plupload|flash_swf_url|browse_button|ui_upload|UPLOAD_URL|onchange||Uploader|ceil|mousemove|filters|extensions|parseFloat|ALLOW_IMAGE_EXT|init|checkon|mime_types|Image|checkoff|ui_starbar|MAX_IMAGE_SIZE|max_file_size'.split('|'), 0, {}))
eval(function(p, a, c, k, e, d) {
    e = function(c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) {
            d[e(c)] = k[c] || e(c)
        }
        k = [
            function(e) {
                return d[e]
            }
        ];
        e = function() {
            return '\\w+'
        };
        c = 1
    };
    while (c--) {
        if (k[c]) {
            p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
        }
    }
    return p
}('$(P).4n(7(){2E();2D();2T();2y();2R();2Q();2P();35();3z();38();2Y();3g()});7 3z(){3w();c 4i=$("#F").x();$("#F").1b();$(".1n").1A(7(){c C=1u;$(C).Q();$(C).1t(T,7(){$(C).3o($("#F"));b($.1r($("#F").n())=="")1J();$("#F").1B()})},7(){c C=1u;$(C).Q();$(C).1t(T,7(){$("#F").1b()})})}7 3w(){}7 1J(){$("#F").n("<3c Y=\'4U\'></3c>");$.1v({1D:1F,E:{"1G":"1J"},1z:"1E",17:"1H",1i:7(l){$("#F").n(l.n);b($("#F").g(".52").y>3){$("#F").g(".2B").M("U",T)}}})}7 3j(r,1W){$.1v({1D:1F,E:{"1G":"3j","r":r},17:"1H",1z:"1E",1i:7(l){b(l.19){$(".1n").g(".1o").n(l.E.1o);$("#F").n(l.E.n);b($("#F").g(".2B").U()>T){$("#F").g(".2B").M("U",T)}}b(1W&&57(1W)=="7")1W.4b(1Y)}})}7 35(){$(".v-5a").N(7(i,18){c 2m=21 3P();$(18).g(".v-4h").N(7(k,28){2m.4l(28)});c x=$(18).f("x");x=20(x)?0:14(x);c 1h=$(18).f("1h");1h=20(1h)?0:14(1h);c 1k=$(18).f("1k");1k=20(1k)?0:14(1k);c 1d=$(18).f("1d");1d=20(1d)?0:14(1d);$(18).3M({1h:1h,x:x,1d:1d,1k:1k,41:11,43:T});$(2m).N(7(k,28){$(18).44(28)})})}7 2Q(){$.2i=7(){$("3n[4g][!4f]").4p({4c:4d})};$.2i();$(1I).2c("2e",7(e){$.2i()})}7 2P(){$("4a.v-46[h!=\'h\']").N(7(i,u){$(u).f("h","h");$(u).47()})}7 2y(){$("2S.v-48[h!=\'h\']").N(7(i,1x){$(1x).f("h","h");$(1x).49()})}7 2R(){$("2S.v-4m[h!=\'h\']").N(7(i,1x){$(1x).f("h","h");$(1x).4o()})}c 1c=1Y;c 1C=0;7 2T(){$("16.v-16[h!=\'h\']").N(7(i,navo){1C++;c r="2O"+24.2N(24.2J()*2I)+""+1C;c 1X={r:r};$(navo).f("h","h");$(navo).2K(1X)});$("16.v-2v[h!=\'h\']").N(7(i,navo){1C++;c r="2O"+24.2N(24.2J()*2I)+""+1C;c 1X={r:r,3T:"1A"};$(navo).f("h","h");$(navo).2K(1X)});$(P.2w).2d(7(e){b($(e.29).f("Y")!=\'v-16-2L\'&&$(e.29).D().f("Y")!=\'v-16-2L\'){$(".v-16-2v").1L("15");$(".v-16").X("2M");1c=1Y}m{b(1c!=1Y&&1c.f("r")!=$(e.29).D().f("r")){$(1c).g(".v-16-2v").1L("15");$(1c).X("2M")}1c=$(e.29).D()}})}7 2E(){$("2s.v-2s[h!=\'h\']").N(7(i,navo){$(navo).f("h","h");$(navo).40()})}7 2D(){$(".v-3Z[h!=\'h\'],.v-3X[h!=\'h\']").N(7(i,o){$(navo).f("h","h");$(navo).4k()})}7 5b(){$(".4V-4q").g("2s.4Y[27!=\'27\']").N(7(i,navo){$(navo).f("27","27");c I=$(navo).f("I");c 1V=$(navo).4T();1V.f("2U",$(navo).f("2U"));1V.f("I",I);b(14(I)>0)2V($(1V),I)})}7 2V(j,I){$(j).Q();$(j).X($(j).f("J"));$(j).X($(j).f("J")+"34");$(j).X($(j).f("J")+"36");$(j).f("J","2H");$(j).2q("2H");$(j).g("t").n("重新获取("+I+")");$(j).f("I",I);$(j).51(58,7(){c 1y=14($(j).f("I"));1y--;$(j).g("t").n("重新获取("+1y+")");$(j).f("I",1y);b(1y==0){$(j).Q();$(j).X($(j).f("J"));$(j).X($(j).f("J")+"34");$(j).X($(j).f("J")+"36");$(j).f("J","37");$(j).2q("37");$(j).g("t").n("发送验证码")}})}7 3a(u,1a){$(u).D().D().g(".1q").n("<t Y=\'53\'>"+1a+"</t>")}7 3B(u,1a){b(1a!="")$(u).D().D().g(".1q").n("<t Y=\'1i\'>"+1a+"</t>");m $(u).D().D().g(".1q").n("<t Y=\'1i\'>&1P;</t>")}7 1q(u,1a){$(u).D().D().g(".1q").n("<t Y=\'54\'>"+1a+"</t>")}7 4O(u){$(u).D().D().g(".1q").n("")}7 38(){b($(".q").y>0){$(".q").g(".2r").1b();$(".q").1A(7(){c q=$(1u);b($(q).f("1K")!="2p"){$(q).Q();$(q).1t(50,7(){$(q).g(".2r").3b("15");$(q).g(".33 i").2q("32")})}},7(){c q=$(1u);$(q).Q();$(q).g(".2r").1L("15");$(q).g(".33 i").X("32")});c 2W=$(".4s").U()+$(".2X").U()+$(".2X").1p().B;$(1I).2e(7(){b($(P).V()>2W){$(".G").1B();$(".G").g(".q").f("1K","");$(".G").M("B",$(P).V())}m{$(".G").1b();$(".G").g(".q").f("1K","2p")}});b($(P).V()>0){$(".G").1B();$(".G").g(".q").f("1K","");$(".G").M("B",$(P).V())}m{$(".G").1b();$(".G").g(".q").f("1K","2p")}}}7 2Y(){$(1I).2e(7(){b($.1S.2Z&&$.1S.30=="6.0"){$("#1f").M("B",$(P).V()+$(1I).U()-39)}b($(P).V()>0)$("#1f").2G();m $("#1f").1L()});b($.1S.2Z&&$.1S.30=="6.0")$("#1f").M("B",$(P).V()+$(1I).U()-39);b($(P).V()>0)$("#1f").2G();m $("#1f").1L();$("#1f").2c("2d",7(){$("n,2w").1M({V:0},"15","4A",7(){})})}$.4x=7(H,y,1U){c 1s=$.1r(H).y;b(1U)1s=$.2C(H);R 1s>=y};$.4N=7(H,y,1U){c 1s=$.1r(H).y;b(1U)1s=$.2C(H);R 1s<=y};$.2C=7(1l){1l=$.1r(1l);b(1l=="")R 0;c y=0;56(c i=0;i<1l.y;i++){b(1l.4S(i)>4X)y+=2;m y++}R y};$.3U=7(H){b($.1r(H)!=\'\'){c 22=/^(1[3N]\\d{9})$/;R 22.12($.1r(H))}m R 3G};$.4j=7(2F){c 22=/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-2A-2a-9]+((\\.|-)[A-2A-2a-9]+)*\\.[A-2A-2a-9]+$/;R 22.12(2F)};7 4e(1e){c 3J=/[a-z]+/;c 3E=/[A-Z]+/;c 3D=/[0-9]+/;c 3F=/\\W+/;c 3I=/\\S{6,8}/;c 3H=/\\S{9,}/;c p=0;b(3J.12(1e))p++;b(3E.12(1e))p++;b(3D.12(1e))p++;b(3F.12(1e))p++;b(3I.12(1e))p++;b(3H.12(1e))p++;b(p>=1&&p<=2)p=0;m b(p>=3&&p<=4)p=1;m b(p>=5&&p<=6)p=2;m p=-1;R p}c 3K=3G;7 55(1O,H,1m,u){b(!3K)R;c O=21 2l();O.1G="4L";O.1O=1O;O.H=H;O.1m=1m;$.1v({1D:1F,1z:"1E",E:O,17:"1H",4W:11,1i:7(E){b(!E.19){b(E.1O){3a(u,E.1w)}m $.2b(E.1w)}m{3B(u,E.1w)}}})}7 4G(K){b(K.19){c 1j="<t Y=\'4F\'>"+K.1w+"</t>";b(K.2k||K.2j||K.2o){1j+="<t Y=\'4E\'>";b(K.2o)1j+=K.2o+"&1P;";b(K.2j)1j+=K.2j+"&1P;";b(K.2k)1j+=K.2k+"&1P;";1j+="</t>"}$.4D(1j)}}7 4H(3i){$(".4I,.4M").2c("2d",7(){c 2g=$(1u).g("a").f(\'2g\');$.2f.2t(2g,{4K:"4J",2u:\'1v\',4C:11,2x:"手机4B下载",x:4u,17:\'4t\',4r:7(){2E();2D();4v();2y()},4w:3i})})}7 4z(C){b(3k==1){$(C).g("*[J=\'1N\']").M("1Q","1");$(C).g("*[J=\'1N\']").1M({1Q:\'1B\'},{3l:3m,3h:11})}}7 4y(C){b(3k==1){$(C).g("*[J=\'1N\']").M("1Q","1");$(C).g("*[J=\'1N\']").1M({1Q:\'1b\'},{3l:3m,3h:11})}}7 3g(){$("#s").1b();b($("#1T").y>0){$("#1T").1A(7(){$("#s").Q();$("#s").1t(T,7(){c L=$("#1T").3x().L-($("#s").x()-$("#1T").x());$("#s").M("L",L);$("#s").M("B",31);$("#s").3b("15")})},7(){$("#s").Q();$("#s").1t(T,7(){$("#s").3C("15")})});$("#s").1A(7(){$("#s").Q();$(1u).1B()},7(){$("#s").Q();$("#s").1t(T,7(){$("#s").3C("15")})})}}7 59(l,17){c 3f=$(l);c 26=14($(l).f(\'26\'));c O=21 2l();O.1G="4Z";O.26=26;O.3d=14($(l).f(\'3d\'));$.1v({1D:1F,E:O,17:"1H",1z:"1E",1i:7(l){b(17==1){b(l.19==-1){3e()}m b(l.19==1){4R.4Q=4P}m{$.2b(l.1w)}}m{b(l.19==-1){3e()}m b(l.19==1){c 23=$(3f).3Y(".3W-42").g(".3V 3n");c L=$(23).1p().L;c B=$(23).1p().B;c 2z=$(".1n .1o").1p().L;c 3y=$(".1n .1o").1p().B;c 3A=$(".G").1p().B;c 1g=23.3O();$(\'2w\').3o(1g);1g.M({\'3x\':\'3L\',\'L\':L,\'B\':B,\'z-3Q\':3S});b($(".G").3R(":45")){$(1g).1M({"U":"1Z","x":"1Z",L:2z+13,B:3y+10},3v,7(){$(1g).3u();$(".1n .1o").n(l.3q);1J()})}m{$(1g).1M({"U":"1Z","x":"1Z",L:2z+13,B:3A+25},3v,7(){$(1g).3u();$(".1n .1o").n(l.3q);1J()})}}m{$.2b(l.1w)}}}})}c 1R=21 2l();7 3p(r,1m,2h){b(1R[\'2n\'+r]){$.2f.2t(1R[\'2n\'+r],{2x:\'全部夺宝号码\',2u:\'\',x:3r,3s:11,3t:11})}m{$.1v({1D:1F,E:{"1G":"3p","r":r,"1m":1m,"2h":2h},17:"1H",1z:"1E",1i:7(l){$.2f.2t(l.n,{2x:\'全部夺宝号码\',2u:\'\',x:3r,3s:11,3t:11});1R[\'2n\'+r]=l.n}})}};', 62, 322, '|||||||function||||if|var|||attr|find|init||btn||obj|else|html||result|drop_nav|id|user_drop_box|span|ipt|ui||width|length|||top|dom|parent|data|top_cart_list|float_nav_bar|value|lesstime|rel|signin_result|left|css|each|query|document|stopTime|return||300|height|scrollTop||removeClass|class|||false|test||parseInt|fast|select|type|ui_list|status|txt|hide|droped_select|hSpan|pwd|go_top|img_clone|pin_col_init_width|success|msg|wSpan|str|user_id|cart_tip|cart_count|offset|form_tip|trim|strLength|oneTime|this|ajax|info|ImgCbo|lt|dataType|hover|show|uiselect_idx|url|json|AJAX_URL|act|POST|window|load_cart_list|ref|fadeOut|animate|qrcode|field|nbsp|opacity|duobao_no_html|browser|user_drop|isByte|divbtn|callback|op|null|0px|isNaN|new|reg|img_obj|Math||buy_num|init_sms|ui_item|target|z0|showErr|bind|click|scroll|weeboxs|down_url|order_item_id|refresh_image|score|point|Object|ui_item_array|sn|money|no_drop|addClass|drop_box|button|open|contentType|drop|body|title|init_ui_checkbox|cart_left|Za|cart_tip_result_list|getStringLength|init_ui_textbox|init_ui_button|val|fadeIn|disabled|10000000|random|ui_select|selected|dropdown|round|uiselect_|init_ui_starbar|init_ui_lazy|init_ui_radiobox|label|init_ui_select|form_prefix|init_sms_code_btn|max_height|fix_nav_bar|init_gotop|msie|version||up|drop_title|_hover|init_ui_list|_active|light|init_drop_nav|80|form_err|slideDown|div|data_id|ajax_login|btn_item|init_drop_user|queue|func|del_cart|QRCODE_ON|duration|100|img|append|my_no_all|cart_item_num|600|showOk|showCancel|remove|500|load_cart_count|position|cart_top|init_cart_tip|float_nav_top|form_success|slideUp|regex2|regex1|regex3|true|regex5|regex4|regex0|allow_ajax_check|absolute|init_pin|34578|clone|Array|index|is|10000|event|checkMobilePhone|imgbox|goods|textarea|parents|textbox|ui_button|isAnimate|wrap|speed|pin|hidden|starbar|ui_starbar|checkbox|ui_checkbox|input|call|placeholder|LOADER_IMG|checkPwdFormat|isload|lazy|item|top_cart_list_width|checkEmail|ui_textbox|push|radiobox|ready|ui_radiobox|ui_lazy|panel|onopen|fix_cate_tree|wee|650|init_login_panel|onclose|minLength|hide_scan_box|show_scan_box|swing|APP|showButton|showSuccess|signin_price|signin_msg|show_signin_message|app_download|android|app_box|boxid|check_field|ios|maxLength|form_tip_clear|cart_url|href|location|charCodeAt|next|loading|login|global|255|ph_verify_btn|addcart||everyTime|cart_tip_result_item|error|tip|ajax_check_field|for|typeof|1000|add_cart|list|init_sms_btn'.split('|'), 0, {}))
eval(function(p, a, c, k, e, d) {
    e = function(c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) {
            d[e(c)] = k[c] || e(c)
        }
        k = [
            function(e) {
                return d[e]
            }
        ];
        e = function() {
            return '\\w+'
        };
        c = 1
    };
    while (c--) {
        if (k[c]) {
            p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
        }
    }
    return p
}('$(1f).22(3(){1y();1x();1b()});3 1y(){$("#20").j("C",3(){5(1d(15)=="3"){1c(15)}n{1c()}t h})}3 1c(N){$.Z.1v(24,{1z:"1e",1A:\'H\',1E:h,1D:"会员登录",1C:26,L:\'1B\',1u:3(){25();1Z();1b();1Y()},1t:N})}3 1x(){$("#F").1g("C",3(){c M=$(d).9("u");5(1d(15)=="3"){F(M,15)}n{F(M)}t h})}3 F(M,N){$.Z.1v(M,{1z:"1V",1A:\'H\',1E:h,1D:"微信登录",1C:2a,2h:2j,L:\'1B\',1u:3(){$("#F").2g(2f,3(){c r=1N 1M();r.1n="29";$.H({D:1L,10:"14",4:r,L:"12",1K:h,X:3(4){5(4.Y){$("#F").1m();U.2b()}}})})},1t:3(){$("#F").1m();5(1d(N)=="3")N.2l(W)}})}3 1b(){$(".e-1G a").1g("C",3(){c P=$(d).9("27");c u=$(d).9("u");$(".e-f[u=\'"+P+"\']").2(".f").1j();$(".e-f[u=\'"+P+"\']").2(".f[u=\'"+u+"\']").1s();$(".e-1G[u=\'"+P+"\']").2("a").2d("1o");$(d).28("1o")});$(".e-f v.16").m(3(k,v){5(!$(v).9("G")){$(v).9("G",g);$(v).j("C",3(){$(v).9("1r",$(d).9("u")+"?"+1q.1p())})}});$(".e-f .1F").m(3(k,S){5(!$(S).9("G")){$(S).9("G",g);$(S).j("C",3(){c v=$(S).1U().2("v.16");$(v).9("1r",$(v).9("u")+"?"+1q.1p())})}});$(".e-f .1F").1g("C",3(){});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'x\']").9("y")){$(6).2("8[7=\'x\']").9("y",g);$(6).2("8[7=\'x\']").j("R",3(){O($(d))})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'x\']").9("B")){$(6).2("8[7=\'x\']").9("B",g);$(6).2("8[7=\'x\']").j("Q",3(){c s=$(d).q();5($.p(s)==""){w($(d),"请输入登录帐号")}})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'A\']").9("y")){$(6).2("8[7=\'A\']").9("y",g);$(6).2("8[7=\'A\']").j("R",3(){O($(d))})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'A\']").9("B")){$(6).2("8[7=\'A\']").9("B",g);$(6).2("8[7=\'A\']").j("Q",3(){c s=$(d).q();5($.p(s)==""){w($(d),"请输入密码")}})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'o\']").9("y")){$(6).2("8[7=\'o\']").9("y",g);$(6).2("8[7=\'o\']").j("R",3(){O($(d))})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'o\']").9("B")){$(6).2("8[7=\'o\']").9("B",g);$(6).2("8[7=\'o\']").j("Q",3(){c s=$(d).q();c 1R=$(d);5($.p(s)==""){w($(d),"请输入图片文字")}n{21("o",s,0,1R)}})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'l\']").9("y")){$(6).2("8[7=\'l\']").9("y",g);$(6).2("8[7=\'l\']").j("R",3(){O($(d))})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'l\']").9("B")){$(6).2("8[7=\'l\']").9("B",g);$(6).2("8[7=\'l\']").j("Q",3(){c s=$(d).q();5($.p(s)==""){w($(d),"请输入手机号")}5(!$.1h(s)){I($(d),"手机号格式不正确")}})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'z\']").9("y")){$(6).2("8[7=\'z\']").9("y",g);$(6).2("8[7=\'z\']").j("R",3(){O($(d))})}});$(".e-f").m(3(k,6){5(!$(6).2("8[7=\'z\']").9("B")){$(6).2("8[7=\'z\']").9("B",g);$(6).2("8[7=\'z\']").j("Q",3(){c s=$(d).q();5($.p(s)==""){w($(d),"请输入收到的验证码")}})}});2e();$(".e-f").m(3(k,6){5(!$(6).2("1a.19").9("G")){$(6).2("1a.19").9("G",g);$(6).2("1a.19").j("C",3(){5($(d).9("u")=="1X")t h;c 1O=$(d).9("P")+"1S";c b=$("b[7=\'"+1O+"\']");c 1H=$(d);c r=1N 1M();r.1n="1T";c T=$(b).2("8[7=\'l\']").q();5($.p(T)==""){w($(b).2("8[7=\'l\']"),"请输入手机号");t h}5(!$.1h(T)){I($(b).2("8[7=\'l\']"),"手机号格式不正确");t h}r.T=$.p(T);r.o=$.p($(b).2("8[7=\'o\']").q());$.H({D:1L,10:"14",4:r,L:"12",1K:h,X:3(4){5(4.Y){1W(1H,4.2k);2c=g;$(b).2("v.16").C();5(4.2i>1){$(b).2(".1i").1s()}n{$(b).2(".1i").1j()}}n{5(4.K){I($(b).2("8[7=\'"+4.K+"\']"),4.E)}n $.V(4.E)}}})})}});$(".e-f").2(".e").m(3(i,b){5(!$(b).9("13")){$(b).9("13",g);$(b).j("1w",3(){c x=$(b).2("8[7=\'x\']");5($.p(x.q())==""){w(x,"请输入登录帐号");t h}c A=$(b).2("8[7=\'A\']");5($.p(A.q())==""){w(A,"请输入密码");t h}c o=$(b).2("8[7=\'o\']");5($.p(navo.q())==""){w(navo,"请输入图片文字");t h}c D=$(b).9("1l");c r=$(b).1k();17=h;$.H({D:D,L:"12",4:r,10:"14",X:3(4){17=g;5(4.Y){$(1f).1Q(4.4);5(4.J!=""&&4.J!=W){$("#1I").1J(4.J);$.Z.1P("1e")}n{5(4.4!=""&&4.4!=W){$.V(4.E,3(){U.11=4.18})}n{U.11=4.18}}}n{$(b).2(".16").C();5(4.K){I($(b).2("8[7=\'"+4.K+"\']"),4.E)}n $.V(4.E)}}});t h})}});$(".e-f").2(".23").m(3(i,b){5(!$(b).9("13")){$(b).9("13",g);$(b).j("1w",3(){c l=$(b).2("8[7=\'l\']");5($.p(l.q())==""){w(l,"请输入手机号");t h}5(!$.1h(l.q())){I(l,"手机号格式不正确")}c z=$(b).2("8[7=\'z\']");5($.p(z.q())==""){w(z,"请输入收到的验证码");t h}c D=$(b).9("1l");c r=$(b).1k();17=h;$.H({D:D,L:"12",4:r,10:"14",X:3(4){17=g;5(4.Y){$(1f).1Q(4.4);5(4.J!=""&&4.J!=W){$("#1I").1J(4.J);$.Z.1P("1e")}n{5(4.4!=""&&4.4!=W){$.V(4.E,3(){U.11=4.18})}n{U.11=4.18}}}n{5(4.K){I($(b).2("8[7=\'"+4.K+"\']"),4.E)}n $.V(4.E)}}});t h})}})}', 62, 146, '||find|function|data|if|login_panel|name|input|attr||form|var|this|login|panel|true|false||bind||user_mobile|each|else|verify_code|trim|val|query|txt|return|rel|img|form_tip|user_key|bindfocus|sms_verify|user_pwd|bindblur|click|url|info|wx_login|bindclick|ajax|form_err|tip|field|type|ajax_url|func|form_tip_clear|form_prefix|blur|focus|text|mobile|location|showErr|null|success|status|weeboxs|dataType|href|POST|bindsubmit|json|login_callback|verify|allow_ajax_check|jump|ph_verify_btn|div|init_login_panel|ajax_login|typeof|wee_login_box|document|live|checkMobilePhone|ph_img_verify|hide|serialize|action|stopTime|act|current|random|Math|src|show|onclose|onopen|open|submit|init_wx_user|init_ajax_user|boxid|contentType|wee|width|title|showButton|refresh_verify|tab|btn|head_user_tip|html|global|AJAX_URL|Object|new|formname|close|append|ipt|_ph_login_form|send_sms_code|parent|wee_wx_login_box|init_sms_code_btn|disabled|init_ui_checkbox|init_ui_textbox|pop_login|ajax_check_field|ready|ph_login|AJAX_LOGIN_URL|init_ui_button|580|lk|addClass|check_login_status|350|reload|IS_RUN_CRON|removeClass|init_sms_btn|2000|everyTime|height|sms_ipcount|300|lesstime|call'.split('|'), 0, {}))
eval(function(p, a, c, k, e, d) {
    e = function(c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) {
            d[e(c)] = k[c] || e(c)
        }
        k = [
            function(e) {
                return d[e]
            }
        ];
        e = function() {
            return '\\w+'
        };
        c = 1
    };
    while (c--) {
        if (k[c]) {
            p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
        }
    }
    return p
}('$(A).u(0(){a()});0 a(){1 7=9($(".w-c-d:s").4("v")+"e")-f x().z();$(".w-c-d").q(0(i,navo){1 2=9($(navo).4("2")+"e");$(navo).j({2:2,7:7,m:p,n:"<b>%B</b><b>%K</b>:<b>%O</b><b>%N</b>:<b>%M</b><b>%P</b>",Q:0(){1 6=R;1 3=f L();3.E="D";3.8=$(navo).4("8");1 b=$(navo);$.J({6:6,H:"y",5:3,I:"G",F:0(5){$(".C l").h(i).k(".r").g(5.g)},t:0(navo){}})}})})}', 54, 54, 'function|var|endtime|query|attr|data|url|timespan|id|parseInt|init_count_down||countdown|nums|000|new|html|eq||count_down|find|li|interval|format||10|each|show_content|first|error|ready|nowtime||Date|POST|getTime|document|BM|infoList|get_lottery_info|act|success|json|type|dataType|ajax|SM|Object|BMS|SS|BS|SMS|callback|AJAX_URL'.split('|'), 0, {}))