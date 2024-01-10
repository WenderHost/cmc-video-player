<?php

namespace CMCPLayer\shortcode;

/**
 * Implements the `[cmcplayer/]` shortcode for displaying the CMC Video Player.
 *
 * @param      array  $atts {
 *   @type  bool  $show_on_desktop  Are we displaying on desktop? Default TRUE.
 *   @type  bool  $show_on_mobile   Are we displaying on mobile? Default TRUE.
 *   @type  str   $excludes         Comma separated list of Post IDs and/or keywords for not showing the player. Available keywords: front_page, archive.
 * }
 *
 * @return     string  CMC Player script
 */
function cmc_video_player( $atts ){
  global $post;

  $args = shortcode_atts([
    'show_on_desktop' => true,
    'show_on_mobile'  => true,
    'excludes'        => null,
  ], $atts );

  $show_player = true;

  if ( $args['show_on_desktop'] === 'false' ) $args['show_on_desktop'] = false;
  $args['show_on_desktop'] = (bool) $args['show_on_desktop'];

  if ( $args['show_on_mobile'] === 'false' ) $args['show_on_mobile'] = false;
  $args['show_on_mobile'] = (bool) $args['show_on_mobile'];

  $excludes = false;
  if( ! is_null( $args['excludes'] ) )
    $excludes = ( stristr( $args['excludes'], ',' ) )? explode( ',', $args['excludes'] ) : [ $args['excludes'] ] ;

  if( wp_is_mobile() ){
    $show_player = ( $args['show_on_mobile'] )? true : false ;
  } else if( ! wp_is_mobile() ){
    $show_player = ( $args['show_on_desktop'] )? true : false ;
  }

  if( $excludes && is_array( $excludes ) ){
    foreach( $excludes as $exclude_id ){
      if( is_numeric( $exclude_id ) && $exclude_id == $post->ID ){
        $show_player = false;
      } elseif( 'front_page' == $exclude_id && is_front_page() ){
        $show_player = false;
      } elseif( 'archive' == $exclude_id && is_archive() ){
        $show_player = false;
      }
    }
  }

  $player = get_option( 'cmc_player_script' );

  if( $show_player ){
    return $player;
  } else {
    return '<!-- CMC Player hidden by attributes on the shortcode. -->';
  }
}
add_shortcode( 'cmcplayer', __NAMESPACE__ . '\\cmc_video_player' );
