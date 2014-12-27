/*Image Bubbles Effect (April 4th, 2010)
* This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
*/

jQuery.fn.imgbubbles=function(options){
	var $=jQuery
	
	var setting=$.extend({}, {factor:2, speed:'400'}, options) //merge options w/ default settings
	return this.each(function(){ //return jQuery obj
		var $bubblewrap=$(this)
		var $imgs=$bubblewrap.find('img')
		$imgs.each(function(){
			var $img=$(this)
			var $parentli=$img.offsetParent()
			var od={width:this.offsetWidth, height:this.offsetHeight} //original dimensions of image
			var nd={width:od.width*setting.factor, height:od.height*setting.factor} //enlarged dimensions of image
			var ncoords=[-(nd.width-od.width)/2, -(nd.height-od.height)/2] //coords to move enlarged image to
			$img.data("specs", { //cache image specs
				od: od,
				nd: nd,
				ncoords: ncoords
			})
			if ($img.attr('alt')){ //if tooltip for image defined
				var $tip=$('<div class="tooltip" style="z-index:1001" />').html($img.attr('alt')).css('visibility', 'hidden').appendTo($img.offsetParent())
				var tipd={width:$tip.outerWidth(), height:$tip.outerHeight()} //tip dimensions
				$tip.data("specs", {
					d: tipd,
					ocoords: [ncoords[0]-tipd.width/2+nd.width/2, ncoords[1]-tipd.height/2], //resting tip coords
					ncoords: [-tipd.width/2+od.width/2, -tipd.height] //tip coords to animate to
				})
				$img.data('$tip', $tip)
			}
		})

		$bubblewrap.mouseover(function(e){
			if (e.target.tagName=="IMG"){
				var $img=$(e.target), $tip=$img.data('$tip')
				var imgspecs=$img.data('specs')
				var ncoords=imgspecs.ncoords
				var od=imgspecs.od
				var nd=imgspecs.nd
				$img.stop().css('zIndex', 1000).animate({left:ncoords[0], top:ncoords[1], width:nd.width, height:nd.height}, setting.speed) //animate image
				if ($img.attr('alt')){
					var tipspecs=$tip.data("specs")
					$tip.css({zIndex:1000, visibility:'visible', left:-tipspecs.d.width/2+od.width/2, top:-tipspecs.d.height})
						.animate({left:ncoords[0]-tipspecs.d.width/2+nd.width/2, top:ncoords[1]-tipspecs.d.height}, setting.speed) //animate tip			
				}
			}
		})
	
		$bubblewrap.mouseout(function(e){
			if (e.target.tagName=="IMG"){
				var $img=$(e.target), $tip=$img.data('$tip')
				var imgspecs=$img.data('specs')
				var od=imgspecs.od
				var nd=imgspecs.nd
				$img.stop().css('zIndex', 999).animate({left:0, top:0, width:od.width, height:od.height}, setting.speed) //animate image
				if ($img.attr('alt')){
					var tipspecs=$tip.data("specs")
					$tip.css({zIndex:999, visibility:'hidden'}) //hide tip
				}
			}
		})
	
	})
}