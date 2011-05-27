<?php
/*	iGallery for RavenNuke: /admin/index.php
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

$modname = 'iGallery';
$moduleName = basename(dirname(dirname(__FILE__)));
define('IN_IGM', TRUE);
require_once(NUKE_BASE_DIR.'modules/'.$moduleName.'/includes/settings.php');
include_once(NUKE_BASE_DIR.'modules/'.$moduleName.'/includes/functions.php');
AddCSSToHead('modules/'.$moduleName.'/includes/style.css', 'file');
get_lang($moduleName);

if (isset($_GET['op'])) {$op = $_GET['op'];} elseif (isset($_POST['op'])) {$op = $_POST['op'];} else {$op = 'galAdmin';}
$pag = (isset($_GET['pag'])) ? $_GET['pag'] : 1;

switch ($op) {
	case 'galAbout': galAbout(); break;
	case 'galAdmin': galAdmin(); break;
	case 'galAlbums': galAlbums(); break;
	case 'galAlbumCreate': galAlbumCreate(); break;
	case 'galAlbumDelete': galAlbumDelete(); break;
	case 'galAlbumDeleteAjax': galAlbumDelete($ajaxForm=1); break;
	case 'galAlbumEdit': galAlbumEdit(); break;
	case 'galAlbumSave': galAlbumSave(); break;
	case 'galAlbumSaveAjax': galAlbumSave($ajaxForm=1); break;
	case 'galApprove': galApprove(); break;
	case 'galApproveAjax': galApprove($ajaxForm=1); break;
	case 'galDeleteComment': galDeleteComment(); break;
	case 'galEditComment': galEditComment(); break;
	case 'galGetUploadedData': getUploadedData(); break;
	case 'galManageComments': galManageComments(); break;
	case 'galManageUploads': galManageUploads(); break;
	case 'galPictures': galPictures(); break;
	case 'galPreviewUploaded': galPreviewUploaded(); break;
	case 'galQuickAdd': galQuickAdd(); break;
	case 'galSettings': galSettings(); break;
	case 'galSettingsSave': galSettingsSave(); break;
	case 'galSettingsSaveAjax': galSettingsSave($ajaxForm=1); break;
	case 'galUpload': galUpload(); break;
	case 'galUploadMove': uploadMove(); break;
	case 'galUploadMoveAjax': uploadMove($ajaxForm=1); break;
}

function galAbout() {
	global $ver, $modname, $moduleName;
	include('header.php');
	galAdminMenu();
	OpenTable();
	echo '<div id="about-gallery"><p style="text-align: center;"><img src="modules/'.$moduleName.'/images/igallery.png" alt="" /></p>
<p style="text-align: center;"><b>'.$modname.' '.$ver.'</b><br />
&copy; 2009 - 2010 by Jonathan Estrella<br />
<a href="http://slaytanic.sourceforge.net">http://slaytanic.sourceforge.net</a></p>
<p style="text-align: justify;">'.$modname.' is a simple yet powerful and elegant gallery module for RavenNuke&trade;, designed
to fit the needs for those looking for a quick and easy module to start showing
your pictures to the world.</p>
<p>This program is free software. You can redistribute it and/or modify it
under the terms of the GNU General Public License (GPL) as published by
the Free Software Foundation; either version 2 of the License. For more details
see the <a href="http://www.gnu.org/licenses/gpl.html"><b>License</b></a>.</p>
<p style="text-align: center;"><a class="button" href="javascript:history.go(-1)">'._IG_GO_BACK.'</a></p></div>';
	CloseTable();
	include('footer.php');
}

function galAdminMenu() {
global $admin_file, $db, $prefix, $ver, $modname, $moduleName;
	OpenTable();
	$bullet = '<img src="modules/'.$moduleName.'/images/star_48.png" alt="-" border="0" width="16" style="vertical-align: middle;" />';
	echo '<h2 style="text-align: center;">'.$modname.' '.$ver.': '._IG_ADM_ADMININDEX.'</h2>'.PHP_EOL;
	echo '<table width="90%" style="font-size: 115%; vertical-align: middle; margin-left:auto; margin-right:auto;">'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galPictures">'._IG_ADM_MANAGECONTENT.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galUpload">'._IG_ADM_CONTENTADD.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galSettings">'._IG_ADM_SETTINGS.'</a></td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galAlbums">'._IG_ADM_MANAGECATS.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galAlbumCreate">'._IG_ADM_CONTENTADDCAT.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php">'._IG_ADM_ADMININDEX.'</a></td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galManageUploads">'._IG_ADM_MANAGEWAITING.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galQuickAdd">'._IG_ADM_QUICKADD.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galAdmin">'._IG_ADM_GALSUMMARY.'</a></td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galManageComments">'._IG_ADM_MANAGECOMMENTS.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="modules.php?name='.$moduleName.'">'._IG_ADM_BROWSE_GALLERY.'</a></td>'.PHP_EOL;
	echo '		<td>'.$bullet.' <a href="'.$admin_file.'.php?op=galAbout">'._IG_ADM_ABOUT.' '.$modname.'</a></td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	echo '</table><br />'.PHP_EOL;
	CloseTable();
}

function galAdmin() {
global $db, $prefix, $ver, $modname, $admin_file, $moduleName;
	include('header.php');
	galAdminMenu();
	OpenTable();
	$bullet = '<img src="modules/'.$moduleName.'/images/accepted_48.png" alt="-" border="0" width="16" style="vertical-align: middle;" />';
	echo '<h2 style="text-align: center;">'.$modname.' '.$ver.': '._IG_ADM_SUMMARY.'</h2>'.PHP_EOL;
	echo '<table style="font-size: 110%; width: 90%; margin:auto;">';
	$total = $db->sql_numrows($db->sql_query('SELECT album_id FROM '.$prefix.'_igallery_albums'));
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_TOTALALBUMS.'</b>:</td> <td>'.$total.'</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$total = $db->sql_numrows($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_temp'));
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_TOTALWAITING.'</b>:</td><td>'.intval($total).'</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$total = $db->sql_numrows($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures'));
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_TOTALPICS.'</b>:</td><td>'.$total.'</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_date FROM '.$prefix.'_igallery_pictures ORDER BY picture_date ASC LIMIT 1'));
	if (!empty($row['picture_date'])) {
		$date = iFormatDate($row['picture_date']);
	} else { $date = ''; }
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_DATEFIRSTADD.'</b>:</td><td>'.$date.'</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_date FROM '.$prefix.'_igallery_pictures ORDER BY picture_date DESC LIMIT 1'));
	if (!empty($row['picture_date'])) {
		$date = iFormatDate($row['picture_date']);
	} else { $date = ''; }
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_DATELASTADD.'</b>:</td><td>'.$date.'</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_id, picture_title, picture_counter FROM '.$prefix.'_igallery_pictures ORDER BY picture_counter DESC LIMIT 1'));
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_MOSTVIEWED.'</b>:</td><td><a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$row['picture_id'].'">'.$row['picture_title'].'</a> ('.$row['picture_counter'].' '._IG_ADM_HITS.')</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_id, picture_title, picture_counter FROM '.$prefix.'_igallery_pictures ORDER BY picture_counter ASC LIMIT 1'));
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_LESSVIEWED.'</b>:</td><td><a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$row['picture_id'].'">'.$row['picture_title'].'</a> ('.$row['picture_counter'].' '._IG_ADM_HITS.')</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_id, picture_rating, picture_votes, picture_title, picture_counter FROM '.$prefix.'_igallery_pictures ORDER BY picture_rating/picture_votes DESC LIMIT 1'));
	$showRating = @number_format($row['picture_rating']/$row['picture_votes'], 2, '.', '');
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_BESTRATED.'</b>:</td><td><a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$row['picture_id'].'">'.$row['picture_title'].'</a> ('._IG_ADM_SCORE.': '.$showRating.')</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	$row = $db->sql_fetchrow($db->sql_query('SELECT picture_id, picture_rating, picture_votes, picture_title, picture_counter FROM '.$prefix.'_igallery_pictures ORDER BY picture_rating/picture_votes ASC LIMIT 1'));
	$showRating = @number_format($row['picture_rating']/$row['picture_votes'], 2, '.', '');
	echo '	<tr>'.PHP_EOL;
	echo '		<td>'.$bullet.' <b>'._IG_ADM_WORSTRATED.'</b>:</td><td><a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$row['picture_id'].'">'.$row['picture_title'].'</a> ('._IG_ADM_SCORE.': '.$showRating.')</td>'.PHP_EOL;
	echo '	</tr>'.PHP_EOL;
	echo '</table>'.PHP_EOL;
	CloseTable();
	include('footer.php');
}

function galAlbums() {
	global $db, $prefix, $moduleName, $iConfig, $admin_file, $modname, $ver;
	$showDetails = intval($iConfig['show_details']);
	//$showColumns = intval($iConfig['show_columns']);
	$thumbsPath = $iConfig['thumbs_path'];
	$thumbsFormat = strtolower($iConfig['thumbs_format']);
	$thumbsRealPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/'.$thumbsPath;
	$inlineJS = '<script type="text/javascript">'.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	//$inlineJS.= '			target:	\'#settingsAlert\','.PHP_EOL;
	$inlineJS.= '			dataType: \'json\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galAlbumSaveAjax\','.PHP_EOL;
	$inlineJS.= '			success: processJson'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'.albumForm\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '</script>'.PHP_EOL;
	$inlineJS.= '<script type="text/javascript">'.PHP_EOL;
	$inlineJS.= '	function processJson(data) { '.PHP_EOL;
	$inlineJS.= '		alert(data.response); '.PHP_EOL;
	$inlineJS.= '	}'.PHP_EOL;
	$inlineJS.= '</script>'.PHP_EOL;

	//AddJSToHead('includes/jquery/jquery.js','file');
	//AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js','file');
	//AddJSToHead($inlineJS,'inline');

	include NUKE_BASE_DIR.'header.php';
	galAdminMenu();
	OpenTable();
	echo '<h2 style="text-align: center;">'.$modname.' '.$ver.': '._IG_ADM_MANAGECATS.'</h2>'.PHP_EOL;
	echo '	<div id="album-edit-wrapper">'.PHP_EOL;
	/*echo '<table cellpadding="4" cellspacing="1" border="0" style="width: 90%; margin-left:auto; margin-right:auto;">'.PHP_EOL;
	echo '	<tr>'.PHP_EOL;*/
	$albumList = getAlbumList();
	foreach($albumList as $list) {
		$albumId = $list['id'];
		$title = $list['title'];
		//$parent = $row['album_parent'];
		$description = $list['descr'];
		$active = $list['active'];
		$cover = $list['cover'];
		$folderName = $list['folder'];
		$date = $list['date'];
		$indent = $list['indent'];
		//$cover = '<img src="modules/'.$moduleName.'/images/no_image.png" alt="'.$title.'" title="'.$title.'" border="0" />';
		$edit = '<img src="modules/'.$moduleName.'/images/paper&amp;pencil_48.png" alt="'._IG_ADM_EDIT.'" title="'._IG_ADM_EDIT.'" border="0" width="22" height="22" />';
		$delete = '<img src="modules/'.$moduleName.'/images/edit-delete.png" alt="'._IG_ADM_DELETE.'" title="'._IG_ADM_DELETE.'" border="0" width="22" height="22" />';
		if ($active==0) {
			$status = '<img src="modules/'.$moduleName.'/images/accepted_48_gray.png" alt="'._IG_ADM_INACTIVE.'" title="'._IG_ADM_INACTIVE.'" border="0" width="22" height="22" />';
		} elseif($active==1) {
			$status = '<img src="modules/'.$moduleName.'/images/accepted_48.png" alt="'._IG_ADM_ACTIVE.'" title="'._IG_ADM_ACTIVE.'" border="0" width="22" height="22" />';
		}

		echo '		<div id="album-edit">'.PHP_EOL;
		echo '			<div id="album-edit-info">';
		echo '				<div id="album-edit-title">'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$indent) .'&mdash; <a title="'.$description.'">'.$title.'</a></div>'.PHP_EOL;
		//echo '				<div id="albumEditDesc">'.substr($description,0,255).'</div>'.PHP_EOL;
		echo '			</div>'.PHP_EOL;
		echo '			<div id="album-edit-actions">'.PHP_EOL;
		echo '				<a href="'.$admin_file.'.php?op=galAlbumStatus&amp;albumid='.$albumId.'">'.$status.'</a>'.PHP_EOL;
		echo '				<a href="'.$admin_file.'.php?op=galAlbumEdit&amp;albumid='.$albumId.'">'.$edit.'</a>'.PHP_EOL;
		echo '				<a href="'.$admin_file.'.php?op=galAlbumDelete&amp;albumid='.$albumId.'">'.$delete.'</a>'.PHP_EOL;
		echo '			</div>'.PHP_EOL;
		echo '			<div style="clear:both;"></div>'.PHP_EOL;
		echo '		</div>'.PHP_EOL;
		//echo ''.PHP_EOL;
		//echo ''.PHP_EOL;
	}
	echo '	</div>'.PHP_EOL;
	echo '<center><button><a href="history.go(-1)">_IG_GO_BACK</a></button></center>'.PHP_EOL;
	CloseTable();
	include NUKE_BASE_DIR.'footer.php';
}

function galAlbumCreate() {
	global $admin_file, $moduleName, $modname, $ver;
	$inlineJS = '<script type="text/javascript">'.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	$inlineJS.= '			target:	\'#albumAlert\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galAlbumSaveAjax\','.PHP_EOL;
	$inlineJS.= '			success: function() {'.PHP_EOL;
	$inlineJS.= '				$(\'#albumAlert\').fadeIn(\'slow\');'.PHP_EOL;
	$inlineJS.= '			}'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'#albumForm\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '</script>'.PHP_EOL;
	AddJSToHead('includes/jquery/jquery.js','file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js','file');
	AddJSToHead($inlineJS,'inline');
	include('header.php');
	galAdminMenu();
	OpenTable();
	echo '<div id="album-new">'.PHP_EOL;
	echo '<h2 style="text-align: center;">'.$modname.' '.$ver.': '._IG_ADM_CONTENTADDCAT.'</h2>'.PHP_EOL;
	echo '<form id="albumForm" action="'.$admin_file.'.php?op=galAlbumSave" method="post">'.PHP_EOL;
	echo '	<p>'.PHP_EOL;
	echo '		<label>'._IG_ADM_TITLE.':</label>'.PHP_EOL;
	echo '		<input type="text" name="album_title" size="30" />'.PHP_EOL;
	echo '	</p>'.PHP_EOL;
	echo '	<p>'.PHP_EOL;
	echo '		<label>'._IG_ADM_ACTIVE.':</label>'.PHP_EOL;
	echo '		<input type="radio" name="album_active" value="1" checked="checked" /> '._YES.'&nbsp;'.PHP_EOL;
	echo '		<input type="radio" name="album_active" value="0" /> '._NO.'&nbsp;'.PHP_EOL;
	echo '	</p>'.PHP_EOL;
	echo '	<p>'.PHP_EOL;
	echo '		<label>'._IG_ADM_PARENT.':</label>'.PHP_EOL;
	getAlbumsDropdown();
	echo '	</p>'.PHP_EOL;
	echo '	<p>'.PHP_EOL;
	echo '		<label>'._IG_ADM_ALBUMFOLDER.':</label>'.PHP_EOL;
	echo '		<input type="text" name="album_folder" size="30" />'.PHP_EOL;
	echo '	</p>'.PHP_EOL;
	echo '	<p>'.PHP_EOL;
	echo '		<label>'._IG_ADM_DESC.':</label>'.PHP_EOL;
	echo '		<textarea name="album_desc" rows="5" cols="40"></textarea><br /><br />'.PHP_EOL;
	echo '	</p>'.PHP_EOL;
	echo '	<hr />'.PHP_EOL;
	echo '	<p style="text-align:center;">'.PHP_EOL;
	echo '		<input type="hidden" name="what" value="AlbumSaveNew" />'.PHP_EOL;
	echo '		<input type="submit" value="'._IG_SEND.'" />'.PHP_EOL;
	echo '		<input type="reset" value="'._IG_RESET.'" />'.PHP_EOL;
	echo '	</p>'.PHP_EOL;
	echo '</form>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	echo '<div id="albumAlert"></div>'.PHP_EOL;
	CloseTable();
	include('footer.php');
}

function galAlbumEdit() {
	global $db, $prefix, $moduleName, $iConfig, $admin_file, $modname, $ver;
	$showDetails = intval($iConfig['show_details']);
	$albumId = intval($_GET['albumid']);
	$inlineJS = '<script type="text/javascript">'.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	$inlineJS.= '			target:	\'#albumAlert\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galAlbumSaveAjax\','.PHP_EOL;
	$inlineJS.= '			success: function() {'.PHP_EOL;
	$inlineJS.= '				$(\'#albumAlert\').fadeIn(\'slow\');'.PHP_EOL;
	$inlineJS.= '			}'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'#albumForm\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '</script>'.PHP_EOL;

	AddJSToHead('includes/jquery/jquery.js','file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js','file');
	AddJSToHead($inlineJS,'inline');

	include NUKE_BASE_DIR.'header.php';
	galAdminMenu();
	OpenTable();
	echo '<h2 style="text-align: center;">'.$modname.' '.$ver.': '._IG_ADM_MANAGECATS.'</h2>'.PHP_EOL;
	$list = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_albums WHERE album_id='.$albumId.' LIMIT 0,1 ;'));
	//$albumId = $list['album_id'];
	$title = $list['album_title'];
	//$parent = $row['album_parent'];
	$description = $list['album_desc'];
	$active = $list['album_active'];
	$cover = $list['album_cover'];
	$folderName = $list['album_folder'];
	$date = $list['album_date'];

	$catListIds = '';
	$albumList = getAlbumList($albumId);
	foreach($albumList as $albums) {
		$catListIds.= $albums['id'].',';
	}
	$catListIds = substr($catListIds,0,-1);
	if(!empty($catListIds) && $catListIds!==',') {
		$sql = 'album_id IN ('.$catListIds.')';
	} else {
		$sql = 'album_id='.$albumId;
	}
	$data = $db->sql_fetchrow($db->sql_query('SELECT picture_id FROM '.$prefix.'_igallery_pictures WHERE '.$sql.' LIMIT 0,1'));
	echo mysql_error();
	$pictureId = $data['picture_id'];
	$coverSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;

	echo '	<div id="album-edit">'.PHP_EOL;
	echo '		<div style="float:right;"><img class="sub-album" src="'.$coverSrc.'" title="'.$title.'" alt="" /></div>'.PHP_EOL;
	echo '		<div>'.PHP_EOL;
	echo '		<form id="albumForm" action="'.$admin_file.'.php?op=galAlbumSave" method="post">'.PHP_EOL;
	echo '			<p>'.PHP_EOL;
	echo '				<label>'._IG_ADM_TITLE.':</label>'.PHP_EOL;
	echo '				<input name="album_title" type="text" value="'.$title.'" />'.PHP_EOL;
	echo '			</p>'.PHP_EOL;
	echo '			<p>'.PHP_EOL;
	echo '			<label>'._IG_ADM_ACTIVE.':</label>'.PHP_EOL;
	//echo '				<input name="album_active" type="radio" value="1" /> '._YES.'&nbsp;'.PHP_EOL;
	//echo '				<input name="album_active" type="radio" value="0" /> '._NO.'&nbsp;'.PHP_EOL;
	if($active) {
		echo '				<input type="radio" name="album_active" value="1" checked="checked" />'._YES.'&nbsp;'.PHP_EOL;
		echo '				<input type="radio" name="album_active" value="0" />'._NO.'&nbsp;'.PHP_EOL;
	} else {
		echo '				<input type="radio" name="album_active" value="1" />'._YES.'&nbsp;'.PHP_EOL;
		echo '				<input type="radio" name="album_active" value="0" checked="checked" />'._NO.'&nbsp;'.PHP_EOL;
	}
	echo '			</p>'.PHP_EOL;
	echo '			<p>'.PHP_EOL;
	echo '				<label>'._IG_ADM_PARENT.':</label>'.PHP_EOL;
	getAlbumsDropdown(getParentNode($albumId));
	echo '			</p>'.PHP_EOL;
	echo '			<p>'.PHP_EOL;
	echo '				<label>'._IG_ADM_ALBUMFOLDER.':</label>'.PHP_EOL;
	echo '				<input type="text" name="album_folder" value="'.$folderName.'" size="30" /><br /><br />'.PHP_EOL;
	echo '			</p>'.PHP_EOL;
	echo '			<p>'.PHP_EOL;
	echo '				<label>'._IG_ADM_DESC.':</label>'.PHP_EOL;
	echo '				<textarea name="album_desc" cols="30" rows="3">'.$description.'</textarea>'.PHP_EOL;
	echo '			</p>'.PHP_EOL;
	echo '			<hr />'.PHP_EOL;
	echo '			<p style="text-align:center;">'.PHP_EOL;
	echo '				<input type="hidden" name="parent_orig" value="'.getParentNode($albumId).'" />'.PHP_EOL;
	echo '				<input type="hidden" name="album_id" value="'.$albumId.'" />'.PHP_EOL;
	echo '				<input type="hidden" name="what" value="AlbumSaveEdit" />'.PHP_EOL;
	echo '				<input type="submit" value="'._SAVE.'" />'.PHP_EOL;
	echo '			</p>'.PHP_EOL;
	echo '		</form>'.PHP_EOL;
	echo '		</div>'.PHP_EOL;
	echo '	</div>'.PHP_EOL;
	echo '	<div id="albumAlert"></div>'.PHP_EOL;
	//echo ''.PHP_EOL;
	//echo ''.PHP_EOL;
	echo '<center><button><a href="history.go(-1)">_IG_GO_BACK</a></button></center>'.PHP_EOL;
	CloseTable();
	include NUKE_BASE_DIR.'footer.php';
}

function galAlbumSave($ajaxForm=0) {
	global $db, $prefix, $admin_file, $iConfig, $moduleName;
	if(!empty($_POST['album_id'])) { $albumId = intval($_POST['album_id']); }
	if(!empty($_POST['parent_orig'])) { $parentOriginal = intval($_POST['parent_orig']); }
	$what = $_POST['what'];
	$title = mysql_real_escape_string($_POST['album_title']);
	$parent = intval($_POST['album_parent']);
	$folder = mysql_real_escape_string($_POST['album_folder']);
	$description = mysql_real_escape_string($_POST['album_desc']);
	$active = intval($_POST['album_active']);
	$cover = '';//$_POST['album_cover'];
	$sqlError = '';

	if($what==='AlbumSaveNew') {
		$picturesPath = $iConfig['pictures_path'];
		$picturesRealPath = iPath($picturesPath);
		if(!file_exists($picturesRealPath.$folder)) { @mkdir($picturesRealPath.$folder); }
		if(!empty($title) && !empty($folder) && !empty($description)) {
			$right = updateNodes($parent);

			if($right[0]) {
				// Insert new category
				$sql = 'INSERT INTO '.$prefix.'_igallery_albums values (\'\', \''.$right[0].'\', \''.($right[0] +1).'\', \''.$active.'\', \''.$title.'\', \''.$description.'\', \''.$folder.'\', \''.$cover.'\', \''.time().'\') ;';
				if($result = $db->sql_query($sql)) {
					echo '<div class="warning"><ul><li>'._IG_ADM_CONFIGSAVED.'</li></ul></div>';
				} else {
					echo '<div class="warning"><ul><li>'._IG_ADM_CONFIGERROR.': '.mysql_error().'</li></ul></div>';
				}
			} else {
				echo '<div class="warning"><ul><li>'._IG_ADM_CONFIGERROR.': '.$right[1].'</li></ul></div>';
			}
		} else {
			echo '<div class="warning"><ul><li>'._IG_ADM_ADDNEWERROR.'</li></ul></div>';
		}
	} elseif($what==='AlbumSaveEdit') {
		if ($parent!==$parentOriginal) {
			$update = updateNodes($parent);
		} else {
			$update = true;
		}
		if($update) {
			$result = $db->sql_query('UPDATE '.$prefix.'_igallery_albums SET album_active='.$active.', album_title=\''.$title.'\', album_desc=\''.$description.'\', album_folder=\''.$folder.'\', album_cover=\''.$cover.'\' WHERE album_id='.$albumId.' LIMIT 1 ;');
			if($result) {
				echo '<div class="warning"><ul><li>'. _IG_ADM_ALBUMSAVED .'</li></ul></div>';
			} else {
				echo '<div class="warning"><ul><li>'. _IG_ADM_CONFIGERROR .': '.mysql_error().'</li></ul></div>';
			}
		}
	}

	if($ajaxForm==0) {
		header('location: '.$admin_file.'.php?op=galAlbums');
	}
}

function galAlbumDelete($ajaxForm=0) {
	global $db, $prefix, $admin_file;
	if(isset($_GET['confirm'])) $confirm = intval($_GET['confirm']);
	$albumDeleteId = intval($_GET['albumid']);
	if(isset($confirm)) {
		$result = $db->sql_query('DELETE FROM '.$prefix.'_igallery_albums WHERE album_id = '.$albumDeleteId.' ;');
	} else {
		include NUKE_BASE_DIR.'header.php';
		galAdminMenu();
		OpenTable();
		echo '<div id="gal-album-delete">'.PHP_EOL;
		echo '	<h3>'._IG_ADM_SELECT_DELETE_ACTION.'</h3>'.PHP_EOL;
		echo '	<form id="select-delete-action" method="post" action="">'.PHP_EOL;
		echo '		<input type="radio" name="delete" value="1" /> '._IG_ADM_DELETE_ALL_CHILD_AND_PICS.'<br />'.PHP_EOL;
		echo '		<input type="radio" name="delete" value="2" /> '._IG_ADM_DELETE_MOVE_CHILD_AND_PICS.'<br />'.PHP_EOL;
		//echo '		<input type="radio" name="delete" value="1" /> _DELETE_ALL_CHILD_AND_PICS'.PHP_EOL;
		//echo '		<input type="radio" name="delete" value="1" /> _DELETE_ALL_CHILD_AND_PICS'.PHP_EOL;
		echo '		<h3>'._IG_CHILDS_WILL_BE_MOVED_TO.'</h3>'.PHP_EOL;
		echo '		<span>'.IG_ONLY_IF.' <b>'._IG_ADM_DELETE_MOVE_CHILD_AND_PICS.'</b> '._IG_ADM_IS_SELECTED_ABOVE.'</span>'.PHP_EOL;
		echo '		<br /><br />'.PHP_EOL;
		getAlbumsDropdown();
		echo '		<br /><br />'.PHP_EOL;
		echo '		<input type="submit" value="'._IG_SEND.'" />'.PHP_EOL;
		echo '	<form>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		CloseTable();
		include NUKE_BASE_DIR.'footer.php';
	}
}

function galPictures() {
	global $adminfile, $iConfig;
	//$albumId = $_POST['album_id'];


}

function galQuickAdd() {
	global $admin_file;
	$continue = (isset($_POST['continue'])) ? intval($_POST['continue']) : 0;
	include NUKE_BASE_DIR.'header.php';
	galAdminMenu();
	OpenTable();
	echo '<h2 style="text-align: center;">'._IG_ADM_QUICKADD.'</h2>'.PHP_EOL;
	if ($continue===1) {
		quickUpdate();
	} else {
		echo '<div style="width: 75%; margin:auto; text-align: justify;">'.PHP_EOL;
		echo '	'._IG_ADM_QUICKADDINFO.'<br /><br />'.PHP_EOL;
		echo '	<b>'._IG_ADM_NOTE.'</b>: '._IG_ADM_QUICKADDNOTE.'<br /><br />'.PHP_EOL;
		echo '<center><b>'._IG_ADM_CLICKCONTINUE.'</b><br /><br />'.PHP_EOL;
		echo '<form action="'.$admin_file.'.php?op=galQuickAdd" method="post">'.PHP_EOL;
		echo '	<input type="hidden" name="continue" value="1" />'.PHP_EOL;
		echo '	<input type="submit" value="'._IG_ADM_CONTINUE.'" />'.PHP_EOL;
		echo '</form></center>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
	CloseTable();
	include NUKE_BASE_DIR.'footer.php';
}

function galSettings() {
	global $db, $prefix, $admin_file, $iConfig, $modname, $ver, $moduleName;
	$galleryTitle = $iConfig['igallery_title'];
	$absPath = $iConfig['gallery_abs_path'];
	$picturesPath = $iConfig['pictures_path'];
	$thumbsPath = $iConfig['thumbs_path'];
	$uploadPath = $iConfig['upload_path'];
	$allowExt = $iConfig['ext_allow'];
	$showRows = $iConfig['show_rows'];
	$showColumns = $iConfig['show_columns'];
	$showDetails = $iConfig['show_details'];
	$uploadsResize = $iConfig['upload_resize'];
	$uploadsAutoSize = $iConfig['upload_autosize'];
	$uploadMaxQuality = $iConfig['max_quality'];
	$thumbsAutoSize = $iConfig['thumbs_autosize'];
	$thumbsQuality = $iConfig['thumbs_quality'];
	$thumbsFormat = $iConfig['thumbs_format'];
	$allowComments = $iConfig['comments_allow'];
	$commentsPublic = $iConfig['comments_public'];
	$popCount = $iConfig['pop_count'];
	$tooltipTheme = $iConfig['tooltip_theme'];
	$help = 'modules/'.$moduleName.'/images/help.png';

	$inlineJS = '<script type="text/javascript">'.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	$inlineJS.= '			target:	\'#settings-alert\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galSettingsSaveAjax\','.PHP_EOL;
	$inlineJS.= '			success: function() {'.PHP_EOL;
	$inlineJS.= '				$(\'#settings-alert\').fadeIn(\'slow\');'.PHP_EOL;
	$inlineJS.= '			}'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'#settings-form\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '</script>'.PHP_EOL;

	AddJSToHead('includes/jquery/jquery.js','file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js','file');
	AddJSToHead($inlineJS,'inline');
	include('header.php');
	galAdminMenu();

	OpenTable();
	echo '<h2 style="text-align:center;">'.$modname.' '.$ver.': '._IG_ADM_SETTINGS.'</h2>'.PHP_EOL;
	echo '<div style="text-align:center;">Hover over <img src="'.$help.'" alt="" /> for additional help.</div>'.PHP_EOL;
	echo '<div id="gal-settings">'.PHP_EOL;
	echo '	<form id="settings-form" action="'.$admin_file.'.php?op=galSettingsSave" method="post">'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="igallery_title">'._IG_ADM_IGTITLE.'</label>'.PHP_EOL;
	echo '			<input type="text" name="igallery_title" id="igallery_title" value="'.$galleryTitle.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="gallery_abs_path">'._IG_ADM_ABSPATH.'</label>'.PHP_EOL;
	echo '			<input type="text" name="gallery_abs_path" id="gallery_abs_path" value="'.$absPath.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="pictures_path">'._IG_ADM_PICSPATH.'</label>'.PHP_EOL;
	echo '			<input type="text" name="pictures_path" id="pictures_path" value="'.$picturesPath.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="thumbs_path">'._IG_ADM_THUMBSPATH.'</label>'.PHP_EOL;
	echo '			<input type="text" name="thumbs_path" id="thumbs_path" value="'.$thumbsPath.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="upload_path">'._IG_ADM_UPLOADPATH.'</label>'.PHP_EOL;
	echo '			<input type="text" name="upload_path" id="upload_path" value="'.$uploadPath.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="ext_allow">'._IG_ADM_ALLOWEXT.'</label>'.PHP_EOL;
	echo '			<input type="text" name="ext_allow" id="ext_allow" value="'.$allowExt.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="show_rows">'._IG_ADM_SHOWROWS.'</label>'.PHP_EOL;
	echo '			<input type="text" name="show_rows" id="show_rows" value="'.$showRows.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="show_columns">'._IG_ADM_SHOWCOLS.'</label>'.PHP_EOL;
	echo '			<input type="text" name="show_columns" id="show_columns" value="'.$showColumns.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	/*echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="show_details">'._IG_ADM_SHOWDETAILS.'</label>'.PHP_EOL;
	if($showDetails) {
		echo '			<input type="radio" name="show_details" id="show_details" value="1" checked="checked" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="show_details" value="0" />'._NO.'&nbsp;'.PHP_EOL;
	} else {
		echo '			<input type="radio" name="show_details" id="show_details" value="1" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="show_details" value="0" checked="checked" />'._NO.'&nbsp;'.PHP_EOL;
	}
	echo '		</p>'.PHP_EOL;*/
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="upload_resize">'._IG_ADM_UPLOADRESIZE.'</label>'.PHP_EOL;
	if($uploadsResize) {
		echo '			<input type="radio" name="upload_resize" id="upload_resize" value="1" checked="checked" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="upload_resize" value="0" />'._NO.'&nbsp;'.PHP_EOL;
	} else {
		echo '			<input type="radio" name="upload_resize" id="upload_resize" value="1" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="upload_resize" value="0" checked="checked" />'._NO.'&nbsp;'.PHP_EOL;
	}
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="upload_autosize">'._IG_ADM_UPLOADAUTOSIZE.'</label>'.PHP_EOL;
	echo '			<input type="text" name="upload_autosize" id="upload_autosize" value="'.$uploadsAutoSize.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="max_quality">'._IG_ADM_MAXQUALITY.'</label>'.PHP_EOL;
	echo '			<input type="text" name="max_quality" id="max_quality" value="'.$uploadMaxQuality.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="thumbs_autosize">'._IG_ADM_THUMBAUTOSIZE.'</label>'.PHP_EOL;
	echo '			<input type="text" name="thumbs_autosize" id="thumbs_autosize" value="'.$thumbsAutoSize.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="thumbs_quality">'._IG_ADM_THUMBQUALITY.'</label>'.PHP_EOL;
	echo '			<input type="text" name="thumbs_quality" id="thumbs_quality" value="'.$thumbsQuality.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="thumbs_format">'._IG_ADM_THUMBSFORMAT.'</label>'.PHP_EOL;
	$formats = array('JPG','PNG');
	echo '			<select name="thumbs_format" id="thumbs_format">'.PHP_EOL;
	foreach ($formats as $option) {
		if($option==$thumbsFormat) {
			echo '				<option value="'.$option.'" selected="selected">'.$option.'</option>'.PHP_EOL;
		} else {
			echo '				<option value="'.$option.'">'.$option.'</option>'.PHP_EOL;
		}
	}
	echo '			</select>'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="tooltip_theme">'._IG_ADM_TOOLTIP_THEME.'</label>'.PHP_EOL;
	echo '			<select name="tooltip_theme" id="tooltip_theme">'.PHP_EOL;
	$tooltipThemeList = scanAlbums('modules/'.$moduleName.'/includes/jquery/jquery.tooltip/themes/', false);
	if(is_array($tooltipThemeList)) {
		natsort($tooltipThemeList);
		foreach($tooltipThemeList as $theme) {
			$displayThemeName = basename($theme);
			if($displayThemeName == $tooltipTheme) {
				echo '				<option value="'.$displayThemeName.'" selected="selected">'.$displayThemeName.'</option>'.PHP_EOL;
			} else {
				echo '				<option value="'.$displayThemeName.'">'.$displayThemeName.'</option>'.PHP_EOL;
			}
		}
	}
	ECHO '			</select>'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="comments_allow">'._IG_ADM_ALLOWCOMMENTS.'</label>'.PHP_EOL;
	if($allowComments) {
		echo '			<input type="radio" name="comments_allow" id="comments_allow" value="1" checked="checked" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="comments_allow" value="0" />'._NO.'&nbsp;'.PHP_EOL;
	} else {
		echo '			<input type="radio" name="comments_allow" id="comments_allow" value="1" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="comments_allow" value="0" checked="checked" />'._NO.'&nbsp;'.PHP_EOL;
	}
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="comments_public">'._IG_COMMENTSPUBLIC.'</label>'.PHP_EOL;
	if($commentsPublic) {
		echo '			<input type="radio" name="comments_public" id="comments_public" value="1" checked="checked" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="comments_public" value="0" />'._NO.'&nbsp;'.PHP_EOL;
	} else {
		echo '			<input type="radio" name="comments_public" id="comments_public" value="1" />'._YES.'&nbsp;'.PHP_EOL;
		echo '			<input type="radio" name="comments_public" value="0" checked="checked" />'._NO.'&nbsp;'.PHP_EOL;
	}
	echo '		</p>'.PHP_EOL;
	echo '		<p>'.PHP_EOL;
	echo '			<img src="'.$help.'" alt="" />'.PHP_EOL;
	echo '			<label for="pop_count">'._IG_ADM_POPCOUNT.'</label>'.PHP_EOL;
	echo '			<input type="text" name="pop_count" id="pop_count" value="'.$popCount.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '		<p align="center">'.PHP_EOL;
	echo '			<input type="submit" value="'._SAVE.'" />'.PHP_EOL;
	echo '		</p>'.PHP_EOL;
	echo '	</form>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	echo '<div id="settings-alert"></div>'.PHP_EOL;
	CloseTable();
	include('footer.php');
}

function galSettingsSave($ajaxForm=0) {
	global $db, $prefix;
	$ajaxForm = intval($ajaxForm);
	$echo = '';
	$error = 0;
	$iConfigSave['igallery_title'] = mysql_real_escape_string(check_html($_POST['igallery_title'], 'nohtml'));
	$iConfigSave['gallery_abs_path'] = mysql_real_escape_string(check_html($_POST['gallery_abs_path'], 'nohtml'));
	$iConfigSave['pictures_path'] = mysql_real_escape_string(check_html($_POST['pictures_path'], 'nohtml'));
	$iConfigSave['thumbs_path'] = mysql_real_escape_string(check_html($_POST['thumbs_path'], 'nohtml'));
	$iConfigSave['upload_path'] = mysql_real_escape_string(check_html($_POST['upload_path'], 'nohtml'));
	$iConfigSave['ext_allow'] = mysql_real_escape_string(check_html($_POST['ext_allow'], 'nohtml'));
	$iConfigSave['show_rows'] = intval($_POST['show_rows']);
	$iConfigSave['show_columns'] = intval($_POST['show_columns']);
	//$iConfigSave['show_details'] = intval($_POST['show_details']);
	$iConfigSave['upload_resize'] = intval($_POST['upload_resize']);
	$iConfigSave['upload_autosize'] = intval($_POST['upload_autosize']);
	$iConfigSave['max_quality'] = intval($_POST['max_quality']);
	$iConfigSave['thumbs_autosize'] = intval($_POST['thumbs_autosize']);
	$iConfigSave['thumbs_quality'] = intval($_POST['thumbs_quality']);
	$iConfigSave['comments_allow'] = mysql_real_escape_string(check_html($_POST['thumbs_format'], 'nohtml'));
	$iConfigSave['comments_allow'] = intval($_POST['comments_allow']);
	$iConfigSave['comments_public'] = intval($_POST['comments_public']);
	$iConfigSave['pop_count'] = intval($_POST['pop_count']);
	$iConfigSave['tooltip_theme'] = mysql_real_escape_string(check_html($_POST['tooltip_theme'], 'nohtml'));
	foreach($iConfigSave as $settingName => $settingValue) {
		$exists = $db->sql_query('SELECT setting_name FROM '.$prefix.'_igallery_settings WHERE setting_name=\''.$settingName.'\' LIMIT 0,1');
		if($exists) {
			$result = $db->sql_query('UPDATE '.$prefix.'_igallery_settings SET setting_value=\''.$settingValue.'\' WHERE setting_name=\''.$settingName.'\'');
			if(!$result) {
				$error=1;
				$echo.= '<div class="warning"><ul><li>'._IG_ADM_CONFIGERROR.': '.mysql_error().'</li></ul></div>';
			}
		}
	}

	if($error===0) { $echo.= '<div class="warning"><ul><li>'._IG_ADM_CONFIGSAVED.'</li></ul></div>'; }

	if($ajaxForm) {
		echo $echo;
	} else {
		include('header.php');
		galAdminMenu();
		echo $echo;
		echo '<button><a href="history.go(-1)">_IG_GO_BACK</a></button>'.PHP_EOL;
		include 'footer.php';
	}
}

function galUpload() {
	global $moduleName, $iConfig, $admin_file;
	//$uploadPath = $iConfig['upload_path'];
	//if((substr($uploadPath, -1)) == '/') { $uploadPath = substr($uploadPath, 0, -1); }
	$inlineJS = '<script type="text/javascript">//<![CDATA['.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	$inlineJS.= '			target:	\'#upload-alert\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galUploadMoveAjax\','.PHP_EOL;
	$inlineJS.= '			success: function() {'.PHP_EOL;
	$inlineJS.= '				$(\'input:file\').MultiFile(\'reset\');'.PHP_EOL;
	$inlineJS.= '				$(\'#upload-alert\').fadeIn(\'slow\');'.PHP_EOL;
	$inlineJS.= '				$(\'#upload-file-list\').empty();'.PHP_EOL;
	$inlineJS.= '			}'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'#upload-form\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '//]]></script>'.PHP_EOL;
	AddJSToHead('includes/jquery/jquery.js', 'file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js', 'file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.MultiFile.pack.js', 'file');
	AddJSToHead($inlineJS,'inline');
	include 'header.php';
	galAdminMenu();
	uploadDiv();
	CloseTable();
	include 'footer.php';
}

function galManageUploads() {
	global $db, $prefix, $user_prefix, $moduleName, $admin_file;
	$inlineJS = '<script type="text/javascript">//<![CDATA['.PHP_EOL;
	$inlineJS.= '	$(document).ready(function() {'.PHP_EOL;
	$inlineJS.= '		var options = {'.PHP_EOL;
	$inlineJS.= '			target:	\'#gal-approve-alert\','.PHP_EOL;
	$inlineJS.= '			url: \''.$admin_file.'.php?op=galApproveAjax\','.PHP_EOL;
	$inlineJS.= '			success: function() {'.PHP_EOL;
	$inlineJS.= '				$(\'#manage-upload-container\').fadeOut(\'fast\').load(\''.$admin_file.'.php?op=galGetUploadedData\').fadeIn("fast");'.PHP_EOL;
	$inlineJS.= '			}'.PHP_EOL;
	$inlineJS.= '		};'.PHP_EOL;
	$inlineJS.= '	$(\'#gal-approve-uploaded\').ajaxForm(options);'.PHP_EOL;
	$inlineJS.= '});'.PHP_EOL;
	$inlineJS.= '//]]></script>'.PHP_EOL;
	AddJSToHead('includes/jquery/jquery.js','file');
	AddJSToHead('modules/'.$moduleName.'/includes/jquery/jquery.form.js','file');
	AddJSToHead($inlineJS,'inline');
	include 'header.php';
	galAdminMenu();
	echo '<div id="gal-approve-alert"></div>';
	echo '<div style="text-align:center;">'.PHP_EOL;
	echo '	<button id="button-select-all">Select All</button>';
	echo '	<button id="button-unselect-all">Unselect All</button>';
	echo '</div>'.PHP_EOL;
	echo '<script type="text/javascript">//<![CDATA['.PHP_EOL;
	echo '	$("#button-select-all").click(function () {'.PHP_EOL;
	echo '		$(\'input[name=tempId[]]\').attr(\'checked\', true);'.PHP_EOL;
	echo '	});'.PHP_EOL;
	echo '	$("#button-unselect-all").click(function () {'.PHP_EOL;
	echo '		$(\'input[name=tempId[]]\').attr(\'checked\', false);'.PHP_EOL;
	echo '	});'.PHP_EOL;
	echo '//]]></script>'.PHP_EOL;
	echo '<form id="gal-approve-uploaded" method="post" action="'.$admin_file.'.php?op=galApprove">'.PHP_EOL;
	getUploadedData();
	echo '<div style="text-align:center;">'.PHP_EOL;
	echo '	<input type="submit" name="action" value="'._IG_ADM_APPROVE.'" />'.PHP_EOL;
	echo '	<input type="submit" name="action" value="'._IG_ADM_DELETE.'" />'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	//echo '<input />'.PHP_EOL;
	echo '</form><br />'.PHP_EOL;
	CloseTable();
	include 'footer.php';
}

function galApprove($ajaxForm=0) {
	global $db, $prefix, $iConfig, $moduleName, $admin_file;
	if(isset($_POST['tempId'])) $tempId = $_POST['tempId'];
	$action = $_POST['action'];
	$uploadPath = $iConfig['upload_path'];
	$error = '';

	if($action === _IG_ADM_APPROVE && !empty($tempId)) {
		if(is_array($tempId)) {
			foreach($tempId as $pictureId) {
				//Get records from temp table
				$picture = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_temp WHERE picture_id='.$pictureId.' LIMIT 1 ;'));
				$album = $picture['album_id'];
				$title = $picture['picture_title'];
				$description = $picture['picture_desc'];
				$filename = $picture['picture_file'];
				$date = $picture['picture_date'];
				$user = $picture['picture_userid'];

				// Insert new record to database
				$result = $db->sql_query('INSERT INTO '.$prefix.'_igallery_pictures VALUES(NULL, \''.$album.'\', \''.$title.'\', \'\', \''.$filename.'\', 1, 0, \''.$date.'\', \''.$user.'\', 0, 0, \'\')');
				if($result) {
					// New record inserted... Now delete the temp entry
					$db->sql_query('DELETE FROM '.$prefix.'_igallery_temp WHERE picture_id='.$pictureId.' LIMIT 1 ;');
				} else {
					// On error output some useful info to the user
					$error.= '<li>'._IG_FILE.' '.$filename.' '._IG_ADM_APPROVE_ERROR.' '.mysql_error().'<li>'.PHP_EOL;
				}
			}
		}
	} elseif($action === _IG_ADM_DELETE && !empty($tempId)) {
		if(is_array($tempId)) {
			foreach($tempId as $pictureId) {
				// Get the current filename, then proceed to delete it
				$picture = $db->sql_fetchrow($db->sql_query('SELECT picture_file FROM '.$prefix.'_igallery_temp WHERE picture_id='.$pictureId.' LIMIT 1 ;'));
				$filename = $picture['picture_file'];

				// Delete records from database
				$result = $db->sql_query('DELETE FROM '.$prefix.'_igallery_temp WHERE picture_id='.$pictureId.' LIMIT 1 ;');

				// Physically delete files on $uploadPath
				@unlink(iPath($uploadPath.$filename));
				if(!$result) {
					// On error output some useful info to the user
					$error.= '<li>'._IG_FILE.' '.$filename.' '._IG_ADM_DELETE_ERROR.' '.mysql_error().'<li>'.PHP_EOL;
				}
			}
		}
	}

	if(!$ajaxForm) {
		include 'header.php';
		galAdminMenu();
	}
	echo '<div class="warning">'.PHP_EOL;
	echo '	<ul>'.PHP_EOL;
	if(!empty($error)) {
		// On error output some useful info to the user
		echo $error;
	} elseif(empty($tempId)) {
		// If nothing is selected, advise the user the select some to proceed
		echo '<li>'._IG_NOTHING_SELECTED.'</li>'.PHP_EOL;
	} else {
		// Whooray! No errors at all, print success message
		echo '<li>'._IG_ALL_ACTIONS_SUCCESS.'</li>'.PHP_EOL;
	}
	echo '	</ul>'.PHP_EOL;
	echo '</div>'.PHP_EOL;
	if(!$ajaxForm) {
		echo '<p style="text-align: center;"><a class="button" href="javascript:history.go(-1)">'._IG_GO_BACK.'</a></p>'.PHP_EOL;
		CloseTable();
		include 'footer.php';
	}
}

function galPreviewUploaded() {
	global $db, $prefix, $iConfig, $moduleName;
	$uploadPath = $iConfig['upload_path'];
	$filename = $_GET['file'];
	$img = iPath($uploadPath.$filename);
	if(file_exists($img)) {
		$imgPath = $img;
	} else {
		$imgPath = NUKE_BASE_DIR.'modules/'.$moduleName.'/images/no_image.png';
	}
	$info = getimagesize($imgPath);
	header('Content-type: '.$info['mime']);
	readfile($imgPath);
	exit();
}

function galManageComments() {
	global $db, $prefix, $user_prefix, $iConfig, $moduleName, $admin_file;
	$deleted = (isset($_GET['deleted'])) ? intval($_GET['deleted']) : 0;
	$updated = (isset($_GET['updated'])) ? intval($_GET['updated']) : 0;
	$inlineJS = '<script type="text/javascript">
			$(document).ready(function() {
				$(\'.ask\').click(function(e) {
					e.preventDefault();
					thisHref	= $(this).attr(\'href\');
					if($(this).next(\'div.question\').length <= 0)
						$(this).after(\'<div class="question">'._IG_ADM_ARE_YOU_SURE.'<br/> <span class="yes">'._YES.'</span><span class="cancel">'._NO.'</span></div>\');
					$(\'.question\').animate({opacity: 1}, 300);
					$(\'.yes\').live(\'click\', function(){
						window.location = thisHref + \'confirm=1\';
					});
					$(\'.cancel\').live(\'click\', function(){
						$(this).parents(\'div.question\').fadeOut(300, function() {
							$(this).remove();
						});
					});
				});
			});
</script>';
	AddJSToHead('includes/jquery/jquery.js','file');
	AddJSToHead($inlineJS,'inline');
	include 'header.php';
	galAdminMenu();

	if($deleted==1) {
		echo '<div class="warning">'._IG_ADM_COMMENT_DELETED.'</div>'.PHP_EOL;
	} elseif($deleted==2) {
		echo '<div class="warning">'._IG_ADM_COMMENT_DELETED_ERROR.'</div>'.PHP_EOL;
	}

	if($updated==1) {
		echo '<div class="warning">'._IG_ADM_COMMENT_UPDATED.'</div>'.PHP_EOL;
	} elseif($updated==2) {
		echo '<div class="warning">'._IG_ADM_COMMENT_UPDATED_ERROR.'</div>'.PHP_EOL;
	}

	echo '<div id="show-comments">'.PHP_EOL;
	echo '	<h2>'._IG_ADM_MANAGECOMMENTS.'</h2>'.PHP_EOL;

	$result = $db->sql_query('SELECT * FROM '.$prefix.'_igallery_comments ORDER BY comment_date DESC ;');
	while($comment = $db->sql_fetchrow($result)) {
		$commentId = intval($comment['comment_id']);
		$userId = intval($comment['comment_userid']);
		$message = nl2br($comment['comment_data']);
		$date = $comment['comment_date'];
		$pictureId = $comment['comment_pictureid'];

		$usrInfo = $db->sql_fetchrow($db->sql_query('SELECT username, user_avatar FROM '.$user_prefix.'_users WHERE user_id='.$userId.' LIMIT 1'));
		$posterName = $usrInfo['username'];
		$posterAvatar = $usrInfo['user_avatar'];
		$posterLink = 'modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$posterName.'';
		$thumbSrc = 'modules.php?name='.$moduleName.'&amp;op=getThumb&amp;pictureid='.$pictureId;

		if(!empty($posterAvatar) && !eregi('blank.gif', $posterAvatar)) {
			if(eregi('http://', $posterAvatar)) { $src = $posterAvatar; } else { $src = 'modules/Forums/images/avatars/'.$posterAvatar; }
		} else {
			$src = 'modules/'.$moduleName.'/images/no_avatar.png';
		}
		echo '<div class="comment-box">'.PHP_EOL;
		echo '<table style="width:100%;">'.PHP_EOL;
		echo '	<tr>'.PHP_EOL;
		echo '		<td class="show-comment-image">'.PHP_EOL;
		echo '			<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'"><img src="'.$thumbSrc.'" title="" alt="" /></a>'.PHP_EOL;
		echo '		</td>'.PHP_EOL;
		echo '		<td class="show-comment-info">'.PHP_EOL;
		echo '			<a href="'.$posterLink.'"><img class="comment-avatar" src="'.$src.'" alt="'.$posterName.'" title="'.$posterName.'" /></a>'.PHP_EOL;
		echo '			<span class="comment-poster"><a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$posterName.'">'.$posterName.'</a></span>'.PHP_EOL;
		echo '			<span class="comment-date">'.dateToDays($date).'</span>'.PHP_EOL;
		echo '			<span class="comment-admin">'.PHP_EOL;
		echo '				<a href="'.$admin_file.'.php?op=galDeleteComment&amp;id='.$commentId.'&amp;" class="ask"><img class="icon-small" src="modules/'.$moduleName.'/images/edit-delete.png" title="'._IG_ADM_DELETE_COMMENT.'" alt="" /></a>'.PHP_EOL;
		echo '				<a href="'.$admin_file.'.php?op=galEditComment&amp;id='.$commentId.'"><img class="icon-small" src="modules/'.$moduleName.'/images/format-justify-fill.png" title="'._IG_ADM_EDIT_COMMENT.'" alt="" /></a>'.PHP_EOL;
		echo '				<a href="modules.php?name='.$moduleName.'&amp;op=showPic&amp;pictureid='.$pictureId.'#show-comments"><img class="icon-small" src="modules/'.$moduleName.'/images/internet-group-chat.png" title="'._IG_ADM_VIEW_COMMENT_THREAD.'" alt="" /></a>'.PHP_EOL;
		echo '			</span>'.PHP_EOL;
		echo '			<div class="comment">'.$message.'</div>'.PHP_EOL;
		echo '		</td>'.PHP_EOL;
		echo '	</tr>'.PHP_EOL;
		echo '</table>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
	echo '</div>'.PHP_EOL;
	CloseTable();
	include 'footer.php';
}

function galDeleteComment() {
	global $db, $prefix, $admin_file, $moduleName;
	$commentId = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
	$confirmDelete = (isset($_GET['confirm'])) ? intval($_GET['confirm']) : 0;

	if($commentId && $confirmDelete) {
		$result = $db->sql_query('DELETE FROM '.$prefix.'_igallery_comments WHERE comment_id='.$commentId.' LIMIT 1 ;');
		if($result) {
			header('location:'.$admin_file.'.php?op=galManageComments&deleted=1');
		} else {
			header('location:'.$admin_file.'.php?op=galManageComments&deleted=2');
		}
	} elseif($commentId && !$confirmDelete) {
		include 'header.php';
		galAdminMenu();
		echo '<p class="title">'._IG_ADM_ARE_YOU_SURE_COMMENT.'</p>'.PHP_EOL;
		echo '<p>'.PHP_EOL;
		echo '	<a class="button" href="'.$admin_file.'.php?op=galDeleteComment&amp;id='.$commentId.'&amp;confirm=1">'._YES.'</a>'.PHP_EOL;
		echo '	<a class="button" href="'.$admin_file.'.php?op=galManageComments">'._NO.'</a>'.PHP_EOL;
		echo '</p>'.PHP_EOL;
		CloseTable();
		include 'footer.php';
	} else {
		header('location:'.$admin_file.'.php?op=galManageComments');
	}
}

function galEditComment() {
	global $db, $prefix, $user_prefix, $admin_file, $moduleName;
	$commentId = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
	$commentEditSave = (isset($_GET['save'])) ? intval($_GET['save']) : 0;
	if(!$commentId) $commentId = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
	if(!$commentEditSave) $commentEditSave = (isset($_POST['save'])) ? intval($_POST['save']) : 0;

	if($commentId && !$commentEditSave) {
		include 'header.php';
		galAdminMenu();

		$comment = $db->sql_fetchrow($db->sql_query('SELECT * FROM '.$prefix.'_igallery_comments WHERE comment_id='.$commentId.' LIMIT 1 ;'));
		if($comment) {
			$userId = $comment['comment_userid'];
			$usrInfo = $db->sql_fetchrow($db->sql_query('SELECT username FROM '.$user_prefix.'_users WHERE user_id='.$userId.' LIMIT 1 ;'));
			$posterName = $usrInfo['username'];
			$message = $comment['comment_data'];
			echo '<div id="comment-editor">'.PHP_EOL;
			echo '<h2>'._IG_ADM_COMMENT_EDITOR.'</h2>'.PHP_EOL;
			echo '<form action="'.$admin_file.'.php" method="post">'.PHP_EOL;
			echo '	<p>'.PHP_EOL;
			echo '		<label for="comment_userid">'._IG_USERNAME.':</label><br />'.PHP_EOL;
			echo '		<input type="text" name="comment_userid" id="comment_userid" value="'.$posterName.'" readonly="readonly" />'.PHP_EOL;
			echo '	</p>'.PHP_EOL;
			echo '	<p>'.PHP_EOL;
			echo '		<label for="comment_data">'._IG_COMMENT.':</label><br />'.PHP_EOL;
			echo '		<textarea name="comment_data" id="comment_data" cols="80" rows="10">'.$message.'</textarea>'.PHP_EOL;
			echo '	</p>'.PHP_EOL;
			echo '	<input type="hidden" name="op" value="galEditComment" />'.PHP_EOL;
			echo '	<input type="hidden" name="id" value="'.$commentId.'" />'.PHP_EOL;
			echo '	<input type="hidden" name="save" value="1" />'.PHP_EOL;
			echo '	<input type="submit" value="'._IG_ADM_SAVE_COMMENT.'" />'.PHP_EOL;
			echo '	</form>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		} else {
			echo '<div id="comment-editor">'.PHP_EOL;
			echo '	<div class="warning">'._IG_ERROR_LOADING_INFO.'</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		CLoseTable();
		include 'footer.php';
	} elseif($commentId && $commentEditSave) {
		$message = check_html($_POST['comment_data'],'nohtml');
		$result = $db->sql_query('UPDATE '.$prefix.'_igallery_comments SET comment_data=\''.$message.'\' WHERE comment_id='.$commentId.' LIMIT 1 ;');
		if($result) {
			header('location:'.$admin_file.'.php?op=galManageComments&updated=1');
		} else {
			header('location:'.$admin_file.'.php?op=galManageComments&updated=2');
		}
	} else {
		header('location:'.$admin_file.'.php?op=galManageComments');
	}
}
?>