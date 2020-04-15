<?php

namespace App;

class documentation extends base
{
    //
    protected $dates = ['updated_at', 'created_at', 'finished_at', 'due_until'];
    
    public function deadlines() {
        return $this->hasMany('App\deadline');
    }

    public function current_deadline(){
        return $this->deadlines()->orderBy('finished_at', 'asc')->orderBy('due_until', 'asc')->first();
    }

}
