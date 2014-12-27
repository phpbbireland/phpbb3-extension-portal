/*!
 * jQuery YouTube Popup Player Plugin v2.1
 * http://lab.abhinayrathore.com/jquery_youtube/
 * Last Updated: May 23 2012
 */
(function ($) {
    var YouTubeDialog = null;
    var methods = {
        //initialize plugin
        init: function (options) {
            options = $.extend({}, $.fn.YouTubePopup.defaults, options);
 
            // initialize YouTube Player Dialog
            if (YouTubeDialog == null) {
                YouTubeDialog = $('<div></div>').css({ display: 'none', padding: 0 });
                $('body').append(YouTubeDialog);
                YouTubeDialog.dialog({ autoOpen: false, resizable: false, draggable: options.draggable, modal: options.modal,
                    close: function () {
                        YouTubeDialog.html('');
                        $(".ui-dialog-titlebar").show();
                    }
                });
            }
 
            return this.each(function () {
                var obj = $(this);
                var data = obj.data('YouTube');
                if (!data) { //check if event is already assigned
                    obj.data('YouTube', { target: obj, 'active': true });
                    $(obj).bind('click.YouTubePopup', function () {
                        var youtubeId = options.youtubeId;
                        if ($.trim(youtubeId) == '') youtubeId = obj.attr(options.idAttribute);
                        var videoTitle = $.trim(options.title);
                        if (videoTitle == '') {
                            if (options.useYouTubeTitle) setYouTubeTitle(youtubeId);
                            else videoTitle = obj.attr('title');
                        }
 
                        //Format YouTube URL
                        var YouTubeURL = "http://www.youtube.com/embed/" + youtubeId + "?rel=0&showsearch=0&autohide=" + options.autohide;
                        YouTubeURL += "&autoplay=" + options.autoplay + "&color1=" + options.color1 + "&color2=" + options.color2;
                        YouTubeURL += "&controls=" + options.controls + "&fs=" + options.fullscreen + "&loop=" + options.loop;
                        YouTubeURL += "&hd=" + options.hd + "&showinfo=" + options.showinfo + "&color=" + options.color + "&theme=" + options.theme;
 
                        //Setup YouTube Dialog
                        YouTubeDialog.html(getYouTubePlayer(YouTubeURL, options.width, options.height));
                        YouTubeDialog.dialog({ 'width': 'auto', 'height': 'auto' }); //reset width and height
                        YouTubeDialog.dialog({ 'minWidth': options.width, 'minHeight': options.height, title: videoTitle });
                        YouTubeDialog.dialog('open');
                        $(".ui-widget-overlay").fadeTo('fast', options.overlayOpacity); //set Overlay opacity
                        if(options.hideTitleBar && options.modal){ //hide Title Bar (only if Modal is enabled)
                            $(".ui-dialog-titlebar").hide(); //hide Title Bar
                            $(".ui-widget-overlay").click(function () { YouTubeDialog.dialog("close"); }); //automatically assign Click event to overlay
                        }
                        if(options.clickOutsideClose && options.modal){ //assign clickOutsideClose event only if Modal option is enabled
                            $(".ui-widget-overlay").click(function () { YouTubeDialog.dialog("close"); }); //assign Click event to overlay
                        }
                        return false;
                    });
                }
            });
        },
        destroy: function () {
            return this.each(function () {
                $(this).unbind(".YouTubePopup");
                $(this).removeData('YouTube');
            });
        }
    };
 
    function getYouTubePlayer(URL, width, height) {
        var YouTubePlayer = '<iframe title="YouTube video player" style="margin:0; padding:0;" width="' + width + '" ';
        YouTubePlayer += 'height="' + height + '" src="' + URL + '" frameborder="0" allowfullscreen></iframe>';
        return YouTubePlayer;
    }
     
    function setYouTubeTitle(id) {
        var url = "https://gdata.youtube.com/feeds/api/videos/" + id + "?v=2&alt=json";
        $.ajax({ url: url, dataType: 'jsonp', cache: true, success: function(data){ YouTubeDialog.dialog({ title: data.entry.title.$t }); } });
    }
 
    $.fn.YouTubePopup = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.YouTubePopup');
        }
    };
 
    //default configuration
    $.fn.YouTubePopup.defaults = {
        'youtubeId': '',
        'title': '',
        'useYouTubeTitle': true,
        'idAttribute': 'rel',
        'draggable': true,
        'modal': false,
        'width': 640,
        'height': 390,
        'hideTitleBar': false,
        'clickOutsideClose': false,
        'overlayOpacity': 0.2,
        'autohide': 2,
        'autoplay': 1,
        'color': 'red',
        'color1': 'FFFFFF',
        'color2': 'FFFFFF',
        'controls': 2,
        'fullscreen': 1,
        'loop': 0,
        'hd': 1,
        'showinfo': 0,
        'theme': 'dark',
		'showsearch': 1,
		'wmode': 'transparent',
    };
})(jQuery);