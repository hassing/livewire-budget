<div x-data="{ modalRowUpdate: @entangle('modalRowUpdate'), modalFieldUpdate: @entangle('modalFieldUpdate'), modalColumnUpdate: @entangle('modalColumnUpdate'), modalDelete: @entangle('modalDelete') }">
    <div class="text-center" >
        <input wire:keydown.enter="createPost" wire:model.defer="createName" name="createName" type="text" class="form-input px-2 py-2 rounded-md w-4/12" placeholder="Name ...">
        
        <select wire:keydown.enter="createPost" wire:model="createType" name="createType" class="form-input px-2 rounded-md w-2/12" placeholder="Type ...">
            <option value="expense">Expense Type</option>
            <option value="income">Income Type</option>
        </select>

        <select wire:keydown.enter="createPost" wire:model.defer="createCategoryID" name="createCategoryID" class="form-input px-2 rounded-md w-2/12" placeholder="Category ...">
            @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
        </select>

        <button wire:click="createPost" class="form-input px-2 py-2 rounded-md color-white bg-green-200">Add</button>
    </div>

    @if(\Auth::user()->hasPosts($year))
        <div>
            <table class="table border-collapse w-full mt-6">
                <tr> 
                    <td>&nbsp;</td>
                    <td wire:click="openColumnUpdate(1)" class="text-right border-left-weak font-bold @if(!$activeMonths[0]) col-muted @endif">Jan</td>
                    <td wire:click="openColumnUpdate(2)" class="text-right border-left-weak font-bold @if(!$activeMonths[1]) col-muted @endif">Feb</td>
                    <td wire:click="openColumnUpdate(3)" class="text-right border-left-weak font-bold @if(!$activeMonths[2]) col-muted @endif">Mar</td>
                    <td wire:click="openColumnUpdate(4)" class="text-right border-left-weak font-bold @if(!$activeMonths[3]) col-muted @endif">Apr</td>
                    <td wire:click="openColumnUpdate(5)" class="text-right border-left-weak font-bold @if(!$activeMonths[4]) col-muted @endif">May</td>
                    <td wire:click="openColumnUpdate(6)" class="text-right border-left-weak font-bold @if(!$activeMonths[5]) col-muted @endif">Jun</td>
                    <td wire:click="openColumnUpdate(7)" class="text-right border-left-weak font-bold @if(!$activeMonths[6]) col-muted @endif">Jul</td>
                    <td wire:click="openColumnUpdate(8)" class="text-right border-left-weak font-bold @if(!$activeMonths[7]) col-muted @endif">Aug</td>
                    <td wire:click="openColumnUpdate(9)" class="text-right border-left-weak font-bold @if(!$activeMonths[8]) col-muted @endif">Sep</td>
                    <td wire:click="openColumnUpdate(10)" class="text-right border-left-weak font-bold @if(!$activeMonths[9]) col-muted @endif">Okt</td>
                    <td wire:click="openColumnUpdate(11)" class="text-right border-left-weak font-bold @if(!$activeMonths[10]) col-muted @endif">Nov</td>
                    <td wire:click="openColumnUpdate(12)" class="text-right border-left-weak font-bold @if(!$activeMonths[11]) col-muted @endif">Dec</td>
                    <td class="text-right border-left font-bold italic">Avg</td>
                    <td class="text-right border-left-weak font-bold">Total</td>
                </tr>
                @foreach($categories as $category)
                    <tr>
                        <td><strong>{{$category->name}}</strong></td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                        <td class="border-left">&nbsp;</td>
                        <td class="border-left-weak">&nbsp;</td>
                    </tr>
                    @foreach($category->posts as $post)
                        <tr>
                            <td class="@if(!$loop->first) border-top-weak @endif">
                                <div  wire:click="openRowUpdate({{$post->id}})">&nbsp; {{$post->name}}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[0]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 1)">{!! $hf->number($post->monthValue(1)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[1]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 2)">{!! $hf->number($post->monthValue(2)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[2]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 3)">{!! $hf->number($post->monthValue(3)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[3]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 4)">{!! $hf->number($post->monthValue(4)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[4]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 5)">{!! $hf->number($post->monthValue(5)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[5]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 6)">{!! $hf->number($post->monthValue(6)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[6]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 7)">{!! $hf->number($post->monthValue(7)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[7]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 8)">{!! $hf->number($post->monthValue(8)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[8]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 9)">{!! $hf->number($post->monthValue(9)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[9]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 10)">{!! $hf->number($post->monthValue(10)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[10]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 11)">{!! $hf->number($post->monthValue(11)) !!}</div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[11]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 12)">{!! $hf->number($post->monthValue(12)) !!}</div>
                            </td>
                            <td class="text-right border-left @if(!$loop->first) border-top-weak @endif italic">
                                {!! $hf->number($post->avgValue($activeMonthCount)) !!}
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif font-bold">
                                {!! $hf->number($post->totalValue()) !!}</td>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="border-top">&nbsp;</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[0]) col-muted @endif">{!! $hf->number($category->monthValue($year, 1)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[1]) col-muted @endif">{!! $hf->number($category->monthValue($year, 2)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[2]) col-muted @endif">{!! $hf->number($category->monthValue($year, 3)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[3]) col-muted @endif">{!! $hf->number($category->monthValue($year, 4)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[4]) col-muted @endif">{!! $hf->number($category->monthValue($year, 5)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[5]) col-muted @endif">{!! $hf->number($category->monthValue($year, 6)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[6]) col-muted @endif">{!! $hf->number($category->monthValue($year, 7)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[7]) col-muted @endif">{!! $hf->number($category->monthValue($year, 8)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[8]) col-muted @endif">{!! $hf->number($category->monthValue($year, 9)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[9]) col-muted @endif">{!! $hf->number($category->monthValue($year, 10)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[10]) col-muted @endif">{!! $hf->number($category->monthValue($year, 11)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[11]) col-muted @endif">{!! $hf->number($category->monthValue($year, 12)) !!}</td>
                        <td class="text-right border-left font-bold border-top italic">{!! $hf->number($category->avgValue($year, $activeMonthCount)) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top">{!! $hf->number($category->totalValue($year)) !!}</td>
                    </tr>
                @endforeach

                <tr>
                    <td>&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                    <td class="border-left">&nbsp;</td>
                    <td class="border-left-weak">&nbsp;</td>
                </tr>

                <tr>
                    <td class="border-top-strong"><strong>Total</strong></td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[0]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 1)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[1]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 2)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[2]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 3)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[3]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 4)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[4]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 5)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[5]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 6)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[6]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 7)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[7]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 8)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[8]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 9)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[9]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 10)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[10]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 11)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold font-bold @if(!$activeMonths[11]) col-muted @endif">{!! $hf->number(\Auth::user()->monthValue($categories, $year, 12)) !!}</td>
                    <td class="text-right border-left border-top-strong font-bold italic">{!! $hf->number(\Auth::user()->avgValue($categories, $year, $activeMonthCount)) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold">{!! $hf->number(\Auth::user()->totalValue($categories, $year)) !!}</td>
                </tr>
            </table>
        </div>
    @elseif(count($yearList) > 0)
        <div>
            <p class="text-center pt-6">
                or copy from:
                <br>

                <select wire:keydown.enter="copyBudget" wire:model.defer="copyYear" name="copyYear" class="form-input px-2 rounded-md w-2/12" placeholder="Year ...">
                    @foreach($yearList as $y)
                        <option value="{{$y}}">{{$y}}</option>
                    @endforeach
                </select>

                <select wire:keydown.enter="copyBudget" wire:model.defer="copyMethod" name="copyMethod" class="form-input px-2 rounded-md w-2/12" placeholder="Method ...">
                    <option value="full">With values</option>
                    <option value="clean">Only posts</option>
                </select>
        
                <button wire:click="copyBudget" class="form-input px-2 py-2 rounded-md color-white bg-green-200">Copy</button>
            </p>
        </div>
    @endif

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-1" role="dialog" aria-modal="true" x-show="modalFieldUpdate">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-1">
                            {{$fieldName}} for ({{$fieldMonthName}}) <br>
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Set value (<span>{!!$fieldTypeHTML!!}</span>)<br>
                            </p>
                        </div>
                        <input wire:keydown.enter="updateField" wire:model.defer="fieldValue" name="fieldValue" type="text" class="form-input px-2 py-2 rounded-md w-10/12" placeholder="Value for month ...">
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="updateField" class="inlin e-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        Update
                    </button>
                    <button type="button" wire:click="closeFieldUpdate" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-2" role="dialog" aria-modal="true" x-show="modalRowUpdate">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="modal-title-2">
                            {{$rowName}} for ({{$fieldMonthName}})
                        </h3>
                        <label for="set-title">
                            <input id="set-title" name="title" type="text" wire:model.defer="rowName" class="form-input px-2 py-2 rounded-md w-10/12" />
                        </label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Set value for all months (<span>{!!$rowTypeHTML!!}</span>)<br>
                            </p>
                        </div>
                        <input wire:keydown.enter="updateRow" wire:model.defer="rowValue" name="rowValue" type="text" class="form-input px-2 py-2 rounded-md w-10/12" placeholder="Value for all months ...">
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="updateRow" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        Update
                    </button>
                    <button type="button" wire:click="closeRowUpdate" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                    <p class="pt-2 text-sm">
                        or <a href="javascript:;" wire:click="openDelete" class="text-red-500">delete</a><br>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-3" role="dialog" aria-modal="true" x-show="modalColumnUpdate">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-3">
                        {{$columnName}}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Clear all values for month?<br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="updateColumn" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:text-sm">
                        Clear
                    </button>
                    <button type="button" wire:click="closeColumnUpdate" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-4" role="dialog" aria-modal="true" x-show="modalDelete">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-4">
                        Delete {{$deleteName}}?
                        </h3>
                        <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            All data related will also be removed.
                        </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="deletePost" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Delete
                    </button>
                    <button type="button" wire:click="closeDelete" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("load", function() {
            Livewire.on("focusField", inputName => {
                setTimeout(function() {
                    $("input[name="+inputName+"]")[0].select();
                    $("input[name="+inputName+"]")[0].focus();
                }, 100);

            });
        });
    </script>
    
</div>
