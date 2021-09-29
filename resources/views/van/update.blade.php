<div>
    <input type="hidden" wire:model="selected_id">
    <div class="form-group">
        <label>Enter Name</label>
        <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
    </div>
    <button wire:click="update()" class="btn btn-primary">Update</button>
</div>
