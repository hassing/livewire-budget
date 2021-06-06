<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    private $monthValueCache = null;
    private $monthActualValueCache = null;

    public function category() {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function delete() {
        foreach(\App\Models\PostValue::where('post_id', $this->id)->get() as $value) {
            $value->delete();
        }
        foreach(\App\Models\TransactionValue::where('post_id', $this->id)->get() as $value) {
            $value->delete();
        }
        parent::delete();
    }

    public function setMonthValue($month, $value) {
        $postValue = \App\Models\PostValue::where("post_id", $this->id)->where("month", $month)->first();
        if($value != 0.00) {
            if($postValue == null) {
                $postValue = new \App\Models\PostValue();
                $postValue->post_id = $this->id;
                $postValue->month = $month;
            }
            $postValue->value = $value;
            $postValue->save();
        } else if($postValue != null) {
            $postValue->delete();
        }
    }

    private function updateMonthValueCache() {
        $this->monthValueCache = [0, 0, 0, 0, 0 ,0, 0, 0, 0, 0, 0 ,0];
        foreach(\App\Models\PostValue::where("post_id", $this->id)->get() as $value) {
            $this->monthValueCache[$value->month-1] = $value->value * ($this->is_expense ? -1.00 : 1.00);
        }
    }

    public function monthValue($month) {
        if($this->monthValueCache == null) {
            $this->updateMonthValueCache();
        }
        return $this->monthValueCache[$month-1];
    }

    public function totalValue() {
        $value = 0;
        for($i=1;$i<=12;$i++) {
            $value += $this->monthValue($i);
        }
        return $value;
    }

    public function avgValue($count) {
        if($count == 0) { return 0; }
        return $this->totalValue() / $count;
    }
    
    private function updateMonthActualValueCache() {
        $this->monthActualValueCache = [0, 0, 0, 0, 0 ,0, 0, 0, 0, 0, 0 ,0];
        foreach(\App\Models\TransactionValue::where("post_id", $this->id)->get() as $value) {
            $this->monthActualValueCache[$value->month-1] += $value->value * ($this->is_expense ? -1.00 : 1.00);
        }
    }

    public function monthActualValue($month, $differenceMode = false) {
        if($this->monthActualValueCache == null) {
            $this->updateMonthActualValueCache();
        }

        if($differenceMode) {
            if(!\Auth::user()->activeActualMonths($this->year)[$month-1]) {
                return 0;
            }

            $actual = $this->monthActualValueCache[$month-1];
            $projected = $this->monthValue($month);

            return $actual - $projected;
        } else {
            return $this->monthActualValueCache[$month-1];
        }
    }

    public function totalActualValue($differenceMode = false) {
        $value = 0;
        for($i=1;$i<=12;$i++) {
            $value += $this->monthActualValue($i);
        }

        if($differenceMode) {
            $projected = 0;
            for($m = 1; $m<=12; $m++) {
                if(\Auth::user()->activeActualMonths($this->year)[$m-1]) {
                    $projected += $this->monthValue($m);
                }
            }

            return $value - $projected;
        } else {
            return $value;
        }
    }

    public function avgActualValue($count, $differenceMode = false) {
        if($count == 0) { return 0; }

        return $this->totalActualValue($differenceMode) / $count;
    }
}
