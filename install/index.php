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
include '../core/head.php';
openDatabase();
//global $host;
function getPlexCategories(){
    //var_dump(getData());
    global $host;
    $sections = new SimpleXMLElement(getData(PLEX_URL));
    $size = $sections['size'];
    $sectionList = array();
    for($i = 0 ; $i < $size ; $i++){
        //var_dump($sections->Directory[$i]);
        if($sections->Directory[$i]['type']=="movie"){
            $section = new StdClass();
            $section->title = (string)$sections->Directory[$i]['title'];
            $section->key = (string)$sections->Directory[$i]['key'];
            array_push($sectionList, $section);
        }
    }
   // var_dump($sectionList);
    return $sectionList;
}

function createDropDown($list,$name){
    echo "<select name=".$name."><option value='-1'>Please Select a Library</option>";
        foreach($list as $item){
            echo "<option value='".json_encode($item)."'>".$item->title."</option>";
        }
    echo "</select>";
}

echo "<h2>Installation Page</h2>";


$movieSections = getPlexCategories();
?>
<form action="install.php" method="post">
<p>Please select the Plex Library you wish to link EYA to (must be set):<?createDropDown($movieSections,"Movies")?></p>

<p>If you wish to enable 3D on EYA, please select the Media Library you wish to link (optional):<?createDropDown($movieSections,"3DMovies")?></p>
<input type="Submit" value="Install"></input>
</form>
<?
//initDB($is3D);
closeDatabase();


?>
