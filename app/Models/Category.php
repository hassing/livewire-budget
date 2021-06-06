<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    static $CACHED_VALUES = [];
    static $CACHED_ACTUAL = [];

    public function posts() {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function move($moveUp = true) {
        $replaces = self::where("user_id", $this->user_id);

        if($moveUp) {
            $replaces = $replaces->where("priority", "<", $this->priority)->orderBy("priority", "desc")->first();
        } else {
            $replaces = $replaces->where("priority", ">", $this->priority)->orderBy("priority", "asc")->first();
        }

        if($replaces) {
            $tmp = $this->priority;
            $this->priority = $replaces->priority;
            $replaces->priority = $tmp;

            $this->save();
            $replaces->save();
        }
    }

    public function delete() {
        foreach($this->posts as $post) {
            $post->delete();
        }
        parent::delete();
    }

    public static function ValueFromCache($id, $year, $month) {
        if(isset(Category::$CACHED_VALUES[$id])) {
            if(isset(Category::$CACHED_VALUES[$id][$year])) {
                if(isset(Category::$CACHED_VALUES[$id][$year][$month])) {
                    return Category::$CACHED_VALUES[$id][$year][$month];
                }
            }
        }
        return null;
    }    

    public static function SetValueInCache($id, $year, $month, $value) {
        if(!isset(Category::$CACHED_VALUES[$id])) {
            Category::$CACHED_VALUES[$id] = [];
        }
        if(!isset(Category::$CACHED_VALUES[$id][$year])) {
            Category::$CACHED_VALUES[$id][$year] = [];
        }
        Category::$CACHED_VALUES[$id][$year][$month] = $value;
    }

    public function monthValue($year, $month) {
        $value = Category::ValueFromCache($this->id, $year, $month);
        if($value !== null) {
            return $value;
        }

        $value = 0;
        foreach($this->posts->where("year", $year) as $post) {
            $value += $post->monthValue($month);
        }

        Category::SetValueInCache($this->id, $year, $month, $value);
        return $value;
    }

    public function totalValue($year) {
        $value = 0;
        for($i=1;$i<=12;$i++) {
            $value += $this->monthValue($year, $i);
        }
        return $value;
    }

    public function avgValue($year, $count) {
        if($count == 0) { return 0; }
        return $this->totalValue($year) / $count;
    }


    public static function ActualFromCache($id, $year, $month) {
        if(isset(Category::$CACHED_ACTUAL[$id])) {
            if(isset(Category::$CACHED_ACTUAL[$id][$year])) {
                if(isset(Category::$CACHED_ACTUAL[$id][$year][$month])) {
                    return Category::$CACHED_ACTUAL[$id][$year][$month];
                }
            }
        }
        return null;
    }    

    public static function SetActualInCache($id, $year, $month, $value) {
        if(!isset(Category::$CACHED_ACTUAL[$id])) {
            Category::$CACHED_ACTUAL[$id] = [];
        }
        if(!isset(Category::$CACHED_ACTUAL[$id][$year])) {
            Category::$CACHED_ACTUAL[$id][$year] = [];
        }
        Category::$CACHED_ACTUAL[$id][$year][$month] = $value;
    }

    public function monthActualValue($year, $month, $differenceMode = false) {
        $value = Category::ActualFromCache($this->id, $year, $month);
        if($value === null) {
            $value = 0;
            foreach($this->posts->where("year", $year) as $post) {
                $value += $post->monthActualValue($month);
            }

            Category::SetActualInCache($this->id, $year, $month, $value);
        }

        if($differenceMode) {
            if(!\Auth::user()->activeActualMonths($year)[$month-1]) {
                return 0;
            }

            $projected = $this->monthValue($year, $month);

            return $value - $projected;
        } else {
            return $value;
        }
        
        return $value;
    }

    public function totalActualValue($year, $differenceMode = false) {
        $value = 0;
        for($i=1;$i<=12;$i++) {
            $value += $this->monthActualValue($year, $i);
        }

        if($differenceMode) {
            $projected = 0;
            for($m = 1; $m<=12; $m++) {
                if(\Auth::user()->activeActualMonths($year)[$m-1]) {
                    $projected += $this->monthValue($year, $m);
                }
            }

            return $value - $projected;
        } else {
            return $value;
        }
    }

    public function avgActualValue($year, $count, $differenceMode = false) {
        if($count == 0) { return 0; }
        return $this->totalActualValue($year, $differenceMode) / $count;
    }
}
