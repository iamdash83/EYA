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
include 'core/head.php';
$is3D = false;
if(isset($_GET['is3D'])){
    if($_GET['is3D'] == "true"){
        $is3D = true;
    }
}
if($is3D){
    echo "<h2>Installing 3D films, if you would like to install standard films please run this page without parameters..</h2>";
}else{
    echo "<h2>Installing films, if you would like to install 3D films please run this page with the is3D parameter set to true i.e. install.php?is3d=true</h2>";
}

openDatabase();
initDB($is3D);
closeDatabase();


?>
