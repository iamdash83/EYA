<?
require_once('apis/transmissionRPC.php');
class Transmission extends TorrentClient{
	protected $api;
	function __construct($host,$user,$pass){
		$this->api = new TransmissionRPC($host,$user,$pass);
		//var_dump($api);
	}

	function addTorrent($magnetURI){
    	$params = array();
		$params['filename'] = $magnetURI;
		$result = $this->api->add($magnetURI,$params);
		return $result;
    }

    function getTorrents(){
    	$runningTorrents = $this->api->get(
	    array(),    // id's
	    array(      // Fields
	            "percentDone",
	            "magnetLink",
	            "status"
	        )
	    );
	    if(isset($runningTorrents->arguments->torrents)){
	        $runningTorrents = $runningTorrents->arguments->torrents;
	    }else{
	        $runningTorrents = array();
	    }
	    return $runningTorrents;
    }

   	function getPercentage(){

    }
}