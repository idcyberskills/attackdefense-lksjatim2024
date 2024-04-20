<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormRequest $request): View
    {
        //
        $user_id = auth()->user()->id;

        if (isset($request->search)) {
            $query = $request->search_query;
            $column_name = $request->column;

            $messages = Message::where(function($q) use($query) {
                $q->where('title', 'like', "%$query%")
                    ->orWhere('message', 'like', "%$query%");
            })
            ->where('sender_id', '=', $user_id)
            ->orWhere('recipient_id', '=', $user_id)
            ->get();

        } else {
            $messages = Message::where('sender_id', '=', $user_id)
            ->orWhere('recipient_id', '=', $user_id)
            ->get();
        }

        return view('messages', ['messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        //
        $users = User::all();
        return view('send', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request): RedirectResponse
    {
        //

        $message_object = new Message();
        $message_object->title = $request->title;
        $message_object->recipient_id = $request->recipient_id;

        $content = $request->message;
        $content = htmlentities($content);

        $message_object->message = $content;
        $message_object->sender_id = auth()->user()->id;

        $message_object->save();

        return Redirect::route('view_messages')->withSuccess('Message has been sent!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message): RedirectResponse
    {
        //
        if (auth()->user()->id == $message->sender->id) {
            $message_id = $message->id;
            $message->delete();

            return Redirect::route('view_messages')->withSuccess("Message with ID $message_id has been deleted.");
        } else {
            return Redirect::route('view_messages')
                ->withErrors("Message can't be deleted!");
        }
    }
}
