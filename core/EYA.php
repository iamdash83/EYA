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
/*
 * EYA.php
 * Core for EYA.
 */

include __DIR__.'/../config.inc.php';
include __DIR__.'/DB.php';

define("CHECK_DEPENDENCY_PLEX", 0x1);
define("CHECK_DEPENDENCY_TRANSMISSION", 0x2);


function checkDependencies($dependencies){
	$success = true;

	if($dependencies & CHECK_DEPENDENCY_PLEX == CHECK_DEPENDENCY_PLEX){
		try{
			$recentlyAddedPlexXML = new SimpleXMLElement(getData(PLEX_URL));
		}catch(Exception $e){
			echo "ERROR: Could not connect to Plex Media Server, Please ensure Plex is installed and running<br/>";
			$success = false;
		}
	}

	if(($dependencies & CHECK_DEPENDENCY_TRANSMISSION) == CHECK_DEPENDENCY_TRANSMISSION){
		try{
			$transmissionRPC = new TransmissionRPC(TRANSMISSION_RPC,TRANSMISSION_RPC_USER,TRANSMISSION_RPC_PASS);
		}catch(Exception $e){
			echo "ERROR: Could not connect to Transmission, Please ensure Transmission is installed and running<br/>";
			$success = false;
		}
	}

	return $success;
}

// Returns array of IMDB IDs for films that are on plex
function getPlexIMDB_a($sectionID){
	// Get all IMDB_IDs on plex
	$sql = "SELECT `IMDB_ID` FROM `Plex_Videos` WHERE SECTION_ID = ".$sectionID;
	$result = runQuery($sql);

	$IMDB_ID_plex = array();

	while($row = mysqli_fetch_array($result)){
		if ($row["IMDB_ID"] != ""){
		    $IMDB_ID_plex[$row["IMDB_ID"]]=$row["IMDB_ID"];
		}
	}
	return $IMDB_ID_plex;
}

// Housekeeping function to check if any new films have ben added to plex and update database accordingly
function updateDB($sectionID){
	$plexSectionKey = getPlexSectionKey($sectionID);
	$recentlyAddedPlexXML = new SimpleXMLElement(getData(PLEX_URL.$plexSectionKey.PLEX_RECENT));
	$numberOfNewFilms = $recentlyAddedPlexXML['size'];

	$timeOfLastFilm = getLastUpdate($sectionID);

	$latestNewTime = $recentlyAddedPlexXML->Video[0]['addedAt'];

	// There are new films, we need to update
	if($latestNewTime > $timeOfLastFilm){
		for($i=0;$i<$numberOfNewFilms;$i++){
			$film = new StdClass();

			// We've reached an item we already have in the db
			if($recentlyAddedPlexXML->Video[$i]['addedAt'] == $timeOfLastFilm){break;}

			$film->t = mysql_real_escape_string((string)$recentlyAddedPlexXML->Video[$i]['title']);
			$film->y = (int)$recentlyAddedPlexXML->Video[$i]['year'];

			$film->imdbID = getIMDBID($film->t, $film->y);

			var_dump($film);
			addFilmToDB($film,$sectionID);
		}
		setLastUpdate($sectionID,$latestNewTime);
	}
}

// Run first time EYA is run to initialise DB
function initDB($sectionID){
	$plexSectionKey = getPlexSectionKey($sectionID);
	// XML for all films on plex
	$plexXML = new SimpleXMLElement(getData(PLEX_URL.$plexSectionKey.PLEX_ALL));
	$numberOfFilmsOnPlex = $plexXML['size'];
	echo "Found ".$numberOfFilmsOnPlex." files<br/>";
	flushOutput();
	for($i=0;$i<$numberOfFilmsOnPlex;$i++){
		$film = new StdClass();
		$film->t = (string)$plexXML->Video[$i]['title'];
		$film->y = (int)$plexXML->Video[$i]['year'];
		$film->queryURL = "http://www.omdbapi.com/?y=".$film->y."&t=";

		echo "<br/>$i. Processing ".$film->t."...";
		$imdbID;
		/* OK, this one's a bit different - (It will take a very long time) */
		if(true){
			$imdbID = getIMDBID($film->t, $film->y);
			echo "...got ".$imdbID;
		}

		$film->t = mysql_real_escape_string($film->t);
		$film->y = mysql_real_escape_string($film->y);
		$film->imdbID = mysql_real_escape_string($imdbID);

		addFilmToDB($film,$sectionID);
		flushOutput();
	}
	echo "<br/>Done.";
}

function getIMDBID($t, $y){
	$queryURL = "http://www.omdbapi.com/?y=".$y."&s=";
	$queryURL = $queryURL.urlencode($t);
	//htmlentities($queryURL . $t) . "<hr>";

	echo "Querying <a target='_blank' href='".$queryURL."'>".$queryURL."</a> for IMDBID...";
	$json = file_get_contents($queryURL);
	$jsonResult = json_decode($json, TRUE);


	// We're going to assume the first one is fine
	return (string)$jsonResult["Search"][0]["imdbID"];
}

// Return XML given an url.
function getData($URI){
	$ch = curl_init($URI);
	curl_setopt($ch, CURLOPT_URL, $URI);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$XMLData = curl_exec($ch);
	curl_close($ch);
	return $XMLData;
}

// Called by index.php to return list of YTS films - Calls getYTSFilms()
function getPageList($setNumber,$sectionID){
	$quality;
	if($sectionID == getConfig(CFG_3D_SECTION_ID)){
		$quality = "3D";
	}else if($sectionID == getConfig(CFG_SECTION_ID)){
		$quality = "1080p";
	}else{
		return null;
	}
	$queryURL = YIFY_API."&quality=".$quality."&set=".$setNumber;
	return getYTSFilms($queryURL);
}

// Called by search.php to return list of YTS films - Calls getYTSFilms()
function getSearchResults($queryString,$is3D=false){
	$quality = $is3D ? "3D" : "1080p";
	$queryURL = YIFY_API."&quality=".$quality."&sort=year&keywords=".urlencode($queryString);
	return getYTSFilms($queryURL);
}

// Returns $films array given a query URL
function getYTSFilms($YTSAPIURL){
	$json = file_get_contents($YTSAPIURL);
	$jsonResult = json_decode($json, TRUE);
	$numberOfResults = count($jsonResult['MovieList']);

	$films = array();

	for($i = 0 ; $i < $numberOfResults ; $i++){
		$film = new StdClass();

		$film->title = (string)$jsonResult['MovieList'][$i]['MovieTitle'];
		$film->titleClean = (string)$jsonResult['MovieList'][$i]['MovieTitleClean'];
		$film->year = (string)$jsonResult['MovieList'][$i]['MovieYear'];
		$film->image_url = (string)$jsonResult['MovieList'][$i]['CoverImage'];
		$film->imdbCode = (string)$jsonResult['MovieList'][$i]['ImdbCode'];
		$film->torrentURL =  (string)$jsonResult['MovieList'][$i]['TorrentUrl'];
		$film->torrentMagnetURL = (string)$jsonResult['MovieList'][$i]['TorrentMagnetUrl'];

		$url = parse_url(urldecode($film->torrentMagnetURL));
		parse_str($url['query'], $query);
		$film->xt = $query['xt'];

		$films[$film->imdbCode] = $film;
	}
	return $films;
}

function printFilmList($filmList, $plexList, $displayPlexFilms = true){
	//open the connection to transmission rpc server
	$transmissionRPC = new TransmissionRPC(TRANSMISSION_RPC,TRANSMISSION_RPC_USER,TRANSMISSION_RPC_PASS);
	//get all the running torrents in transmission
	//$runningTorrents = $transmissionRPC->torrent_get();
	$runningTorrents = $transmissionRPC->get(
	    array(),    // id's
	    array(      // Fields
	            "id",
	            "name",
	            "percentDone",
	            "magnetLink"
	        )
	    );
	//refine the array

	if(isset($runningTorrents->arguments->torrents)){
		$runningTorrents = $runningTorrents->arguments->torrents;
	}else{
		$runningTorrents = array();
	}

	//create array to store the unique magnet links that we will later check for
	$runningTorrentsMagnets = array();

	for($i =0 ; $i < count($runningTorrents) ; $i++){
		$url = $runningTorrents[$i]->magnetLink;
		$url = parse_url($url);
		parse_str($url['query'], $query);
		//echo $query['xt'];
		if(isset($runningTorrents[$i]->percentDone)){
	        $runningTorrents[$i]->percentDone = (string)(((float)$runningTorrents[$i]->percentDone)*(float)100.0);
	    }else{
	        $runningTorrents[$i]->percentDone = "0";
	    }
		$runningTorrentsMagnets[$query['xt']] = $i;
	}
	echo "<div class=\"clear\"></div>";
	echo "<div class=\"movieListOuter\">";
	echo "<div class=\"movieListInner\">";
	$count = 0;
	foreach($filmList as $film){
		$imdbCode = $film->imdbCode;

		echo '<div class="film-grid" data-xt="'.(string)$film->xt.'" title="'.(string)$film->titleClean.' ('.(string)$film->year.')">';
		
	    if(isset($plexList[$imdbCode])){
		   //Film is in plex
	    	if ($displayPlexFilms == true){
			?>
				<img class="box-art greyed"  src="<?=(string)$film->image_url?>"/>
				<div class="downloaded"></div>
			<?php
				$count++;
			}	
		}elseif(isset($runningTorrentsMagnets[$film->xt])){
			//this is in transmission
			?>
			<img class="box-art greyed"  src="<?=(string)$film->image_url?>"/>
			<div class="downloading" style="height: <?=$runningTorrents[$runningTorrentsMagnets[$film->xt]]->percentDone?>%" ></div>
			<p class="percentage" ><?=$runningTorrents[$runningTorrentsMagnets[$film->xt]]->percentDone?>%</p>
			<?php
			$count++;
	    }else{
	    	if(ENABLE_TORRENT_URLS){
	    		echo '<img class="box-art" data-magnet="'.$film->torrentURL.'" src="'.(string)$film->image_url.'"/>';
			}else{
				echo '<img class="box-art" data-magnet="'.$film->torrentMagnetURL.'" src="'.(string)$film->image_url.'"/>';
			}
			$count++;
	    }
	    echo "</div>";
	}
	echo "</div>";
	echo "</div>";
	echo '<div class="clear"></div>';
	return $count;
}

function flushOutput(){
	flush();
	//ob_end_flush();
	sleep(1);
}