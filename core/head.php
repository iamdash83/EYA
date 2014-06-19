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
include 'EYA.php';
include 'pagetimer.php';
require_once('transmissionRPC.php');



openDatabase();
$time = startTimer();

$sectionID;
if(!isset($_GET['id'])){
	$sectionID = getConfig(CFG_SECTION_ID);
}else{
	$sectionID=$_GET['id'];
}
// 4 pagination.
$setNumber=1;
if(isset($_GET["page"])){
	$temp = $_GET["page"];
	if ($temp != "" && $temp > 0){
		$setNumber = $temp;
	}
}

$query = (isset($_REQUEST['queryString'])) ? '&queryString='.$_REQUEST['queryString']:'';



?>
<html>
	<head>
		<title>EYA - Easy YTS Adder</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link href="<?=ROOT_DIR?>/css/style.css" rel="stylesheet" type="text/css">
    	<link href='http://fonts.googleapis.com/css?family=Lemon' rel='stylesheet' type='text/css'>
    	<script src="<?=ROOT_DIR?>/lib/jquery-2.1.0.min.js"></script>
		<script src="<?=ROOT_DIR?>/lib/jquery.blockUI.js"></script>
		<script src="<?=ROOT_DIR?>/js/script.js"></script>
	</head>
	<body>	
	<div class="navBar">
		<div class="search">
			<form action="search.php" method="post">
				<span><input type="text" name="queryString" class="search rounded" placeholder="Search..."></span>
				<input type="hidden" name="id" value="<?=sectionID?>">
			</form>
		</div>
		<div class="sectionSelect">
			<?php
			if(getConfig(CFG_3D_ENABLED)==1){
				if($sectionID == getConfig(CFG_SECTION_ID)){
					echo "<p><a class='selected-a' href='?id=".getConfig(CFG_SECTION_ID). $query."'>Movies</a> <a  href='?id=".getConfig(CFG_3D_SECTION_ID). $query."'>3D Movies</a></p>";
				}else if($sectionID == getConfig(CFG_3D_SECTION_ID)){
					echo "<p><a href='?id=".getConfig(CFG_SECTION_ID). $query."'>Movies</a> <a class='selected-a' href='?id=".getConfig(CFG_3D_SECTION_ID). $query."'>3D Movies</a></p>";
				}
				
			}
			//echo "<p><a href='?id=".getConfig(CFG_SECTION_ID). $query."'>Movies</a> <a class='selected-a' href='?id=".getConfig(CFG_3D_SECTION_ID). $query."'>3D Movies</a></p>";
			?>
		</div>
	</div>
	<div class="title">
		<h1>EYA - Easy YTS Adder</h1>
	</div>