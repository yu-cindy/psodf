<?php

namespace App\Repositories;

use App\Models\School;
use Auth;

class SchoolRepository
{


    public function update(array $data){
        $id=Auth::user()->School_id;
        $now = date('Y-m-d H:i:s');
        $data['updated_at']=$now;
        $school = School::find($id);
        return  $school ? $school->update($data) : false;
    }



}
