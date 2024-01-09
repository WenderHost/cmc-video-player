<?php

namespace CMCPlayer\settings;

function enqueue_scripts( $hook ) {
  if ( 'settings_page_cmc-video-player' != $hook )
    return;
  wp_enqueue_style( 'cmc_video_player_admin_css', CMCPLAYER_URL . '/lib/css/admin.css', null, filemtime( CMCPLAYER_PATH . '/lib/css/admin.css' ) );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );


function menu_option() {
  add_options_page('CMC Player Settings', 'CMC Player', 'manage_options', 'cmc-video-player', __NAMESPACE__ . '\\settings_page');
}
add_action('admin_menu', __NAMESPACE__ . '\\menu_option');

function settings_page() {
  // Check user capabilities
  if ( ! current_user_can( 'manage_options' ) )
    return;

  // Check if the user has submitted the settings
  // WordPress will add the "settings-updated" $_GET parameter to the url
  if ( isset( $_GET['settings-updated'] ) ) {
    // Add settings saved message with the class of "updated"
    add_settings_error( 'cmc_video_player_messages', 'cmc_video_player_message', 'Settings Saved', 'updated' );
  }

  // Show error/update messages
  //settings_errors('cmc_video_player_messages');
  ?>
  <div class="wrap">
      <h1><?= esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
          <?php
          // Output security fields for the registered setting "cmc_video_player"
          settings_fields('cmc_video_player');
          // Output setting sections and their fields
          do_settings_sections('cmc-video-player');
          // Output save settings button
          submit_button('Save Settings');
          ?>
      </form>
  </div>
  <?php
}

function settings_init() {
    register_setting('cmc_video_player', 'cmc_player_script');

    add_settings_section(
        'cmc_video_player_section_developers',
        __( 'Player Shortcode and Script', 'cmc_video_player' ),
        __NAMESPACE__ . '\\cmc_video_player_section_developers_cb',
        'cmc-video-player'
    );

    add_settings_field(
        'cmc_video_player_field_pill', // As of WP 4.6 this value is used only internally
        __('CMC Player Script', 'cmc_video_player'),
        __NAMESPACE__ . '\\cmc_video_player_field_pill_cb',
        'cmc-video-player',
        'cmc_video_player_section_developers',
        [
            'label_for' => 'cmc_video_player_field_pill',
            'class' => 'cmc_video_player_row',
            'cmc_video_player_custom_data' => 'custom',
        ]
    );
}
add_action('admin_init', __NAMESPACE__ . '\\settings_init');

function cmc_video_player_section_developers_cb($args) {
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= __('<p>Add the player script for this site in the field below and click Save Settings. Once saved, you may insert the player on the site using the following shortcode:</p> <p><code>[cmcplayer/]</code> - displays the CMC Player saved in the "CMC Player Script" field below. Available attributes:</p><ul style="list-style-type: disc; margin-left: 2em;"><li><code>show_on_desktop</code> (bool) - Show on desktop? Default: <code>true</code></li><li><code>show_on_mobile</code> (bool) - Show on mobile? Default: <code>true</code></li></ul><p>Example: <code>[cmcplayer show_on_desktop="true" show_on_mobile="false"/]</code></p>', 'cmc_video_player'); ?></p>
    <?php
}

function cmc_video_player_field_pill_cb($args) {
    $options = get_option('cmc_player_script');
    ?>
    <textarea id="<?= esc_attr($args['label_for']); ?>"
      style="width: 100%; height: 120px;"
      data-custom="<?= esc_attr($args['cmc_video_player_custom_data']); ?>"
      name="cmc_player_script"><?= esc_html($options); ?></textarea>
    <?php
}
