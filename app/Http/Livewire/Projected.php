<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Projected extends Component
{
    // General data
    public int $year;
    public array $activeMonths;
    public int $activeMonthCount;

    // Creating posts
    public string $createName;
    public string $createType;
    public int $createCategoryID;

    // Updating rows
    public string $rowName;
    public string $rowTypeHTML;
    public string $rowValue;
    public int $rowPostID;

    // Updating fields
    public string $fieldName;
    public string $fieldTypeHTML;
    public int $fieldMonth;
    public string $fieldMonthName;
    public string $fieldValue;
    public int $fieldPostID;

    // Update column
    public string $columnName;
    public int $columnMonth;

    // Delete row
    public int $deletePostID;
    public string $deleteName;

    // Copy year
    public int $copyYear;
    public string $copyMethod;

    // Modals
    public bool $modalRowUpdate;
    public bool $modalFieldUpdate;
    public bool $modalColumnUpdate;
    public bool $modalDelete;

    public function mount() {
        $this->createName = "";
        $this->createType = "expense";
        $this->createCategoryID = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->first()->id;

        $this->rowName = "";
        $this->rowTypeHTML = "expense";
        $this->rowValue = "";
        $this->rowPostID = 0;

        $this->fieldName = "";
        $this->fieldTypeHTML = "expense";
        $this->fieldMonth = 1;
        $this->fieldMonthName = "January";
        $this->fieldValue = "";
        $this->fieldPostID = 0;

        $this->columnName = "January";
        $this->columnMonth = 1;

        $this->deletePostID = 0;
        $this->deleteName = "";

        $this->modalRowUpdate = false;
        $this->modalFieldUpdate = false;
        $this->modalColumnUpdate = false;
        $this->modalDelete = false;

        $this->copyYear = 0;
        $this->copyMethod = "full";
    }

    public function render()
    {
        $this->activeMonths = \Auth::user()->activeMonths($this->year);
        $this->activeMonthCount = 0;
        foreach($this->activeMonths as $month) {
            if($month) {
                $this->activeMonthCount++;
            }
        }

        $categories = \App\Models\Category::with(["posts" => function($query) {
            $query->where("year", $this->year);
        }])->where("user_id", \Auth::user()->id)->orderBy("priority")->get();

        $yearList = [];
        if(!\Auth::user()->hasPosts($this->year)) {
            $results = \DB::select(
                \DB::raw("SELECT DISTINCT posts.year
                            FROM posts
                                INNER JOIN categories ON posts.category_id = categories.id
                            WHERE
                                categories.user_id = :user_id
                            ORDER BY posts.year DESC"),
                    array('user_id' => \Auth::user()->id));
            foreach($results as $result) {
                if($this->copyYear == 0) {
                    $this->copyYear = $result->year;
                }
                $yearList[] = $result->year;
            }
        }

        $hf = new \App\Http\Livewire\Helper();

        return view('livewire.projected', ['categories' => $categories, 'yearList' => $yearList, 'hf' => $hf]);
    }

    public function openDelete() {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->rowPostID)->first();

        if($post) {
            $this->deletePostID = $post->id;
            $this->deleteName = $post->name;
            $this->modalDelete = true;
            $this->modalRowUpdate = false;
        }
    }

    public function deletePost() {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->deletePostID)->first();

        if($post) {
            $post->delete();
            $this->emitTo('actual', '$refresh');
        }
        $this->modalDelete = false;
    }

    public function closeDelete() {
        $this->modalDelete = false;
    }

    public function openColumnUpdate($month) {
        $this->columnName = ["-", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][$month];
        $this->columnMonth = $month;
        $this->modalColumnUpdate = true;
    }
    
    public function updateColumn() {
        $categories = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get();
        foreach($categories as $category) {
            foreach($category->posts->where("year", $this->year) as $post) {
                $value = \App\Models\PostValue::where("post_id", $post->id)->where("month", $this->columnMonth)->first();
                if($value != null) {
                    $value->delete();
                }
            }
        }
        $this->emitTo('actual', '$refresh');
        $this->modalColumnUpdate = false;
    }

    public function closeColumnUpdate() {
        $this->modalColumnUpdate = false;
    }

    public function openFieldUpdate($id, $month) {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $id)->first();

        if($post) {
            $this->fieldPostID = $post->id;
            $this->fieldName = $post->name;
            $this->fieldTypeHTML = "<span class=\"".($post->is_expense ? "col-neg" : "col-pos")."\">".($post->is_expense ? "expense" : "income")."</span>";
            $this->fieldMonth = $month;
            $this->fieldMonthName = ["-", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][$month];
            $this->fieldValue = $post->monthValue($month) * ($post->is_expense ? -1.00 : 1.00);
            if($this->fieldValue == "-0") {
                $this->fieldValue = "0";
            }
            $this->modalFieldUpdate = true;
            $this->emit("focusField", "fieldValue");
        }
    }

    public function updateField() {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->fieldPostID)->first();

        $value = null;
        if($this->fieldValue != null && trim($this->fieldValue) != "" && is_numeric(trim($this->fieldValue))) {
            $value = floatval($this->fieldValue);
        }

        if($post != null && $value !== null) {
            $post->setMonthValue($this->fieldMonth, $value);
        }
        $this->emitTo('actual', '$refresh');
        $this->modalFieldUpdate = false;
    }

    public function closeFieldUpdate() {
        $this->modalFieldUpdate = false;
    }

    public function openRowUpdate($id) {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $id)->first();

        if($post) {
            $this->rowPostID        = $post->id;
            $this->rowName          = $post->name;
            $this->rowTypeHTML      = "<span class=\"".($post->is_expense ? "col-neg" : "col-pos")."\">".($post->is_expense ? "expense" : "income")."</span>";
            $this->modalRowUpdate   = true;
            $this->emit("focusField", "rowValue");
        }
    }

    public function updateRow(): void
    {
        $post = \App\Models\Post::whereHas('category', function ($query) {
            return $query->where('user_id', \Auth::user()->id);
        })->where("id", $this->rowPostID)->first();

        # edit name
        if ($post->name !== $this->rowName) {
            $post->update(['name' => $this->rowName]);
            $post->save();
        }

        $value = null;
        if($this->rowValue !== null && trim($this->rowValue) !== "" && is_numeric(trim($this->rowValue))) {
            $value = (float)$this->rowValue;
        }

        if($post !== null && $value !== null) {
            for($m=1;$m<=12;$m++) {
                $post->setMonthValue($m, $value);
            }
        }
        $this->emitTo('actual', '$refresh');
        $this->rowValue = "";
        $this->modalRowUpdate = false;
    }

    public function closeRowUpdate() {
        $this->modalRowUpdate = false;
    }

    public function createPost() {
        if(trim($this->createName) == "") {
            $this->createName = ""; return;
        }

        $category = \App\Models\Category::where("user_id", \Auth::user()->id)->where("id", $this->createCategoryID)->first();
        if($category == null) {
            return;
        }

        $expense = $this->createType != "income";

        $post = new \App\Models\Post();
        $post->year = $this->year;
        $post->name = trim($this->createName);
        $post->is_expense = $expense;
        $post->category_id = $this->createCategoryID;
        $post->save();

        $this->createName = "";
        $this->emitTo('actual', '$refresh');
        $this->emit("focusField", "createName");
    }

    public function copyBudget() {
        $categories = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get();
        foreach($categories as $category) {
            foreach($category->posts->where("year", $this->copyYear) as $post) {
                $newPost = new \App\Models\Post();
                $newPost->category_id = $category->id;
                $newPost->year = $this->year;
                $newPost->name = $post->name;
                $newPost->is_expense = $post->is_expense;
                $newPost->save();

                if($this->copyMethod == "full") {
                    for($m=1; $m <= 12; $m++) {
                        $newPost->setMonthValue($m, $post->monthValue($m) * ($newPost->is_expense ? -1.00 : 1.00));
                    }
                }
            }
        }
        $this->emitTo('actual', '$refresh');
    }

    public function closeAllModals() {
        $this->modalRowUpdate = false;
        $this->modalFieldUpdate = false;
        $this->modalColumnUpdate = false;
        $this->modalDelete = false;
    }
}
