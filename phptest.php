<?php
	ini_set('display_errors',1);  
	error_reporting(E_ALL);

	$possible_url = array("register", "add_sticker", "remove_sticker", "add_equipment", "remove_equipment", "get_inventory", "testing");
	$value = "An error has occurred";

	function register($username, $fname, $lname){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$username = $db_player->quote($username);
		$fname = $db_player->quote($fname);
		$lname = $db_player->quote($lname);
		
		$rows = $db_player->prepare("INSERT INTO players 
			(pid, username, fname, lname, level, exp, gold, 
				gem, current_equipment, max_equipment, current_sticker, max_sticker) 
			VALUES (NULL, $username, $fname, $lname, 1, 1, 100, 100, 0, 0, 0, 0);");
		$rows->execute();
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);

		$rows = $db_player->prepare("SELECT * FROM players");
		$rows->execute();
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){	?>	
			<p><?= $row["Username"] ?></p>
			<p><?= $row["Fname"] ?></p>	
			<p><?= $row["Lname"] ?></p>
		<?php
		}

		echo "<p> Results is empty </p>";
		echo "<p> Register was found </p>"; 
		$arr = array('status' => 200);
		return $results;
	}

	function add_sticker($pid, $sid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$db_reference = new PDO("mysql:dbname=reference_schema;host=localhost", "root", "powerade");
		$rows = $db_reference->prepare("SELECT * FROM sticker_table WHERE sid = :sid;");
		$rows->execute(array(':sid' => $sid));
		$results=$rows->fetch(PDO::FETCH_ASSOC);
		
		$rows1 = $db_player->prepare("INSERT INTO players_sticker 
			(pstid, pid, sid, name, current_level, current_exp, current_speed, 
				current_reach, spaid, saaid, evolve) 
			VALUES (NULL, :pid, :sid, :name, :current_level, :current_exp, :current_speed, 
				:current_reach, :spaid, :saaid, :evolve);");
		$rows1->execute(array(':pid' => $pid, ':sid' => $results["sid"], ':name' => $results["name"], 
			':current_level' => 1, ':current_exp' => 0, 
			':current_speed' => $results["initial_speed"],
			':current_reach' => $results["initial_reach"], 
			':spaid' => $results["spaid"], 
			':saaid' => $results["saaid"], 
			':evolve' => $results["evolve"]));
		$sticker_results=$rows1->fetch(PDO::FETCH_ASSOC);
		return array('status' => 200);
	}

	function add_equipment($pid, $eid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$db_reference = new PDO("mysql:dbname=reference_schema;host=localhost", "root", "powerade");

		$rows = $db_reference->prepare("SELECT * FROM equipment_table WHERE eid = :eid");
		$rows->execute(array(':eid' => $eid));
		$results=$rows->fetch(PDO::FETCH_ASSOC);	
		
		$rows1 = $db_player->prepare("INSERT INTO players_equipment 
			(etid, pid, eid, pstid, name, category, equipped, leader, 
				current_level, current_exp, current_speed, current_reach, eaid) 
			VALUES (NULL, :pid, :pstid, :eid, :name, :category, :equipped, :leader, 
				:current_level, :current_exp, :current_speed, :current_reach, :eaid);");
		$rows1->execute(array(':pid' => $pid, ':eid' => $eid, ':name' => $results["name"], ':pstid' => -1,
			':category' => $results["category"],
			':equipped' => 0,
			':leader' => 0,
			':current_level' => 1,
			':current_exp' => 0,
			':current_speed' => $results["initial_speed"],
			':current_reach' => $results["initial_reach"], 
			':eaid' => $results["eaid"]));

		$sticker_results=$rows1->fetch(PDO::FETCH_ASSOC);
		return array('status' => 200);
	}

	function remove_equipment($etid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$db_reference = new PDO("mysql:dbname=reference_schema;host=localhost", "root", "powerade");
		$rows = $db_player->prepare("DELETE FROM players_equipment WHERE etid = :etid");
		$rows->execute(array(':etid' => $etid));
		$results=$rows->fetch(PDO::FETCH_ASSOC);
		return array('status' => 200);
	}

	function remove_sticker($pstid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$db_reference = new PDO("mysql:dbname=reference_schema;host=localhost", "root", "powerade");
		$rows = $db_player->prepare("DELETE FROM players_sticker WHERE pstid = :pstid");
		$rows->execute(array(':pstid' => $pstid));
		$results=$rows->fetch(PDO::FETCH_ASSOC);
		return array('status' => 200);
	}

	function get_inventory($pid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$rows = $db_player->prepare("SELECT * FROM players_equipment WHERE pid = :pid");
		$rows->execute(array(':pid' => $pid));

		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){	?>	
			<p><?= $row["name"] ?></p>
			<p><?= $row["pstid"] ?></p>	
			<p><?= $row["equipped"] ?></p>
		<?php
		}

		return $results;
	}

	function get_stickers($pid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$rows = $db_player->prepare("SELECT * FROM players_stickers WHERE pid = :pid");
		$rows->execute(array(':pid' => $pid));

		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}	

	function get_friends($pid){
		$db_player = new PDO("mysql:dbname=player_schema;host=localhost", "root", "powerade");
		$rows = $db_player->prepare("SELECT * FROM friends_table WHERE pid = :pid");
		$rows->execute(array(':pid' => $pid));

		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}	

	function add_friend($pid1, $pid2){

	}

	function remove_friend($pid1, $pid2){

	}
	
	/*
	* Sanity check if shit is broken
	*/
	function testing(){
		$db_reference = new PDO("mysql:dbname=reference_schema;host=localhost", "root", "powerade");
		$rows = $db_reference->prepare("SELECT * FROM item");
		$rows->execute();
		$results=$rows->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){	?>		
			<p><?= $row["Name"] ?></p>
		<?php
		}

		return $results;
	}
	
?>

<html>
	<head>Testing Code</head>
	<body>	
		More testing
		<?php
			if(isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
			{
				echo "<p>Action was found</p>";

				switch ($_GET["action"])	
			    {
			       case "register":
			       		if(isset($_GET["firstname"]) && isset($_GET["lastname"]) && isset($_GET["username"])){
			       			$fname = $_GET["firstname"];
			       			$lname = $_GET["lastname"];
			       			$username = $_GET["username"];
			       			/*
			       				echo "<p> $lname </p>";
			       				echo "<p> $fname </p>";
			       				echo "<p> $username </p>";
							*/
			       			$value = register($username, $fname, $lname);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}
			       		
			       		break;
			       	case "add_sticker":
			       		if(isset($_GET["pid"]) && isset($_GET["sid"])){
			       			$pid = $_GET["pid"];
			       			$sid = $_GET["sid"];

			       			$value = add_sticker($pid, $sid);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}
			       		break;
			       	case "remove_sticker":
			       		if(isset($_GET["pstid"])){
			       			$pstid = $_GET["pstid"];

			       			/*
			       				echo "<p> $lname </p>";
			       				echo "<p> $fname </p>";
			       				echo "<p> $username </p>";
							*/
			       			$value = remove_sticker($pstid);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}

			       		break;
			       	case "get_inventory":
			       		if(isset($_GET["pid"])){
			       			$pid = $_GET["pid"];

			       			/*
			       				echo "<p> $lname </p>";
			       				echo "<p> $fname </p>";
			       				echo "<p> $username </p>";
							*/
			       			$value = get_inventory($pid);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}
			       		
			       		break;
			       	case "add_equipment":
			       		if(isset($_GET["pid"]) && isset($_GET["eid"])){
			       			$pid = $_GET["pid"];
			       			$eid = $_GET["eid"];

			       			/*
			       				echo "<p> $lname </p>";
			       				echo "<p> $fname </p>";
			       				echo "<p> $username </p>";
							*/
			       			$value = add_equipment($pid, $eid);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}
			       		break;
			       	case "remove_equipment":
			       		if(isset($_GET["etid"])){
			       			$etid = $_GET["etid"];

			       			$value = remove_equipment($etid);
			       		}
			       		else{
			       			$value = array('status' => 800);
			       		}
			       		break;			       		
			       	case "testing":
			       		$value = testing();
			       		break;
			    }
			}

	       	//echo "<p> $value </p>"; 
		?>

	</body>
</html>

<?php 
	exit(json_encode($value));
?>