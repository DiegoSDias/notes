<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.user.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileUpdateRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect(route('admin.users.index'))->with('sucess', 'Novo usuário cadastrado');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', [
            'user'=>$user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        $data = $request->validated();
        if($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect(route('admin.users.index'))->with('sucess', 'Usuário atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect(route('admin.users.index'))->with('sucess', 'Usuário deletado com sucesso');
    }

    public function dashboard() {
        $totalUsers = User::count();
        $totalNotes = Note::count();
        $totalPublicNotes = Note::where('is_public', true)->count();
        $totalDeletedNotes = Note::whereNotNull('deleted_at')->count();
        $admins = User::where('role', 'admin')->count();
        $regulars = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalNotes',
            'totalPublicNotes',
            'totalDeletedNotes',
            'admins',
            'regulars',
        ));
    }

    public function communityNotes() {
        $notes = Note::where('is_public', true)
        ->with('user')
        ->orderByDesc('created_at')
        ->get();

        return view('admin.community_notes', compact('notes'));
    }
}
