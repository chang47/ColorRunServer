<?php
	function register(){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");
		$results = array();
		$id = -1
		while(count($results) == 0){
			$min = 1;
			$max = 2147483647;
			$id = rand($min, $max);

			$pid = $db_player->quote($id);

			$rows = $db_player->prepare("SELECT * FROM player p 
											WHERE p.id = :pid
											LIMIT 1");
			$rows->execute(array(':pid' => $id));
			$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		}

		return $id
	}

	function update_player_stats(){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");

	}

	function update_player_inventory(){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");

	}

	function get_player_inventory($id){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");
		$fname = $db_player->quote($fname);
		$lname = $db_player->quote($lname);
		$pid = $db_player->quote($pid);

		//Break any ties with same name via film count and id
		$rows = $db_player->prepare("SELECT p.id, p.speed, p.inventory FROM player p 
										WHERE p.first_name = :fname
										AND p.last_name = :lname
										AND p.id = :pid
										LIMIT 1");

		$rows->execute(array(':fname' => $_GET["firstname"] . "%", ':lname' => $_GET["lastname"], 'pid' => $_GET["pid"]));
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($results);		
		return $results;
	}

	function get_player_stats($fname, $lname, $id){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");
		$fname = $db_player->quote($fname);
		$lname = $db_player->quote($lname);
		$pid = $db_player->quote($pid);

		//Break any ties with same name via film count and id
		$rows = $db_player->prepare("SELECT p.id, p.speed, p.inventory FROM player p 
										WHERE p.first_name = :fname
										AND p.last_name = :lname
										AND p.id = :pid
										LIMIT 1");

		$rows->execute(array(':fname' => $_GET["firstname"] . "%", ':lname' => $_GET["lastname"], 'pid' => $_GET["pid"]));
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($results);		
		return $results;
	}

	function get_friend_list($fname, $lname, $id){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "brian", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "brian", "powerade");
		$fname = $db_player->quote($fname);
		$lname = $db_player->quote($lname);
		$pid = $db_player->quote($pid);

		//Break any ties with same name via film count and id
		$rows = $db_player->prepare("SELECT p.friends FROM player p 
										WHERE p.first_name = :fname
										AND p.last_name = :lname
										AND p.id = :pid
										LIMIT 1");

		$rows->execute(array(':fname' => $_GET["firstname"] . "%", ':lname' => $_GET["lastname"], 'pid' => $_GET["pid"]));
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($results);		
		return $results;
	}

	$possible_url = array("register", "get_friend_list", "get_player_stats", "get_player_inventory");
	$value = "An error has occurred";


	if(isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
	{
		switch ($_GET["action"])	
	    {
	      case "get_friend_list":
	      	if (isset($_GET["pid"] && isset($_GET["firstname"]  && isset($_GET["lastname"] )
	      		$value = get_friend_list();
	      	else
	      		$value = "Missing argument";
	        break;
	      case "get_player_stats":
	        if (isset($_GET["pid"] && isset($_GET["firstname"]  && isset($_GET["lastname"] )
	      		$value = get_player_stats($_GET["firstname"], $_GET["lastname"], $_GET["pid"]);
	      	else
	      		$value = "Missing argument";
	      	break;
	      case "get_player_inventory":
	      	if (isset($_GET["pid"] && isset($_GET["firstname"]  && isset($_GET["lastname"] )
	      		$value = get_player_equipments($_GET["firstname"], $_GET["lastname"], $_GET["pid"]);
	      	else
	      		$value = "Missing argument";
	        break;
	       case "register":
	       		$value = register();
	    }
	}

	exit(json_encode($value));
?>