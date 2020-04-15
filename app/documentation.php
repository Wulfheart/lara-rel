<?php

namespace App;

class documentation extends base
{
    //
    public function deadlines() {
        return $this->hasMany('App\deadline');
    }

    public function current_deadline(){
        return $this->deadlines()->orderBy('finished_at', 'asc')->orderBy('due_until', 'asc')->first();
    }

}
