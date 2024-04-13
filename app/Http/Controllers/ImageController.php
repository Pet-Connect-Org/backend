<?php

namespace App\Http\Controllers;

use App\Models\Image;

class ImageController extends Controller
{
    //
    public function create(String $id, String $link) {
        $image = Image::create([
            'post_id' => $id,
            'link' => $link
        ]);

        if ($image) {
            return response()->json([
                'message' => "OK",
                'data' => $image
            ],201);
        }
    }

    public function update() {
    }
}
