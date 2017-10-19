<?php

$debugfile = "/tmp/focuserdebug.log";
$positionFile = "/home/www-data/position.data";
$backlash = 0;

function debug($message) {
	global $debugfile;

	$fp = fopen($debugfile, 'a');
	fwrite($fp, date("d/M/Y H:i", time())." ".$message);
	fclose($fp);
}

function loadPosition() {
       global $positionFile;

	$temp = 25000;
	if (file_exists($positionFile)) {
		$temp = unserialize(file_get_contents($positionFile));
	}
	return (int)$temp;
}

function savePosition($position) {
	global $positionFile;

	file_put_contents($positionFile, serialize($position));
}

function uptime() {
	$str   = @file_get_contents('/proc/uptime');
	$num   = floatval($str);
	$secs  = fmod($num, 60); $num = (int)($num / 60);
	$mins  = $num % 60;      $num = (int)($num / 60);
	$hours = $num % 24;      $num = (int)($num / 24);
	$days  = $num;

	return $days." days ".$hours.":".$mins.":".$secs;
}

function printStatus() {
	global $backlash;

	$temp = Array();
//	$temp["uptime"] = uptime();
//	$temp["speed"] = 1;
 //       $temp["temperature"] = null;
  //      $temp["temperatureCompensationOn"] = false;
   //     $temp["backlashSteps"] = $backlash;
        $temp["absolutePosition"] = loadPosition();
        $temp["maxPosition"] = 50000;
        $temp["minPosition"] = 0;
//        $temp["gearBoxMultiplier"] = 4;
	print(json_encode($temp));
}

$currentPosition = loadPosition();
if ($_GET["op"] == "forwardrelative") {
        if ($_GET["back"] != 1) {
		debug("Morving relative forward");
	}
	$steps = $_GET["steps"];
        if ($_GET["back"] != 1) {
		debug(" ".$steps." steps");
	}
	debug("\n");
	exec("sudo python /home/cedric/motor.py forward ".$steps);
	savePosition($currentPosition + $steps);
	if ($_GET["back"] == 1) {
		header("Location: index.php");
	} else {
		printStatus();
	}
} else if ($_GET["op"] == "backwardrelative") {
        if ($_GET["back"] != 1) {
	        debug("Morving relative backward");
	}
        $steps = $_GET["steps"];
        if ($_GET["back"] != 1) {
	        debug(" ".$steps." steps");
	}
	debug("\n");
        exec("sudo python /home/cedric/motor.py backward ".$steps);
	savePosition($currentPosition - $steps);
        if ($_GET["back"] == 1) {
                header("Location: index.php");
        } else {
		printStatus();
	}
} else if ($_GET["op"] == "move") {
        if ($_GET["back"] != 1) {
                debug("Morving absolute");
        }
        $position = $_GET["position"];
        if ($_GET["back"] != 1) {
                debug(" ".$position." position");
        }
	$steps = $position - $currentPosition;
        if ($_GET["back"] != 1) {
                debug(" with ".$steps." steps");
        }
	debug("\n");

	$direction = "forward";
	if ($steps<0) {
		$direction = "backward";
	} else {
		$direction = "forward";
	}
        exec("sudo python /home/cedric/motor.py ".$direction." ".abs($steps));
        savePosition($position);
        if ($_GET["back"] == 1) {
                header("Location: index.php");
        } else {
		printStatus();
	}
} else if($_GET["op"] == "position") {
	print(loadPosition());
} else if($_GET["op"] == "status") {
	printStatus();
} else {
	print("<h2>HomeMade Focuser control</h2>");
	print("Current position: ".$currentPosition."<br>\n");
	print("<form action=\"index.php?op=move\">\n");
	print("<input type=\"hidden\" name=\"op\" value=\"move\">\n");
	print("Absolute position: <input name=\"position\"><br>\n");
	print("<input type=\"submit\"><br>\n");
	print("</form>\n");

	print("Shortcuts:<br>\n");
	print("Forward <a href=\"?op=forwardrelative&steps=100&back=1\">+100</a> <a href=\"?op=forwardrelative&steps=10&back=1\">+10</a> <a href=\"?op=forwardrelative&steps=1&back=1\">+1</a><br>\n");
        print("Backward <a href=\"?op=backwardrelative&steps=100&back=1\">-100</a> <a href=\"?op=backwardrelative&steps=10&back=1\">-10</a> <a href=\"?op=backwardrelative&steps=1&back=1\">-1</a><br>\n");

	printStatus();
}


?>
