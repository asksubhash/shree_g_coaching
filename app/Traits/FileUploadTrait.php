<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadSingleFile($file, $filePath)
    {
        try {
            // Generate the new unique name
            $newFileName = $file->hashName();

            // Store the file
            $path = $file->storeAs($filePath, $newFileName);

            // Check if the file uploaded
            if (Storage::exists($path)) {
                return [
                    'status' => true,
                    'filename' => $newFileName,
                    'path' => $path
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error while uploading image. Please try again.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Something went wrong. Error: ' . $e
            ];
        }
    }
}
