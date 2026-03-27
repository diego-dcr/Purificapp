<?php

namespace App\Livewire\Permissions;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Permisos')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public ?int $editingPermissionId = null;

    public string $name = '';

    public bool $showForm = false;

    public function mount(): void
    {
        $user = Auth::user();

        abort_unless($user instanceof User && $user->hasRole('admin'), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $permissionId): void
    {
        $permission = Permission::findOrFail($permissionId);

        $this->editingPermissionId = $permission->id;
        $this->name = $permission->name;
        $this->showForm = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->editingPermissionId
                    ? Rule::unique('permissions', 'name')->ignore($this->editingPermissionId)
                    : Rule::unique('permissions', 'name'),
            ],
        ]);

        if ($this->editingPermissionId) {
            $permission = Permission::findOrFail($this->editingPermissionId);
            $permission->update(['name' => $this->name]);
            $message = 'Permiso actualizado correctamente.';
        } else {
            Permission::create(['name' => $this->name, 'guard_name' => 'web']);
            $message = 'Permiso creado correctamente.';
        }

        session()->flash('status', $message);

        $this->resetForm();
        $this->resetPage();
    }

    public function delete(int $permissionId): void
    {
        Permission::findOrFail($permissionId)->delete();

        session()->flash('status', 'Permiso eliminado correctamente.');

        if ($this->editingPermissionId === $permissionId) {
            $this->resetForm();
        }

        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->resetValidation();

        $this->editingPermissionId = null;
        $this->name = '';
        $this->showForm = false;
    }

    #[Computed]
    public function permissions(): LengthAwarePaginator
    {
        return Permission::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function totalPermissions(): int
    {
        return Permission::count();
    }
}
