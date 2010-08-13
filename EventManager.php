<?php

Class EventManager{
	
Public static $canvas = array('width'=>620,'height'=>720);
Public static $day_start = "9:00am";

function  layOutDay(&$events) {
/**
Lays out events for a single day
set the width, the left and top positions of an event 

@param events     
 An array of calendar events. Each event consists of a start time, end 
 Time (measured in minutes) from 9am, as well as a unique id. The
 Start and end time of each event will be [0, 720]. The start time will
 Be less than the end time.  The array is not sorted.
  
 events is an array of event object
 
 @return TRUE on success or FALSE on failure. 
 

**/

//sort events arrcording to starttime
sort($events);

//set top and length
foreach($events as &$event){	
	$top = $event->get_starttime();
	$end = $event->get_endtime();
	$length = $end -$top;
	$event->set_top($top);
	$event->set_length($length);	
}


//initialize periods
$periods = null;
$s=$events[0]->get_starttime();
$end=$events[0]->get_endtime();
$periods[$end]=array('list'=> null,
					   'next'=> null);
$list = array($events[0]->get_id());
$periods[$s]=array('list' => $list,
					 'next'=> $end);


$total = count($events);
for($i=1;$i<$total;$i++){
	$s = $events[$i]->get_starttime();
	$c_event=$events[$i]->get_id();
	$end=$events[$i]->get_endtime();
		
	$j = $events[$i-1]->get_starttime();;
	$insert_start = false;
	while($periods[$j]['next']!=null){
		$comp = $periods[$j]['next'];
		if($s<$comp){			
			$periods[$j]['next']=$s;
			$periods[$s]['next']=$comp;
			$list = $periods[$j]['list'];			
			$list[]=$c_event;
			$periods[$s]['list']=$list;
			$insert_start = true;
		}elseif($s==$comp){
			$list = $periods[$comp]['list'];
			$list[]=$c_event;
			$periods[$comp]['list']=$list;
			$insert_start = true;
	    }
	    if($insert_start) break;	    
		$j = $comp;
	}

	
	if(!$insert_start){
		$list = array($c_event);
		$periods[$s]=array('list' => $list,
					 'next'=> $end);		
		$periods[$end]=array('list'=> null,
					   'next'=> null);
		$periods[$j]['next']=$s;		
	}else{
		$insert_end = false;
		$j = $s;
		while($periods[$j]['next']!=null){
			$comp = $periods[$j]['next'];
			if($end < $comp){				
				$periods[$j]['next']=$end;
				$periods[$end]['next']=$comp;				
				foreach($periods[$j]['list'] as $id){
					if($id != $c_event){
						$periods[$end]['list'][]=$id;
					}
				}
				$insert_end = true;
			}elseif($end == $comp){
				$insert_end = true;
			}else{
				$list = $periods[$comp]['list'];
				$list[] = $c_event;
				$periods[$comp]['list'] = $list;
			}
			if($insert_end) break;
			$j = $comp;
		}
		if(!$insert_end){
			$periods[$end]=array('list'=> null,
			 			   'next'=> null);			
			$periods[$j]['next']=$end;
		}
	}
}

//initialize collident array
$collide = null;
for ($i=0;$i<$total;$i++){	
	$id = $events[$i]->get_id();
	for($j=0;$j<$total;$j++){
		$cols = $events[$j]->get_id();
		$collide[$id][$cols]= 0;	
	}
	
}
//caculate collident array and get the width of each event
$i = $events[0]->get_starttime();
while($periods[$i]['next']!=null){
	$i = $periods[$i]['next'];
	$list = &$periods[$i]['list'];
	$c = count($list);
	if ($c == 0) continue;
	$w = floor(EventManager::$canvas['width']/$c);	
	for($j=0;$j<$c;$j++){		
		for($k=0;$k<$total;$k++){
			if ($list[$j] != $events[$k]->get_id()) continue;
			if ($events[$k]->get_width()==-1 || $events[$k]->get_width()>$w){
				$events[$k]->set_width($w);
			}
		}
		for($k=0;$k<$c;$k++){
			if($k!=$j){
				$collide[$list[$j]][$list[$k]]=1;
			}	
		}		
	}	
}

//caculate the left of each event
for($i=0;$i<$total;$i++){	
	$event = &$events[$i];	
	for($left = 0;$left < EventManager::$canvas['width'];$left=$left +$event->get_width()){
		$set_left = true;
		for($j=0;$j<=$i;$j++){			
			$pre = &$events[$j];
			if ($collide[$event->get_id()][$pre->get_id()] ==1){				
				if($pre->get_left()==$left){
					$set_left = false;
					break;
				}				
			}					
		}//for
		if ($set_left) break;	
	}
	$event->set_left($left);	
}


}//end of layOutDay


}
?>