<?php
	$con = new PDO("mysql:host=localhost;dbname=musicman", "musicman", "sql");
	if (isset($_POST['queuealbum'])) {
		$album = $_POST['queuealbum'];
		$tracklist = array();
		foreach ($con->query("SELECT DISTINCT title, track FROM music WHERE album='$album' ORDER BY track") as $row) {
			array_push($tracklist, $row['path']);
		}
		foreach ($tracklist as $row) {
			$path = $row['path'];
			echo shell_exec("/bin/echo -n \"enqueue $path\" | nc -U /var/www/music/vlc.sock");
		}
		echo 'hellos';
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
		echo shell_exec("/bin/echo -n \"$action\" | nc -U /var/www/music/vlc.sock");
		exit;
	}
	if (isset($_POST['script'])) {
		$script = $_POST['script'];
		if ($script == "longhelp") {
			echo shell_exec("./longhelp");
		} else if ($script == "now_playing") {
			echo shell_exec("./now_playing");
		} else if ($script == "playlist") {
			echo shell_exec("./playlist");
		} else if ($script == "sockback") {
			echo shell_exec("./sockback");
		}
		exit;
	}
	if (isset($_POST['albums'])) {
		$artist = $_POST['albums'];
		$con = new PDO("mysql:host=localhost;dbname=musicman", "root", "l18w38");

		foreach ($con->query("SELECT DISTINCT album FROM music WHERE artist='$artist' ORDER BY album") as $row) {
			$album = $row['album'];
			echo "<option ondblclick=\"playalbum('$album')\">";
			echo $row['album'];
			echo "</option>";
		}
	}
	if (isset($_POST['songs'])) {
		$album = $_POST['songs'];
		$con = new PDO("mysql:host=localhost;dbname=musicman", "root", "l18w38");
		$json = '[';
		foreach ($con->query("SELECT DISTINCT title, track, path FROM music WHERE album='$album' ORDER BY track") as $row) {
			$path = $row['path'];
			$track = $row['track'];
			$title = $row['title'];
			$json .= '{';
			$json .= "track:\"$track\",";
			$json .= "title:\"$title\",";
			$json .= "path:\"$path\"";
			$json .= '},';
		}
		$json = substr($json, 0, strlen($json) - 1) . "]";

		echo $json;
	}
?>
