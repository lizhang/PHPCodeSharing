<?php
Class Event{
private $starttime;
private $endtime;
private $id;
private $top;
private $left;
private $width;
private $length;

function Event($s,$e,$id){	
	$this->starttime = (strtotime($s)-strtotime(EventManager::$day_start))/60;
	$this->endtime = (strtotime($e)-strtotime(EventManager::$day_start))/60;
	$this->id = $id;	
	$this->top = -1;
	$this->left = -1;
	$this->width = -1;
	$this->length = -1;
}

function set_top($top){$this->top=$top;}
function set_left($left){$this->left=$left;}
function set_width($width){$this->width=$width;}
function set_length($length){$this->length=$length;}

function get_starttime(){return $this->starttime;}
function get_endtime(){return $this->endtime;}
function get_id(){return $this->id;}
function get_top(){return $this->top;}
function get_left(){return $this->left;}
function get_width(){return $this->width;}
function get_length(){return $this->length;}

}
?>