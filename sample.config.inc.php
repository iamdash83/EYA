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

// Configuration file for plex-eya - fill in details then remove sample from filename
define("ROOT_DIR", "/EYA/");//e.g. /EYA/ will work with localhost/EYA or 192.168.1.2:8080/EYA
// Max number of results for YIFY API to return
define("LIMIT", 50);// DON'T go over 50. You'll make yts cry.

// Domain to use for YTS API
define("YIFY_DOMAIN", "http://yts.im/");

// YIFY API - Put on application wide strings
define("YIFY_API", YIFY_DOMAIN."api/list.json?limit=".LIMIT);

// Host where mySQL is
define('SQL_HOST', 'localhost');

// Host for Plex and transmission
define('HOST', 'http://localhost');

// Transmission RPC Config. Define the port, and authentication details.  It is recommended authentication is turned on
define('TRANSMISSION_PORT',"9091");
define('TRANSMISSION_RPC', HOST.":".TRANSMISSION_PORT."/transmission/rpc");
//If Transmission Authentication is off leave these fields null
define('TRANSMISSION_RPC_USER',null);
define('TRANSMISSION_RPC_PASS',null);

// Category number of plex movies XML
define("PLEX_SECTIONS", ":32400/library/sections/");
define("PLEX_URL", HOST.PLEX_SECTIONS);
define("PLEX_ALL", "/all");
define("PLEX_RECENT", "/recentlyAdded");

//MySQL config
define('DB', "DB");
define('DB_USER', 'DB_USER');
define('DB_PASS', 'DB_PASS');

//configuration strings
define('CFG_3D_ENABLED', '3D_ENABLED');
define('CFG_3D_SECTION_ID', '3D_SECTION_ID');
define('CFG_SECTION_ID', 'SECTION_ID');
