<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoWallScreenController extends Controller
{

    public function index()
    {
        $screens = Screen::where('is_touch', 1)->where('is_model', 1)->get();
        return view('video_wall_screens.index', compact('screens'));
    }


    public function create()
    {
        return view('video_wall_screens.create');
    }


    public function store(Request $request)
    {
        //
         //return $request;
        try {
            $data = $request->except('_token');
            $data['is_touch'] = 1;
            $data['is_model'] = 1;
            $data['is_rfid'] = 0;
            Screen::create($data);
            return redirect()->route('video_wall.screens.index')->with('success', 'Video Wall Screen is added!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error', 'Error: Something went wrong!');
        }
    }


    public function edit($id)
    {
        //
        $screen = Screen::find($id);
        return view('video_wall_screens.edit', compact('screen'));
    }


    public function update(Request $request, $id)
    {
        //
        // return $request;
        try {
            $screen = Screen::find($id);
            $data = $request->except('_token', '_method');
            $screen->update($data);
            return redirect()->route('video_wall.screens.index')->with('success', 'video_wall Screen is updated!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error', 'Error: Something went wrong!');
        }
    }


    public function destroy($id)
    {
        //
        try {
            $screen = Screen::find($id);
            $screen->delete();
            return redirect()->route('video_wall.screens.index')->with('success', 'Video Wall Screen is deleted!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error', 'Error: Something went wrong!');
        }
    }
}
