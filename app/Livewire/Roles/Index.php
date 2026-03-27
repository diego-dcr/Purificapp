<?php

namespace App\Livewire\Roles;

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
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Roles')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public ?int $editingRoleId = null;

    public string $name = '';

    public array $selectedPermissions = [];

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

    public function edit(int $roleId): void
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
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
                $this->editingRoleId
                    ? Rule::unique('roles', 'name')->ignore($this->editingRoleId)
                    : Rule::unique('roles', 'name'),
            ],
        ]);

        if ($this->editingRoleId) {
            $role = Role::findOrFail($this->editingRoleId);
            $role->update(['name' => $this->name]);
            $message = 'Rol actualizado correctamente.';
        } else {
            $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
            $message = 'Rol creado correctamente.';
        }

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('status', $message);

        $this->resetForm();
        $this->resetPage();
    }

    public function delete(int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $role->delete();

        session()->flash('status', 'Rol eliminado correctamente.');

        if ($this->editingRoleId === $roleId) {
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

        $this->editingRoleId = null;
        $this->name = '';
        $this->selectedPermissions = [];
        $this->showForm = false;
    }

    #[Computed]
    public function roles(): LengthAwarePaginator
    {
        return Role::query()
            ->withCount('permissions')
            ->with('permissions')
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function availablePermissions(): \Illuminate\Database\Eloquent\Collection
    {
        return Permission::orderBy('name')->get();
    }

    #[Computed]
    public function totalRoles(): int
    {
        return Role::count();
    }

    #[Computed]
    public function totalPermissions(): int
    {
        return Permission::count();
    }
}
