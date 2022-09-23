<?php

namespace App\Traits;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait AttachableMedia
{
    function uploadFile($file,$uploadDirectoryPath)
    {
        if (!is_dir(public_path() . DIRECTORY_SEPARATOR . $uploadDirectoryPath)) {
            @mkdir(public_path() . DIRECTORY_SEPARATOR . $uploadDirectoryPath, 0777, true);
        }

        $attachmentFiles = [];
        $originalName = $file->getClientOriginalName();
        $customFileName = $originalName;
        $new_name = md5(rand() . strtotime(date('Y-m-d H:i:s') . microtime())).$customFileName;// . $originalName;
        $destination = $uploadDirectoryPath . DIRECTORY_SEPARATOR . $new_name;
        Storage::disk('public')->put($destination, File::get($file));
        $attachmentFiles[] = [
            'url' => $destination,
            'file_name' => $new_name,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'original_name' => $customFileName,
            'created_by' => '',
            'created_at' => now()
        ];
        return $attachmentFiles[0]['url'];
    }

    function uploadFiles($files,$uploadDirectoryPath,$fileName=null)
    {
        if (!is_dir(public_path() . DIRECTORY_SEPARATOR . $uploadDirectoryPath)) {
            @mkdir(public_path() . DIRECTORY_SEPARATOR . $uploadDirectoryPath, 0777, true);
        }

        $attachmentFiles = [];
        if (count($files) > 0) {
            $i = 0;
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $customFileName = isset($fileName[$i]) && !empty($fileName[$i]) ? $fileName[$i] : $originalName;
                $new_name = md5(rand() . strtotime(date('Y-m-d H:i:s') . microtime())) . $originalName;
                $destination = $uploadDirectoryPath . DIRECTORY_SEPARATOR . $new_name;
                Storage::disk('public')->put($destination, File::get($file));
                $attachmentFiles[] = [
                    'url' => $destination,
                    'file_name' => $new_name,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'original_name' => $customFileName,
//                    'created_by' => Auth::user()->id,
                    'created_at' => now()
                ];
                $i++;
            }
        }

        return $attachmentFiles;
    }
}
