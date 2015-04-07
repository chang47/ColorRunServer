<?php
	function register(){
		$db_reference = new PDO("mysql:dbname=reference;host=localhost", "root", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=localhost", "root", "powerade");
		$results = array();
		$id = -1

		while(count($results) == 0){
			$min = 1;
			$max = 2147483647;
			$id = rand($min, $max);

			$pid = $db_player->quote($id);

			$rows = $db_player->prepare("SELECT * FROM player 
											WHERE Id = :pid
											LIMIT 1");

			$rows->execute(array(':pid' => $id));
			$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		}

		$player_key = $db_player->quote("player");
		$id_key = $db_player->quote("Id");
		$username_key = $db_player->quote("Username");
		$fname_key = $db_player->quote("Fname");
		$lname = $db_player->quote("Lname");
		$pid = $db_player->quote($pid);

		$db_player->prepare("INSERT INTO player(Id, Username, Fname, Lname, 
			Level, Experience, Gold, Gems, Friends, Items, Equipments, 
			Speed, Endurance`, Power) VALUES (:pid, :user, :fname, :lname,
			1, 1, 100, 0, none, none, none, 5, 5, 5)"); 

		$rows->execute(array(':fname' => $_GET["firstname"], ':lname' => $_GET["lastname"], 'pid' => $id, 'user' => $_GET["username"]));
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		return $id
	}

	function update_player_stats(){
		$db_reference = new PDO("mysql:dbname=reference;host=54.148.77.196", "root", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=54.148.77.196", "root", "powerade");

	}

	function update_player_inventory(){
		$db_reference = new PDO("mysql:dbname=reference;host=54.148.77.196", "root", "powerade");
		$db_player = new PDO("mysql:dbname=player;host=54.148.77.196", "root", "powerade");

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
		echo "Is it here?";

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
	       		if (isset($_GET["username"] && isset($_GET["firstname"]  && isset($_GET["lastname"] ){
					$value = register();
					echo $value;
	       		}
	       		else{
	       			$value = "Missing argument";
	    			echo $value;
	       		}

	       		break;
	    }
	}

	exit(json_encode($value));
?>	

<html>
	<head>Testing Code</head>
	<body>
		More testing
		<p> 
		<?php
			if (isset($_GET["username"] && isset($_GET["firstname"]  && isset($_GET["lastname"] ){
				$value = register();
				echo $value;
	       	}
		?>
		</p>
	</body>
</html>