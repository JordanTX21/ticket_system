<?php

namespace App\Http\Controllers;

use App\Professor;
use App\Person;
use Illuminate\Http\Request;
use App\Utils\WithUtils;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.professor');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person_id = $request->person_id;
        $career = $request->career;

        if(!$person_id){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        if(!$career){
            return response()->json(['value' => false,'message' => "Ingrese una carrera"]);
        }

        try{
            $professor = Professor::create([
                'person_id' => $person_id,
                'career' => $career,
            ]);
            if(!$professor){
                return response()->json(['value' => false,'message' => "Error"]);
            }
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => "El profesor ya existe"]);
        }
        return response()->json(['value' => true,'message' => "Profesor Creado"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function show( $professor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function edit( $professor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $professor)
    {
        $person_id = $request->person_id;
        $career = $request->career;

        if(!$person_id){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        if(!$career){
            return response()->json(['value' => false,'message' => "Ingrese una carrera"]);
        }

        try{
            $professor_new = Professor::where(['id',$professor])->update([
                'person_id' => $person_id,
                'career' => $career,
            ]);
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => "El profesor ya existe"]);
        }
        return response()->json(['value' => true,'message' => "Profesor Actualizado"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function destroy( $professor)
    {
        $professort_destroyed = Professor::where('id',$professor)->update(['status' => false]);
        if(!$professort_destroyed){
            return response()->json(['value' => false,'message' => "Error"]);
        }
        return response()->json(['value' => true,'message' => "Profesor Eliminado"]);
    }

    public function search(Request $request)
    {
        $document = $request->document;
        if($document){
            $people = Person::where(
                'document', 'like', '%'.$document.'%'
            )->first();
            if(!$people){
                return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
            }
            $professors = Professor::with(WithUtils::withProfessor())->where(
                'person_id', '=', $people->id
            )->get();
        }else{
            $professors = Professor::with(WithUtils::withProfessor())->get();
        }
        if(count($professors) == 0){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }
        return response()->json(['value' => true,'data' => $professors,'message' => "Success"]);
    }
}
