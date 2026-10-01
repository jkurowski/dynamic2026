<?php

use Carbon\Carbon;

if (! function_exists('tableDate')) {
    function tableDate($date)
    {
        if($date == null){
            return '';
        }

        $table_date = Carbon::parse($date)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');
        $diffForHumans = Carbon::createFromFormat('Y-m-d', $table_date)->diffForHumans();

        if($table_date >= $now){
            return '<span>'.$table_date.'</span>';
        } else {
            return '<span>'.$table_date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
        }
    }
}