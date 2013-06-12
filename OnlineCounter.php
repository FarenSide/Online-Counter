<?php

/*
 __PocketMine Plugin__
name=Online Counter
description=Count the number of players who joins the server
version=1.1
author=Junyi00
class=OnlineCounter
apiversion=8
*/

class OnlineCounter implements Plugin {
	private $api, $path, $counter, $countf;

	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init() {
		
		$this->api->addHandler("player.join", array($this, "eventHandler"));	
	    $this->api->console->register("counter", "Function for the OnlineCounter plugin", array($this, "CCommand"));
		$this->path = $this->api->plugin->createConfig($this, array());
		$this->counter = true;
		$this->countf = 0;
	}
	
	public function __destruct(){
	
	}

	public function eventHandler($data, $event) {
		
		switch($event) {
			
			case "player.join":
				if ($this->counter == true) {
					
					$this->countf = $this->countf + 1;
					
					$day = date("d");
					$month = date("M");
					$year = date("Y");
					
					$cfg = $this->api->plugin->readYAML($this->path . "config.yml");					
						
					$counts = array("$day $month $year" => array(
						"number of people who joined" => $this->countf
							));
					$this->overwriteConfig($counts);
					
				}
			
		}
		
	}

    public function CCommand($cmd, $arg, $issuer) {
    	
    	switch($cmd) {
    		
    		case "counter":
    			$s1 = $arg[0];
    		
    			switch($s1) {
    				case "stop":
    			 	   if ($this->counter == true) {
            	    	    $this->counter = false;
            	    	    if (($issuer instanceof Player)) {
               	  	    		$issuer.sendChat("[Online Counter] Counting stopped");            	    	    }
            	    	    else {
            	    	    	$output .= "[Online Counter] Counting stopped";
            	    	    }
                  	 		break;
    			    	}
    		 	   		else {
    		    			if (($issuer instanceof Player)) {
               	  	    		$issuer.sendChat("[Online Counter] Counting is already stopped");            	    }
            	    	    else {
    		  	  				$output .="[Online Counter] Counting is already stopped";
            	    	    }
    		   	 			break;
    		    	
    		    		}
                
            		case "continue":   
              	 	    if ($this->counter == false) {
                		    $this->counter = true;
                		    
                		    if (($issuer instanceof Player)) {
               	  	    		$issuer.sendChat("[Online Counter] Counting continued");            	    }
            	    	    else {
                  		    	$output .= "[Online Counter] Counting continued";
            	    	    }
                 		    break;
    		 	   		}
    		    		else {
    		    			
    		    			if (($issuer instanceof Player)) {
               	  	    		$issuer.sendChat("[Online Counter] Counting is still on-going...");            	    }
            	    	    else {
    		    				$output .="[Online Counter] Counting is still on-going...";
            	    	    }
    		    			break;
    		    	
    		    		}
                
            		case "get":
            			if (($issuer instanceof Player)) {
               	  	    		$issuer.sendChat("Number of players who joined today: $this->countf");            	}
            	    	else {
                		$output .= "Number of players who joined today: $this->countf";
            	    	}
                		break;
                	
                	case "reset":
                		$this->countf = 0;
                	
                		$day = date("d");
						$month = date("M");
						$year = date("Y");
					
						$cfg = $this->api->plugin->readYAML($this->path . "config.yml");					
						
						$counts = array("$day $month $year" => array(
							"number of people who joined" => 0
								));
						$this->overwriteConfig($counts);

                	default:	
                		if (($issuer instanceof Player)) {
               	  	    	$issuer.sendChat("[Online Counter] Usage: /counter <get/stop/continue>");            	
               	  	    }
            	    	else {
                			$output .= "[Online Counter] Usage: /counter <get/stop/continue>";
            	    	}
                	
    			}                	
                    
    	}
    	
    	return $output;
    	
    } 
    
    private function overwriteConfig($dat){
		$cfg = array();
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		$result = array_merge($cfg, $dat);
		$this->api->plugin->writeYAML($this->path."config.yml", $result);
	}
    
}
    
?>
