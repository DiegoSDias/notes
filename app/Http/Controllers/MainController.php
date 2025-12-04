<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {

        // load user's notes
        $id = session('user.id');
        $notes = User::find($id)
                    ->notes()
                    ->whereNull('deleted_at')
                    ->get()
                    ->toArray();

        // Show home view
        return view('home', ['notes' => $notes]);
    }
    
    public function newNote(Request $request) {
        // created new note
        return view('new_note');
    }

    public function newNoteSubmit(Request $request) {
        //validate request
        $request->validate(
            //rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            // error mensages
            [
                'text_title.required' => "O título é obrigatório",
                'text_title.min' => "O título deve ter pelo menos :min caracteres",
                'text_title.max' => "O título deve ter no máximo :max caracteres",
                'text_note.required' => "A nota é obrigatória",
                'text_note.min' => "A nota deve ter pelo menos :min caracteres",
                'text_note.max' => "A nota deve ter no máximo :max caracteres",
            ]
        );
        
        //get user id
        $id = session(('user.id'));
        
        //create new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->is_public = $request->has('is_public');
        $note->save();

        //redirect to home
        return redirect()->route('home');
    }

    public function editNote($id) {
        $id = Operations::decryptId($id);
        
        // load note
        $note = Note::find($id);

        // show edit note view
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request) {
        // validate request
        $request->validate(
            //rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            // error mensages
            [
                'text_title.required' => "O título é obrigatório",
                'text_title.min' => "O título deve ter pelo menos :min caracteres",
                'text_title.max' => "O título deve ter no máximo :max caracteres",
                'text_note.required' => "A nota é obrigatória",
                'text_note.min' => "A nota deve ter pelo menos :min caracteres",
                'text_note.max' => "A nota deve ter no máximo :max caracteres",
            ]
        );

        // check if note_id exists
        if($request->note_id == null) {
            return redirect()->route('home');
        }

        // decrypt note_id
        $id = Operations::decryptId($request->note_id);

        // load note
        $note = Note::find($id);

        // update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->is_public = $request->has('is_public');
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function deleteNote($id) {
        // check if $id is encrypyted
        $id = Operations::decryptId($id);

        // load note
        $note = Note::find($id);

        // 1. hard delete
        //$note->delete();

        //2. soft delete
        $note->deleted_at = date('Y:m:d H:i:s');
        $note->save();

        //redirect to home
        return redirect()->route('home');
    }

    public function community(){
        $notes = Note::where('is_public', true)
            ->whereNull('deleted_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('community', [
            'notes' => $notes,
        ]);
    }
}
