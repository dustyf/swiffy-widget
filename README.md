Swiffy Widget
=============

Add a widget to your WordPress widget area that contains a Swiffy converted Flash file.

Currently this plugin requires Advanced Custom Fields. I may make my own fields if I feel especially ambitious one day, but this met my needs at the time.

# Adding an Animation

1. Add the widget to your widget area.
1. Add a title or keep it blak to display no title.
1. Go to the page where the animation will be located. This is where the fields will display.
1. Open the Swiffy generated HTML file.
1. Copy the "swiffyobject =" and the following code to a new .js file and save.
1. Upload this file on the page
1. Set your other settings including height, width, max & min height, and max & min width.
1. You can set a fallback image that will display if the browser does not support SVG.

#  Changing Where the Fields Appear

I have by default set the fields to appear on the page that I want the animation on, since this met my needs for the project.  I may change this to be more flexible when I decide to expand this plugin.  You can, however, change where the fields display by editing the ACF generated code.  You can add to or edit the Locations section of the array or generate new code and export it from ACF replacing the Locations section. Be careful not to replace the fields, though, or be sure to name them the same as I did to be sure they still work.