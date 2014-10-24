<html>
	<?php
	function mysql_fetch_all($result)
	{
	    $resultArray = array();
	    while(($resultArray[] = mysql_fetch_assoc($result)) || array_pop($resultArray));
	    return $resultArray;
	}
	?>
	<script type="text/javascript" src="jquery.js"></script>
	<script>
		function play() {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					action: 'pause'
				},
				success: function(response){
					// alert(response);
				}
			});
		}
		function getplaylist() {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					script: 'playlist'
				},
				success: function(response){
					
					alert(response);
				}
			});
		}
		function getalbums(artist) {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					albums: artist
				},
				success: function(response) {
					document.getElementById('album').innerHTML+=response;
				}
			});
		}
		function playalbum(album) {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					action: 'clear'
				},
				success: function(response){
					// alert(response);
				}
			});
			document.getElementById('songs').innerHTML = "";
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					queuealbum: album
				},
				success: function(response){
					// document.getElementById('songs').innerHTML += "<option onclick=\"goto($track)\">" + json[i]['title'] + "</option>";
				}
			});
						// document.getElementById('songs').innerHTML=response;
					}
					$.ajax({
						url: "ajax.php",
						type: "post",
						data: {
							action: 'goto 1'
						},
						success: function(response){
							// alert(response);
						}
					});
				}
			});
		}

		function queuealbum() {
			var album = WhatsSelected('album');
			alert(album);
			for (var i=0;i<album.length;i++){
				// alert(album[i]);
				$.ajax({
					url: "ajax.php",
					type: "post",
					data: {
						queuealbum: album[i]
					},
					success: function(response){
						alert(response);
					}
				});
			}
		}

		function add(path) {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					action: 'add ' + path
				},
				success: function(response){
					// alert(response);
				}
			});
		}
		function queue(path) {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: {
					action: 'enqueue ' + path
				},
				success: function(response){
					// alert(response);
				}
			});
		}

		function artistselect() {
			// document.getElementById("artist");

			document.getElementById("album").innerHTML = "";
			var selection = WhatsSelected("artist");
			for (var i=0;i<selection.length;i++) {
				getalbums(selection[i]);
			}
		}

		function WhatsSelected(sel) {
			var SelectedOptions = document.getElementById(sel);
			var retval = new Array();
			var cnt = 0;
			for (var i=0;i<SelectedOptions.length;i++) {
				if (SelectedOptions.options[i].selected) {
					retval[cnt++] = SelectedOptions.options[i].text;
				}
			}
			return retval;
		}

		window.onload = function()
		{
			// getplaylist();
		};
	</script>

	<style>

	</style>

	<button type="button" onclick="play()">Play</button>
	<button type="button" onclick="getplaylist()">Playlist</button>
	<button type="button" onclick="playalbum()">Play Album</button>
	<br>
	<select id="artist" name="artist" width="50%" size="14" multiple onchange="artistselect()">
		<?php
			$con = new PDO("mysql:host=localhost;dbname=musicman", "root", "l18w38");

			// $result = mysql_fetch_all(mysql_query("SELECT DISTINCT artist FROM music ORDER BY artist"));
			foreach ($con->query("SELECT DISTINCT artist FROM music ORDER BY artist") as $row) {
				$artist = $row['artist'];
				echo "<option>";
				echo $row['artist'];
				echo "</option>";
			}
		?>
	</select>
	<select name="album" id="album" width="50%" size="14" multiple>
	</select>
	<br>
	<select name="songs" id="songs" width="100%" size="40" multiple>
	</select>
	
</html>