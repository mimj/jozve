<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $topics = Topic::where('user_id', Auth::id())->get();
        $topics = Topic::where('user_id', Auth::id())->get();
        return $topics;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'create a topic for: ' . auth()->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO implement Validation Process
        $topic = Topic::create($request->all() + ["user_id" => auth()->id(), "unique_name" => $this->random_strings(10)]);
        return json_encode($topic);
    }

    /**
     * Display the specified resource.
     *
     * @param Topic $topic
     * @return void
     */
    public function show(Topic $topic)
    {
//        if ($topic['user_id'] !== auth()->id()) {
//            abort(403);
//        }
//        abort_if($topic['user_id'] != \auth()->id(), 403);

//        $this->authorize('view', $topic); // Using Policy

//        abort_unless(\Gate::allows('view', $topic), 403);

        return $topic;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Topic $topic
     * @return void
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Topic $topic
     * @return void
     */
    public function update(Request $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Topic $topic
     * @return void
     */
    public function destroy(Topic $topic)
    {
        //
    }

    public static function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }
}
