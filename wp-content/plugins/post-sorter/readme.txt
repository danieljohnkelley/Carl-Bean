=== Post Sorter ===
Contributors: rolice
Donate link: http://rolice.intellisys.info/en-US
Tags: sort, sorting, order, ordering, position, custom sort, custom sorting, custom order, custom ordering, post, page, posts, pages
Requires at least: 3.2
Tested up to: 3.7.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin for easily sorting of posts or pages by numeric factor or custom mechanism.  

== Description ==

Plugin for easy sorting of posts or pages (both called *posts* below) by user-selected integer numeric factor. The plugin allows easily to switch direction or to 
enable/disable it. After activation you will be able to manage post position in a loop directly by the new column - Sort or when adding/editing post to fulfill the 
desired number in the new metabox, that will appear for you - *Post Sorter*.

There are two arrows in the admin post list, which could be used for fast switching of posts positions, i.e. if you click up it will set post order *numeric factor* with 1 more or less (depending on sorting direction) than the one above.

In the options page of the plugin you will see several basic settings, whether to enable it and the sorting direction - ascending or descending.
If the plugin is enabled it will sort the posts on the front end for you by the numeric values you have previously entered, in the direction selected on the plugin options.

Keep in mind that you could pick any number you like, so if you left some **free range** between posts you could manage to achieve an order that would satisfy your needs even 
in more than one view of post lists (loops) - for example: *Post1* => 10, *Post2* => 20, *Post3* => 30, etc. This way also, when you need to insert post between **Post1** and 
**Post2** you could set the new one with any numeric factor value between 11 and 19.

There is also another option to separate a group of posts, but to maintain their default sorting within the group - simply give the group same numeric factor for sorting, 
example:

**Post 1** => 10
**Post 2** => 20
**Post 3** => 20
**Post 4** => 30

Depending on the sorting direction the first and the last post will be **Post 1** or **Post 4** and in the middle we will have both, **Post 2** and **Post 3** sorted by date 
of creation (by default). Then if **Post 3** is newer than **Post 2** it will be up in both cases of sorting direction.

With the use of the expert settings and knowledge in SQL you could implement custom sorting. With such sorting the arrows will disappear, since they are no longer applicable.

Access management is now available. You can authorize specific roles to use the plugin from its section - submenu **Permissions**. By default, only administrator would be allowed to operate with the plugin.

== Installation ==

1. Upload the extracted `post_sorter` folder to the `/wp-content/plugins/` directory (for manual setup)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure it from the newly appeared `Post Sorter` administration menu
1. Enjoy using it :)

== Frequently Asked Questions ==

= How it actually works? =

The plugin would replace the default sorting of posts (by newest to oldest) to numeric value sorting - *numeric factor*. It also allows its user to select the direction,
from lesser numeric value to greater or oppositve.

= Do I have to make code changes or to insert code in the template? =

No. It will be done automatically for your by the plugin.

== Screenshots ==

1. View of the plugin options page in the admin. See the newly added menu on the bottom left, in the navigator.
2. View of post list in the administration. We have a new column for easy sorting.
3. The screen of post edition - you could see the **metabox added with caption *Post Sorter***.
4. A screenshot of updated Post Sorter settings.
5. Permissions screen where you can authorize roles to operate with Post Sorter.

== Changelog ==

= 1.4 =
Access to plugin funcionality is now granted with permissions. These permissions are given to roles from the new settings screen.
Optimized and refactored code added.

= 1.3.1 =
* Escaping quotes with backslashes in SQL clauses fixed.

= 1.3 =
* Fixed a bug where custom post types disappear from the list. This problem was due to INNER join, but not OUTER.
* New option for custom post types in the settings. With it post sorter will know which custom post types it has to handle (it handles them already, but some functionality needs explicit telling).

= 1.2 =
* Fixed a ordering bug where the postmeta.meta_value is sorted as longtext, but not as unsigned int.
* Improvements of sorting.
* Up and down arrows for quick sorting now can be turned on or off from the options.
* New proxy filters added: **post_sorter_join** and **post_sorter_order**. External plugins may cooperate with this plugin using them.
* Expert settings added where the user may implement in SQL custom sorting (ordering).
* Several fixes and cleanup.
* Method order_by renamed to order_by_at_requst.
* Method order_by_front renamed to order_by.
* Method order_by_at_request is depricated.

= 1.0 =
* Brought it to the world.

== Upgrade Notice ==

= 1.0 =
Basic functionality delivered in this package.

== Arbitrary section ==

We are open for any ideas that would improve the plugin.