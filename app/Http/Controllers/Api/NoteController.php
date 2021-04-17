<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of users note
     *
     * @return \Illuminate\Http\Response
     */
    public function userNote()
    {
        try {
            return response([
                'success' => true,
                'notes' => Auth::user()->notes()->orderBy('id', 'DESC')->get()
            ]);
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function changeNoteStatus(Request $request, $noteid)
    {
        try {
            $note = Note::find($noteid);
            $note->update([
                'public' => $request->public
            ]);
            return response([
                'success' => true,
                'note' => $note
            ]);

        } catch (\Exception $exception) {

            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'success' => true,
            'notes' => Note::where('public', true)->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $public = false;
        if ($request->public) {
            $public = true;
        }
        $note = Auth::user()->notes()->create([
            'title' => $request->title,
            'body' => $request->body,
            'public' => $public
        ]);
        return response([
            'success' => true,
            'note' => $note,
            'message' => 'note stored'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        return response([
            'note' => $note
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $public = false;
        if ($request->public) {
            $public = true;
        }
        $note->update([
            'title' => $request->title,
            'body' => $request->body,
            'public' => $public
        ]);
        return response([
            'success' => true,
            'note' => $note,
            'message' => 'note successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response([
            'success' => true,
            'message' => 'note deleted'
        ]);
    }
}
