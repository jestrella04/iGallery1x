<?php
/************************************************************************
* Script:     TegoNuke(tm) ShortLinks
* Version:    1.2.1
* Author:     Rob Herder (aka: montego) of http://montegoscripts.com
* Contact:    montego@montegoscripts.com
* Copyright:  Copyright © 2006 by Montego Scripts
* License:    GNU/GPL (see provided LICENSE.txt file)
************************************************************************/
//GT-NExtGEn 0.4/0.5 by Bill Murrin (Audioslaved) http://gt.audioslaved.com (c) 2004
//Original Nukecops GoogleTap done by NukeCops (http://www.nukecops.com)

$urlin = array(
'"(?<!/)modules.php\?name=iGallery&amp;op=getImg&amp;pictureid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=getThumb&amp;pictureid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=addPics"',
'"(?<!/)modules.php\?name=iGallery&amp;op=ratePic&amp;pictureid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=commentPost"',
'"(?<!/)modules.php\?name=iGallery&amp;op=topPics"',
'"(?<!/)modules.php\?name=iGallery&amp;op=recentMoves"',
'"(?<!/)modules.php\?name=iGallery&amp;op=showSlide&amp;albumid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=showPic&amp;pictureid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=showAlbum&amp;albumid=([0-9]*)&amp;pag=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery&amp;op=showAlbum&amp;albumid=([0-9]*)"',
'"(?<!/)modules.php\?name=iGallery"'
);

$urlout = array(
'igallery-showimage-\\1.php',
'igallery-showthumb-\\1.php',
'igallery-add.html',
'igallery-ratepicture-\\1.html',
'igallery-comment-post.html',
'igallery-top.html',
'igallery-recent.html',
'igallery-slide-\\1.html',
'igallery-picture-\\1.html',
'igallery-album-\\1-page\\2.html',
'igallery-album-\\1.html',
'igallery.html'
);
?>