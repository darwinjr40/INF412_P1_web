<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Recipe;
use App\Models\Specialty;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{


    
    public function guardar($inquiry_id)
    {
        $inquiry = Inquiry::find($inquiry_id);
        if ($inquiry->tipo == Inquiry::P) {
            $inquiry->tipo = Inquiry::F;
            $inquiry->save();
        }
        $specialty = Specialty::find($inquiry->specialty_id);
        $doctor =  User::find($inquiry->doctor_id);
        $patient = collect( User::where('id',$inquiry->patient_id)->first());
        $patient = $patient->merge(['edad' =>  (Carbon::parse($patient['fecha'])->age)]);
        $vital = Vital::where('inquiry_id', $inquiry_id)->first();
        $recipes = Recipe::all()->where('inquiry_id', $inquiry_id);
        $name_file = $inquiry->id . '.pdf';
        //guardamos la url del archivo 
        $url = public_path().'/storage/'.$inquiry_id;
        if (!file_exists($url))    Storage::makeDirectory('public/'. $inquiry_id);
        // generar un pdf atravez de una vista
        $pathName = $url.'/'. $name_file;
        $pdf = FacadePdf::loadView('show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'));     
        $pdf->save($pathName);
        $file = $this->pathToUploadedFile($pathName);
        //crea el archiva en aws
        $pathNube = Storage::disk('s3')->put($inquiry_id, $file, 'public');
        $inquiry->path =  Storage::disk('s3')->url($pathNube);
        $inquiry->pathLocal =  'storage/'.$inquiry_id.'/'.$name_file;
        $inquiry->name_file = $name_file;
        $inquiry->fecha_file = date('d-m-y H:i:s', time());
        $inquiry->save();
        return redirect()->route('patients.show', $patient['id'])->with('info', 'Guardado Exitosamente');
    }

    
    public function show($inquiry_id)
    {                
        $inquiry = Inquiry::find($inquiry_id);
        // return $inquiry->url;
        // $url = $inquiry->url. $inquiry->name_file;
        if ($inquiry->path) {
            return view('archive.show', compact('inquiry'));
        } else {
            abort(403);
        }
    }


    public function pathToUploadedFile( $path, $test = true ) {
        $filesystem = new Filesystem;
        
        $name = $filesystem->name( $path );
        $extension = $filesystem->extension( $path );
        $originalName = $name . '.' . $extension;
        $mimeType = $filesystem->mimeType( $path );
        $error = null;    
        return new UploadedFile( $path, $originalName, $mimeType, $error, $test );
    }
    public function imprimir($inquiry_id)
    {        
        // $inquiry = Inquiry::find($inquiry_id);
        // $specialty = Specialty::find($inquiry->specialty_id);
        // $doctor =  User::find($inquiry->doctor_id);
        // $patient = collect( User::where('id',$inquiry->patient_id)->first());
        // $patient = $patient->merge(['edad' =>  (Carbon::parse($patient['fecha'])->age)]);
        // $vital = Vital::where('inquiry_id', $inquiry_id)->first();
        // $recipes = Recipe::all()->where('inquiry_id', $inquiry_id);
        
        // $pdf = FacadePdf::loadView('show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'));     
        // return  $pdf->download($patient['nombre'].'.pdf');              
    }
    
    // public function guardar($inquiry_id)
    // {
        //     $inquiry = Inquiry::find($inquiry_id);
        //     $specialty = Specialty::find($inquiry->specialty_id);
    //     $doctor =  User::find($inquiry->doctor_id);
    //     $patient = collect( User::where('id',$inquiry->patient_id)->first());
    //     $patient = $patient->merge(['edad' =>  (Carbon::parse($patient['fecha'])->age)]);
    //     $vital = Vital::where('inquiry_id', $inquiry_id)->first();
    //     $recipes = Recipe::all()->where('inquiry_id', $inquiry_id);
    //     $name_file = $inquiry->id.'.pdf';
        
    //     $url = base_path().'/public/storage/'.$inquiry_id.'/';
    //     if (!file_exists($url)) {
    //         Storage::makeDirectory('public/'. $inquiry_id);
    //     } 
        
    //     $pdf = FacadePdf::loadView('show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'));     
    //     $pdf->save(storage_path('app/public/'.$inquiry_id.'/') . $name_file);
    //     $inquiry->url =  'storage/'.$inquiry_id.'/'.$name_file;
    //     $inquiry->name_file = $name_file;
    //     $inquiry->save();
    //     // $pdf = FacadePdf::loadView('inquiry.show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'))->output();     
    //     // Storage::disk('public')->put('mi-archivo.pdf', $pdf);
         
    //     // return  $pdf->download('primerpdf.pdf');
    //     return redirect()->route('patients.show', $patient['id']);
    //     // $a = storage_path('app/public/3');
    //     // $a = 
    //     // base_path();
    //     // return $a;
    //     $url =  '/public/' . '1' . '/';
    //     // Storage::put( 'storage', $content);
    //     // Storage::putFileAs($url, $content, 'duashdkashdkashd');
        
    //     return "hola";
    // }
    
    public function guardar1($inquiry_id)
    {
        $inquiry = Inquiry::find($inquiry_id);
        $specialty = Specialty::find($inquiry->specialty_id);
        $doctor =  User::find($inquiry->doctor_id);
        $patient = collect( User::where('id',$inquiry->patient_id)->first());
        $patient = $patient->merge(['edad' =>  (Carbon::parse($patient['fecha'])->age)]);
        $vital = Vital::where('inquiry_id', $inquiry_id)->first();
        $recipes = Recipe::all()->where('inquiry_id', $inquiry_id);
        $name_file = $inquiry->id.'.pdf';
        //guardamos la url del archivo
        $url = public_path().'/storage/'.$inquiry_id;
        if (!file_exists($url))    Storage::makeDirectory('public/'. $inquiry_id);
        // return $url;
        $pdf = FacadePdf::loadView('show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'));     
        $pdf->save($url.'/'. $name_file);
        $inquiry->path =  'storage/'.$inquiry_id.'/'.$name_file;
        $inquiry->name_file = $name_file;
        $inquiry->fecha_file = date('d-m-y H:i:s', time());
        $inquiry->save();
        // $pdf = FacadePdf::loadView('inquiry.show2', compact ('patient', 'inquiry', 'specialty', 'doctor', 'vital', 'recipes'))->output();     
         
        // return  $pdf->download('primerpdf.pdf');
        return redirect()->route('patients.show', $patient['id']);
        // $a = storage_path('app/public/3');
        // $a = 
        // base_path();
        // return $a;
        $url =  '/public/' . '1' . '/';
        // Storage::put( 'storage', $content);
        // Storage::putFileAs($url, $content, 'duashdkashdkashd');
        
        return "hola";
    }
    
}
