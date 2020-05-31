<?php

namespace App\Http\Controllers\API\Admin;

use App\File;
use App\Http\Controllers\Controller;
use App\Jobs\HandleFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Handle CSV file upload
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulk_insert(Request $request)
    {
        $file = $request->file('file');
        $description = $request->input('description');
        $ignore_invalid_rows = $request->boolean('ignore_invalid_rows', false);
        if ($file->isValid() && Str::startsWith($file->getMimeType(), 'text/')) {
            $filename = md5(rand(0, 10000)) . '.csv';
            $file->storeAs('uploads', $filename);
            $fileRecord = new File([
                'filename' => $filename,
                'description' => $description,
                'ignore_invalid_rows' => $ignore_invalid_rows,
                'uploaded_by' => auth()->user()->id
            ]);
            $fileRecord->save();
            dispatch(new HandleFileUploadJob($fileRecord));
            return response()->json([
                'status' => 'success',
                'message' => 'file uploaded successfully',
                'data' => [
                    'file_id' => $fileRecord->id
                ]
            ]);
        }
        dd($file->getMimeType());
        return response()->json([
            'status' => 'failed',
            'message' => 'failed to upload file'
        ]);
    }

}
