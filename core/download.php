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
require_once('TorrentClientFactory.php');
require_once('../config.inc.php');

if(isset($_GET['magnetLink'])){
	$MagnetURI = $_GET['magnetLink'];
	//open the connection to transmission rpc server
	$transmissionRPC = TorrentClientFactory::getTorrentClient("transmission",TRANSMISSION_RPC,TRANSMISSION_RPC_USER,TRANSMISSION_RPC_PASS);
	$params = array();
	$params['filename'] = $MagnetURI;
	$result = $transmissionRPC->addTorrent($MagnetURI);
	if($result){
		echo json_encode($result);
	}
}else{
	echo "Fail";
}
