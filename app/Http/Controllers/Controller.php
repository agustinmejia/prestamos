<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function custom_authorize($permission){
        if(!Auth::user()->hasPermission($permission)){
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }
    }
    

    public function agent($id)
    {
        return DB::table('users as u')
            ->join('roles as r', 'r.id', 'u.role_id')
            ->where('u.id', $id)
            ->select('u.id', 'u.name', 'r.name as role')
            ->first();
    }


    // Funcion para ver la caja abierta
    public function cashierOpen()
    {
        return Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();
    }

    public function store_image($file, $folder, $size = 512){
        try {
            Storage::makeDirectory($folder.'/'.date('F').date('Y'));
            $base_name = Str::random(20);

            // imagen normal
            $filename = $base_name.'.'.$file->getClientOriginalExtension();
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $path =  $folder.'/'.date('F').date('Y').'/'.$filename;
            $image_resize->save(public_path('../storage/app/public/'.$path));

            // imagen cuadrada
            $filename_small = $base_name.'-cropped.'.$file->getClientOriginalExtension();
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize(null, 256, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->resizeCanvas(256, 256);
            $path_small = "$folder/".date('F').date('Y').'/'.$filename_small;
            $image_resize->save(public_path('../storage/app/public/'.$path_small));

            return $path;
        } catch (\Throwable $th) {
            return null;
        }
    }
}