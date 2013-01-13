/**
 * remy sharp / http://remysharp.com
 * Twitter / @rem
 * http://remysharp.com/2007/05/18/add-twitter-to-your-blog-step-by-step/
 *
 * @params
 *   cssIdOfContainer: e.g. twitters
 *   options: 
 *       {
 *           id: {String} username,
 *           count: {Int} 1-20, defaults to 1 - max limit 20
 *           prefix: {String} '%name% said', defaults to blank
 *           clearContents: {Boolean} true, removes contents of element specified in cssIdOfContainer, defaults to true
 *           ignoreReplies: {Boolean}, skips over tweets starting with '@', defaults to false
 *           template: {String} HTML template to use for LI element (see URL above for examples), defaults to predefined template
 *           enableLinks: {Boolean} linkifies text, defaults to true,
 *           newwindow {Boolean} opens links in new window, defaults to false
 *           timeout: {Int} How long before triggering onTimeout, defaults to 10 seconds if onTimeout is set
 *           onTimeoutCancel: {Boolean} Completely cancel twitter call if timedout, defaults to false
 *           onTimeout: {Function} Function to run when the timeout occurs. Function is bound to element specified with 
 *           cssIdOfContainer (i.e. 'this' keyword)
 *           callback: {Function} Callback function once the render is complete, doesn't fire on timeout
 *           maskLinkText: {String} replaces link text if present, default not defined
 *           maxLinkLength : {Int} maximum length of links, defaults to 25
 *           relativeTime : {Boolean} Whether to use relative time stamps, defaults to true
 *
 *      CURRENTLY DISABLED DUE TO CHANGE IN TWITTER API:
 *           withFriends: {Boolean} includes friend's status
 *
 *       }
 *
 * @license MIT (MIT-LICENSE.txt)
 * @version 1.13.custom - unofficial new features and bug fixes
 * @date $Date: 2010-03-21 20:52:00 -0500 (Sun, 21 Mar 2010) $
 */

// to protect variables from resetting if included more than once
if (typeof getTwitters != 'function') (function () {
    /** Private variables */
    
    // only used for the DOM ready, since IE & Safari require special conditions
    var browser = (function() {
        var b = navigator.userAgent.toLowerCase();

        // Figure out what browser is being used
        return {
            webkit: /(webkit|khtml)/.test(b),
            opera: /opera/.test(b),
            msie: /msie/.test(b) && !(/opera/).test(b),
            mozilla: /mozilla/.test(b) && !(/(compatible|webkit)/).test(b)
        };
    })();

    var guid = 0;
    var readyList = [];
    var isReady = false;
    
    var monthDict = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    function renderTwitters(obj, options) {
    
    // based on Dustin Diaz's ify, but with my fixes :-)
        var ify = {
        "link": function(t) {
          return t.replace(/[a-z]+(:\/\/)?[a-z0-9-_]+\.[a-z0-9-_:~%&\?\/.=]+[^!:\.,\)\s*$]/ig, function(m, m1) {
            var lt = options.maskLinkText, ll = options.maxLinkLength;
            var t = lt ? lt : m.substr(m1 ? m.indexOf(m1)+m1.length : 0, ll) + (m.length > ll ? '...' : '');
            return '<a href="' + (m1 ? '' : 'http://') + m + '"> ' + t + ' </a>';
          });
        },
        "at": function(t) {
          return t.replace(/(^|[^\w]+)\@([a-zA-Z0-9_]{1,15}(\/[a-zA-Z0-9-_]+)*)/g, function(m, m1, m2) {
            return m1 + '@<a href="http://twitter.com/' + m2 + '">' + m2 + '</a>';
          });
        },
        "hash": function(t) {
          return t.replace(/(^|[^&\w'"]+)\#([a-zA-Z0-9_]+)/g, function(m, m1, m2) {
            return m1 + '#<a href="http://search.twitter.com/search?q=%23' + m2 + '">' + m2 + '</a>';
          });
        },
        "clean": function(tweet) {
          return this.hash(this.at(this.link(tweet)));
        }
      };
    
        // private shortcuts
        function node(e) {
            return document.createElement(e);
        }
        
        function text(t) {
            return document.createTextNode(t);
        }

        var target = document.getElementById(options.twitterTarget);
        var data = null;
        var ul = node('ul'), li, statusSpan, timeSpan, i;
        var max = obj.length > options.count ? options.count : obj.length;
        
        for (i = 0; i < max && obj[i]; i++) {
            data = getTwitterData(obj[i], options);
                        
            if (options.ignoreReplies && obj[i].text.substr(0, 1) == '@') {
                max++;
                continue; // skip
            }
            
            li = node('li');
            
            if (options.template) {
                li.innerHTML = options.template.replace(/%([a-z_\-\.]*)%/ig, function (m, l) {
                    var r = data[l] + "" || "";
                    if (l == 'text' && options.enableLinks) r = ify.clean(r);
                    return r;
                });
            } else {
                statusSpan = node('span');
                statusSpan.className = 'twitterStatus';
                timeSpan = node('span');
                timeSpan.className = 'twitterTime';
                statusSpan.innerHTML = obj[i].text; // forces the entities to be converted correctly

                if (options.enableLinks == true) {
                    statusSpan.innerHTML = ify.clean(statusSpan.innerHTML);
                }

                timeSpan.innerHTML = fuzzy_time(obj[i].created_at, options.relativeTime);

                if (options.prefix) {
                    var s = node('span');
                    s.className = 'twitterPrefix';
                    s.innerHTML = options.prefix.replace(/%(.*?)%/g, function (m, l) {
                        return obj[i].user[l];
                    });
                    li.appendChild(s);
                    li.appendChild(text(' ')); // spacer :-(
                }

                li.appendChild(statusSpan);
                li.appendChild(text(' '));
                li.appendChild(timeSpan);
            }
            
            if (options.newwindow) {
                li.innerHTML = li.innerHTML.replace(/<a href/gi, '<a target="_blank" href');
            }
            
            ul.appendChild(li);
        }

        if (options.clearContents) {
            while (target.firstChild) {
                target.removeChild(target.firstChild);
            }
        }

        target.appendChild(ul);
        
        if (typeof options.callback == 'function') {
            options.callback();
        }
    }
    
    /** Global functions */
    
    // to create a public function within our private scope, we attach the 
    // the function to the window object
    
    window.getTwitters = function (target, id, count, options) {
        guid++;

        if (typeof id == 'object') {
            options = id;
            id = options.id;
            count = options.count;
        } 

        // defaulting options
        if (!count) count = 1;
        
        if (options) {
            options.count = count;
        } else {
            options = {};
        }
        
        if (!options.maxLinkLength) {
            options.maxLinkLength = 25;
        }
        
        if (typeof options.relativeTime == 'undefined') {
            options.relativeTime = true;
        }
        
        if (!options.timeout && typeof options.onTimeout == 'function') {
            options.timeout = 10;
        }
        
        if (typeof options.clearContents == 'undefined') {
            options.clearContents = true;
        }
        
        // Hack to disable withFriends, twitter changed their API so this requires auth
        // http://getsatisfaction.com/twitter/topics/friends_timeline_api_call_suddenly_requires_auth
        if (options.withFriends) options.withFriends = false;

        // need to make these global since we can't pass in to the twitter callback
        options['twitterTarget'] = target;
        
        // default enable links
        if (typeof options.enableLinks == 'undefined') options.enableLinks = true;

        // this looks scary, but it actually allows us to have more than one twitter
        // status on the page, which in the case of my example blog - I do!
        window['twitterCallback' + guid] = function (obj) {
            if (options.timeout) {
                clearTimeout(window['twitterTimeout' + guid]);
            }
            getTwitters.renderTwitters(obj, options);
        };

        // check out the mad currying!
        ready((function(options, guid) {
            return function () {
                // if the element isn't on the DOM, don't bother
                if (!document.getElementById(options.twitterTarget)) {
                    return;
                }
                
                var url = 'http://www.twitter.com/statuses/' + (options.withFriends ? 'friends_timeline' : 'user_timeline') + '/' + id + '.json?callback=twitterCallback' + guid + '&count=20&cb=' + Math.random();

                if (options.timeout) {
                    window['twitterTimeout' + guid] = setTimeout(function () {
                        // cancel callback
                        if (options.onTimeoutCancel) window['twitterCallback' + guid] = function () {};
                        options.onTimeout.call(document.getElementById(options.twitterTarget));
                    }, options.timeout * 1000);
                }
                
                var script = document.createElement('script');
                script.setAttribute('src', url);
                document.getElementsByTagName('head')[0].appendChild(script);
            };
        })(options, guid));
    };
    
    getTwitters.renderTwitters = renderTwitters;
        
    // GO!
    DOMReady();
    

    /** Private functions */
    
    function getTwitterData(orig, options) {
        var data = orig, i;
        for (i in orig.user) {
            data['user_' + i] = orig.user[i];
        }
        data.time = fuzzy_time(orig.created_at, options.relativeTime);
        
        return data;
    }
    
    function ready(callback) {
        if (!isReady) {
            readyList.push(callback);
        } else {
            callback.call();
        }
    }
    
    function fireReady() {
        isReady = true;
        var fn;
        while (fn = readyList.shift()) {
            fn.call();
        }
    }

    // ready and browser adapted from John Resig's jQuery library (http://jquery.com)
    function DOMReady() {
        if ( document.addEventListener && !browser.webkit ) {
            document.addEventListener( "DOMContentLoaded", fireReady, false );
        } else if ( browser.msie ) {
            // If IE is used, use the excellent hack by Matthias Miller
            // http://www.outofhanwell.com/blog/index.php?title=the_window_onload_problem_revisited

            // Only works if you document.write() it
            document.write("<scr" + "ipt id=__ie_init defer=true src=//:><\/script>");

            // Use the defer script hack
            var script = document.getElementById("__ie_init");

            // script does not exist if jQuery is loaded dynamically
            if (script) {
                script.onreadystatechange = function() {
                    if ( this.readyState != "complete" ) return;
                    this.parentNode.removeChild( this );
                    fireReady.call();
                };
            }

            // Clear from memory
            script = null;

        } else if ( browser.webkit ) {
            // Continually check to see if the document.readyState is valid
            var safariTimer = setInterval(function () {
                // loaded and complete are both valid states
                if ( document.readyState == "loaded" || 
                document.readyState == "complete" ) {

                    // If either one are found, remove the timer
                    clearInterval( safariTimer );
                    safariTimer = null;
                    // and execute any waiting functions
                    fireReady.call();
                }
            }, 10);
        }
    }
    
    function fuzzy_time(time_value, is_relative) {
        var values = time_value.split(" "),
            parsed_date = Date.parse(values[1] + " " + values[2] + ", " + values[5] + " " + values[3] + " UTC"),
            date = new Date(parsed_date),
            relative_to = new Date(),
            r = '',
            delta = parseInt((relative_to.getTime() - date.getTime()) / 1000);
        
        var seconds = {
          'from' : {
            'minutes' : function(v) { return v * 60; },
            'hours'   : function(v) { return this.minutes(v) * 60; },
            'days'    : function(v) { return this.hours(v) * 24; },
            'weeks'   : function(v) { return this.days(v) * 7; },
            'months'  : function(v) { return this.weeks(v) * 4.34812141; },
            'years'   : function(v) { return this.months(v) * 12; }
          },
          'to' : {
            'minutes' : function(v) { return v / 60; },
            'hours'   : function(v) { return this.minutes(v) / 60; },
            'days'    : function(v) { return this.hours(v) / 24; },
            'weeks'   : function(v) { return this.days(v) / 7; },
            'months'  : function(v) { return this.weeks(v) / 4.34812141; },
            'years'   : function(v) { return this.months(v) / 12; }
          }
        };
        
        if (!is_relative)
            return formatTime(date) + ' ' + formatDate(date);
                  
          if (delta < 30) 
            return 'recién';
          var minutes = parseInt(seconds.to.minutes(delta)+0.5);
          if (minutes <= 1) 
            return 'hace un minuto';
          var hours = parseInt(seconds.to.hours(delta)+0.5);
          if (hours < 1) 
            return 'hace ' + minutes + ' minutos';
          if (hours == 1) 
            return 'hace una hora';
          var days = parseInt(seconds.to.days(delta)+0.5);
          if (days < 1) 
            return 'hace ' + hours + ' horas';
          if (days==1) 
            return 'ayer';
          var weeks = parseInt(seconds.to.weeks(delta)+0.5);
          if (weeks < 2) 
            return 'hace ' + days + ' días';
          var months = parseInt(seconds.to.months(delta)+0.5);
          if (months < 2) 
            return weeks + ' semanas atrás';
          var years = parseInt(seconds.to.years(delta)+0.5);
          if (years < 2) 
            return months + ' meses atrás';
          return years + ' years ago';        
        function formatTime(date) {
            var hour = date.getHours(),
                min = date.getMinutes() + "",
                ampm = 'AM';
            
            if (hour >= 12) ampm = 'PM';
            if (hour > 12) hour -= 12;
            
            if (min.length == 1) {
                min = '0' + min;
            }
            
            return hour + ':' + min + ' ' + ampm;
        }
        
        function formatDate(date) {
            var ds = date.toDateString().split(/ /),
                mon = monthDict[date.getMonth()],
                day = date.getDate()+'',
                dayi = parseInt(day),
                year = date.getFullYear(),
                thisyear = (new Date()).getFullYear(),
                th = 'th';
            
            // anti-'th' - but don't do the 11th, 12th or 13th
            if ((dayi % 10) == 1 && day.substr(0, 1) != '1') {
                th = 'st';
            } else if ((dayi % 10) == 2 && day.substr(0, 1) != '1') {
                th = 'nd';
            } else if ((dayi % 10) == 3 && day.substr(0, 1) != '1') {
                th = 'rd';
            }
            
            if (day.substr(0, 1) == '0') {
                day = day.substr(1);
            }
            
            return mon + ' ' + day + th + (thisyear != year ? ', ' + year : '');
        }
    }
})();

