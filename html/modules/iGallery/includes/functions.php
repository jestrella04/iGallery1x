<?php
/*	iGallery for RavenNuke: /includes/functions.php
 *	Copyright 2009 - 2010 Jonathan Estrella <jestrella04@gmail.com>
 * 	Join me at http://slaytanic.sourceforge.net
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

/* Check if this file is being requested within iGallery */
defined('IN_IGM') OR die('You can\'t access this file...');

/* Displays and format gallery header and top links */
function galleryHeader() {
	global $moduleName, $iConfig, $admin, $admin_file;
	$galleryTitle = $iConfig['igallery_title'];
	include('header.php');
	OpenTable();
	echo '<div id="gal-header">'.PHP_EOL;
	echo '	<h2>'.$galleryTitle.'</h2>'.PHP_EOL;
	echo '	<a href="modules.php?name='.$moduleName.'">'._IG_GALLHOME.'</a> |'.PHP_EOL;
	//if($_GET['op']==='showAlbum') { echo '	<a href="modules.php?name='.$moduleName.'&amp;op=showSlide&amp;albumid='.$_GET['albumid'].'">'._IG_GALLSLIDE.'</a> |'.PHP_EOL; }
	if(is_admin($admin)) { echo '	<a href="'.$admin_file.'.php?op=galAdmin">'._IG_GALLADMIN.'</a> |'.PHP_EOL; }
	echo '	<a href="modules.php?name='.$moduleName.'&amp;op=topPics">'._IG_GALLTOP.'</a> |'.PHP_EOL;
	echo '	<a href="modules.php?name='.$moduleName.'&amp;op=recentMoves">'._IG_RECENTMOVES.'</a> |'.PHP_EOL;
	echo '	<a href="modules.php?name='.$moduleName.'&amp;op=addPics">'._IG_ADDMEDIA.'</a>'.PHP_EOL;
	echo '	<hr />'.PHP_EOL;
	echo '</div>'.PHP_EOL;
}

/* Displays the gallery footer */
function galleryFooter() {
	CloseTable();
	include NUKE_BASE_DIR.'footer.php';
}

/* Convert a given unix timestamp to its equivalent in time elapsed since that date */
function dateToDays($startDate) {
	//$startDate = strtotime($startDate);
	$endDate = time();
	$diff = $endDate-$startDate;
	$seconds = 0;
	$hours   = 0;
	$minutes = 0;

	if($diff % 86400 <= 0){$days = $diff / 86400;}  // 86,400 seconds in a day
	if($diff % 86400 > 0) {
		$rest = ($diff % 86400);
		$days = ($diff - $rest) / 86400;
		if($rest % 3600 > 0) {
			$rest1 = ($rest % 3600);
			$hours = ($rest - $rest1) / 3600;
			if($rest1 % 60 > 0) {
				$rest2 = ($rest1 % 60);
				$minutes = ($rest1 - $rest2) / 60;
				$seconds = $rest2;
			} else {
				$minutes = $rest1 / 60;
			}
		} else {
			$hours = $rest / 3600;
		}
	}

	if($days > 0){ $days = $days.' '._IG_DAYS; return $days; }
	if($hours > 0){ $hours = $hours.' '._IG_HOURS; return $hours; }
	if($minutes > 0){ $minutes = $minutes.' '._IG_MINUTES; return $minutes; }
	if($seconds) { $seconds = $seconds.' '._IG_SECONDS; return $seconds; } // always be at least one second
}

/* Displays a simpe star rating (no javascript) */
function showRating($rating) {
	global $moduleName;
	if(defined('IN_IGB')) { $moduleName = IN_IGB; }
	$showRating = number_format($rating, 2, '.', '');
	$rating = round($rating);
	$image = '<img class="star-rating" src="modules/'.$moduleName.'/images/full.png" alt="'._IG_SCORE.': '.$showRating.'" title="'._IG_SCORE.': '.$showRating.'" />';
	$null = '<img class="star-rating" src="modules/'.$moduleName.'/images/null.png" alt="'._IG_SCORE.': '.$showRating.'" title="'._IG_SCORE.': '.$showRating.'" />';
	$return = '';

	if ($rating==5) {
		for ($i=0; $i < 5; $i++) {$return.= $image;}
	} elseif ($rating==4) {
		for ($i=0; $i < 4; $i++) {$return.= $image;}
		for ($ii=0; $ii < 1; $ii++) {$return.= $null;}
	} elseif ($rating==3) {
		for ($i=0; $i < 3; $i++) {$return.= $image;}
		for ($ii=0; $ii < 2; $ii++) {$return.= $null;}
	} elseif ($rating==2) {
		for ($i=0; $i < 2; $i++) {$return.= $image;}
		for ($ii=0; $ii < 3; $ii++) {$return.= $null;}
	} elseif ($rating==1) {
		for ($i=0; $i < 1; $i++) {$return.= $image;}
		for ($ii=0; $ii < 4; $ii++) {$return.= $null;}
	} elseif ($rating==0) {
		for ($i=0; $i < 5; $i++) {$return.= $null;}
	}

	return $return;
}

/* Determine if the NEW emblem have to be displayed */
function showNewEmblem($startDate) {
	global $moduleName;
	if(!empty($startDate)) {
		if(defined('IN_IGB')) { $moduleName = IN_IGB; }
		//$startDate = strtotime($date);
		$todayDate = time();
		$offset = $todayDate - $startDate;
		$daysOld = floor($offset/60/60/24);

		if ($daysOld<=1) {
			return '<img class="show-new" src="modules/'.$moduleName.'/images/new_red.png" alt="" title="'._IG_NEWTODAY.'" />';
		} elseif($daysOld<=3 && $daysOld>1) {
			return '<img class="show-new" src="modules/'.$moduleName.'/images/new_green.png" alt="" title="'._IG_NEWRECENT.'" />';
		} elseif($daysOld<=7 && $daysOld>3) {
			return '<img class="show-new" src="modules/'.$moduleName.'/images/new_yellow.png" alt="" title="'._IG_NEWTHISWEEK.'" />';
		}
	} else {
		return false;
	}
}

/* Determine if the POP emblem have to be displayed */
function showPopEmblem($counter) {
	global $iConfig, $moduleName, $db, $prefix;
	if(defined('IN_IGB')) {
		$moduleName = IN_IGB;
		$iConfig = $db->sql_fetchrow($db->sql_query('SELECT pop_count FROM '.$prefix.'_igallery_settings LIMIT 1'));
		$popCount = $iConfig['pop_count'];
	} else {
		$popCount = $iConfig['pop_count'];
	}

	if($counter >= $popCount) {
		return '<img class="show-new" src="modules/'.$moduleName.'/images/popular.png" alt="" title="'._IG_POPULAR.'" />';
	}
}

/* Format date, deprecated in favor of dateToDays() function */
function iFormatDate($date) {
	return strftime(_IG_DATESTRING, $date);
}

/* Return human readable filesize of the given file */
function getSize($file) {
	$size = filesize($file);
	$b = (int)$size;
	$s = array('B', 'kB', 'MB', 'GB', 'TB');
	if($b < 0){
		return "0 ".$s[0];
	}
	$con = 1024;
	$e = (int)(log($b,$con));
	return number_format($b/pow($con,$e),2,',','.').' '.$s[$e];
}

/* On request, this function generates thumbnails of the gallery pictures */
function createThumb($moduleName, $folderName, $picture, $type=0) {
	global $iConfig;
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsAutoSize = intval($iConfig['thumbs_autosize']);
	$thumbsQuality = intval($iConfig['thumbs_quality']);
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	$picturesPath = $iConfig['pictures_path'];
	$uploadPath = $iConfig['upload_path'];
	$return = '';

	$info = pathinfo($picture);
	$rawPicture = str_replace($info['extension'], '', $picture);

	if($type) {
		$thumbsRealPath = iPath($uploadPath.'thumbs/');
		$picturesRealPath = iPath($uploadPath);
		$folderName = '';
	} else {
		$thumbsRealPath = iPath($thumbsPath);
		$picturesRealPath = iPath($picturesPath);
	}

	// Create album thumbnail directory if not exists
	if(!file_exists($thumbsRealPath.$folderName)) {
		$create = @mkdir($thumbsRealPath.$folderName);
		if(!$create) { $return.= _IG_THUMBDIRFAILED.' <b>'.$folderName.'</b>'; }
	}

	// Generate thumbnail
	if(!file_exists($thumbsRealPath.$folderName.'/'.$rawPicture.$thumbsFormat)) {
		require_once NUKE_BASE_DIR.'modules/'.$moduleName.'/classes/phpthumb/ThumbLib.inc.php';
		try {
			$thumb = PhpThumbFactory::create($picturesRealPath.$folderName.'/'.$picture);
		}
		catch (Exception $e) {
			$return.= 'ERROR: '.$e->getMessage().PHP_EOL;
		}

		$outputPath = $thumbsRealPath.$folderName.'/'.$rawPicture.$thumbsFormat;
		$thumb->adaptiveResize($thumbsAutoSize, $thumbsAutoSize)->save($outputPath,$thumbsFormat);
	}

	return $return;
}

/* Recursively scan album directories
 * Original PHP code by Chirp Internet: www.chirp.com.au
 */
function scanAlbums($dir, $recursive=true) {
	// array to hold return value
	$returnValue = array();
	// add trailing slash if missing
	if(substr($dir, -1) != '/') $dir.= '/';
	// open pointer to directory and read list of files
	$d = @dir($dir) or die();
	while(false !== ($entry = $d->read())) {
		// skip hidden files
		if($entry[0] == ".") continue;
		if(is_dir($dir.$entry)) {
			$returnValue[] = $dir.$entry.'/';
			if($recursive && is_readable($dir.$entry.'/')) {
				$returnValue = array_merge($returnValue, scanAlbums($dir.$entry.'/', true));
			}
		}
	}
	$d->close();
	return array_unique($returnValue);
}

/* Get the list of all subalbums (and related info) under the specified one */
function getAlbumList($root=1) {
	global $db, $prefix;
	$return = array();

	// start with an empty $right stack
	$right = array();

	// now, retrieve all descendants of the $root node
	$sql = 'SELECT node.*
FROM '.$prefix.'_igallery_albums AS node,
'.$prefix.'_igallery_albums AS parent
WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
AND parent.album_id = \''.$root.'\'
ORDER BY node.album_left;';
	$result = $db->sql_query($sql);

	// get album info from each row
	while ($row = $db->sql_fetchrow($result)) {
		$albumId = $row['album_id'];
		$albumTitle = $row['album_title'];
		$albumRight = $row['album_right'];
		$albumActive = $row['album_active'];
		$albumDesc = $row['album_desc'];
		$albumFolder = $row['album_folder'];
		$albumCover = $row['album_cover'];
		$albumDate = $row['album_date'];
		// only check stack if there is one
		if (count($right)>0) {
			// check if we should remove a node from the stack
			while ($right[count($right)-1]<$albumRight) {
				array_pop($right);
			}
		}
		// display indented node title
		$return[] = array(
					'id' => $albumId,
					'title' => $albumTitle,
					'indent' => count($right),
					'active' => $albumActive,
					'descr' => $albumDesc,
					'folder' => $albumFolder,
					'cover' => $albumCover,
					'date' => $albumDate,
					);
		// add this node to the stack
		$right[] = $albumRight;
	}

	return $return;
}

/* Generates a indented dropdown list of all albums */
function getAlbumsDropdown($selected='') {
	$selected = intval($selected);
	$albumList = getAlbumList();
	echo '		<select name="album_parent">'.PHP_EOL;
	foreach ($albumList as $list) {
		$albumId = $list['id'];
		$albumTitle = $list['title'];
		$albumIndent = $list['indent'];
		if($albumTitle!=='Root') {
			if($albumId==$selected) {
				echo '			<option value="'.$albumId.'" selected="selected">'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$albumIndent) .'&mdash; '. $albumTitle.'</option>'.PHP_EOL;
			} else {
				echo '			<option value="'.$albumId.'">'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$albumIndent) .'&mdash; '. $albumTitle.'</option>'.PHP_EOL;
			}
		}
	}
	echo '		</select>'.PHP_EOL;
}

/* get the left and right nodes of the given album using the nested set model */
function getNodes($id=1) {
	global $db, $prefix;
	$id = intval($id);
	$nodes = $db->sql_fetchrow($db->sql_query('SELECT album_left,album_right FROM '.$prefix.'_igallery_albums WHERE album_id=\''.$id.'\' LIMIT 0,1'));
	return array('id' => $id, 'left' => $nodes['album_left'], 'right' => $nodes['album_right']);
}

/* When adding a new node, all nodes to its right would have their left and right values increased by two */
function updateNodes($id=1) {
	global $db, $prefix;
	$id = intval($id);
	$continue = false;
	$sqlError = '';

	// Get nodes info
	$nodes = getNodes($id);
	$right = $nodes['right'];

	// Lock tables
	$result = $db->sql_query('LOCK TABLES '.$prefix.'_igallery_albums WRITE');

	// Update right values
	$sql = 'UPDATE '.$prefix.'_igallery_albums SET album_right=album_right+2 WHERE album_right > '. ($right - 1) .' ;';
	if($result = $db->sql_query($sql)) { $continue = true; } else { $continue = false; $sqlError.= mysql_error(); }

	// Update left values
	if($continue) {
		$sql = 'UPDATE '.$prefix.'_igallery_albums SET album_left=album_left+2 WHERE album_left > '. ($right - 1) .' ;';
		if($result = $db->sql_query($sql)) { $continue = true; } else { $continue = false; $sqlError.= mysql_error(); }
	}
	/*if($continue) {
		// UPDATE RIGHT AND LEFT VALUES OF THE CURRENT ALBUM
		$sql = 'UPDATE '.$prefix.'_igallery_albums SET album_left='.$nodes['album_left'].'+2, album_right='.$nodes['album_right'].'+2 WHERE album_id = '.$id.' LIMIT 1 ;';
		if($result = $db->sql_query($sql)) { $continue = true; } else { $continue = false; }
	}*/

	// Unlock tables
	$result = $db->sql_query('UNLOCK TABLES');

	if($continue) { return array($right,$sqlError); }
}

/* Gets the parent album */
function getParentNode($node) {
	global $db, $prefix;
	$node = intval($node);
	$sql = 'SELECT album_id,
(SELECT album_id FROM '.$prefix.'_igallery_albums AS node2
WHERE node2.album_left  < node1.album_left
AND node2.album_right > node1.album_right
ORDER BY node2.album_right - node1.album_right ASC LIMIT 0,1) AS parent
FROM '.$prefix.'_igallery_albums AS node1
WHERE node1.album_id = '.$node.'
ORDER BY album_right - album_left DESC;';
	$parentNode = $db->sql_fetchrow($db->sql_query($sql));
	if ($parentNode) return $parentNode['parent'];
}

/* Scan all album directories to find new pictures and subalbums
 * and automatically add them to database
 */
function quickUpdate() {
	global $db, $prefix, $iConfig, $moduleName, $user, $cookie, $admin, $aid;

	if(is_user($user)) { $submitter = $cookie[0]; } elseif(is_admin($admin)) { $submitter = substr($aid, 0, 25); }
	$picturesPath = $iConfig['pictures_path'];
	$thumbsPath = $iConfig['thumbs_path'];
	$picturesRealPath = iPath($picturesPath);
	$thumbsRealPath = iPath($thumbsPath);
	$albumList = scanAlbums($picturesRealPath);

	foreach($albumList as $albums) {
		$albumRelativePath = substr(str_replace($picturesRealPath, '', $albums), 0, -1);
		$albumName = basename($albums);
		$parentPath = substr($albumRelativePath, 0, strlen($albumRelativePath) - 1);
		$parentPath = substr($parentPath, 0, strrpos($parentPath, '/'));
		$exists = $db->sql_numrows($db->sql_query('SELECT album_id FROM '.$prefix.'_igallery_albums WHERE album_folder=\''.$albumRelativePath.'\''));
		if(!$exists) {
			if(!empty($parentPath)) {
				$parentId = $db->sql_fetchrow($db->sql_query('SELECT album_id FROM '.$prefix.'_igallery_albums WHERE album_folder=\''.$parentPath.'\' LIMIT 0,1'));
			} else {
				$parentId = 1;
			}
			$right = updateNodes($parentId);

			if($right[0]) {
				// Insert new category
				$sql = 'INSERT INTO '.$prefix.'_igallery_albums values (\'\', \''.$right[0].'\', \''.($right[0] +1).'\', \'0\', \''.$albumName.'\', \'\', \''.$albumRelativePath.'\', \'\', \''.time().'\') ;';
				if($result = $db->sql_query($sql)) {
					if(!file_exists($thumbsRealPath.$albumRelativePath)) { @mkdir($thumbsRealPath.$albumRelativePath); }
					echo _IG_ADDEDALBUM.' <b>'.$albumName.'</b>... '.$albumRelativePath.'/<br />'.PHP_EOL;
				}
			}
		}
	}
	echo '<hr />'.PHP_EOL;

	$row = $db->sql_query('SELECT album_id, album_folder FROM '.$prefix.'_igallery_albums;');
	while($result = $db->sql_fetchrow($row)) {
		$albumId = $result['album_id'];
		$folderName = $result['album_folder'];
		if (is_dir($picturesRealPath.$folderName)) {
			if ($dir2 = opendir($picturesRealPath.$folderName)) {
				while (($picture = readdir($dir2)) !== false) {
					if(preg_match('#^(.*)\.('.allowedExt('preg').')$#i', $picture) && is_file($picturesRealPath.$folderName.'/'.$picture) && $picture!=="." && $picture!=="..") {
						$current = $db->sql_numrows($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE picture_file=\''.$picture.'\' AND album_id=\''.$albumId.'\''));
						if($current==0 && $picture!=='.' && $picture!=='..' && !is_dir($picture)) {
							$result = $db->sql_query('INSERT INTO '.$prefix.'_igallery_pictures VALUES(NULL, \''.$albumId.'\', \''.$picture.'\', \'\', \''.$picture.'\', 0, 0, \''.time().'\', \''.$submitter.'\', 0, 0, \'\')');
							if ($result) {
								echo _IG_ADDEDPIC.' <b>'.$folderName.'/'.$picture.'</b><br />'.PHP_EOL;
							} else {
								echo _IG_ADDEDPICFAIL.' <b>'.$folderName.'/'.$picture.'</b>... '.mysql_error().'<br />'.PHP_EOL;
							}
							createThumb($moduleName, $folderName, $picture);
						}
					}
				}
				closedir($dir2);
			}
		}
	}
	echo '<br /><center><b>'._IG_QUICKADDFINISHED.'</b></center><br />';
}

/* Show additional information of a picture
 * @param int $pictureId
 */
function showDetails($pictureId) {
	global $moduleName, $user, $cookie, $db, $prefix, $iConfig, $nukeurl;
	$picturesPath = $iConfig['pictures_path'];
	$uploadPath = $iConfig['upload_path'];
	$showDetails = $iConfig['show_details'];
	$data = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 1'));
	$albumId = $data['album_id'];
	$title = $data['picture_title'];
	$date = $data['picture_date'];
	$filename = $data['picture_file'];
	$type = $data['picture_type'];
	$counter = intval($data['picture_counter']);
	$totalRates = intval($data['picture_rating']);
	$totalVotes = intval($data['picture_votes']);
	if ($totalRates!==0 && $totalVotes!==0) {
		$rating = $totalRates/$totalVotes;
	} else {
		$rating = 0;
	}
	$totalComments = intval($db->sql_numrows($db->sql_query('SELECT comment_id FROM '.$prefix.'_igallery_comments WHERE comment_pictureid='.$pictureId.'')));

	$album = $db->sql_fetchrow($db->sql_query('SELECT album_folder FROM '.$prefix.'_igallery_albums WHERE album_id='.$albumId.' LIMIT 1'));
	$folderName = $album['album_folder'];
	if($type) {
		if(file_exists(iPath($uploadPath.$filename))) {
			$info = getimagesize(iPath($uploadPath.$filename));
			$imgSize = getSize(iPath($uploadPath.$filename));
		}
	} else {
		if(file_exists(iPath($picturesPath.$folderName.'/'.$filename))) {
			$info = getimagesize(iPath($picturesPath.$folderName.'/'.$filename));
			$imgSize = getSize(iPath($picturesPath.$folderName.'/'.$filename));
		}
	}

	echo '<div id="show-rating-share">'.PHP_EOL;
	echo '	<div id="show-rating">'.PHP_EOL;
	echo '		<form method="post" action="modules.php?name='.$moduleName.'&amp;op=ratePic&amp;pictureid='.$pictureId.'">'.PHP_EOL;
	echo '			<script type="text/javascript">'.PHP_EOL;
	echo '				$(function(){'.PHP_EOL;
	echo '					$(\'.submit-star\').rating({'.PHP_EOL;
	echo '						callback: function(value, link){'.PHP_EOL;
	echo '							// Submit the form automatically:'.PHP_EOL;
	echo '							this.form.submit();'.PHP_EOL;
	echo '							// Submit the form via ajax:'.PHP_EOL;
	echo '							//$(this.form).ajaxSubmit();'.PHP_EOL;
	echo '  					}'.PHP_EOL;
	echo '					});'.PHP_EOL;
	echo '				});'.PHP_EOL;
	echo '			</script>'.PHP_EOL;
	//echo '			<label class="star-rating">'._IG_RATING.': </label>'.PHP_EOL;
	$stitle[5] = _IG_EXCELLENT;
	$stitle[4] = _IG_VERYGOOD;
	$stitle[3] = _IG_GOOD;
	$stitle[2] = _IG_FAIR;
	$stitle[1] = _IG_POOR;
	if ($totalRates!==0 && $totalVotes!==0) {
		$roundedRating = round($totalRates/$totalVotes);
	} else {
		$roundedRating = 0;
	}

	for ($i=1; $i <= 5; $i++) {
		if($roundedRating==$i) {
			echo '			<input class="submit-star" type="radio" name="picture_rating" value="'.$i.'" title="'.$stitle[$i].'" checked="checked" />'.PHP_EOL;
		} else {
			echo '			<input class="submit-star" type="radio" name="picture_rating" value="'.$i.'" title="'.$stitle[$i].'" />'.PHP_EOL;
		}
	}
	echo '		</form><br />'.PHP_EOL;
	if ($totalRates!==0 && $totalVotes!==0 && $rating) {
		echo '	<div id="rating-total-votes">'.PHP_EOL;
		echo '		<b>'.number_format($rating, 2, '.', '').'</b> '._IG_WITH.' '.$totalVotes.' '._IG_TOTALVOTES.'.'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
	} else {
		echo '	<div id="rating-total-votes">'.PHP_EOL;
		echo '		<span>'._IG_NOT_RATED_YET.'</span>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
	}
	echo '	</div>'.PHP_EOL;
	echo '	<div id="show-share">'.PHP_EOL;
	$pageUrl = $nukeurl.'/modules.php?name='.$moduleName.'&pa=showPic&pictureid='.$pictureId;
	$bookmarkTitleAnchor = rawurlencode(utf8_encode($title));
	$BookmarkUrlAnchor = rawurlencode(utf8_encode($pageUrl));
	$bookmarkTitleJs = mysql_real_escape_string($title);
	$bookmarkUrlJs = str_replace('&amp;','&', $pageUrl);

	echo '		<b>'._IG_BOOKMARK.'</b><br />'.PHP_EOL;
	echo '		<a class="a2a_dd" onmouseover="a2a_show_dropdown(this)" onmouseout="a2a_onMouseOut_delay()" href="http://www.addtoany.com/share_save?linkname='.$bookmarkTitleAnchor.'&amp;linkurl='.$BookmarkUrlAnchor.'" target="_blank">'.PHP_EOL;
	echo '			<img class="share-this" src="modules/'.$moduleName.'/images/sharethis.png" height="16" border="0" alt="'._IG_BOOKMARKTHIS.'" />'.PHP_EOL;
	echo '		</a>'.PHP_EOL;
	echo '		<script type="text/javascript">//<![CDATA['.PHP_EOL;
	echo '   		a2a_linkname="'.$bookmarkTitleJs.'";a2a_linkurl="'.$bookmarkUrlJs.'";'.PHP_EOL;
	echo '   	//]]></script>'.PHP_EOL;
	echo '		<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>'.PHP_EOL;
	echo '	</div>'.PHP_EOL;
	echo '	<div id="show-hits">'.PHP_EOL;
	echo '		<span class="big-text">'.$counter.'</span> '._IG_HITS.'<br />'.PHP_EOL;
	echo '		<span class="big-text">'.$totalComments.'</span> '._IG_TOTALCOMMENTS.''.PHP_EOL;
	echo '	</div>'.PHP_EOL;
	echo '	<div style="clear: both;"></div>'.PHP_EOL;
	echo '</div>'.PHP_EOL;

	if($showDetails) {
		echo '<div id="show-details">'.PHP_EOL;
		echo '	<div id="showInfo">'.PHP_EOL;
		echo '		<h3>'._IG_DETAILS.'</h3>'.PHP_EOL;
		echo '		<b>'._IG_SUBMITTED.'</b>: '.dateToDays($date).'<br />'.PHP_EOL;
		echo '		<b>'._IG_FILENAME.'</b>: '.$filename.'<br />'.PHP_EOL;
		if(isset($imgSize) && !empty($imgSize)) echo '		<b>'._IG_FILESIZE.'</b>: '.$imgSize.'<br />'.PHP_EOL;
		if(isset($info) && is_array($info)) echo '		<b>'._IG_RESOLUTION.'</b>: '.$info[0].' x '.$info[1].'px<br />'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
	//echo '<div style="clear: both;"></div>'.PHP_EOL;
}

/* Display a form to add comments to a picture
 * @param int $pictureId
 */
function commentForm($pictureId) {
	global $db, $prefix, $user, $cookie, $moduleName;
	$data = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 1'));
	$albumId = $data['album_id'];
	$title = $data['picture_title'];

	if(is_user($user)) {
		echo '<div id="post-comment">';
		//echo '<h4>'._IG_POSTCOMMENT.'</h4>'.PHP_EOL;
		echo '<form action="modules.php?name='.$moduleName.'&amp;op=commentPost" method="post">'.PHP_EOL;
		echo '	<label for="comment-user"><b>'._IG_USERNAME.'</b>:</label><br />'.PHP_EOL;
		echo '	<input id="comment-user" type="text" name="user_name" value="'.$cookie[1].'" readonly="readonly" /><br /><br />'.PHP_EOL;
		echo '	<label for="comment-body"><b>'._IG_YOURCOMMENT.'</b>:</label><br />'.PHP_EOL;
		echo '	<textarea id="comment-body" name="comment-body" cols="60" rows="5"></textarea><br /><br />'.PHP_EOL;
		echo '	<input type="hidden" name="userid" value="'.$cookie[0].'" />'.PHP_EOL;
		//echo '	<input type="hidden" name="user_name" value="'.$cookie[1].'" />'.PHP_EOL;
		echo '	<input type="hidden" name="pictureid" value="'.$pictureId.'" />'.PHP_EOL;
		echo '	<input type="hidden" name="picture_title" value="'.$title.'" />'.PHP_EOL;
		echo '	<input type="submit" value="'._IG_SEND.'" />'.PHP_EOL;
		echo '</form>'.PHP_EOL;
		echo '</div>';
	} else {
		echo '<div id="post-comment">'.PHP_EOL;
		echo '	<h4>'._IG_POSTCOMMENT.'</h4>'.PHP_EOL;
		echo '	'._IG_NOACCESS.'</div>'.PHP_EOL;
	}
}
/* Saves a rating to database and redirect to picture page */
function ratePic() {
	global $moduleName, $db, $prefix;
	$rating = intval($_POST['picture_rating']);
	$pictureId = $_GET['pictureid'];

	if($rating>0) {
		$result = $db->sql_query('UPDATE '.$prefix.'_igallery_pictures SET picture_rating = picture_rating + '.$rating.', picture_votes = picture_votes + 1, picture_lastrate = \''.time().'\' WHERE picture_id='.$pictureId.' LIMIT 1 ;');
		//if(!$result) echo mysql_error();
		header('Location: modules.php?name='.$moduleName.'&op=showPic&pictureid='.$pictureId.'');
	}
}

function commentPost() {
	global $db, $prefix, $user_prefix, $moduleName, $nukeurl, $sitename, $adminmail;
	$userId = intval($_POST['userid']);
	$username = $_POST['user_name'];
	$pictureId = intval($_POST['pictureid']);
	$pictureTitle = $_POST['picture_title'];
	$message = mysql_real_escape_string(check_html($_POST['comment-body']));
	$emailArray = array();

	if($userId>=0 && $pictureId>=0 && (!empty($message))) {
		$result = $db->sql_query('INSERT INTO '.$prefix.'_igallery_comments VALUES(NULL, \''.$userId.'\', \''.$pictureId.'\', \''.$message.'\', \''.time().'\')');
		if($result) {
			$result2=$db->sql_query('SELECT comment_userid FROM '.$prefix.'_igallery_comments WHERE comment_pictureid=\''.$pictureId.'\'');
			while ($row2=$db->sql_fetchrow($result2)) {
				$email=$db->sql_fetchrow($db->sql_query('SELECT user_email FROM '.$user_prefix.'_users WHERE user_id=\''.$row2['comment_userid'].'\' LIMIT 0,1'));
				$emailArray[]=$email['user_email'];
				//echo $email['user_email'];
			}
			$emailArray = array_unique($emailArray);
			$params = array('html'=> 1); // Activate HTML mode for TegoNuke Mailer
			$subject = $username.' '._IG_COMMENTED.' '.$pictureTitle;
			$message = $subject.'<br /><br />';
			$message.= _IG_VIEWALLCOMMENTS.'<br />';
			$message.= $nukeurl.'/modules.php?name='.$moduleName.'&op=showPic&pictureid='.$pictureId.'<br /><br />';
			$message.= _IG_THANKS.'<br />';
			$message.= _IG_STAFF.' '.$sitename;
			if(defined('PNM_IS_ACTIVE')) {
				phpnukemail($emailArray, $subject, $message, $adminmail, $sitename, $encode=1);
			} elseif(defined('TNML_IS_ACTIVE')) {
				tnml_fMailer($emailArray, $subject, $message, $adminmail, $sitename, $params);
			}
		}
	}
	header('Location: modules.php?name='.$moduleName.'&op=showPic&pictureid='.$pictureId);
}

function displayComments($pictureId) {
	global $db, $prefix, $user_prefix, $moduleName;
	$totalComments = $db->sql_numrows($db->sql_query('SELECT comment_id FROM '.$prefix.'_igallery_comments WHERE comment_pictureid='.$pictureId.''));
	if($totalComments > 0) {
		echo '<div id="show-comments">'.PHP_EOL;
		echo '	<h2>'._IG_USERCOMMENTS.'</h2>';

		$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_comments WHERE comment_pictureid='.$pictureId.' ORDER BY comment_date DESC');
		while($comment = $db->sql_fetchrow($result)) {
			$userId = intval($comment['comment_userid']);
			$message = nl2br($comment['comment_data']);
			$date = $comment['comment_date'];

			$usrInfo = $db->sql_fetchrow($db->sql_query('SELECT username, user_avatar FROM '.$user_prefix.'_users WHERE user_id='.$userId.' LIMIT 1'));
			$posterName = $usrInfo['username'];
			$posterAvatar = $usrInfo['user_avatar'];
			$posterLink = 'modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$posterName.'';

			if(!empty($posterAvatar) && !eregi('blank.gif', $posterAvatar)) {
					if(eregi('http://', $posterAvatar)) { $src = $posterAvatar; } else  { $src = 'modules/Forums/images/avatars/'.$posterAvatar; }
			} else {
				$src = 'modules/'.$moduleName.'/images/no_avatar.png';
			}

			echo '<div class="comment-box">'.PHP_EOL;
			echo '	<div class="show-comment-info">'.PHP_EOL;
			echo '		<a href="'.$posterLink.'"><img class="comment-avatar" src="'.$src.'" alt="'.$posterName.'" title="'.$posterName.'" /></a>'.PHP_EOL;
			echo '		<span class="comment-poster"><a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$posterName.'">'.$posterName.'</a></span>'.PHP_EOL;
			echo '		<span class="comment-date">'.dateToDays($date).'</span>'.PHP_EOL;
			echo '		<div class="comment">'.$message.'</div>'.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '	<div style="clear: both;"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		echo '</div>'.PHP_EOL;
	} else {
		echo '<div class="content-box">'.PHP_EOL;
		echo '	<span class="title">'. _IG_NO_COMMENTS. '</span><br />'.PHP_EOL;
		echo '	<span>'. _IG_NO_COMMENTS_YET. '</span>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
}

function picTopMenu($pictureId, $albumId) {
	global $db, $prefix, $moduleName;
	$first = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE album_id=\''.$albumId.'\' ORDER BY picture_id ASC LIMIT 1'));
	$prev = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE album_id=\''.$albumId.'\' AND picture_id < '.$pictureId.' ORDER BY picture_id DESC LIMIT 1'));
	$next = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE album_id=\''.$albumId.'\' AND picture_id > '.$pictureId.' ORDER BY picture_id ASC LIMIT 1'));
	$last = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE album_id=\''.$albumId.'\' ORDER BY picture_id DESC LIMIT 1'));

	echo '<div style=" width: 80%; margin-left: auto; margin-right: auto;">'.PHP_EOL;
	echo '	<span style="float: left; text-align: left;">'.PHP_EOL;

	if(!empty($first['picture_id']) && $first['picture_id'] !== $pictureId) {
		echo '		<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$first['picture_id'].'"><img src="modules/'.$moduleName.'/images/go-first.png" alt="<<" title="'._IG_FIRSTIMG.'" /></a>'.PHP_EOL;
	} else {
		echo '		<img src="modules/'.$moduleName.'/images/go-first-no.png" alt="<<" title="'._IG_FIRSTIMG.'" />'.PHP_EOL;
	}

	if(!empty($prev['picture_id'])) {
		echo '		<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$prev['picture_id'].'"><img src="modules/'.$moduleName.'/images/go-previous.png" alt="<" title="'._IG_PREVIMG.'" /></a>'.PHP_EOL;
	} else {
		echo '		<img src="modules/'.$moduleName.'/images/go-previous-no.png" alt="<" title="'._IG_PREVIMG.'" />'.PHP_EOL;
	}

	echo '</span>';
	echo '	<span style="float: right; text-align: right;">';
	if(!empty($next['picture_id'])) {
		echo '		<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$next['picture_id'].'"><img src="modules/'.$moduleName.'/images/go-next.png" alt=">" title="'._IG_NEXTIMG.'" /></a>'.PHP_EOL;
	} else {
		echo '		<img src="modules/'.$moduleName.'/images/go-next-no.png" alt=">" title="'._IG_NEXTIMG.'" />'.PHP_EOL;
	}

	if(!empty($last['picture_id']) && $last['picture_id'] !== $pictureId) {
		echo '		<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$last['picture_id'].'"><img src="modules/'.$moduleName.'/images/go-last.png" alt=">>" title="'._IG_LASTIMG.'" /></a>'.PHP_EOL;
	} else {
		echo '		<img src="modules/'.$moduleName.'/images/go-last-no.png" alt=">>" title="'._IG_LASTIMG.'" />'.PHP_EOL;
	}

	echo '	</span>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	echo '<br />'.PHP_EOL;
}

function showTopLinks($albumId=0, $pictureId=0) {
	global $db, $prefix, $moduleName;
	$do = $_GET['op'];
	echo '<div id="top-links">'.PHP_EOL;
	echo '	<a href="modules.php?name='.$moduleName.'"><b>'.$moduleName.'</b></a> &gt;'.PHP_EOL;
	$sql = 'SELECT parent.album_id,parent.album_title
FROM '.$prefix.'_igallery_albums AS node,
'.$prefix.'_igallery_albums AS parent
WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
AND node.album_id = '.$albumId.'
ORDER BY parent.album_left;';
	$result = $db->sql_query($sql);
	while($row=$db->sql_fetchrow($result)) {
		$id = $row['album_id'];
		$title = $row['album_title'];
		if($albumId==$id && $id!=1 &&$do!='showPic') {
			echo '<b>'.$title.'</b> &gt;'.PHP_EOL;
		} elseif($id!=1) {
			echo '<a href="modules.php?name='.$moduleName.'&amp;op=showAlbum&amp;albumid='.$id.'"><b>'.$title.'</b></a> &gt;'.PHP_EOL;
		}
	}

	if($do = 'showPic') {
		$picture = $db->sql_fetchrow($db->sql_query('SELECT picture_title FROM '.$prefix.'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 0,1'));
		echo '<b>'.$picture['picture_title'].'</b>'.PHP_EOL;
	}

	echo '</div>'.PHP_EOL;
}

function paginationSystem($ofsbgn, $ofsppg, $totalItems) {
global $prefix, $moduleName, $op, $cid, $pag;
	/* New pagination class by Vecchio Joe   [ http://www.vecchiojoe.it ] */
	include_once(NUKE_BASE_DIR.'modules/'.$moduleName.'/classes/class.paginationsystem.php');
	$ps = new paginationSystem();
	$ps->items 	= $ofsppg;
	$ps->actpg 	= $pag;
	$ps->tot_items 	= $totalItems;
	if($op=='showAlbum') {
		$albumId = $_GET['albumid'];
		$ps->url = 'modules.php?name='.$moduleName.'&amp;op=showAlbum&amp;albumid='.$albumId.'&amp;pag={{N}}';
	} elseif($op=='galIndex') {
		$ps->url = 'modules.php?name='.$moduleName.'&amp;pag={{N}}';
	}
	$ps->show();
}

function showSubAlbum($root=1) {
	global $db, $prefix, $moduleName, $iConfig, $pag, $ofsppg, $ofsbgn;
	$root = intval($root);
	$showDetails = intval($iConfig['show_details']);
	$showColumns = intval($iConfig['show_columns']);
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsRealPath = iPath($thumbsPath);
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	$albumCounter = 0;

	echo '<table class="show-child" cellspacing="5" cellpadding="5"><tr>'.PHP_EOL;

	$column=1; $srow=1;
	$node = getNodes($root);
	$sql ='SELECT node.*, (COUNT(parent.album_id) - (sub_tree.depth + 1)) AS depth
	FROM '.$prefix.'_igallery_albums AS node,
	'.$prefix.'_igallery_albums AS parent,
	'.$prefix.'_igallery_albums AS sub_parent,
	(
		SELECT node.*, (COUNT(parent.album_id) - 1) AS depth
		FROM '.$prefix.'_igallery_albums AS node,
		'.$prefix.'_igallery_albums AS parent
		WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
		AND node.album_id = \''.$node['id'].'\'
		GROUP BY node.album_id
		ORDER BY node.album_left
	) AS sub_tree
	WHERE node.album_left BETWEEN parent.album_left AND parent.album_right
		AND node.album_left BETWEEN sub_parent.album_left AND sub_parent.album_right
		AND sub_parent.album_id = sub_tree.album_id
	GROUP BY node.album_id
	HAVING depth = 1
	ORDER BY node.album_left;';
	$result = $db->sql_query($sql);
	while($album = $db->sql_fetchrow($result)) {
		$albumCounter++;
		$catId = $album['album_id'];
		$folderName = $album['album_folder'];
		$cover = $album['album_cover'];
		$title = $album['album_title'];
		$description = $album['album_desc'];
		$albumParent = $album['album_id'];

		$what=getAlbumInfo($catId);
		$latestDate = $what[0];
		$totalPics = $what[1];

		if (!empty($cover)) {
			$info = pathinfo($cover);
			$rawCover = str_replace($info['extension'], '', $cover);
		}

		if(!empty($rawCover)) {
			$pictureId = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE picture_file=\''.$cover.'\' AND album_id=\''.$albumId.'\' LIMIT 0,1'));
			$coverSrc= 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId['picture_id'];
		} else {
			$catListIds = getSubAlbumIds($catId);
			if(!empty($catListIds) && $catListIds!==',') {
				$catListIds = $catId.', '.$catListIds;
				$sql = 'album_id IN ('.$catListIds.')';
			} else {
				$sql = 'album_id='.$catId.'';
			}
			$data = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE '.$sql.' LIMIT 0,1'));
			$pictureId = $data['picture_id'];
			$coverSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;
		}

		echo '<td class="sub-album">'.PHP_EOL;
		echo '<div class="thumb-target">'.PHP_EOL;
		echo '	<a href="modules.php?name='.$moduleName.'&amp;op=showAlbum&amp;albumid='.$catId.'"><img class="sub-album" src="'.$coverSrc.'" alt="'.$title.'" /></a>'.PHP_EOL;
		echo '	<div class="tooltip_description">'.PHP_EOL;
		echo '		<p>'.PHP_EOL;
		echo '			<span class="title">'.$title.'</span>';
		echo showNewEmblem($latestDate);
		echo '<br />'.PHP_EOL;
		if($description) echo '			<span>'.$description.'</span></p>'.PHP_EOL;
		echo '		<p>'.$totalPics.' '._IG_TOTALPICS.'</p>'.PHP_EOL;
		echo '	</div>';
		echo '</div>';
		echo '</td>'.PHP_EOL;
		if(($column%$showColumns)==0) {
			//echo '<div style="clear:left;"></div>'.PHP_EOL;
			echo '</tr><tr>'.PHP_EOL;
			$srow++;
		}
		$column++;
	}
	//echo '<div style="clear:left;"></div></div>';
	echo '<td></td></tr></table>'.PHP_EOL;
	//$totalItems = $db->sql_numrows($db->sql_query('SELECT album_id FROM '.$prefix.'_igallery_albums WHERE album_parent=0 AND album_active=1 ;'));
	//paginationSystem($ofsbgn, $ofsppg, $totalItems);
	//if($srow!==1 || $column!==1) echo '<hr />';
}

function showTopPics($what,$type='') {
	global $db, $prefix, $moduleName, $iConfig;
	$showDetails = intval($iConfig['show_details']);
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	if($type=='Recent') {
		$orderByPop = 'picture_date';
		$orderByRated = 'picture_lastrate';
		//$sql = 'SELECT comment_pictureid FROM '.$prefix.'_igallery_comments ORDER BY '.$orderby_comment.' DESC LIMIT 10 ;';
		$sql = 'SELECT *, (SELECT comment_date FROM '.$prefix.'_igallery_comments WHERE comment_pictureid=picture_id GROUP BY comment_pictureid) AS m_date FROM '.$prefix.'_igallery_pictures ORDER BY m_date DESC LIMIT 12 ;';
	} else {
		$orderByPop = 'picture_counter';
		$orderByRated = 'picture_rating/picture_votes';
		$sql = 'SELECT *, (SELECT count(comment_pictureid) FROM '.$prefix.'_igallery_comments WHERE comment_pictureid=picture_id) AS m_total FROM '.$prefix.'_igallery_pictures ORDER BY m_total DESC LIMIT 12 ;';
	}

	if ($what=='Popular') {
		$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures ORDER BY '.$orderByPop.' DESC LIMIT 12 ;');
	} elseif($what=='Rated') {
		$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures ORDER BY '.$orderByRated.' DESC LIMIT 12 ;');
	} elseif($what=='Commented') {
		$result = $db->sql_query($sql);
	}

	echo '<table class="show-child" cellspacing="5" cellpadding="5"><tr>'.PHP_EOL;
	$i=1;

	while($picture = $db->sql_fetchrow($result)) {
		$pictureId = intval($picture['picture_id']);
		$albumId = intval($picture['album_id']);
		$title = $picture['picture_title'];
		$description = $picture['picture_desc'];
		$filename = $picture['picture_file'];
		$counter = intval($picture['picture_counter']);
		$date = $picture['picture_date'];
		$totalRates = intval($picture['picture_rating']);
		$totalVotes = intval($picture['picture_votes']);
		if ($totalRates!==0 && $totalVotes!==0) { $rating = $totalRates/$totalVotes; } else { $rating = 0; }
		$totalComments = intval($db->sql_numrows($db->sql_query('SELECT comment_id FROM '.$prefix.'_igallery_comments WHERE comment_pictureid='.$pictureId.'')));
		$album = $db->sql_fetchrow($db->sql_query('SELECT album_folder FROM '.$prefix.'_igallery_albums WHERE album_id='.$albumId.' LIMIT 1'));
		$folderName = $album['album_folder'];
		$thumbSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;

		/*if($i==1) {
			echo '		<td class="sub-album" colspan="3">'.PHP_EOL;
			echo '			<div class="thumb-target">'.PHP_EOL;
			$cover = '<img class="thumb-top" src="'.$thumbSrc.'" alt="'.$title.'" />';
			echo '			<h2>'.$i.'</h2>'.PHP_EOL;
		} else {*/
			echo '		<td class="sub-album">'.PHP_EOL;
			echo '			<div class="thumb-target">'.PHP_EOL;
			$cover = '<img class="thumb-top-other" src="'.$thumbSrc.'" alt="'.$title.'" />';
			echo '			<span class="title">'.$i.'</span><br />'.PHP_EOL;
		//}
		echo '			<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'">'.$cover.'</a>'.PHP_EOL;
		echo '		<div class="tooltip_description">'.PHP_EOL;
		echo '			<p><span class="title">'.$title.'</span>';
		echo showNewEmblem($date) . showPopEmblem($counter);
		echo '<br />'.PHP_EOL;
		if($description) echo '			<span>'.$description.'</span>';
		echo '			</p>';
		if ($totalRates && $totalVotes && $rating) {
			echo '			<p>'.showRating($rating).'</p>'.PHP_EOL;
			//echo '			<p>'.PHP_EOL:
			echo '			<b>'.number_format($rating, 2, '.', '').'</b> '._IG_WITH.' '.$totalVotes.' '._IG_TOTALVOTES.'<br />'.PHP_EOL;
		} else {
			echo '			<p>'.showRating(0).'</p>'.PHP_EOL;
		}
		echo '			<b>'.$counter.'</b> '._IG_HITS.'<br />'.PHP_EOL;
		echo '			<b>'.$totalComments.'</b> '._IG_TOTALCOMMENTS.'<br />'.PHP_EOL;
		echo '		</div>';
		echo '	</div>'.PHP_EOL;
		echo '</td>'.PHP_EOL;
		if(($i % 4)==0) {
			echo '</tr><tr>'.PHP_EOL;
		}
		$i++;
	}
	echo '<td></td></tr></table>'.PHP_EOL;
}

function showSubPics($albumId) {
	global $db, $prefix, $moduleName, $iConfig, $ofsppg, $ofsbgn;
	$showDetails = intval($iConfig['show_details']);
	$showColumns = intval($iConfig['show_columns']);
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	$albumInfo = $db->sql_fetchrow($db->sql_query('SELECT album_folder, album_desc FROM '.$prefix.'_igallery_albums WHERE album_id='.$albumId.''));
	$folderName = $albumInfo['album_folder'];
	$column=1;
	$srow=1;

	echo '<table class="show-child" cellspacing="5" cellpadding="5"><tr>'.PHP_EOL;

	$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures WHERE album_id='.$albumId.' ORDER BY picture_id ASC LIMIT '.$ofsbgn.','.$ofsppg.'');
	while($picture = $db->sql_fetchrow($result)) {
		$pictureId = intval($picture['picture_id']);
		$catId = intval($picture['album_id']);
		$title = $picture['picture_title'];
		$description = $picture['picture_desc'];
		$filename = $picture['picture_file'];
		$counter = intval($picture['picture_counter']);
		$totalRates = intval($picture['picture_rating']);
		$totalVotes = intval($picture['picture_votes']);
		if ($totalRates!==0 && $totalVotes!==0) { $rating = $totalRates/$totalVotes; } else { $rating = 0; }
		$date = $picture['picture_date'];
		$totalComments = intval($db->sql_numrows($db->sql_query('SELECT comment_id FROM '.$prefix.'_igallery_comments WHERE comment_pictureid='.$pictureId.'')));
		echo '<td class="sub-album">'.PHP_EOL;
		echo '	<div class="thumb-target">'.PHP_EOL;
		$thumbSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;
		echo '		<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'"><img class="sub-album" src="'.$thumbSrc.'" alt="'.$title.'" /></a>'.PHP_EOL;
		echo '		<div class="tooltip_description">'.PHP_EOL;
		echo '			<p><span class="title">'.$title.'</span>';
		echo showNewEmblem($date) . showPopEmblem($counter);
		echo '<br />'.PHP_EOL;
		if($description) echo '			<span>'.$description.'</span>';
		echo '			</p>';
		if ($totalRates && $totalVotes && $rating) {
			echo '			<p>'.showRating($rating).'</p>'.PHP_EOL;
			//echo '			<p>'.PHP_EOL:
			echo '			<b>'.number_format($rating, 2, '.', '').'</b> '._IG_WITH.' '.$totalVotes.' '._IG_TOTALVOTES.'<br />'.PHP_EOL;
		} else {
			echo '			<p>'.showRating(0).'</p>'.PHP_EOL;
		}
		echo '			<b>'.$counter.'</b> '._IG_HITS.'<br />'.PHP_EOL;
		echo '			<b>'.$totalComments.'</b> '._IG_TOTALCOMMENTS.'<br />'.PHP_EOL;
		echo '		</div>';
		echo '	</div>'.PHP_EOL;
		echo '</td>'.PHP_EOL;
		if(($column%$showColumns)==0) {
			echo '</tr><tr>'.PHP_EOL;
			$srow++;
		}
		$column++;
	}
	echo '<td></td></tr></table>'.PHP_EOL;
}

function getSubAlbumIds($root) {
	$catListIds = '';
	//Get ids of all subalbums
	$albumList = getAlbumList($root);
	foreach ($albumList as $list) {
		$id = $list['id'];
		if(!empty($catListIds)){ $catListIds.= ', '; }
		$catListIds.= $id;
	}

	return trim($catListIds);
}

function getAlbumInfo($parent) {
	global $db, $prefix;
	$catListIds = getSubAlbumIds($parent);

	if(!empty($catListIds) && $catListIds!==',') {
		$catListIds = $parent.', '.$catListIds;
		$sql = 'album_id IN ('.$catListIds.')';
	} else {
		$sql = 'album_id='.$parent.'';
	}

	//Get id and date of the last photo added to any of the above albums
	$picture = $db->sql_fetchrow($db->sql_query('SELECT picture_date FROM '.$prefix.'_igallery_pictures WHERE '.$sql.' ORDER BY picture_date DESC LIMIT 0,1'));
	$latestPictureDate = $picture['picture_date'];

	//Count the number of pictures within this album and subalbums
	$totalSubPics = $db->sql_numrows($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE '.$sql.''));

	//Thats it, now return an array with latest picture id and date
	return array($latestPictureDate, $totalSubPics);
}

function uploadDiv() {
	global $moduleName, $iConfig;
	$maxFileSize = $iConfig['max_file_size'];
	echo '<script type="text/javascript" language="javascript">//<![CDATA['.PHP_EOL;
	echo '	$(function(){'.PHP_EOL;
	echo '		$(\'.upload-multi-file\').MultiFile({'.PHP_EOL;
	echo '			list: \'#upload-file-list\','.PHP_EOL;
	echo '			STRING: {'.PHP_EOL;
	echo '				remove: \'<img src="modules/'.$moduleName.'/images/edit-delete.png" style="vertical-align:middle;width:16px;height:16px;" title="'._IG_DELETE_FROM_QUEUE.'" alt="x" />\''.PHP_EOL;
	echo '			}'.PHP_EOL;
	echo '		});'.PHP_EOL;
	echo '	});'.PHP_EOL;
	echo '//]]></script>'.PHP_EOL;
	echo '<div id="upload-form-wrapper">'.PHP_EOL;
	echo '	<img src="modules/'.$moduleName.'/images/upload.png" alt="" />'.PHP_EOL;
	echo '	<form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data" action="modules.php?name='.$moduleName.'&amp;op=uploadMove">'.PHP_EOL;
	echo '		<h3>'._IG_SELECT_ALBUM.'</h3>'.PHP_EOL;
	getAlbumsDropdown();
	echo '		<br /><br />'.PHP_EOL;
	echo '		<h3>'._IG_SELECTIMGS.'</h3>'.PHP_EOL;
	echo '		<b>'._IG_SELECT_UP_TO_10.'</b><br /><br />'.PHP_EOL;
	echo '		<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxFileSize.'" />'.PHP_EOL;
	echo '		<input name="uploadFiles[]" type="file" class="upload-multi-file" accept="'.allowedExt('browse').'" maxlength="10" />'.PHP_EOL;
	echo '		<div class="content-box">'.PHP_EOL;
	echo '			<div><b>'._IG_SELECTED_FILES_APPEAR_HERE.'</b></div>'.PHP_EOL;
	echo '			<div id="upload-file-list"></div>'.PHP_EOL;
	echo '		</div>'.PHP_EOL;
	echo '		<input type="submit" value="'._IG_UPLOADIMGS.'" />'.PHP_EOL;
	echo '	</form>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	echo '<div id="upload-alert"><img src="modules/'.$moduleName.'/images/loading.gif" alt="Loading" title="Loading" /></div>'.PHP_EOL;
}

function uploadMove($ajaxForm=0) {
	global $db, $prefix, $iConfig, $moduleName, $cookie;
	$uploadPath = $iConfig['upload_path'];
	$uploadsResize = intval($iConfig['upload_resize']);
	$uploadsAutoSize = intval($iConfig['upload_autosize']);
	$uploadMaxQuality = intval($iConfig['max_quality']);
	$uploadMaxFileSize = intval($iConfig['max_file_size']);
	$uploaded = '';
	$albumParent = $_POST['album_parent'];
	$submitter = $cookie[0];
	include 'modules/'.$moduleName.'/classes/class.upload.php';

	if(isset($_FILES['uploadFiles'])){
		foreach ($_FILES['uploadFiles'] as $name=>$value) {
			if(is_array($value)){
				foreach($value as $index=>$data) {
					$uploadFiles[$index][$name] = $data;
				}
			}
		}
	}

	if(is_array($uploadFiles)) { foreach($uploadFiles as $filename) {
		$handle = new Upload($filename);
		// Check if the file has been uploaded properly
		if($handle->uploaded) {
			// yes, the file is on the server
			$handle->allowed = allowedMimeTypes();
			//$handle->file_max_size = $uploadMaxFileSize;
			//$handle->jpeg_quality = 50;
			//$handle->image_watermark = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/watermark.png';
			//$handle->image_watermark_position = 'BR';
			$saveTitle = $handle->file_src_name_body; // Save original title for later usage
			$handle->file_new_name_body = md5(time().$filename['name']);
			if ($uploadsResize) {
				$handle->image_resize = true;
				$handle->image_ratio_no_zoom_in = true;
				if ($uploadsAutoSize) {
					$handle->image_x = $uploadsAutoSize;
					$handle->image_y = $uploadsAutoSize;
				}
			}

			// Copy the uploaded file from its temporary location
			$handle->Process(iPath($uploadPath));

			// Check if everything went fine
			if ($handle->processed) {
				// everything was ok... Add it to database
				$picture = $handle->file_dst_name;
				$result = $db->sql_query('INSERT INTO '.$prefix.'_igallery_temp VALUES(NULL, \''.$albumParent.'\', \''.$saveTitle.'\', \'\', \''.$picture.'\', 1, \''.time().'\', \''.$submitter.'\')');
				if($result) $uploaded.= '<li>'._IG_FILE.' '.$filename['name'].' '._IG_ADDEDPIC.'</li>'.PHP_EOL;
			} else {
				$uploaded.= '<li>'._IG_FILE.' '.$filename['name'].' '._IG_ADDEDPICFAIL.' '.$handle->error.'</li>'.PHP_EOL;
			}

			// Delete the temporary files
			$handle->Clean();
		} else {
			// Upload failed! Something must be wrong...
			$uploaded.= '<li>'._IG_FILE.' '.$filename['name'].' '._IG_ADDEDPICFAIL.' '.$handle->error.'</li>'.PHP_EOL;
		}
	}}

	if(!$ajaxForm) galleryHeader();
	echo '<div class="warning">'.PHP_EOL;
	echo '	<ul>'.PHP_EOL;
	if(!empty($uploaded)) {
		echo $uploaded;
	} elseif(!isset($_FILES['uploadFiles'])) {
		echo '<li>'._IG_NO_FILES_SELECTED.'</li>'.PHP_EOL;
	} else {
		echo '<li>'._IG_UNKNOWN_ERROR.'</li>'.PHP_EOL;
	}
	echo '	</ul>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	if(!$ajaxForm) galleryFooter();
}

function allowedExt($type) {
	global $iConfig;
	$allowExt = explode(',',strtolower($iConfig['ext_allow']));
	$printExt = '';
	if($type=='browse') {
		foreach($allowExt as $ext) {
			$ext = trim(strtolower($ext));
			$printExt.= '*.'.$ext.'; ';
		}
	} elseif($type=='list') {
		foreach($allowExt as $ext) {
			$ext = trim(strtoupper($ext));
			$printExt.= $ext.', ';
		}
	} elseif($type=='preg') {
		foreach($allowExt as $ext) {
			$ext = trim(strtolower($ext));
			$printExt.= $ext.'|';
		}
	}
	return substr(trim($printExt), 0, -1);
}

function allowedMimeTypes() {
	global $iConfig;
	$allowExt = explode(',',strtolower($iConfig['ext_allow']));
	// Work in progress, this is a list of commonly used mimetypes
	// Based on user input, this list will be growing in the future
	foreach($allowExt as $ext) {
		$ext = trim(strtolower($ext));
		if($ext=='gif') { $mime[] = 'image/gif'; }
		if($ext=='png') { $mime[] = 'image/png'; }
		if($ext=='jpg' || $ext='jpeg' || $ext='jpe') { $mime[] = 'image/jpeg'; }
		if($ext=='tif' || $ext='tiff') { $mime[] = 'image/tiff'; }
		if($ext=='xbm') { $mime[] = 'image/xbm'; }
		if($ext=='bmp' || $ext='bm') { $mime[] = 'image/bmp'; }
		if($ext=='ico') { $mime[] = 'image/x-icon'; }
		if($ext=='xpm') { $mime[] = 'image/xpm'; }
	}

	return array_unique($mime);
}

function iPath($path) {
	global $iConfig, $moduleName;
	$absPath = $iConfig['gallery_abs_path'];
	if(!empty($absPath) && file_exists($absPath)) {
		$realPath = $absPath.$path;
	} else {
		$realPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/'.$path;
	}

	return $realPath;
}

function getImg() {
	global $db, $prefix, $iConfig, $moduleName;
	$pictureId = $_GET['pictureid'];
	$picturesPath = $iConfig['pictures_path'];
	$uploadPath = $iConfig['upload_path'];
	$result = $db->sql_query('UPDATE '.$prefix.'_igallery_pictures SET picture_counter=picture_counter+1 WHERE picture_id='.$pictureId.'');
	$picture = $db->sql_fetchrow($db->sql_query('SELECT picture_file, album_id, picture_type FROM '.$prefix.'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 0,1'));
	$filename = $picture['picture_file'];
	$pictureType = $picture['picture_type'];
	$albumId = $picture['album_id'];
	$album = $db->sql_fetchrow($db->sql_query('SELECT album_title, album_folder FROM '.$prefix.'_igallery_albums WHERE album_id=\''.$albumId.'\' LIMIT 0,1'));
	$folderName = $album['album_folder'];
	if($pictureType) {
		$img = iPath($uploadPath.$filename);
	} else {
		$img = iPath($picturesPath.$folderName.'/'.$filename);
	}
	if(file_exists($img) && is_readable($img)) {
		$imgPath = $img;
	} else {
		$imgPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/no_image.png';
	}
	if(file_exists($imgPath) && is_readable($imgPath)) {
		$info = getimagesize($imgPath);
		$offset = 3600 * 24 * 60; // Set offset to keep in cache for 2 months (thumbs would not really change that much)
		header('Cache-Control: max-age='.$offset.', must-revalidate');
		header('Content-type: '.$info['mime']);
		header('Expires: '.gmdate('D, d M Y H:i:s', time() + $offset).' GMT');
		// check if the browser is sending  a $_SERVER['HTTP_IF_MODIFIED_SINCE'] global variable
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
			// if the browser has a cached version of this image, send 304
			header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
			exit();
		}
		header('Last-Modified: ' .gmdate('D, d M Y H:i:s', filemtime($imgPath)).' GMT');
		ob_start();
		readfile($imgPath);
		$outputImage = ob_get_contents();
		//header('Content-length: '.ob_get_length()); //This was causing a loop reloading the picture 'til browser just give up (Google Chrome)
		ob_end_clean();
        echo $outputImage;
	}
	exit;
}

function getThumb() {
	global $db, $prefix, $iConfig, $moduleName;
	$pictureId = intval($_GET['pictureid']);
	$thumbsPath = $iConfig['thumbs_path'];
	$uploadPath = $iConfig['upload_path'];
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	$thumbsRealPath = iPath($thumbsPath);
	if($pictureId) {
		$picture = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_pictures WHERE picture_id='.$pictureId.' LIMIT 0,1'));
		$filename = $picture['picture_file'];
		$pictureType = $picture['picture_type'];
		$albumId = intval($picture['album_id']);
		$album = $db->sql_fetchrow($db->sql_query('SELECT album_title, album_folder FROM '.$prefix.'_igallery_albums WHERE album_id=\''.$albumId.'\' LIMIT 0,1'));
		$folderName = $album['album_folder'];
		$info = pathinfo($filename);
		$rawFilename = str_replace($info['extension'], '', $filename);
		if($pictureType) {
			$thumb = iPath($uploadPath.'thumbs/'.$rawFilename.$thumbsFormat);
			if(file_exists($thumb)) {
				$thumbPath = $thumb;
			} else {
				createThumb($moduleName, '', $filename, $type=1);
				$thumbPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/no_image.png';
			}
		} else {
			$thumb = iPath($thumbsPath.$folderName.'/'.$rawFilename.$thumbsFormat);
			if(!file_exists($thumbsRealPath.$folderName)) { @mkdir($thumbsRealPath.$folderName); }

			if(file_exists($thumb)) {
				$thumbPath = $thumb;
			} else {
				createThumb($moduleName, $folderName, $filename);
				$thumbPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/no_image.png';
			}
		}
	}
	if(!isset($thumbPath) || empty($thumbPath)) $thumbPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/no_image.png';
	if(file_exists($thumbPath) && is_readable($thumbPath)) {
		$info = getimagesize($thumbPath);
		$offset = 3600 * 24 * 60; // Set offset to keep in cache for 2 months (thumbs would not really change that much)
		header('Cache-Control: max-age='.$offset.', must-revalidate');
		header('Content-type: '.$info['mime']);
		header('Expires: '.gmdate('D, d M Y H:i:s', time() + $offset).' GMT');
		// check if the browser is sending a $_SERVER['HTTP_IF_MODIFIED_SINCE'] global variable
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
			// if the browser has a cached version of this image, send 304
			header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
			exit();
		}
		header('Last-Modified: ' .gmdate('D, d M Y H:i:s', filemtime($thumbPath)).' GMT');
		ob_start();
		readfile($thumbPath);
		//ob_end_flush();
		$outputImage = ob_get_contents();
		//header('Content-length: '.ob_get_length()); //This was causing a loop reloading the picture 'til browser just give up (Google Chrome)
		ob_end_clean();
        echo $outputImage;
	}
	exit;
}

function getUploadedData() {
	global $db, $prefix, $user_prefix, $moduleName, $admin_file;
	echo '<div id="manage-upload-container">';
	echo '<table id="gal-manage-uploads">'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;
	echo '		<th id="th-pic">Picture</th>'.PHP_EOL;
	echo '		<th id="th-album">Album</th>'.PHP_EOL;
	echo '		<th id="th-date">Date</th>'.PHP_EOL;
	echo '		<th id="th-posted">Posted by</th>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$count = 0;
	$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_temp ORDER BY picture_date,album_id ASC LIMIT 50 ;');
	while($temp = $db->sql_fetchrow($result)) {
		$count++;
		$pictureId = intval($temp['picture_id']);
		$albumId = intval($temp['album_id']);
		$pictureTitle = $temp['picture_title'];
		$pictureFile = $temp['picture_file'];
		$pictureDate = intval($temp['picture_date']);
		$pictureUser = intval($temp['picture_userid']);
		$album = $db->sql_fetchrow($db->sql_query('SELECT album_title FROM '.$prefix.'_igallery_albums WHERE album_id=\''.$albumId.'\' LIMIT 1 ;'));
		$user = $db->sql_fetchrow($db->sql_query('SELECT username FROM '.$user_prefix.'_users WHERE user_id=\''.$pictureUser.'\' LIMIT 1 ;'));
		$viewImage = $admin_file.'.php?op=galPreviewUploaded&amp;file='.$pictureFile;
		echo '	<tr>'.PHP_EOL;
		echo '		<td><input type="checkbox" name="tempId[]" value="'.$pictureId.'" />&nbsp;&nbsp;<a href="'.$viewImage.'" class="colorbox" target="_blank">'.$pictureTitle.'</a></td>'.PHP_EOL;
		echo '		<td><a href="modules.php?name='.$moduleName.'&amp;op=showAlbum&amp;albumid='.$albumId.'">'.$album['album_title'].'</a></td>'.PHP_EOL;
		echo '		<td>'.dateToDays($pictureDate).'</td>'.PHP_EOL;
		echo '		<td><a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$user['username'].'">'.$user['username'].'</a></td>'.PHP_EOL;
		echo '	</tr>'.PHP_EOL;
	}
	if($count===0) {
		echo '	<tr>'.PHP_EOL;
		echo '		<td colspan="4"><b>'._IG_ADM_CURRENTLY_NO_QUEUE.'</b></td>'.PHP_EOL;
		echo '	</tr>'.PHP_EOL;
	}
	echo '</table>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
}
?>