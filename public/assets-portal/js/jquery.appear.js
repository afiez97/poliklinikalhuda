/*!
    jQuery appear plugin
    Version: 0.4
    Plugin URL: https://github.com/morr/jquery.appear/
    License: Copyright (c) 2012 Andrey Sidorov | Released under the MIT license
!*/
(function(s) {
  var r = [],
  n = !1,
  a = !1,
  o = {
      interval: 250,
      force_process: !1
  },
  l = s(window),
  c = [];
  function d() {
      a = !1;
      for (var e = 0, t = r.length; e < t; e++) {
          n = r[e];
          var i, n = s(n).filter(function() {
              return s(this).is(":appeared")
          });
          n.trigger("appear", [n]),
          c[e] && (i = c[e].not(n)).trigger("disappear", [i]),
          c[e] = n
      }
  }
  s.expr.pseudos.appeared = s.expr.createPseudo(function(e) {
      return function(e) {
          var t, i, n, r, e = s(e);
          return !! e.is(":visible") && (t = l.scrollLeft(), i = l.scrollTop(), n = (r = e.offset()).left, (r = r.top) + e.height() >= i) && r - (e.data("appear-top-offset") || 0) <= i + l.height() && n + e.width() >= t && n - (e.data("appear-left-offset") || 0) <= t + l.width()
      }
  }),
  s.fn.extend({
      appear: function(e, t) {
          return s.appear(this, t),
          this
      }
  }),
  s.extend({
      appear: function(e, t) {
          var i = s.extend({},
          o, t || {});
          n || (t = function() {
              a || (a = !0, setTimeout(d, i.interval))
          },
          s(window).on("scroll", t).on("resize", t), n = !0),
          i.force_process && setTimeout(d, i.interval),
          r.push(e),
          c.push()
      },
      force_appear: function() {
          return !! n && (d(), !0)
      }
  })
} ("undefined" != typeof module ? require("jquery") : jQuery));