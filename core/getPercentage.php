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
require_once('transmissionRPC.php');
require_once('../config.inc.php');

$param = $_REQUEST['xt'];
if(!isset($param)){
    die();
}
//open the connection to transmission rpc server
$transmissionRPC = new TransmissionRPC(TRANSMISSION_RPC,TRANSMISSION_RPC_USER,TRANSMISSION_RPC_PASS);
//get all the running torrents in transmission
$runningTorrents = $transmissionRPC->get(
    array(),    // id's
    array(      // Fields
            "percentDone",
            "magnetLink",
            "status"
        )
    );
    $found = false;
    if(isset($runningTorrents->arguments->torrents)){
        $runningTorrents = $runningTorrents->arguments->torrents;
    }else{
        $runningTorrents = array();
    }

    for($i =0 ; $i < count($runningTorrents) ; $i++){
        $url = $runningTorrents[$i]->magnetLink;
        $url = parse_url($url);
        parse_str($url['query'], $query);

        if($query['xt'] == $param){
            if(isset($runningTorrents[$i]->percentDone)){
                $runningTorrents[$i]->percentDone = (string)(((float)$runningTorrents[$i]->percentDone)*(float)100.0);
            }else{
                $runningTorrents[$i]->percentDone = "0";
            }

            $ret = new StdClass();
            if(isset($runningTorrents[$i]->status)){
                $ret->status = $runningTorrents[$i]->status;    
            }else{
                $ret->status = 0;
            }
                

            
            $ret->percentage = $runningTorrents[$i]->percentDone;
            echo json_encode($ret);
            $found = true;
            break;
        }
        //echo $query['xt'];
    }
    if(!$found){
        $ret = new StdClass();
        $ret->status = -1;
        echo json_encode($ret);
    }
