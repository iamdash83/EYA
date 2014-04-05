<?

require_once('TorrentClient.php');
class TorrentClientFactory{
	public static function getTorrentClient($clientName = "transmission", $host = "", $user = "", $pass = "",$port = "") {
        switch ($clientName) {
        	case 'transmission':
        		$torrentRemote = new Transmission($host,$user,$pass);
        		break;
        	case 'utorrent':
        		$torrentRemote = new uTorrent($host,$port,$user,$pass);
        		break;
        	default:
        		# code...
        		break;
        }
        return $torrentRemote;
    }
}