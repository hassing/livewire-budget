<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static $ACTUAL_MONTH_CACHE = null;

    public function hasPosts($year) {
        return \App\Models\Post::whereHas('category', function ($query) {
                return $query->where('user_id', \Auth::user()->id);
            })->where("year", $year)->count() > 0;
    }

    public function monthValue($categories, $year, $month) {
        $value = 0;
        foreach($categories as $category) {
            $value += $category->monthValue($year, $month);
        }
        return $value;
    }

    public function totalValue($categories, $year) {
        $value = 0;
        foreach($categories as $category) {
            $value += $category->totalValue($year);
        }
        return $value;
    }

    public function avgValue($categories, $year, $count) {
        if($count == 0) { return 0; }
        return $this->totalValue($categories, $year) / $count;
    }

    public function activeMonths($year) {
        $months = [false, false, false, false, false, false, false, false, false, false, false, false];

        foreach(\App\Models\Category::where('user_id', $this->id)->get() as $category) {
            foreach($category->posts->where("year", $year) as $post) {
                for($i = 0; $i < 12; $i++) {
                    if($months[$i]) {
                        continue;
                    }

                    if($post->monthValue($i+1) != 0) {
                        $months[$i] = true;
                    }
                }
            }
        }

        return $months;
    }

    public function monthActualValue($categories, $year, $month, $differenceMode = false) {
        $value = 0;
        foreach($categories as $category) {
            $value += $category->monthActualValue($year, $month);
        }

        if($differenceMode) {
            if(!$this->activeActualMonths($year)[$month-1]) {
                return 0;
            }

            $projected = $this->monthValue($categories, $year, $month);

            return $value - $projected;
        } else {
            return $value;
        }
    }

    public function totalActualValue($categories, $year, $differenceMode = false) {
        $value = 0;
        foreach($categories as $category) {
            $value += $category->totalActualValue($year);
        }

        if($differenceMode) {
            $projected = 0;
            for($m = 1; $m<=12; $m++) {
                if(\Auth::user()->activeActualMonths($this->year)[$m-1]) {
                    $projected += $this->monthValue($categories, $year, $m);
                }
            }

            return $value - $projected;
        } else {
            return $value;
        }
    }

    public function avgActualValue($categories, $year, $count, $differenceMode = false) {
        if($count == 0) { return 0; }
        return $this->totalActualValue($categories, $year, $differenceMode) / $count;
    }

    public function activeActualMonths($year) {
        if(self::$ACTUAL_MONTH_CACHE === null) {
            self::$ACTUAL_MONTH_CACHE = [false, false, false, false, false, false, false, false, false, false, false, false];

            foreach(\App\Models\Category::where('user_id', $this->id)->get() as $category) {
                foreach($category->posts->where("year", $year) as $post) {
                    for($i = 0; $i < 12; $i++) {
                        if(self::$ACTUAL_MONTH_CACHE[$i]) {
                            continue;
                        }
    
                        if($post->monthActualValue($i+1) != 0) {
                            self::$ACTUAL_MONTH_CACHE[$i] = true;
                        }
                    }
                }
            }
        }        

        return self::$ACTUAL_MONTH_CACHE;
    }
}
