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
function startInstall(){
    createConfigTable();
    createPlexVideosTable();
    createSectionsTable();
}
include '../core/head.php';
flushOutput();
$movies;
$movies3D;
if(isset($_POST['Movies']) && isset($_POST['3DMovies'])){
    if($_POST['Movies']==-1){
        die("Movies library not set");
    }
    $movies = json_decode(stripslashes($_POST['Movies']));

    if($_POST['3DMovies']==-1){
        $movies3D = $_POST['3DMovies'];
    }

    $movies3D = json_decode(stripslashes($_POST['3DMovies']));
}else{
    die("Params not set");
}

openDatabase();

startInstall();

echo "Processing Movies (This may take a while)...</br>";
flushOutput();
$sectionID = addSection($movies->key, $movies->title);
setConfig(CFG_SECTION_ID, $sectionID);
initDB(getConfig(CFG_SECTION_ID));
if(is_int($movies3D) && $movies3D==-1){
    setConfig(CFG_3D_ENABLED,0);
}else{
    echo "<br/>Processing 3D Movies (This may take a while)...</br>";
    flushOutput();
    setConfig(CFG_3D_ENABLED,1);
    $sectionID = addSection($movies3D->key, $movies3D->title);
    setConfig(CFG_3D_SECTION_ID, $sectionID);
    initDB(getConfig(CFG_3D_SECTION_ID)); 
}
echo "<br/>Go to <a href='".ROOT_DIR."'>Home to continue.</a>";
closeDatabase();