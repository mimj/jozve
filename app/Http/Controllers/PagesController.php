<?php

namespace App\Http\Controllers;

use App\MyHelpers\Helpers;
use App\Page;
use App\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    // 2^22 : it means user can have 1024 page (per topic) <=  1024 * 4194304 = 4294967295 = MAX UnsignedInteger
//    private CONST POSITION_STEP = 4194304;
    private CONST POSITION_STEP = 1;


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Topic $topic)
    {
        $parentPageId = $request->parentPageId;
        try {
            $this->authorize('view', $topic);
        } catch (AuthorizationException $e) {
        }

//        return json_encode($topic->pages);
        $pages = Db::table('pages')
            ->where('topic_id', '=', $topic->id)
            ->where('parent_id', '=', $parentPageId)
            ->select('id', 'title', 'position', 'unique_name', 'parent_id', 'topic_id', '_lft', '_rgt')->get();
        return json_encode($pages);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Topic $topic)
    {
        try {
            $this->authorize('view', $topic);
        } catch (AuthorizationException $e) {
        }
//        dd($request->all());
        $data = [
            'unique_name' => self::random_strings(10), //TODO Doesn't have a default value error :(
            'parent_id' => $request->parent_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'position' => 0,
            'content' => $request->page_content,
        ];
        if ($request->parent_id == null && $request->prevPageId == null) {
            $node = Page::create($data);
        } elseif ($request->parent_id == null && $request->prevPageId != null) {
            $prev = \App\Page::where('id', $request->prevPageId)->first();
//            dd($prev);
            $node = new \App\Page($data);
            $node->afterNode($prev)->save();
//            dd($node);
        } elseif ($request->parent_id != null && $request->prevPageId == null) {
            $parent = \App\Page::where('id', $request->parent_id)->first();
//            dd($prev);
            $node = new \App\Page($data);
            $parent->appendNode($node);
//            dd($node);
        } elseif ($request->parent_id != null && $request->prevPageId != null) {
            $prev = \App\Page::where('id', $request->prevPageId)->first();
//            dd($prev);
            $node = new \App\Page($data);
            $node->afterNode($prev)->save();
//            dd($node);
        }
        return $node;

        $position = null;

        if ($request->prevPagePosition == 0 && $request->parent_id == 0) {
            $prevRecord = DB::table('pages')
                ->where('topic_id', $topic->id)
                ->where('parent_id', $request->parent_id)
                ->orderBy('id', 'DESC')->first();

            $position = 1; //page position on pages menu list
            if ($prevRecord) {
//            $position_as_sql = DB::select('Select @sum:= :lastPosition + :step as position', ["lastPosition" => $lp, "step" => $step]);
//            $position = ($position_as_sql[0]->position);

                $position = $prevRecord->position + self::POSITION_STEP;
            }
        } elseif ($request->prevPagePosition >= 1) {

            $pages = DB::select('update pages p
                                        set p.position = p.position + 1
                                        where p.topic_id = :topic_id 
                                        and p.parent_id = :parent_id 
                                        and p.position > :position 
                                         ', [':topic_id' => $request->topic_id, ":parent_id" => $request->parent_id, 'position' => $request->prevPagePosition]);
            $position = $request->prevPagePosition + 1;
        }


        $data = [
            'unique_name' => self::random_strings(10), //TODO Doesn't have a default value error :(
            'parent_id' => $request->parent_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'position' => $position,
            'content' => $request->page_content,
        ];
//        dd($data);
        $page = Page::create($data);
//        $page = $topic->pages()->save($data);
        return json_encode($page);

    }

    /**
     * Display the specified resource.
     *
     * @param Topic $topic
     * @param  \App\Page $page
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic, Page $page)
    {
//        return $topic;
        try {
            $this->authorize('view', $topic);
            return $page;
        } catch (AuthorizationException $e) {

        }
        return $page;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
//        return $request->all();
//        $files = $request->file('files');
//        dd($request->all());
//        if ($request->hasFile('files')) {
////            return 'true';
//            print_r($files);
//            foreach ($files as $file) {
//                $filename = $file->getClientOriginalName();
//                echo $filename;
//                print $file;
//                echo "<br>";
//                $extension = $file->getClientOriginalExtension();
//                //dd($check);
//            }
//
//        }
////        return 'false';
////        dd($request->files);
//        return 'done';

//        $page = DB::select('select * from pages where id=:id', [':id' => $request->id]);
//        $page = \App\Page::where('id', $request->id)->first();
//        dd($page);
        $attributes = [
            'parent_id' => $request['parent_id'],
            'topic_id' => $topic->id,
            'title' => $request['title'],
            'position' => $request['position'],
            'content' => $request['content']
        ];
//        $page->update($attributes);
        $page = DB::select('update pages p
                                        set p.content = :content
                                        where p.id = :id 
                                         ', [":id" => $request->id, ':content' => $request['content']]);
//        dd($page);

        return $page;
    }

    public function upload(Request $request, Topic $topic, Page $page)
    {
//        return $request->all();
        $page_unique_name = $page->unique_name;
        $topic_unique_name = $topic->unique_name;
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                /** Same Name Creation Pattern Should be on client side */
                $uploadFile = $file->move('public' . DIRECTORY_SEPARATOR
                    . 'uploads' . DIRECTORY_SEPARATOR
                    . $topic_unique_name . DIRECTORY_SEPARATOR
                    . $page_unique_name . DIRECTORY_SEPARATOR, $filename);
            }
        }
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic, Page $page)
    {
//        DB::delete("delete from pages p where p.parent_id = ?", [$page->id]);
//        dd($page);
        $id = $page->parent_id;
        $page->forceDelete();
        $pages = DB::table('pages')
            ->where('topic_id', $topic->id)
            ->where('parent_id', $id)
            ->select('id', 'title', 'position', 'unique_name', 'parent_id', 'topic_id', '_lft', '_rgt')->get();
        return json_encode($pages);
    }

    public function deletePieceImage(Request $request, Topic $topic, Page $page, $pieceLocationOnPage)
    {
        return unlink('public' . DIRECTORY_SEPARATOR
            . 'uploads' . DIRECTORY_SEPARATOR
            . $topic->unique_name . DIRECTORY_SEPARATOR
            . $page->unique_name . DIRECTORY_SEPARATOR);

    }

    public static function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }
}
