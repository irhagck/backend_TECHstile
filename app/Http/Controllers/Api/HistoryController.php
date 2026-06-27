<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Production;

class HistoryController extends Controller
{
    public function history($id){
        return Production::all();
    }
}
