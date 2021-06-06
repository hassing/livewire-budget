<div x-data="{ modalFieldUpdate: @entangle('modalFieldUpdate'), modalColumnUpdate: @entangle('modalColumnUpdate'), modalDelete: @entangle('modalDelete') }">
    @if(\Auth::user()->hasPosts($year))
        <div>
            <div class="text-center" >
                <input wire:keydown.enter="quickinput" wire:model.defer="quickValue" name="quickValue" type="text" class="form-input px-2 py-2 rounded-md w-2/12" placeholder="Quick value ...">
                
                <select wire:keydown.enter="quickinput" wire:model.defer="quickPostID" name="quickPostID" class="form-input px-2 rounded-md w-2/12" placeholder="Post ...">
                    @foreach($categories as $category)
                        <optgroup label="{{$category->name}}">
                            @foreach($category->posts->where('year', $year) as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <select wire:keydown.enter="quickinput" wire:model.defer="quickMonth" name="quickMonth" class="form-input px-2 rounded-md w-1/12" placeholder="Month ...">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>

                <button wire:click="quickinput" class="form-input px-2 py-2 rounded-md color-white bg-green-200">Add</button>

                <span class="border-left pl-3 ml-2">
                    <a class="rounded-left color-white p-2 {{$switchValuesCSS}}" wire:click="switchMode('values')" href="javascript:;">Values</a><a class="rounded-right p-2 color-white {{$switchDifferenceCSS}}" wire:click="switchMode('difference')" href="javascript:;">Difference</a>
                </span>
            </div>

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
                    @foreach($category->posts->where("year", $year) as $post)
                        <tr>
                            <td class="@if(!$loop->first) border-top-weak @endif">&nbsp; {{$post->name}}</td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[0]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 1)">
                                    {!! $hf->number($post->monthActualValue(1, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[1]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 2)">
                                    {!! $hf->number($post->monthActualValue(2, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[2]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 3)">
                                    {!! $hf->number($post->monthActualValue(3, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[3]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 4)">
                                    {!! $hf->number($post->monthActualValue(4, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[4]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 5)">
                                    {!! $hf->number($post->monthActualValue(5, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[5]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 6)">
                                    {!! $hf->number($post->monthActualValue(6, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[6]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 7)">
                                    {!! $hf->number($post->monthActualValue(7, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[7]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 8)">
                                    {!! $hf->number($post->monthActualValue(8, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[8]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 9)">
                                    {!! $hf->number($post->monthActualValue(9, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[9]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 10)">
                                    {!! $hf->number($post->monthActualValue(10, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[10]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 11)">
                                    {!! $hf->number($post->monthActualValue(11, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif @if(!$activeMonths[11]) col-muted @endif">
                                <div wire:click="openFieldUpdate({{$post->id}}, 12)">
                                    {!! $hf->number($post->monthActualValue(12, ($switchMode == "difference"))) !!}
                                </div>
                            </td>
                            <td class="text-right border-left @if(!$loop->first) border-top-weak @endif italic">{!! $hf->number($post->avgActualValue($activeMonthCount, ($switchMode == "difference"))) !!}</td>
                            <td class="text-right border-left-weak @if(!$loop->first) border-top-weak @endif font-bold">{!! $hf->number($post->totalActualValue(($switchMode == "difference"))) !!}</td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="border-top">&nbsp;</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[0]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 1, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[1]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 2, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[2]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 3, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[3]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 4, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[4]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 5, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[5]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 6, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[6]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 7, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[7]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 8, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[8]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 9, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[9]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 10, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[10]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 11, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top @if(!$activeMonths[11]) col-muted @endif">{!! $hf->number($category->monthActualValue($year, 12, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left font-bold border-top italic">{!! $hf->number($category->avgActualValue($year, $activeMonthCount, ($switchMode == "difference"))) !!}</td>
                        <td class="text-right border-left-weak font-bold border-top">{!! $hf->number($category->totalActualValue($year, ($switchMode == "difference"))) !!}</td>
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
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[0]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 1, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[1]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 2, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[2]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 3, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[3]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 4, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[4]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 5, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[5]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 6, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[6]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 7, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[7]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 8, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[8]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 9, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[9]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 10, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold @if(!$activeMonths[10]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 11, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold font-bold @if(!$activeMonths[11]) col-muted @endif">{!! $hf->number(\Auth::user()->monthActualValue($categories, $year, 12, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left border-top-strong font-bold italic">{!! $hf->number(\Auth::user()->avgActualValue($categories, $year, $activeMonthCount, ($switchMode == "difference"))) !!}</td>
                    <td class="text-right border-left-weak border-top-strong font-bold">{!! $hf->number(\Auth::user()->totalActualValue($categories, $year, ($switchMode == "difference"))) !!}</td>
                </tr>
            </table>

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
    @else
        <div>
            <p class="text-center">
                No posts created ...
            </p>
        </div>
    @endif

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-1" role="dialog" aria-modal="true" x-show="modalColumnUpdate">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-1">
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

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-2" role="dialog" aria-modal="true" x-show="modalFieldUpdate">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-2">
                        {{$fieldName}} for ({{$fieldMonthName}})
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Added values (<span>{!!$fieldTypeHTML!!}</span>)<br>
                            </p>
                            <p>
                                <span>{!! $fieldHistoryHTML !!}</span>
                            </p>
                        </div>
                        <hr class="my-4">
                        <input wire:keydown.enter="addValue" wire:model.defer="fieldAddValue" name="fieldAddValue" type="text" class="form-input px-2 py-2 rounded-md w-10/12" placeholder="Add value ...">
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="addValue" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        Add
                    </button>
                    <button type="button" wire:click="closeFieldUpdate" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display:none" aria-labelledby="modal-title-3" role="dialog" aria-modal="true" x-show="modalDelete">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-3">
                        {{$fieldName}} for ({{$fieldMonthName}})
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Delete value <span>{!!$deleteValueHTML!!}</span>?<br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="deleteValue" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Delete
                    </button>
                    <button type="button" wire:click="closeDelete" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
