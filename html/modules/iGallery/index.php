<?php
/*	iGallery for RavenCMS(tm): /index.php
 *	Copyright 2009 - 2013 Jonathan Estrella <jonathan@exuberaza.net>
 * 	Join me at www.exuberanza.net
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation; either version 2 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *	MA 02110-1301, USA.
 */

/* Block direct access to file */
defined( 'MODULE_FILE' ) OR die( 'You can\'t access this file directly...' );

/* Require main library & set working name of the module */
require_once( 'mainfile.php' );
$moduleName = basename( dirname( __FILE__ ) );

/* Get language translations for your language*/
get_lang( $moduleName );

/* Setting to show/hide right blocks */
//define( 'INDEX_FILE', true); // Uncomment to show right blocks
if ( defined( 'INDEX_FILE' ) ) { $index = 1; } // You don't have to change this line

/* Define iGallery is requesting info & include secondary libraries */
define( 'IN_IGM', TRUE );
require_once( 'modules/'. $moduleName .'/includes/settings.php' );
include_once( 'modules/'. $moduleName .'/includes/functions.php' );

/* Add the main iGallery CSS file to head */
//Trying to move on to Bootstrap
//AddCSSToHead( 'modules/'. $moduleName .'/includes/style.css', 'file' );

/* Get actual size of the thumbnails and add it to CSS
 * to avoid some positioning issues with tooltips */
$thumbSize = ( isset( $iConfig['thumb_autosize'] ) ) ? $iConfig['thumb_autosize'] : 150;
//AddCSSToHead( '<style type="text/css">.sub-album,.sub-pic,.thumb-target{max-width:'. ( $thumbSize + 10 ) .'px;}</style>','inline' );

/* Set some variables widely used all over the module */
$op = ( isset( $_GET['op'] ) ) ? $_GET['op'] : 'galIndex';
$pag = ( isset( $_GET['pag'] ) ) ? $_GET['pag'] : 1;
$ofsppg = $iConfig['show_columns'] * $iConfig['show_rows'];
$ofsbgn = ( $pag * $ofsppg ) - $ofsppg;

/* Do some URL formatting */
switch( $op) {
	case 'addPics': addPics(); break;
	case 'addPicsSimple': addPicsSimple(); break;
	case 'commentPost': commentPost(); break;
	case 'galIndex': galIndex(); break;
	case 'getImg': getImg(); break;
	case 'getThumb': getThumb(); break;
	case 'ratePic': ratePic(); break;
	case 'recentMoves': recentMoves(); break;
	case 'showAlbum': showAlbum(); break;
	case 'showPic': showPic(); break;
	case 'showSlide': showSlide(); break;
	case 'topPics': topPics(); break;
	case 'uploadMove': uploadMove(); break;
    case 'uploadMoveAjax': uploadMove( $ajaxForm = 1 ); break;
}

/* This is the gallery index */
function galIndex() {
	global $moduleName, $iConfig;
	$tooltipTheme = $iConfig['tooltip_theme'];
	$inlineJS = '<script type="text/javascript">
      $j = jQuery.noConflict();
      $j(document).ready(function(){
        $j(".thumb-target").tooltip();
      });
    </script>' . PHP_EOL;
    AddCSSToHead( 'modules/'. $moduleName .'/includes/jquery/jquery.tooltip/themes/'. $tooltipTheme .'/jquery.tooltip.css','file' );
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	AddJSToHead( 'modules/'. $moduleName .'/includes/jquery/jquery.tooltip.js','file' );
	AddJSToHead( $inlineJS, 'inline' );
	galleryHeader();
	showSubAlbum();
	galleryFooter();
}

/* This function's used to show and format photo albums in iGallery */
function showAlbum() {
	global $db, $prefix, $moduleName, $iConfig, $ofsppg, $ofsbgn;
	$tooltipTheme = $iConfig['tooltip_theme'];
	$inlineJS = '<script type="text/javascript">
      $j = jQuery.noConflict();
      $j(document).ready(function(){
        $j(".thumb-target").tooltip();
      });
    </script>' . PHP_EOL;
    //AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip/themes/'.$tooltipTheme.'/jquery.tooltip.css','file' );
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	//AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip.js','file' );
	//AddJSToHead( $inlineJS, 'inline' );

	$albumId = ( isset( $_GET['albumid'] ) ) ? $_GET['albumid'] : 0;
	$showDetails = intval( $iConfig['show_details'] );
	$showColumns = intval( $iConfig['show_columns'] ); // Better be responsive aware, consider dropping
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsFormat = strtolower( $iConfig['thumbs_format'] );
	$albumInfo = $db->sql_fetchrow( $db->sql_query( 'SELECT album_folder, album_desc FROM '. $prefix .'_igallery_albums WHERE album_id='. $albumId .'' ) );
	$folderName = $albumInfo['album_folder'];
	$albumDesc = $albumInfo['album_desc'];

	galleryHeader();
	echo '<div id="show-album-wrapper">' . PHP_EOL;
	showTopLinks( $albumId );
	if ( ! empty( $albumDesc ) ) {
		echo '<div class="content-box">'.$albumDesc.'</div>' . PHP_EOL;
	}

	$sql ='SELECT node.album_id, (COUNT(parent.album_id) - (sub_tree.depth + 1) ) AS depth
	FROM '. $prefix .'_igallery_albums AS node,
	'. $prefix .'_igallery_albums AS parent,
	'. $prefix .'_igallery_albums AS sub_parent,
	(
		SELECT node.album_id, (COUNT(parent.album_id) - 1) AS depth
		FROM '. $prefix .'_igallery_albums AS node,
		'. $prefix .'_igallery_albums AS parent
		WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
		AND node.album_id = \''. $albumId .'\'
		GROUP BY node.album_id
		ORDER BY node.album_left
	) AS sub_tree
	WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
		AND node.album_left BETWEEN sub_parent.album_left AND sub_parent.album_right
		AND sub_parent.album_id = sub_tree.album_id
	GROUP BY node.album_id
	HAVING depth = 1
	ORDER BY node.album_left;';
	$albumCount = $db->sql_numrows( $db->sql_query( $sql ) );
	if ( $albumCount > 0 ) {
		//echo '<center><b>'._IG_SUBALBUMS.'</b></center><br />';
		showSubAlbum( $albumId );
		echo '<hr />' . PHP_EOL;
	}

	$totalItems = $db->sql_numrows( $db->sql_query( 'SELECT picture_id FROM '. $prefix .'_igallery_pictures WHERE album_id='.$albumId.'') );
	if ( $totalItems > 0 ) {
		showSubPics( $albumId );
		paginationSystem( $ofsbgn, $ofsppg, $totalItems );
	}
	echo '</div>' . PHP_EOL;
	galleryFooter();
}

/* Shows requested image and its information */
function showPic() {
	global $db, $prefix, $iConfig, $moduleName, $user;
	$picturesPath = $iConfig['pictures_path'];
	$popCount = intval( $iConfig['pop_count'] );
	//$inlineJS = '';
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.MetaData.js','file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.rating.js','file' );
	AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.rating.css','file' );
	//AddJSToHead( $inlineJS,'inline' );
	galleryHeader();
	echo '<div id="show-picture-wrapper">' . PHP_EOL;
	$pictureId = $_GET['pictureid'];
	$picture = $db->sql_fetchrow( $db->sql_query( 'SELECT * FROM '. $prefix .'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 0,1') );
	$filename = $picture['picture_file'];
	$albumId = $picture['album_id'];
	$pictureTitle = $picture['picture_title'];
	$pictureType = $picture['picture_type'];
	$description = $picture['picture_desc'];
	$counter = $picture['picture_counter'];
	$dateAdded = $picture['picture_date'];
	$userId = $picture['picture_userid'];
	$album = $db->sql_fetchrow( $db->sql_query( 'SELECT album_title, album_folder FROM '. $prefix .'_igallery_albums WHERE album_id=\''.$albumId.'\' LIMIT 0,1') );
	$albumTitle = $album['album_title'];
	$folderName = $album['album_folder'];
	showTopLinks( $albumId, $pictureId); echo '<br /><br />' . PHP_EOL;
	picTopMenu( $pictureId, $albumId);
	echo '<div id="show-pic">' . PHP_EOL;
	echo '	'.showNewEmblem( $dateAdded) . showPopEmblem( $counter, $popCount).'<br />' . PHP_EOL;
	echo '	<img class="show-pic" src="modules.php?name='.$moduleName.'&amp;op=getImg&amp;pictureid='.$pictureId.'" alt="'.$filename.'" title="'.$pictureTitle.'" />' . PHP_EOL;
	echo '</div>' . PHP_EOL;
	echo '<div class="content-box"><b>'.$pictureTitle.'</b>: '.$description.'</div>' . PHP_EOL;
	showDetails( $pictureId);
	echo '<br />' . PHP_EOL;
	echo '<div id="comment-button"><button>'._IG_POSTCOMMENT.'</button></div>' . PHP_EOL;
	commentForm( $pictureId);
	echo '<script type="text/javascript">//<![CDATA[' . PHP_EOL;
	echo '	$("button").click(function () {' . PHP_EOL;
	echo '		$("#post-comment").show("slow");' . PHP_EOL;
	echo '	});' . PHP_EOL;
	echo '//]]></script>' . PHP_EOL;
	displayComments( $pictureId);
	echo '</div>' . PHP_EOL;
	galleryFooter();
}

/* Still not finished, this will be used to display photo slideshow of the requested album */
function showSlide() {
	global $db, $prefix, $moduleName, $iConfig, $ThemeSel;
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsFormat = strtolower( $iConfig['thumbs_format'] );
	$albumId = intval( $_GET['albumid'] );
	$picturesPath = $iConfig['pictures_path'];
	AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.nivo.slider.css','file' );
	AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.nivo.custom.slider.css','file' );
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.nivo.slider.pack.js','file' );
	galleryHeader();
	showTopLinks( $albumId);

	$album = $db->sql_fetchrow( $db->sql_query( 'SELECT album_title, album_folder FROM '. $prefix .'_igallery_albums WHERE album_id=\''.$albumId.'\' LIMIT 0,1') );
	$albumTitle = $album['album_title'];
	$folderName = $album['album_folder'];
	echo '<div id="slider">' . PHP_EOL;
	$result=$db->sql_query( 'SELECT picture_id, picture_title, picture_desc, picture_file FROM '. $prefix .'_igallery_pictures WHERE album_id=\''.$albumId.'\'' );
	while( $row=$db->sql_fetchrow( $result) ) {
		$pictureId=$row['picture_id'];
		$pictureTitle=$row['picture_title'];
		$pictureDesc=$row['picture_desc'];
		$pictureFile=$row['picture_file'];
		$imgSrc = 'modules.php?name='.$moduleName.'&amp;op=getImg&amp;pictureid='.$pictureId;
		$thumbSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;
		echo '	<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'">' . PHP_EOL;
		echo '		<img class="slider-img" src="'.$imgSrc.'" alt="'.$pictureTitle.'" title="'.$pictureDesc.'" />' . PHP_EOL;
		echo '	</a>' . PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	echo '<script type="text/javascript">' . PHP_EOL;
	echo '$(window).load(function() {' . PHP_EOL;
	echo '	$(\'#slider\').nivoSlider({' . PHP_EOL;
	echo '		animSpeed:800,' . PHP_EOL;
	echo '		pauseTime:5000,' . PHP_EOL;
	echo '	});' . PHP_EOL;
	echo '});' . PHP_EOL;
	echo '</script>' . PHP_EOL;
	CloseTable();
	galleryFooter();
}

/* Display the most popular, best rated and most commented images */
function topPics() {
	global $moduleName, $iConfig;
	$tooltipTheme = $iConfig['tooltip_theme'];
	$inlineJS = '<script type="text/javascript">
      $j = jQuery.noConflict();
      $j(document).ready(function(){
        $j(".thumb-target").tooltip();
      });
    </script>' . PHP_EOL;
    AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip/themes/'.$tooltipTheme.'/jquery.tooltip.css','file' );
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip.js','file' );
	AddJSToHead( $inlineJS, 'inline' );
	galleryHeader();
	echo '<h2 class="top-pics">'._IG_MOSTPOPPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Popular' );
	echo '<h2 class="top-pics">'._IG_BESTRATEDPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Rated' );
	echo '<h2 class="top-pics">'._IG_MOSTCOMMPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Commented' );
	galleryFooter();
}

/* Displays most recent, recently rated and recently commented images */
function recentMoves() {
	global $moduleName, $iConfig;
	$tooltipTheme = $iConfig['tooltip_theme'];
	$inlineJS = '<script type="text/javascript">
      $j = jQuery.noConflict();
      $j(document).ready(function(){
        $j(".thumb-target").tooltip();
      });
    </script>' . PHP_EOL;
    AddCSSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip/themes/'.$tooltipTheme.'/jquery.tooltip.css','file' );
	AddJSToHead( 'includes/jquery/jquery.js','file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.tooltip.js','file' );
	AddJSToHead( $inlineJS, 'inline' );
	galleryHeader();
	echo '<h2 class="top-pics">'._IG_LATESTPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Popular','Recent' );
	echo '<h2 class="top-pics">'._IG_RECENTLYRATEDPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Rated','Recent' );
	echo '<h2 class="top-pics">'._IG_RECENTLYCOMMPICS.'</h2>' . PHP_EOL;
	showTopPics( 'Commented','Recent' );
	galleryFooter();
}

/* Allow users to add pictures to the gallery */
function addPics() {
	global $moduleName, $iConfig;
	//$uploadPath = $iConfig['upload_path'];
	//if ((substr( $uploadPath, -1) ) == '/') { $uploadPath = substr( $uploadPath, 0, -1); }
	$inlineJS = '<script type="text/javascript">//<![CDATA[' . PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {' . PHP_EOL;
	$inlineJS.= '		var options = {' . PHP_EOL;
	$inlineJS.= '			target:	\'#upload-alert\',' . PHP_EOL;
	$inlineJS.= '			url: \'modules.php?name='.$moduleName.'&op=uploadMoveAjax\',' . PHP_EOL;
	$inlineJS.= '			success: function() {' . PHP_EOL;
	$inlineJS.= '				$(\'input:file\').MultiFile(\'reset\' );' . PHP_EOL;
	$inlineJS.= '				$(\'#upload-alert\').fadeIn(\'slow\' );' . PHP_EOL;
	$inlineJS.= '				$(\'#upload-file-list\').empty();' . PHP_EOL;
	$inlineJS.= '			}' . PHP_EOL;
	$inlineJS.= '		};' . PHP_EOL;
	$inlineJS.= '	$(\'#upload-form\').ajaxForm(options);' . PHP_EOL;
	$inlineJS.= '});' . PHP_EOL;
	$inlineJS.= '//]]></script>' . PHP_EOL;
	AddJSToHead( 'includes/jquery/jquery.js', 'file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.form.js', 'file' );
	AddJSToHead( 'modules/'.$moduleName.'/includes/jquery/jquery.MultiFile.pack.js', 'file' );
	AddJSToHead( $inlineJS,'inline' );
	galleryHeader();
	uploadDiv();
	galleryFooter();
}
?>