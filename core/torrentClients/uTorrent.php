<?
require_once('apis/uTorrentRemote.php');
class uTorrent extends TorrentClient{
	protected $api;
	function __construct($host,$port,$user,$pass){
		$this->api = new uTorrentRemote($host,$port,$user,$pass);
	}

	function addTorrent($magnetURI){
    	return $this->api->torrentAdd($magnetURI);
    }

    function getTorrents(){
    	return $this->api->getTorrents();
    }

    function getPercentage(){

    }
}