<?php
/* EYA - Easy YTS Adder.  Plex library aware YTS torrent download viewer with Transmission Integration
*	Copyright (C) 2014 	Jamie Briers 	<development@jrbriers.co.uk>
*						Chris Pomfret	<enquiries@chrispomfret.com>
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
*	You should have received a copy of the GNU General Public License along
*	with this program; if not, write to the Free Software Foundation, Inc.,
*	51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
include('core/head.php');
// Start by opening connection to database




// Done on every page load - check if there's any new films in plex that should be added to db
updateDB($sectionID);

// Search stuff
?>

<?php

$films = getPageList($setNumber,$sectionID);
// Check against Plex
$IMDB_ID_plex_a = getPlexIMDB_a($sectionID);
//$IMDBID_YIFY_a = array_flip($IMDBID_YIFY_a);

printFilmList($films,$IMDB_ID_plex_a);


if($setNumber-1 < 1){

}

$pURL = "?page=".($setNumber-1);
$spURL = "?page=".($setNumber-10);
$nURL = "?page=".($setNumber+1);
$snURL = "?page=".($setNumber+10);

if($setNumber-10 < 1){
	$spURL = "?page=1";
}

echo "<div class='pageNav'>";
if ($setNumber > 1){
	?>
	<a href="<?php echo $spURL; ?>"><<</a>
	<a href="<?php echo $pURL; ?>"><</a>
	<?php
}
echo $setNumber;
	?>

<a href="<?php echo $nURL; ?>">></a>
<a href="<?php echo $snURL; ?>">>></a>

</div>
<?php
//echo "<br/>".endTimer($time);
include('core/foot.php');
?>
