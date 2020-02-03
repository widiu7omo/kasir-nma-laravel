<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Filepond;
use Illuminate\Support\Facades\Storage;

class FilepondController extends BaseController
{

    /**
     * @var Filepond
     */
    private $filepond;

    public function __construct(Filepond $filepond)
    {
        $this->filepond = $filepond;
    }

    /**
     * Uploads the file to the temporary directory
     * and returns an encrypted path to the file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $tempPath = config('filepond.temporary_files_path');

        $filePath = tempnam($tempPath, 'laravel-filepond');
        $filePath .= '.' . $file->extension();

        $filePathParts = pathinfo($filePath);
        var_dump(realpath(sys_get_temp_dir()));

        if (!Storage::disk('local')->put($filePathParts['basename'],$filePathParts['dirname'])) {
            return Response::make('Could not save file', 500);
        }

        return Response::make($this->filepond->getServerIdFromPath($filePath), 200);
    }

    /**
     * Takes the given encrypted filepath and deletes
     * it if it hasn't been tampered with
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $filePath = $this->filepond->getPathFromServerId($request->getContent());
        if(unlink($filePath)) {
            return Response::make('', 200);
        } else {
            return Response::make('', 500);
        }
    }
}
