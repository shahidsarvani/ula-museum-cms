<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class VideoWallMediaController extends Controller
{
    //
    public function index()
    {
        $media = Media::all();
        return view('media.index', compact('media'));
    }

    public function video_wall_video_index()
    {
        $media = Media::where('screen_type', 'videowall')->get();
        // return $media;
        $media_grouped = $media->groupBy('screen_slug');
        return view('videowall_media.index', compact('media_grouped'));
    }

    public function video_wall_video_create()
    {
        $screens = Screen::where('screen_type', 'videowall')->where('is_touch', 0)->get();
        return view('videowall_media.create', compact('screens'));
    }
    public function video_wall_video_store(Request $request)
    {
        //
        // return $request;
        if (!$request->lang) {
            return back()->with('error', 'Select Language');
        }

        if ($request->file_names) {
            foreach ($request->file_names as $index => $fileName) {
                // $media = Media::whereName($fileName)->first();
                $media = Media::create([
                    'lang' => $request->lang,
                    'name' => $fileName,
                    'screen_slug' => $request->screen_id,
                    'screen_type' => 'videowall',
                    'type' => $request->types[$index],
                ]);
            }
            return redirect()->route('videowall_media.media.index');
        } else {
            return back()->with('error', 'Error! Uploading Media File');
        }
    }
    public function video_wall_video_delete($id)
    {
        $media = Media::findOrFail($id);
        Storage::delete('/public/media/' . $media->name);
        $media->delete();
        return redirect()->route('videowall_media.media.index')->with('success', 'Media deleted');
    }
}
