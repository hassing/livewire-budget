<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Actual extends Component
{
    protected $listeners = [
        '$refresh'
    ];

    // General data
    public int $year;
    public array $activeMonths;
    public int $activeMonthCount;

    // Quick add
    public string $quickValue;
    public int $quickPostID;
    public int $quickMonth;
    
    // Clear month
    public string $columnName;
    public int $columnMonth;

    // Edit field
    public string $fieldName;
    public string $fieldMonthName;
    public string $fieldTypeHTML;
    public string $fieldHistoryHTML;
    public int $fieldPostID;
    public int $fieldMonth;
    public string $fieldAddValue;

    // Delete value
    public int $deleteValueID;
    public string $deleteValueHTML;

    // View mode switch
    public string $switchValuesCSS;
    public string $switchDifferenceCSS;
    public string $switchMode;

    // Modals
    public bool $modalFieldUpdate;
    public bool $modalColumnUpdate;
    public bool $modalDelete;

    public function mount() {
        $category = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->first();

        $this->quickValue = "";
        $this->quickPostID = $category->posts->where('year', $this->year)->count() > 0 ? $category->posts->where('year', $this->year)->first()->id : -1;
        $this->quickMonth = intval((new \Datetime())->format("m"));

        $this->columnName = "January";
        $this->columnMonth = 1;

        $this->fieldName = "";
        $this->fieldMonthName = $this->columnName;
        $this->fieldTypeHTML = "expense";
        $this->fieldHistoryHTML = "Nothing";
        $this->fieldPostID = $this->quickPostID;
        $this->fieldAddValue = "";
        $this->month = $this->columnMonth;

        $this->deleteValueID = 0;
        $this->deleteValueHTML = "";

        $this->modalFieldUpdate = false;
        $this->modalColumnUpdate = false;
        $this->modalDelete = false;

        $this->switchMode("values");
    }

    public function render()
    {
        $this->activeMonths = \Auth::user()->activeActualMonths($this->year);
        $this->activeMonthCount = 0;
        foreach($this->activeMonths as $month) {
            if($month) {
                $this->activeMonthCount++;
            }
        }

        $categories = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get();

        if($this->quickPostID == -1) {
            $category = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->first();
            $this->quickPostID = $category->posts->where('year', $this->year)->count() > 0 ? $category->posts->where('year', $this->year)->first()->id : -1;
        }

        $hf = new \App\Http\Livewire\Helper();

        return view('livewire.actual', ["categories" => $categories, "hf" => $hf]);
    }

    public function quickinput() {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->quickPostID)->first();

        $value = null;
        if($this->quickValue != null && trim($this->quickValue) != "" && is_numeric(trim($this->quickValue))) {
            $value = floatval($this->quickValue);
        }

        if($value != 0) {
            $transaction = new \App\Models\TransactionValue();
            $transaction->post_id = $post->id;
            $transaction->value = $value;
            $transaction->month = $this->quickMonth;
            $transaction->save();
        }

        $this->quickValue = "";
        $this->emit("focusField", "quickValue");
    }

    public function openColumnUpdate($month) {
        $this->columnName = ["-", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][$month];
        $this->columnMonth = $month;
        $this->modalColumnUpdate = true;
    }

    public function closeColumnUpdate() {
        $this->modalColumnUpdate = false;
    }

    public function updateColumn() {
        $categories = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get();
        foreach($categories as $category) {
            foreach($category->posts->where("year", $this->year) as $post) {
                foreach(\App\Models\TransactionValue::where("post_id", $post->id)->where("month", $this->columnMonth)->get() as $value) {
                    if($value != null) {
                        $value->delete();
                    }
                }
            }
        }
        $this->modalColumnUpdate = false;
    }

    public function openFieldUpdate($postID, $month) {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $postID)->first();

        if($post) {
            $this->fieldPostID = $post->id;
            $this->fieldName = $post->name;
            $this->fieldTypeHTML = "<span class=\"".($post->is_expense ? "col-neg" : "col-pos")."\">".($post->is_expense ? "expense" : "income")."</span>";
            $this->fieldMonth = $month;
            $this->fieldMonthName = ["-", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][$month];

            $values = \App\Models\TransactionValue::where("post_id", $post->id)->where("month", $month);
            if($values->count() > 0) {
                $hf = new \App\Http\Livewire\Helper();
                $this->fieldHistoryHTML = "<span class=\"text-sm text-gray-500\">";

                $first = true;
                foreach($values->get() as $value) {
                    $v = $value->value * ($post->is_expense ? -1.00 : 1.00);
                    
                    if(!$first) {
                        if($value->value > 0) {
                            $this->fieldHistoryHTML .= " + ";        
                        } else {
                            $this->fieldHistoryHTML .= " - ";
                        }
                    } else {
                        $first = false;
                    }

                    $this->fieldHistoryHTML .= "<span wire:click=\"openDelete(".$value->id.")\">";
                    $this->fieldHistoryHTML .= $hf->number($v);
                    $this->fieldHistoryHTML .= "</span>";
                }

                if($values->count() > 1) {
                    $this->fieldHistoryHTML .= " = <strong>" . $hf->number($post->monthActualValue($month))."</strong>";
                }

                $this->fieldHistoryHTML .= "</span>";
            } else {
                $this->fieldHistoryHTML = "<span class=\"text-sm text-gray-500\">Nothing ...</span>";
            }
            $this->modalFieldUpdate = true;
            $this->emit("focusField", "fieldAddValue");
        }
    }

    public function closeFieldUpdate() {
        $this->modalFieldUpdate = false;
    }

    public function addValue() {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->fieldPostID)->first();

        $value = null;
        if($this->fieldAddValue != null && trim($this->fieldAddValue) != "" && is_numeric(trim($this->fieldAddValue))) {
            $value = floatval($this->fieldAddValue);
        }

        if($value != 0) {
            $transaction = new \App\Models\TransactionValue();
            $transaction->post_id = $post->id;
            $transaction->value = $value;
            $transaction->month = $this->fieldMonth;
            $transaction->save();
        }

        $this->fieldAddValue = "";
        $this->openFieldUpdate($this->fieldPostID, $this->fieldMonth);
        $this->emit("focusField", "fieldAddValue");
    }

    public function openDelete($valueID) {
        $hf = new \App\Http\Livewire\Helper();

        $this->deleteValueID = $valueID;
        $value = \App\Models\TransactionValue::with("post")->whereHas('post', function ($query) {
            return $query->whereHas('category', function($query2) {
                return $query2->where('user_id', \Auth::user()->id);
            });
        })->where("id", $this->deleteValueID)->first();      
                
        $this->deleteValueHTML = $hf->number($value->value * ($value->post->is_expense ? -1.0 : 1.0));

        $this->modalFieldUpdate = false;
        $this->modalDelete = true;
    }

    public function closeDelete() {
        $this->modalFieldUpdate = true;
        $this->modalDelete = false;
    }

    public function deleteValue() {
        $value = \App\Models\TransactionValue::with("post")->whereHas('post', function ($query) {
            return $query->whereHas('category', function($query2) {
                return $query2->where('user_id', \Auth::user()->id);
            });
        })->where("id", $this->deleteValueID)->first();

        if($value != null) {
            $value->delete();
        }
        
        $this->modalDelete = false;
        $this->openFieldUpdate($this->fieldPostID, $this->fieldMonth);
    }

    public function switchMode($mode) {
        $this->switchMode = $mode;
        
        if($mode == "values") {
            $this->switchValuesCSS = "bg-blue-700";
            $this->switchDifferenceCSS = "bg-gray-300";
        } else {
            $this->switchValuesCSS = "bg-gray-300";
            $this->switchDifferenceCSS = "bg-blue-700";
        }
    }

    public function closeAllModals() {
        $this->modalFieldUpdate = false;
        $this->modalColumnUpdate = false;
        $this->modalDelete = false;
    }
}
