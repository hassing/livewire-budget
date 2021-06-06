<?php

namespace App\Http\Livewire;

class Helper {
    public function Number($value) {
        return '<span class="'.(round($value) == 0 ? 'col-zero' : (round($value) > 0 ? 'col-pos' : 'col-neg')) .'">'.number_format(abs(round($value)), 0).'</span>';
    }
}

