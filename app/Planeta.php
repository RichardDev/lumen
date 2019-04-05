<?php 

namespace App;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * Model para representar um Planeta na base de dados.
 */
class Planeta extends Eloquent 
{
    protected $collection = 'planeta'; 
    public $timestamps = false;  
    
    public function getID() {
        return $this->_id;
    }
}