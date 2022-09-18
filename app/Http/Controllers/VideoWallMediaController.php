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
        $screens = Screen::where('is_touch', 1)->where('is_model', 1)->get();
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

    public function upload_media_dropzone(Request $request)
    {
        // create the file receiver
        $receiver = new FileReceiver("media", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return $this->saveFile($save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        $type = explode('-', $mime)[0];

        // Build the file path
        // $media = new Media();
        $filePath = 'public/media/';
        // $filePath = "upload/{$mime}/{$dateFolder}/";
        $finalPath = storage_path("app/" . $filePath);

        // move the file name
        $file->move($finalPath, $fileName);

        Log::info($finalPath . $fileName);
        $response = [
            'path' => $filePath,
            'name' => $fileName,
            'type' => $type,
        ];

        return response()->json($response);
    }

    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        // $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension
        $filename = 'media';

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }
}
