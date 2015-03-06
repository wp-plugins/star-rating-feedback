=== Star Rating Feedback ===
Contributors: Stephen Scott & Somme Sakounthong 
Donate link:
Tags: UK Feedback Form, Feedback form, feedback, UK feedback, anonymous feedback, star rating, star rating feedback, star icons, comments
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 0.2

This plugin allows you to add a Star Rating feedback form. Currently it is localised for use in the UK
 

== Description ==

Simple star rating plugin to allow anonymous feedback to be added to your Wordpress. Within the admin side users can view the feedback and export to excel. Currently it is localised for use in the UK.


Any feedback or suggestions are welcome.


== Installation ==
Simple steps:  

1.  Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation.
2.  Then activate the Plugin from Plugins page.
3.  Add the shortcode [feedback_form] to any page or text widget you would like the form to appear
4.  Done!

== Options ==  

You can specify options in your short code:


Amount of stars
-------------------

amount="value" 

Maximum is 10

example. [feedback_form amount="4"]


Type of icon
-------------------

type="value"

Specify image or leave blank for icon

example. [feedback_form type="image"]


Other icons
-------------------

icon="value"

If you don't want to use a star.

Available icons:
star
thumbs
smile
heart
circle

example. [feedback_form icon="thumbs"]

Color
-------------------

color="value" 
color_on="value"

For icon only

example. [feedback_form color="black" coloron="yellow"] or hex value [feedback_form color="#000000" coloron="#ffff00" ]

Single colors
-------------------

colors="value"

Individual colors. Seperated each color by commas. These colours must match the same amount you set.

example. [feedback_form amount="5" colors="red,yellow,pink,blue,purple" ]

Size
-------------------

size="value" 

Specify size in px, %, em, rem etc.  (for icon only)

example. [feedback_form size="40px"]


Set question text
-------------------

question="value" 

example. [feedback_form size="What do you think or our site?"]


Set message text after form submission
--------------------------------------

message="value" 


example. [feedback_form message="Your feedback has been sent"]


Set comments heading
---------------------

comments="value" 

example. [feedback_form comments="Tell us your comment"]


Set custom image
-----------------

staron="value" 
staroff="value" 

example. [feedback_form staron="image-on.png" staroff="image-off.png"]

Both images should be the same height and width.
Images should be be stored in wp_content/plugins/star-rating-feedback/images/


Hints / Tooltip
---------------

hints="value" 

Set custom hints. Seperated each hint by commas.

example. [feedback_form hints="bad, better, average, good, excellent"]


== Samples ==

Using this shortcode [feedback_form color="red" color_on="blue" size="40px" question="What do you think?" message="Your feedback has been sent" comments="Comments are optional"]

will produce:
To do: add screen shot

Using this shortcode  [feedback_form type="image" question="What do you think?" message="Your feedback has been sent" comments="Comments are optional"]

will produce:
To do: add screen shot


=What are the requirements?=

PHP 5.2 and up.

=I have Found a Bug, Now what?=

Simply use the <a href=\"http://wordpress.org/support/plugin/star-rating-feedback\">Support Forum</a> and thanks ahead for doing that.
== Screenshots ==

<img src="https://raw.githubusercontent.com/somme/wordpress-star-rating-plugin/master/star-rating-feedback/images/screen1.png" />
<img src="https://raw.githubusercontent.com/somme/wordpress-star-rating-plugin/master/star-rating-feedback/images/screen2.png" />
<img src="https://raw.githubusercontent.com/somme/wordpress-star-rating-plugin/master/star-rating-feedback/images/screen3.png" />

== Changelog ==

1.1 to do
0.1 initial release.

== Known bugs ==
It will submit twice if 2 instances are on the same page. ie on the side bar and in a post/page.
Stars within admin screen does not know the setting in the shortcode therefore we can't display e.g 3 / 5. The next version will store the number of stars in the DB.