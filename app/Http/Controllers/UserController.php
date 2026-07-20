<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);

        return view('utilisateurs.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('display_name')->get();

        return view('utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'telephone' => 'required|string|max:20|unique:users,telephone',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'mac_address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        $roles = Role::orderBy('display_name')->get();

        return view('utilisateurs.edit', ['user' => $utilisateur, 'roles' => $roles]);
    }

    public function update(Request $request, User $utilisateur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($utilisateur->id)],
            'telephone' => ['required', 'string', 'max:20', Rule::unique('users', 'telephone')->ignore($utilisateur->id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'mac_address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $utilisateur->update($validated);

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->delete();

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}