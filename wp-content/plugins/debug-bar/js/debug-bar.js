var wpDebugBar;(function(i){var e;wpDebugBar=e={body:void 0,init:function(){e.body=i(document.body),e.toggle.init(),e.tabs(),e.actions.init()},isVisible:function(){return e.body.hasClass("debug-bar-visible")},toggle:{init:function(){i("#wp-admin-bar-debug-bar").click(function(i){i.preventDefault(),e.toggle.visibility()})},visibility:function(t){t=t===void 0?!e.isVisible():t,e.body.toggleClass("debug-bar-visible",t),i(this).toggleClass("active",t)}},tabs:function(){var e=i(".debug-menu-link"),t=i(".debug-menu-target");e.click(function(a){var n=i(this);a.preventDefault(),n.hasClass("current")||(t.hide().trigger("debug-bar-hide"),e.removeClass("current"),n.addClass("current"),i("#"+this.href.substr(this.href.indexOf("#")+1)).show().trigger("debug-bar-show"))})},actions:{init:function(){var t=i("#debug-bar-actions");i(document).keydown(function(i){var t=i.key||i.which||i.keyCode;if(27==t&&e.isVisible())return i.preventDefault(),e.actions.close()}),i(".maximize",t).click(e.actions.maximize),i(".restore",t).click(e.actions.restore),i(".close",t).click(e.actions.close)},maximize:function(){e.body.removeClass("debug-bar-partial"),e.body.addClass("debug-bar-maximized")},restore:function(){e.body.removeClass("debug-bar-maximized"),e.body.addClass("debug-bar-partial")},close:function(){e.toggle.visibility(!1),console.log("boo")}}},wpDebugBar.Panel=function(){},i(document).ready(wpDebugBar.init)})(jQuery);