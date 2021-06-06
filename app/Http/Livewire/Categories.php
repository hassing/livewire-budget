<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Categories extends Component
{
    public $name;
    public $deleteID;

    public function render()
    {
        return view('livewire.categories', [
            'categories' => \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get()
        ]);
    }

    public function create() {
        if(trim($this->name) == "") {
            $this->name = ""; return;
        }

        $existing = \App\Models\Category::where("user_id", \Auth::user()->id)->where("name", $this->name)->first();
        if($existing != null) {
            $this->name = ""; return;
        }

        $highest = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority", "desc")->first();
        if($highest != null) {
            $highest = $highest->priority;
        } else {
            $highest = 0;
        }

        $cat = new \App\Models\Category();
        $cat->user_id = \Auth::user()->id;
        $cat->name = trim($this->name);
        $cat->priority = $highest + 1;
        $cat->save();

        $this->name = "";
    }

    public function delete() {
        $cat = \App\Models\Category::where("user_id", \Auth::user()->id)->where("id", $this->deleteID)->first();
        if($cat) {
            $cat->delete();
        }
    }

    public function moveUp($catID) {
        $cat = \App\Models\Category::where("user_id", \Auth::user()->id)->where("id", $catID)->first();
        if($cat) {
            $cat->move(true);
        }
    }

    public function moveDown($catID) {
        $cat = \App\Models\Category::where("user_id", \Auth::user()->id)->where("id", $catID)->first();
        if($cat) {
            $cat->move(false);
        }
    }
}
