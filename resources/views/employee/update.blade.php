<form wire:submit.prevent="update">
    <input type="hidden" wire:model="selected_id">
    <div class="shadow overflow-hidden sm:rounded-md text-black">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" placeholder="Name" wire:model="name"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="banner_id" class="block text-sm font-medium text-gray-700">Banner ID</label>
                    <input type="text" name="banner_id" placeholder="Banner ID" wire:model="banner_id"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('banner_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update
            </button>
        </div>
    </div>
</form>
