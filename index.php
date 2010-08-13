<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php
include "./Event.php";
include "./EventManager.php";

$event = new Event("9:20am","11:40am",1);
$events[]=$event;
$event = new Event("10:40am", "11:30am",2);
$events[]=$event;
$event = new Event("10:00am","1:00pm",3);
$events[]=$event;
$event = new Event("2:00pm","3:30pm",4);
$events[]=$event;
$event = new Event("3:00pm","5:00pm",5);
$events[]=$event;
$event = new Event("4:00pm","7:00pm",6);
$events[]=$event;
$event = new Event("6:00pm","6:30pm",7);
$events[]=$event;
$event = new Event("7:30pm","9:00pm",8);
$events[]=$event;



$em = &new EventManager;
$em->layOutDay($events);

$num = count($events);

//transfer to javascript

print "<script type='text/javascript'>\n";

print "var eventArr = new Array(".$num.");\n";

$i=0;
foreach($events as &$e){
	print "eventArr[".$i."]= new Array(4);\n";
	print "eventArr[".$i."][0]=".($e->get_left()+55)."\n";
	print "eventArr[".$i."][1]=".($e->get_top()+10)."\n";
	print "eventArr[".$i."][2]=".($e->get_width()-60)."\n";
	print "eventArr[".$i."][3]=".$e->get_length()."\n";
	$i++;
}
print "</script>\n";				
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Test</title>
</head>
<body>
<canvas id="eventCanvas" style="position:absolute; left:100px;" 
	height="<?print (EventManager::$canvas['height']+40).'px'?>+" width="<?print EventManager::$canvas['width'].'px'?>">
Your browser does not support the canvas element.
</canvas>

<script type="text/javascript">

var c=document.getElementById("eventCanvas");
var cxt=c.getContext("2d");

//time lable
cxt.lineWidth = 0.5;
cxt.strokeStyle ="#A8A49E";
var lables =["9:00 AM","10:00 AM","11:00 AM","12:00 PM","1:00 PM","2:00 PM","3:00 PM",
			 "4:00 PM","5:00 PM","6:00 PM","7:00 PM","8:00 PM", "9:00 PM"];
for(i=0;i<13;i++){
	var h = 10+i*60;
	cxt.fillText(lables[i],0,h);
	cxt.beginPath();
	cxt.moveTo(50,h);
	cxt.lineTo(<?print (EventManager::$canvas['width'])?>, h);
	cxt.stroke();
	
}
cxt.lineWidth = 1;
cxt.strokeStyle ="#000";
cxt.strokeRect(50,0,<?print (EventManager::$canvas['width']-50)?>,<? print (EventManager::$canvas['height']+40)?>);

//draw events
cxt.globalAlpha = 0.2; 
cxt.fillStyle ="green";
var i=0;
for(i=0;i<eventArr.length;i++){	
	cxt.fillRect(eventArr[i][0],eventArr[i][1],eventArr[i][2],eventArr[i][3]);
}

</script>




</body>
</html>