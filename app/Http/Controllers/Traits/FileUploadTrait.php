<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait FileUploadTrait
{

    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request, $dir='products') {
        $uploadDir = $dir;
        
        ini_set('memory_limit', '-1');
        if (!file_exists(upload_path($uploadDir))) {
            mkdir(upload_path($uploadDir), 0777);
            mkdir(upload_path($uploadDir . '/thumb'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                    // Check file width
                    $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
                    $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . str_slug($name) . '.' . $extension;
                    $file = $request->file($key);
                    $image = Image::make($file);
                    if (!file_exists(upload_path($uploadDir . '/thumb'))) {
                        mkdir(upload_path($uploadDir . '/thumb'), 0777, true);
                    }

                    Image::make($file)->resize(50, 50)->save(upload_path($uploadDir . '/thumb') . '/' . $filename);

                    $width = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                        $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                    } elseif ($width > $request->{$key . '_max_width'}) {
                        $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_max_width'}) {
                        $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(upload_path($uploadDir) . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {

                    $extension = Arr::last(explode('.', $request->file($key)->getClientOriginalName()));
                    $name = Arr::first(explode('.', $request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . Str::slug($name) . '.' . $extension;
                    $request->file($key)->move(upload_path($uploadDir), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }
        return $finalRequest;
    }
}