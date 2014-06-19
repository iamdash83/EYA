<?php
/* EYA - Easy YTS Adder.  Plex library aware YTS torrent download viewer with Transmission Integration
*	Copyright (C) 2014 	Jamie Briers 	<development@jrbriers.co.uk>
*						 					Chris Pomfret	<enquiries@chrispomfret.com>
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
// Open connection to database
$con;
function openDatabase(){
    global $con;
    $con = mysqli_connect(SQL_HOST,DB_USER,DB_PASS,DB);

    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    //echo "Connected to mySQL db<br/>";
}

// Close connection to database
function closeDatabase(){
    global $con;
    mysqli_close($con);
    //echo "db closed";
}

// Execute query
function runQuery($sql){
    global $con;
    //echo $sql;
    if ($result = mysqli_query($con,$sql)){
        //echo $msg."</br>";
    }else{
        echo "Error: " . mysqli_error($con)."<br/>";
        throw new Exception("Error Processing Request" . mysqli_error($con), 1);
    }
    return $result;
}

function getLastInsertedID(){
    global $con;
    return mysqli_insert_id($con);
}

function setConfig($key, $value){
    $sql = "INSERT INTO `Config` (`KEY`, `VALUE`) VALUES ('".$key."','".$value."') ON DUPLICATE KEY UPDATE `VALUE` = ".$value;
    runQuery($sql);
}

function getConfig($key){
    $sql = "SELECT `VALUE` FROM `Config` WHERE `KEY` = '".$key."'";
    
    try{
        $result = runQuery($sql);
        $value = mysqli_fetch_assoc($result);    
    }catch(Exception $e){
        return -1;
    }
    
    return $value['VALUE'];
}

function getPlexSectionKey($sectionID){
    $sql = "SELECT `PLEX_SECTION_ID` FROM `Sections` WHERE `ID` = ".$sectionID;
    $result = runQuery($sql);
    $value = mysqli_fetch_assoc($result);
    return $value['PLEX_SECTION_ID'];
}

function createConfigTable(){
    $sql = "DROP TABLE IF EXISTS `Config`";
    runQuery($sql);
    $sql = "CREATE TABLE `Config` (
        `KEY` varchar(128) NOT NULL,
        `VALUE` varchar(128) NOT NULL,
        PRIMARY KEY (`KEY`)
        ) ENGINE=InnoDB";
    runQuery($sql);
}

function createPlexVideosTable(){
    $sql = "DROP TABLE IF EXISTS `Plex_Videos`";
    runQuery($sql);

    // Create Plex_videos Table
    $sql = "CREATE TABLE IF NOT EXISTS `Plex_Videos` (
        `PID` int(11) NOT NULL AUTO_INCREMENT,
        `SECTION_ID` int(11) NOT NULL,
        `FILM_TITLE` varchar(128) NOT NULL,
        `FILM_YEAR` int(11) NOT NULL,
        `QUERY_ID` varchar(128) NOT NULL,
        `IMDB_ID` varchar(10) NOT NULL,
        PRIMARY KEY (`PID`)
        ) ENGINE=InnoDB";
    runQuery($sql);
}

function createSectionsTable(){
    $sql = "DROP TABLE IF EXISTS `Sections`";
    runQuery($sql);
    $sql = "CREATE TABLE `Sections` (
        `ID` int(11) NOT NULL AUTO_INCREMENT,
        `PLEX_SECTION_ID` int(11) NOT NULL,
        `NAME` varchar(128) NOT NULL,
        `LAST_UPDATE` int(11) NOT NULL,
        PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB";
    runQuery($sql);
}

function addFilmToDB($film,$sectionID){
    $sql = "INSERT INTO `Plex_Videos` VALUES(
        NULL,
        '".$sectionID."',
        '".$film->t."',
        '".$film->y."',
        '".$film->queryURL."',
        '".$film->imdbID."'
        )";
    runQuery($sql);
}

function addSection($key,$title){
    $recentlyAddedPlexXML = new SimpleXMLElement(getData(PLEX_URL.$key.PLEX_RECENT));
    $latestNewTime = $recentlyAddedPlexXML->Video[0]['addedAt'];

    $sql = "INSERT INTO `Sections` VALUES(
        NULL,
        '".$key."',
        '".$title."',
        '".$latestNewTime."'
        )";
    runQuery($sql);
    return getLastInsertedID();
}

function getLastUpdate($sectionID){
    $sql = "SELECT `LAST_UPDATE` FROM `Sections` WHERE `ID` = ".$sectionID;
    $result = runQuery($sql);
    $value = mysqli_fetch_assoc($result);
    return (int)$value['LAST_UPDATE'];
}

function setLastUpdate($sectionID, $value){
    $sql = "UPDATE `Sections` SET `LAST_UPDATE`= ".$value." WHERE `ID`=".$sectionID;
    runQuery($sql);
}
