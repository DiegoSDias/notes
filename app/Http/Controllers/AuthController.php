<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Services\Operations;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function login() {
        return view ('auth.login');
    }

    public function loginSubmit(Request $request) {
        // form validation
        $request->validate(
            [
                'text_username' => ['required', 'email'], 
                'text_password' => ['required', 'min:6', 'max:16']
            ],
            // error message
            [
                'text_username.required' => "O username é obrigatório",
                'text_username.email' => "Username deve ser um email válido",
                'text_password.required' => "A senha é obrigatória",
                'text_password.min' => "A senha deve ter pelo menos :min caracteres",
                'text_password.max' => "A senha deve ter no máximo :max caracteres",
            ]
        );

        // get user input
        $email = $request->input('text_username');
        $password = $request->input('text_password');

        // check if user exists
        $user = User::where('email', $email)
                ->where('deleted_at', NULL)
                ->first();

        if(!$user){
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Email ou Senha incorretos!');
        }

        // check if password is corret
        if(!password_verify($password, $user->password)){
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Email ou Senha incorretos!');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        // login user
        session([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                "role" => $user->role
            ]
        ]);

        return redirect('/');
     }

    public function logout(Request $request) {
        // logout from the application
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function register() {
        return view('auth.register');
    }

    public function store(ProfileUpdateRequest $request) {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect('login');
    }

    public function edit(string $id) {
        $id = Operations::decryptId($id);
        $user = User::find($id);
        return view('auth.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, string $id) {
         $id = Operations::decryptId($id);
        $user = User::find($id);
        $data = $request->validated();

        if($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            unset($data['password']);
        }

        $user -> update($data);

        session([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                "role" => $user->role
            ]
        ]);

        return redirect(route('home'))->with('sucess', 'Usuário atualizado com sucesso');
    }
    
    public function destroy(Request $request, string $id) {
        $id = Operations::decryptId($id);
        $user = User::find($id);
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'))->with('sucess', 'Usuário deletedo com sucesso');
    }
}
