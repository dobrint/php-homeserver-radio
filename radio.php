<?php

	$radioplaylist = array(
        array('radio1' , 'Радио 1', 'http://stream.metacast.eu/radio1.opus'),
        array('radio1rock', 'Радио 1 Рок', 'http://stream.metacast.eu/radio1rock.opus'),
        array('melody', 'Мелъди', 'http://193.108.24.6:8000/melody'),
        array('njoy', 'NJOY', 'http://46.10.150.243/njoy.mp3'),
        array('z-rock', 'З-рок', 'http://46.10.150.243/z-rock.mp3'),
        array('magicfm', 'Magic FM', 'https://bss.neterra.tv/rtplive/magicfmradio_live.stream/playlist.m3u8'),
        array('vitosha', 'Vitosha', 'https://bss.neterra.tv/rtplive/vitosharadio_live.stream/playlist.m3u8')
	);

	$output = "";

	if(isset($_POST['radio']) && isset($_POST['action'])) {
        $radio = $_POST['radio'];
        $action = $_POST['action'];
	} else {
        $radio = "";
        $action = "";
	}

	$currentSinkFull = shell_exec("pactl list sinks | grep 'Sink #'");
	$currentSink = explode("#", $currentSinkFull);

	if($radio != "" && $action != "") {
	    if($action == "Play") {
	    	foreach ($radioplaylist as $playlistitem) {
	    		if($radio == $playlistitem[0]) {
	    			shell_exec("ps aux | grep -i mplayer | awk {'print $2'} | xargs kill");
	                shell_exec("mplayer " . $playlistitem[2] );
	    		}
	    	}
	    } elseif($action == "Volume -") {
	        shell_exec("pactl set-sink-volume ".trim($currentSink[1])." -1%");
	    } elseif($action == "Volume +") {
	        shell_exec("pactl set-sink-volume ".trim($currentSink[1])." +1%");
	    } else {
	        shell_exec("ps aux | grep -i mplayer | awk {'print $2'} | xargs kill");
	    }
	}

	$currentVolumeFull = shell_exec("pactl list sinks | grep Volume");
	$currentVolume = explode("/", $currentVolumeFull);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Radio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container" style="padding: 0; margin:0;">
      <div style="background-image: url('radio.jpg'); width: 650px; height: 400px; background-repeat: no-repeat;">
        <div style="width: 290px; margin-left: 125px; padding-top: 95px;">
          <form action="radio.php" method="post" style="margin: 0 auto;">
            <div class="alert alert-warning" role="alert">
                <select class="custom-select mr-sm-2" name="radio" style="width: 248px; margin: 0 auto;">
                	<?php
                		foreach ($radioplaylist as $playlistitem) {
                			$output .= '<option value="'.$playlistitem[0].'" ';
                            if($radio == $playlistitem[0]) $output .= 'selected="selected"';
                            $output .= '>'.$playlistitem[1].'</option>';
                		}
                        echo $output;
                	?>
                </select><br>
                <div class="btn-group" role="group" aria-label="Radio Controls" style="padding-top: 10px; padding-bottom: 5px;">
                    <input type="submit" class="btn btn-sm btn-success" name="action" value="Play">
                    <input type="submit" class="btn btn-sm btn-danger" name="action" value="Stop">
                    <input type="submit" class="btn btn-sm btn-info" name="action" value="Volume -">
                    <input type="submit" class="btn btn-sm btn-primary" name="action" value="Volume +">
                </div><br />
                Current Volume: <?php echo $currentVolume[1]; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>