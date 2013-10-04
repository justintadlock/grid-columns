=== Grid Columns ===

Contributors: greenshady
Donate link: http://themehybrid.com/donate
Tags: shortcode
Requires at least: 3.4
Tested up to: 3.7
License: GPLv2 or later
Stable tag: 0.2.0

A [column] shortcode for creating columnized content.

== Description ==

This plugin has one function and one function only &mdash; to make columns.  You use it by inputting content between `[column]` and `[/column]` within your post content editor (or anywhere shortcodes are allowed).

Grid Columns was created to fix the problem in which many theme developers were adding 20+ column shortcodes to their themes for something that should be extremely simple and done with only a single shortcode.

### Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/support), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 40,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/grid-columns). 

### Donations

Yes, I do accept donations.  If you want to buy me a beer or whatever, you can do so from my [donations page](http://themehybrid.com/donate).  I appreciate all donations, no matter the size.  Further development of this plugin is not contingent on donations, but they are always a nice incentive.

== Installation ==

1. Uzip the `grid-columns.zip` folder.
2. Upload the `grid-columns` folder to your `/wp-content/plugins` directory.
3. In your WordPress dashboard, head over to the *Plugins* section.
4. Activate *Grid Columns*.

== Frequently Asked Questions ==

### Why was this plugin created?

Many theme developers add several column shortcodes to their themes (note: this isn't allowed on WordPress.org).  Essentially, the theme developers are doing a few things wrong:

* They lock users into using their themes forever.
* They create 20+ shortcodes for what is possible for one.  This makes it look like their themes have more "features".
* They remove core WordPress filters that other plugins rely on.
* The code is just poorly developed altogether (most likely because they all copied from the same, bad source).

This plugin allows you to switch between any theme (no lock-in to your current theme).  It was also developed with WordPress standards and usability in mind.

### How do I use the [column] shortcode?

You can find more detailed instructions in the plugin's `docs/readme.html` file.

Everything is based on a grid.  By default, this grid is "4".  So, you can set up four columns like so:

	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]

You'll notice that each "span" is equal to the number of columns in the grid.  So, if the span is "1", it's equal to one column.  If the span is "2", it's equal to two columns.  You can only have as many spans/columns as the grid allows.  Therefore, `grid="4"` means you can only have four columns.

### Can I get more detailed instructions?

If you need a more detailed guide, see `readme.html`, which is included with the plugin.  It has a few examples and explains everything.

### Help!  My site's broken!  What should I do?

Most likely, it's because you either have too many spans or not enough spans for your grid.  Make sure each `span` argument for your `[column]` shortcode equals exactly the `grid` argument.

### But, I did everything right.

If you're absolutely sure you're math is correct, it could be a conflict with your theme.  It'd be impossible for me to know without seeing it in use on your site, so you'll either need to ask on my support forums or get your theme developer to help.

### Can I have more than one set of grid columns in a post?

Yes.  Absolutely.  Just make sure each grid has the correct number of columns before starting a new one.

### Can I nest columns?

No.  This is a limitation of how WordPress handles shortcodes.

### Can I use other shortcodes within the `[column]` shortcode?

Yes, you can.  However, keep in mind, that I can't guarantee that your plugin developer knows what he's doing and created his shortcode correctly.  But, yes, you can do this with properly-coded shortcodes.

### Can I put content between two different column shortcodes?

It's possible, but you'll probably break something.  I recommend against attempting this.

== Changelog ==

### Version 0.2.0

* Added CSS style rules to override some issues with WordPress' `wpautop()`, which sometimes adds empty `<p>` and extra `<br />` tags.
* Added support for right-to-left languages.
* No more anonymous objects created by the plugin class.
* No need for `&` when adding an action/filter.

### Version 0.1.1

* Add more specific prefixes in the CSS.
* Add some better margin handling in case other CSS code is overwriting things willy-nilly.
* Add the `gc_column_content` filter hook and use it to apply formatting.

### Version 0.1.0

* Plugin released.  Everything is new!