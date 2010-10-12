<?php
/*      iGallery for RavenNuke(tm): /blocks/block-iGallery_Random.php
 *      Copyright 2009 Jonathan Estrella <jestrella04@gmail.com>
 * 		Join me at http://slaytanic.sourceforge.net
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

defined('BLOCK_FILE') or header('Location: ../index.php');

global $db, $prefix;
$showPic = 2; // Number of pics to display in the block
$blockModuleName = 'iGallery'; // Update this line if you have renamed the module folder

// You should not edit anything after this line

defined('IN_IGM') or define('IN_IGM', TRUE);
define('IN_IGB', $blockModuleName);
get_lang($blockModuleName);
include_once(NUKE_BASE_DIR.'modules/'.$blockModuleName.'/includes/settings.php');
include_once(NUKE_BASE_DIR.'modules/'.$blockModuleName.'/includes/functions.php');

$content = '<center>'.PHP_EOL;

$result = $db->sql_query('SELECT *, (SELECT count(comment_pictureid) FROM '.$prefix.'_igallery_comments WHERE comment_pictureid=picture_id) AS m_total FROM '.$prefix.'_igallery_pictures ORDER BY rand() LIMIT 0,'.$showPic.' ;');
while($picture = $db->sql_fetchrow($result)) {
	$pictureId = intval($picture['picture_id']);
	$albumId = intval($picture['album_id']);
	$title = $picture['picture_title'];
	$counter = intval($picture['picture_counter']);
	$totalRates = intval($picture['picture_rating']);
	$totalVotes = intval($picture['picture_votes']);
	$totalComments = intval($picture['m_total']);
	$date = $picture['picture_date'];
	$popCount = $iConfig['pop_count'];
	$content.= '	'.showNewEmblem($date) . showPopEmblem($counter, $popCount) . PHP_EOL;
	$thumbSrc = 'modules.php?name='.$blockModuleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;
	$content.= '	<a href="modules.php?name='.$blockModuleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'"><img style="max-width: 90%; border: 0px none;" src="'.$thumbSrc.'" title="'.$title.'" alt="" /></a>'.PHP_EOL;
	//if($showDetails) {
		$content.= '<br /><a href="modules.php?name='.$blockModuleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'">'.$title.'</a><br />'.PHP_EOL;
		if ($totalRates!==0 && $totalVotes!==0) {
			$content.= '	'.showRating($totalRates/$totalVotes).'<br />'.PHP_EOL;
			//echo '	<b>'._IG_TOTALVOTES.'</b>: '.$totalVotes.'<br />'.PHP_EOL;
		} else {
			$content.= '	'.showRating(0).'<br />'.PHP_EOL;
		}
		$content.= '	<b>'.$counter.'</b> '._IG_HITS.'<br />'.PHP_EOL;
		$content.= '	<b>'.$totalComments.'</b> '._IG_TOTALCOMMENTS.'<br /><br />'.PHP_EOL;
	//}
}
$content.= '</center>'.PHP_EOL;
?>