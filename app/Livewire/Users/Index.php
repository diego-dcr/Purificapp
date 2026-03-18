<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Usuarios')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public ?int $editingUserId = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $role = 'operation';

    public string $password = '';

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

    public function edit(int $userId): void
    {
        $user = User::with('roles')->findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        $this->role = $user->roles->pluck('name')->first() ?? 'operation';
        $this->password = '';
        $this->showForm = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules());

        $attributes = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        if ($validated['password'] !== '') {
            $attributes['password'] = $validated['password'];
        }

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->update($attributes);
            $message = 'Usuario actualizado correctamente.';
        } else {
            $attributes['password'] = $attributes['password'] ?? $validated['password'];
            $attributes['email_verified_at'] = now();

            $user = User::create($attributes);
            $message = 'Usuario creado correctamente.';
        }

        $user->syncRoles([$validated['role']]);

        session()->flash('status', $message);

        $this->resetForm();
        $this->resetPage();
    }

    public function delete(int $userId): void
    {
        $user = User::findOrFail($userId);

        if (Auth::id() === $user->id) {
            session()->flash('status', 'No puedes eliminar tu propio usuario.');

            return;
        }

        $user->delete();

        session()->flash('status', 'Usuario eliminado correctamente.');

        if ($this->editingUserId === $userId) {
            $this->resetForm();
        }

        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->editingUserId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId),
            ],
            'role' => ['required', Rule::in($this->availableRoles())],
            'password' => [$this->editingUserId ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }

    protected function resetForm(): void
    {
        $this->resetValidation();

        $this->editingUserId = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->role = 'operation';
        $this->password = '';
        $this->showForm = false;
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('username', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function totalUsers(): int
    {
        return User::count();
    }

    #[Computed]
    public function adminUsers(): int
    {
        return User::role('admin')->count();
    }

    #[Computed]
    public function availableRoles(): array
    {
        return ['admin', 'op_manager', 'driver', 'operation'];
    }
}