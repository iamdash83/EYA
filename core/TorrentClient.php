<?
require_once('torrentClients/Transmission.php');
require_once('torrentClients/uTorrent.php');

abstract class TorrentClient{
	protected $api = null;

    abstract function addTorrent($magnetURI);


    abstract function getTorrents();


    abstract function getPercentage();
}
