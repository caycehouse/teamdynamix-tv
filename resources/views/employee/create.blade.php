<div>
    <div class="form-group">
        <label>Enter Name</label>
        <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
    </div>
    <div class="form-group">
        <label>Enter Banner ID</label>
        <input type="text" class="form-control input-sm" placeholder="Banner ID" wire:model="banner_id">
    </div>
    <button wire:click="store()" class="btn btn-primary">Submit</button>
</div>
