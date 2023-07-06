<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $imageName = Image::make($request->image)
                ->resize($request->width, $request->height)
                ->encode('jpg');

        $thumbImageName = Image::make($request->image)
                ->resize(40, 40)
                ->encode('jpg');

        $imagePath = 'public/temp/' . $request->get('path') . '/';
        $hashName = $request->image->hashName();
        Storage::disk('local')->put($imagePath . $hashName, (string) $imageName, 'public');
        Storage::disk('local')->put($imagePath . 'thumb_' . $hashName, (string) $thumbImageName, 'public');

        $media = Media::create([
            'name'  => $hashName,
            'type'  => $request->get('path')
        ]);

        return $media->id;
    }

    public function removePhoto(Request $request)
    {
        if($request->get('image') != 'avatar.png') {
            Storage::disk('local')->delete('public/temp/' . $request->path .'/'. $request->image);
            Storage::disk('local')->delete('public/temp/' . $request->path .'/thumb_'. $request->image);
            $media = Media::where('id', $request->get('id'))->get();
            $media->delete();
        }
        return "Removed";
    }
}
