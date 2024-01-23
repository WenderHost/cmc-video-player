<?php

namespace CMCPLayer\shortcode;

/**
 * Implements the `[cmcplayer/]` shortcode for displaying the CMC Video Player.
 *
 * @param      array  $atts {
 *   @type  bool   $show_on_desktop  Are we displaying on desktop? Default TRUE.
 *   @type  bool   $show_on_mobile   Are we displaying on mobile? Default TRUE.
 *   @type  str    $excludes         Comma separated list of Post IDs and/or keywords for not showing the player. Available keywords: front_page, archive.
 *   @type  mixed  $player_id        Include the player ID to specify a player other than the stored player in the settings. Default FALSE.
 *   @type  bool   $qortex           Include the Qortex ad banner tag? Default FALSE.
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
    'player_id'       => false,
    'qortex'          => false,
  ], $atts );

  $show_player = true;

  if ( $args['show_on_desktop'] === 'false' ) $args['show_on_desktop'] = false;
  $args['show_on_desktop'] = (bool) $args['show_on_desktop'];

  if ( $args['show_on_mobile'] === 'false' ) $args['show_on_mobile'] = false;
  $args['show_on_mobile'] = (bool) $args['show_on_mobile'];

  if ( $args['player_id'] === 'false' ) $args['player_id'] = false;
  $args['player_id'] = $args['player_id'];

  if ( $args['qortex'] === 'false' ) $args['qortex'] = false;
  $args['qortex'] = (bool) $args['qortex'];

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

  if( $show_player && $args['player_id'] ){
    $player = "\n<!-- START CMC Player -->\n";
    if( $args['qortex'] )
      $player.= "<script src=\"https://tags.qortex.ai/bootstrapper?group-id=oZY4NHogUywbXAxaxq6w&video-container=AV{$args['player_id']}\" defer></script>\n";
    $player.= "<script async id=\"AV{$args['player_id']}\" type=\"text/javascript\" src=\"https://tg1.aniview.com/api/adserver/spt?AV_TAGID={$args['player_id']}&AV_PUBLISHERID=624e25402d2a7c268c34f1d8\"></script>\n<!-- END CMC Player -->\n";
    return $player;
  } else if( $show_player ) {
    $player = get_option( 'cmc_player_script' );
    return $player;
  } else {
    return '<!-- CMC Player hidden by attributes on the shortcode. -->';
  }
}
add_shortcode( 'cmcplayer', __NAMESPACE__ . '\\cmc_video_player' );
