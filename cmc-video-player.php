<?php
/**
 * Plugin Name:     CMC Video Player
 * Plugin URI:      https://github.com/WenderHost/cmc-video-player
 * Description:     Plugin for adding Cloud Media Center's video player to your WordPress site. Provides the <code>[cmcplayer/]</code> shortcode.
 * Author:          Michael Wender
 * Author URI:      https://mwender.com
 * Text Domain:     cmc-video-player
 * Domain Path:     /languages
 * Version:         1.2.1
 *
 * @package         Cmc_Video_Player
 */

define( 'CMCPLAYER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CMCPLAYER_URL', plugin_dir_url( __FILE__ ) );
define( 'CMCPLAYER_PLUGIN_CHECK_EP', 'https://cmc-video-player.wenmarkdigital.com/update.php' );
define( 'CMCPLAYER_PLUGIN_CHECK_EXPIRATION', 7200 );
define( 'CMCPLAYER_PLUGIN_CHECK_TRANSIENT_NAME', 'cmcplayer_plugin_update' );
define( 'CMCPLAYER_PLUGIN_FULL_FILENAME', __FILE__ );
define( 'CMCPLAYER_PLUGIN_SLUG', plugin_basename( __DIR__ ) );
define( 'CMCPLAYER_PLUGIN_FILE', plugin_basename( __FILE__ ) );

// Include required files
require_once( CMCPLAYER_PATH . 'lib/fns/update_api.php' );
require_once( CMCPLAYER_PATH . 'lib/fns/settings.php' );
require_once( CMCPLAYER_PATH . 'lib/fns/shortcode.cmcplayer.php' );