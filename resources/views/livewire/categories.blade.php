<div class="text-center" x-data="{ deleteshow: false }">
    <input wire:keydown.enter="create" wire:model.defer="name" name="name" type="text" class="form-input px-2 py-1 rounded-md" placeholder="Category name ...">
    <button wire:click="create" class="form-input px-2 py-1 rounded-md color-white bg-green-200">Create</button>

    <ul class="pt-6 w-4/12 m-auto text-left">
        @foreach($categories as $category)
            <li>
                <span class="text-sm text-gray-600">
                    [
                    <a href="javscript:;" class="text-blue-500" wire:click="moveUp({{$category->id}})">↑</a>
                    <a href="javscript:;" class="text-blue-500" wire:click="moveDown({{$category->id}})">↓</a>
                    <a href="javscript:;" class="text-red-500"  wire:click="$set('deleteID', {{$category->id}})" @click="deleteshow=true">X</a>
                    ]
                </span>
                {{$category->name}}
                
            </li>
        @endforeach
    </ul>

    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="deleteshow">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-1 text-center sm:mt-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Delete category?
                        </h3>
                        <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            All posts and data related will also be removed.
                        </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 text-center">
                    <button type="button" wire:click="delete" @click="deleteshow=false" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Delete
                    </button>
                    <button type="button" @click="deleteshow=false" class="inline-flex w-5/12 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
