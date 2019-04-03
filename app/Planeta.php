<?php 

#use Jessenger\Mongodb\Eloquent\Model as Eloquent;
namespace App;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Planeta extends Eloquent 
{
    protected $collection = 'planeta'; 
    public $timestamps = false;   
}