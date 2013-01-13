/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 0.5.0
 * 
 */
var JSON = JSON || {};
JSON.stringify = JSON.stringify || function (obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string") obj = '"'+obj+'"';
        return String(obj);
    }
    else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n]; t = typeof(v);
            if (t == "string") v = '"'+v+'"';
            else if (t == "object" && v !== null) v = JSON.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};
;(function($) {
    jQuery.fn.extend({
        dataPlugins: function(options) {
            this.each(function(){
                var $this = $(this);
                var dataPlugins = jQuery.parseJSON($this.attr('data-plugins'));
                for(var i in dataPlugins) {
                    var plugin = dataPlugins[i];
                    var pluginName = plugin.name;
                    var pluginParams = plugin.params;
                    jQuery.fn[pluginName].apply($this, [pluginParams]);
                }
            });
        }
    })
})(jQuery);
