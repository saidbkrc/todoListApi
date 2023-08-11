<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;

class TasksController extends Controller
{
    public function list(Request $request)
    {
        $tasks = Task::where('user_id', auth('sanctum')->user()->id)
            ->when($request->has('status'), function($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->has('title'), function($query) use ($request) {
                $query->where('title', 'LIKE', '%'.$request->input('title').'%');
            })
            ->when($request->has('ranking'), function($query) use ($request) {
                $query->orderBy('created_at', $request->input('ranking', 'ASC'));
            })
            ->get();

        return response()->json([
            'status'    => true,
            'message'   => 'Görevler listelendi',
            'data'      => $tasks
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required|min:10',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ]);
        }

        $task = Task::create(array_merge($request->all(), [
            'status'    => 'waiting',
            'user_id'   => auth('sanctum')->user()->id
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Yeni görev başarılı bir şekilde oluşturuldu.',
            'data'  => $task
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'min:10',
            'task_id'   => 'required',
            'status'    => 'in:waiting,process,cancelled,done',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ]);
        }

        $task = Task::where('id', $request->input('task_id'))->first();

        if(!$task){
            return response()->json([
                'status' => false,
                'message' => 'Görev bulunamadı.'
            ]);
        }

        $task->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Görev başarılı bir şekilde güncellendi.',
            'data'  => $task
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ]);
        }

        $task = Task::where('id', $request->input('task_id'))->first();

        if(!$task){
            return response()->json([
                'status' => false,
                'message' => 'Görev bulunamadı.'
            ]);
        }

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Görev başarılı bir şekilde silindi.',
        ]);
    }
}
