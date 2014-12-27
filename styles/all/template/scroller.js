/*****
* scroller.js
* Variable speed vertical scrolling box script by livewirestu
* An alternative to using marquee elements which are not W3C compliant. 
* Scroll speed varies with position of mouse over the element
* Usage:
* <div class="scroll_outer" id="uniqueId1" onmouseover="do_speed(this, event)" onmousemove="do_speed(this, event)" onmouseout="set_defaults(this, event)"><div class="scroll_inner" id="uniqueId2">
* Note: do not seperate the two divs because firefox (and maybe other browsers) treat the white space as an element
* thus screwing up the childNode / parentNode references
* This script is maintained @ phpbbireland.com & stargate-portal.com (livewirestu/mike)
*
* Last undated: 15 August 2011 Mike
* + Increased dead_band for a little more control...
* + Corrected for coding standards (wrapped if, else etc., in {...}
*
*****/

var scroll_controllers = new Array();
var default_speed = 1;
var default_dir = "down";
var dead_band = 19;

function init_scrollers()
{
	var allDivs = new Array();
	var thisDiv, scroll_div_count = 0;
	var box_height = 160;

	allDivs = document.getElementsByTagName("DIV");

	for(var i = 0; i < allDivs.length; i++)
	{
	    thisDiv = allDivs[i];
	    if(thisDiv.className == "scroll_inner")
	    {
		    scroll_controllers[scroll_div_count] = new scroller_obj(default_speed, default_dir, thisDiv.id);

			if(parseInt(thisDiv.style.height) < box_height)
			{
                thisDiv.parentNode.style.height = parseInt(thisDiv.offsetHeight) + "px";
			}
            else
			{
                thisDiv.parentNode.style.height = box_height + "px";
			}
            scroll_div_count += 1;

            if(thisDiv.parentNode.parentNode.className == "box")
            {
               thisDiv.parentNode.parentNode.style.paddingTop = "0px";
               thisDiv.parentNode.parentNode.style.paddingBottom = "0px";
			}
    	}
	}
	
	setInterval("scroll()",30);
}

function scroller_obj(_step_size, _scroll_dir, _id)
{
	this.step_size = _step_size;
	this.scroll_dir = _scroll_dir;
	this.scroll_id = _id;
}

function scroll()
{
	var allDivs, thisDiv;

	for(var i = 0; i < scroll_controllers.length; i++)
	{
		thisDiv = document.getElementById(scroll_controllers[i].scroll_id);
	    									
		var inner_top = parseInt(thisDiv.style.top);
		var inner_height = parseInt(thisDiv.offsetHeight);
		var inner_bottom = inner_top + inner_height;
		var outer_height = parseInt(thisDiv.parentNode.offsetHeight);
		
		if(isNaN(inner_top))
		{
			inner_top = 0;
		}
							
		if(scroll_controllers[i].scroll_dir == "down")
		{						
			if(inner_top < inner_height * -1)
			{
				thisDiv.style.top = outer_height + "px";
			}
			else
			{
				thisDiv.style.top = inner_top - scroll_controllers[i].step_size + "px";
			}
		}
		else
		{
			if(inner_top > outer_height)
			{
				thisDiv.style.top = outer_height - inner_bottom -1 + "px";	
			}
			else
			{
				thisDiv.style.top = inner_top + scroll_controllers[i].step_size + "px";
			}
		}
	}
}

function do_speed(obj, event)
{
	var cursor_y = 0;
	if (event.pageY) // Browser compatibility check
	{
		cursor_y = event.pageY;
	}
    else
	{
        cursor_y = event.clientY + (document.documentElement.scrollTop || document.body.scrollTop) - document.documentElement.clientTop;
	}
    
   	mid = (obj.offsetHeight / 2) + obj.offsetTop;    
    
    if(cursor_y > mid)
    {
	    if(cursor_y < mid + dead_band)
		{
	    	speed = 0;
		}
        else
		{
        	speed = (cursor_y - mid - dead_band);
		}
	    scroll_direction = "down";
    }
    else
    {
        if(cursor_y > mid - dead_band)
		{
        	speed = 0;
		}
        else
		{
        	speed = (mid - dead_band - cursor_y);
		}
	    scroll_direction = "up";
    }

    // Normalise speed to 0-15 for any element height
    speed = parseInt(14 * (speed / obj.offsetHeight) * 2);
    step_size = speed;

	for(i = 0; i < scroll_controllers.length; i++)
	{
		if(scroll_controllers[i].scroll_id == obj.firstChild.id)
		{
			scroll_controllers[i].step_size = step_size;
			scroll_controllers[i].scroll_dir = scroll_direction;
			break;
		}
	}
}

function set_defaults(obj, event)
{
	for(i = 0; i < scroll_controllers.length; i++)
	{
		if(scroll_controllers[i].scroll_id == obj.firstChild.id)
		{
			scroll_controllers[i].step_size = default_speed;
			scroll_controllers[i].scroll_dir = default_dir;
			break;
		}
	}				
}
