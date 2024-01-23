# CMC Video Player #
**Contributors:** [thewebist](https://profiles.wordpress.org/thewebist/)  
**Requires at least:** 4.5  
**Tested up to:** 6.4.2  
**Requires PHP:** 8.0  
**Stable tag:** 1.3.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Implements Cloud Media Company's video player on your WordPress site.

## Description ##

This plugin provides an admin screen, found under "Settings > CMC Player", for adding Cloud Media Company's player script. Once you've added the player, you may use the following shortcode to display the player:

`[cmcplayer/]` - displays the CMC Player saved in the "CMC Player Script" field below. Available attributes:

- `show_on_desktop` (bool) - Show on desktop? Default TRUE.
- `show_on_mobile` (bool) - Show on mobile? Default TRUE.
- `excludes` (string) - Comma separated list of Post IDs and/or keywords for not showing the player. Available keywords: front_page, archive.
- `player_id` (mixed) - Include the player ID to specify a player other than the stored player in the settings. Default FALSE.
- `qortex` (bool) - Include the Qortex ad banner tag? Default FALSE.
- `meta`  (bool) - Show meta information below the player? Default FALSE.

Example: `[cmcplayer show_on_desktop="true" show_on_mobile="false"/]`

## Changelog ##

### 1.3.0 ###
* Adding `meta` attribute to allow display of meta information below the player. Only displays for logged in users with capability of `activate_plugins`.

### 1.2.2 ###
* BUGFIX: Returning `$player` when `player_id` attribute is set.

### 1.2.1 ###
* Updating documentation on Settings page.

### 1.2.0 ###
* Adding `player_id` and `qortex` attributes for showing different players based on the `player_id`.

### 1.1.1 ###
* Correcting variables passed in the Plugin Update API.

### 1.1.0 ###
* Adding an "excludes" attribute to the `[cmcplayer/]` shortcode. It allows for a comma separated list of Post IDs and/or keywords for not showing the player. Available keywords: front_page, archive.

### 1.0.0 ###
* Initial release.
