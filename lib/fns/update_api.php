<?php
namespace CMCPLayer\updates;

/**
 * Fetches remote data from our Plugin Update Server.
 *
 * @return     object  The remote data.
 */
function fetch_remote_data() {
  $remoteData = wp_remote_get(
    CMCPLAYER_PLUGIN_CHECK_EP,
    [
      'timeout' => 10,
      'headers' => [
        'Accept' => 'application/json'
      ]
    ]
  );

  // Something went wrong!
  if (
    is_wp_error($remoteData) ||
    wp_remote_retrieve_response_code($remoteData) !== 200
  ) {
    return null;
  }

  $remoteData = json_decode( wp_remote_retrieve_body( $remoteData ) );

  $readme_path = CMCPLAYER_PATH . 'readme.txt';
  $default_headers = [
    'Plugin Name' => 'CMC Video Player',
  ];
  $readme_data = get_file_data( $readme_path, $default_headers );

  //*
  return (object) [
      'name'          => $readme_data['Plugin Name'],
      'slug'          => CMCPLAYER_PLUGIN_SLUG,
      'plugin'        => CMCPLAYER_PLUGIN_FILE,
      'new_version'   => $remoteData->version,  // <-- Important!
      'url'           => 'https://wenmarkdigital.com',
      'package'       => $remoteData->package,  // <-- Important!
      'tested'        => '',
      'requires_php'  => '',
      'compatibility' => new \stdClass(),
      'banners'       => [
        'low'   => 'https://cmc-video-player.wenmarkdigital.com/assets/banner-772x250.jpg',
        'high'  => 'https://cmc-video-player.wenmarkdigital.com/assets/banner-1544x500.jpg',
      ],
      'icons'         => [
        'low'   => 'https://cmc-video-player.wenmarkdigital.com/assets/icon-256x256.jpg',
        'high'  => 'https://cmc-video-player.wenmarkdigital.com/assets/icon-256x256.jpg',
      ],
  ];
  /**/
}

/**
 * Filters the `plugins_api` information for our plugin.
 *
 * @param      stdClass  $res     The resource
 * @param      <type>    $action  The action
 * @param      <type>    $args    The arguments
 *
 * @return     stdClass  The standard class.
 */
function filter_plugin_info( $res, $action, $args ){
  // do nothing if you're not getting plugin information right now
  if( 'plugin_information' !== $action ) {
    return $res;
  }

  // do nothing if it is not our plugin
  if( CMCPLAYER_PLUGIN_SLUG !== $args->slug ) {
    return $res;
  }

  $remoteData = fetch_remote_data();
  if( ! $remoteData ) {
    return $res;
  }

  $res = new \stdClass();

  $res->name = $remoteData->name;
  $res->slug = $remoteData->slug;
  $res->version = $remoteData->new_version;
  //$res->tested = $remoteData->tested;
  //$res->requires = $remoteData->requires;
  //$res->author = $remoteData->author;
  //$res->author_profile = $remoteData->author_profile;
  //$res->download_link = $remoteData->download_url;
  //$res->trunk = $remoteData->download_url;
  //$res->requires_php = $remoteData->requires_php;
  //$res->last_updated = $remoteData->last_updated;

  $res->sections = array(
    'description' => 'Adds the CMC Video Player to your site.',
    'installation' => null,
    'changelog' => '<strong>0.0.0</strong><ul><li>Changlog coming soon.</li></ul>'
  );

  if( ! empty( $remoteData->banners ) ) {
    $res->banners = array(
      'low' => $remoteData->banners['low'],
    );
  }

  return $res;
}
add_filter( 'plugins_api', __NAMESPACE__ . '\\filter_plugin_info', 20, 3 );

/**
 * Checks to see if our plugin has an update available.
 *
 * @param      object  $update_plugins  The update plugins object
 *
 * @return     object  The filtered update plugins object
 */
function filter_update_plugins( $update_plugins ){

  if( ! is_object( $update_plugins ) )
    return $update_plugins;

  if( ! isset( $update_plugins->response ) || ! is_array( $update_plugins->response ) )
    $update_plugins->response = [];

  $remoteData = get_transient( CMCPLAYER_PLUGIN_CHECK_TRANSIENT_NAME );
  if( false === $remoteData ){
    $remoteData = fetch_remote_data();
    set_transient( CMCPLAYER_PLUGIN_CHECK_TRANSIENT_NAME, $remoteData, CMCPLAYER_PLUGIN_CHECK_EXPIRATION );
  }

  if( ! function_exists( 'get_plugin_data' ) )
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');

  $currentPluginData = get_plugin_data( CMCPLAYER_PLUGIN_FULL_FILENAME );

  $res = new \stdClass();
  if( $remoteData ){
    $res->slug = CMCPLAYER_PLUGIN_SLUG;
    $res->plugin = CMCPLAYER_PLUGIN_FILE;
    $res->new_version = $remoteData->new_version;
    $res->url = 'https://wenmarkdigital.com/sellers-json-wordpress-plugin';
    $res->package = $remoteData->package;
  }

  if( $remoteData && version_compare( $remoteData->new_version, $currentPluginData['Version'], '>' ) ){
    //uber_log('👉 Plugin needs an update! ' . $currentPluginData['Version'] . ' => ' . $remoteData->new_version );
    $update_plugins->response[ CMCPLAYER_PLUGIN_FILE ] = $res;
  } else {
    //uber_log('🛑 Plugin DOES NOT need an update.');
    $update_plugins->no_update[ CMCPLAYER_PLUGIN_FILE ] = $res;
  }

  return $update_plugins;
}
add_filter( 'site_transient_update_plugins', __NAMESPACE__ . '\\filter_update_plugins' );