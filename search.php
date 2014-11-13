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
include 'core/head.php';

$queryString = $_REQUEST['queryString'];	// string that is the search term to send to api

?>
	<h2>Search Results</h2>
	<h3><a href="<?=ROOT_DIR?>">Return Home</a></h2>
	<br/><br/>
<?php

$films = getSearchResults($queryString);
$numberOfResults = count($films);

if ($numberOfResults==0){$numberOfResults=0;
}else{

	// Get array of IMDB_IDs on plex
	$IMDB_ID_plex_a = getPlexIMDB_a($sectionID);


	$numberPrinted = printFilmList($films,$IMDB_ID_plex_a,false);
}
echo '<hr/>';
echo $numberPrinted.' results found not in Plex. Actually Found '.$numberOfResults.'.<br/>'.endTimer($time);
?>
