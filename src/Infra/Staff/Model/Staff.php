<?php 
namespace Infra\Staff\Model;

use Infra\Shared\Models\BaseModel;

class Staff extends BaseModel{
    public function division(){
        return $this->belongsTo(Divison::class);
    }
    
}