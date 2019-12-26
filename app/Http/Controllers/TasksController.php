<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 以下を追加しないとそんなモデルないと言われる
use Validator;  //入力値のチェックをやってくれる
use App\Task;
use Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // __constract() 他の関数が実行されるときに自動的に先に実行される
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        //$tasks = Task::orderBy('deadline', 'asc')->get(); //dbの中身を取得して$taskへ
        //ddd($tasks);
        // viewのfileでわたされた中身を表示sる $tasksでおくられる
        $tasks = Task::where('user_id', Auth::user()->id)   //ログインuserのidが登録されてるレコードを取得
            ->orderBy('deadline', 'asc')                    // 時間で並べる
            ->get();
        return view('tasks', [
            'tasks' => $tasks
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ddd($request);  //var_dumpみたいなやつ
        //
        $validator = Validator::make($request->all(), [
            // 必須項目にはrequired
            'task' => 'required|max:255',
            'deadline' => 'required',
        ]);
        // :
        if ($validator->fails()) {
            return redirect()
                ->route('tasks.index')
                ->withInput()
                ->withErrors($validator);
        }
        // Eloquent
        $task = new Task;
        $task->user_id = Auth::user()->id;
        $task->task = $request->task;
        $task->deadline = $request->deadline;
        $task->comment = $request->comment;
        $task->save();
        // tasks.index
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        return view('taskedit', ['task' => $task]);
        // taskeditっていうviewファイルに、taskって名前で、$taskっていう変数(該当するレコード)を渡しますよー
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'task' => 'required|max:255',
            'deadline' => 'required',
        ]);
        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect()
                ->route('tasks.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
        //データ更新処理
        $task = Task::find($id);   //該当するidのレコードを持ってくる
        $task->task   = $request->task;
        $task->deadline = $request->deadline;
        $task->comment = $request->comment;
        $task->save();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = Task::find($id);
        // ddd($task);
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
