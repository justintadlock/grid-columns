=== Grid Columns ===
Contributors: greenshady
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3687060
Tags: shortcode
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 0.1

A [column] shortcode that allows users to create columnized content.

== Description ==

*Grid Columns* was created to fix the problem in which many theme developers were adding 20+ column shortcodes to their themes for something that should be extremely simple and done with only a single shortcode.

This plugin has one function and one function only &mdash; to make columns.  You use it by inputting content between `[column]` and `[/column]` within your post content editor (or anywhere shortcodes are allowed).  You can view usage examples within the `docs/readme.html` file.

Support for this plugin is handled on the Theme Hybrid <a href="http://themehybrid.com/support">support forums</a>.

== Installation ==

1. Uzip the `grid-columns.zip` folder.
1. Upload the `grid-columns` folder to your `/wp-content/plugins` directory.
1. In your WordPress dashboard, head over to the *Plugins* section.
1. Activate *Grid Columns*.

== Frequently Asked Questions ==

= Why was this plugin created? =

Many theme developers add several column shortcodes to their themes (note: this isn't allowed on WordPress.org).  Essentially, the theme developers are doing a few things wrong:

* They lock users into using their themes forever.
* They create 20+ shortcodes for what is possible for one.  This makes it look like their themes have more "features".
* They remove core WordPress filters that other plugins rely on.
* The code is just poorly developed altogether (most likely because they all copied from the same, bad source).

This plugin allows you to switch between any theme (no lock-in to your current theme).  It was also developed with WordPress standards and usability in mind.

= How do I use the [column] shortcode? =

You can find more detailed instructions in the plugin's `docs/readme.html` file.

Everything is based on a grid.  By default, this grid is "4".  So, you can set up four columns like so:

`
[column grid="4" span="1"]Some content[/column]

[column grid="4" span="1"]Some content[/column]

[column grid="4" span="1"]Some content[/column]

[column grid="4" span="1"]Some content[/column]
`

You'll notice that each "span" is equal to the number of columns in the grid.  So, if the span is "1", it's equal to one column.  If the span is "2", it's equal to two columns.  You can only have as many spans/columns as the grid allows.  Therefore, `grid="4"` means you can only have four columns.

= Can I get more detailed instructions? =

If you need a more detailed guide, see `readme.html`, which is included with the plugin.  It has a few examples and explains everything.

== Changelog ==

**Version 0.1**

* Plugin released.  Everything is new!