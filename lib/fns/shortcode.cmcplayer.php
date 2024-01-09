<?php

namespace CMCPLayer\shortcode;

function cmc_video_player( $atts ){
  $args = shortcode_atts([
    'show_on_desktop' => true,
    'show_on_mobile'  => true,
  ], $atts );

  $show_player = true;

  if ( $args['show_on_desktop'] === 'false' ) $args['show_on_desktop'] = false;
  $args['show_on_desktop'] = (bool) $args['show_on_desktop'];

  if ( $args['show_on_mobile'] === 'false' ) $args['show_on_mobile'] = false;
  $args['show_on_mobile'] = (bool) $args['show_on_mobile'];

  if( wp_is_mobile() ){
    $show_player = ( $args['show_on_mobile'] )? true : false ;
  } else if( ! wp_is_mobile() ){
    $show_player = ( $args['show_on_desktop'] )? true : false ;
  }

  $player = get_option( 'cmc_player_script' );

  if( $show_player ){
    return $player;
  } else {
    return '<!-- CMC Player hidden by attributes on the shortcode. -->';
  }
}
add_shortcode( 'cmcplayer', __NAMESPACE__ . '\\cmc_video_player' );
