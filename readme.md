# Grid Columns

A `[column]` shortcode for creating columnized content.

This plugin has one function and one function only &mdash; to make columns.  You use it by inputting content between `[column]` and `[/column]` within your post content editor (or anywhere shortcodes are allowed).

Grid Columns was created to fix the problem in which many theme developers were adding 20+ column shortcodes to their themes for something that should be extremely simple and done with only a single shortcode.

## Why the plugin was created

Many theme developers add several column shortcodes to their themes (note: this isn't allowed on WordPress.org).  Essentially, the theme developers are doing a few things wrong:

* They lock users into using their themes forever.
* They create 20+ shortcodes for what is possible for one.  This makes it look like their themes have more "features".
* They remove core WordPress filters that other plugins rely on.
* The code is just poorly developed altogether (most likely because they all copied from the same, bad source).

This plugin allows you to switch between any theme (no lock-in to your current theme).  It was also developed with WordPress standards and usability in mind.

## Uage

You can find more detailed instructions in the plugin's `docs/readme.html` file.

Everything is based on a grid.  By default, this grid is "4".  So, you can set up four columns like so:

	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]
	
	[column grid="4" span="1"]Some content[/column]

You'll notice that each "span" is equal to the number of columns in the grid.  So, if the span is "1", it's equal to one column.  If the span is "2", it's equal to two columns.  You can only have as many spans/columns as the grid allows.  Therefore, `grid="4"` means you can only have four columns.

## Changelog

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

## Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/support), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 40,000+ users (and growing).

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2012&thinsp;&ndash;&thinsp;2013 &copy; [Justin Tadlock](http://justintadlock.com).