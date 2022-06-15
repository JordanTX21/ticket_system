<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::all();
        return view('modules.person', compact('people'));
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
        $name = $request->name;
        $last_name = $request->last_name;
        $sur_name = $request->sur_name;
        $document = $request->document;

        if(!$document){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        if(strlen($document) != 8){
            return response()->json(['value' => false,'message' => "El documento debe tener 8 dígitos"]);
        }

        if(!$name){
            return response()->json(['value' => false,'message' => "Ingrese un nombre"]);
        }

        if(!$last_name){
            return response()->json(['value' => false,'message' => "Ingrese un apellido paterno"]);
        }

        if(!$sur_name){
            return response()->json(['value' => false,'message' => "Ingrese un apellido materno"]);
        }

        try{
            $person = Person::create([
                'name' => $name,
                'last_name' => $last_name,
                'sur_name' => $sur_name,
                'document' => $document,
            ]);
            if(!$person){
                return response()->json(['value' => false,'message' => "Error"]);
            }
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => "El documento ya existe"]);
        }
        return response()->json(['value' => true,'message' => "Persona Creada"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show($person)
    {
        $person_get = Person::findOrFail($person);
        return response()->json(['value' => true,'data' => $person_get,'message' => "Success"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $person)
    {
        $name = $request->name;
        $last_name = $request->last_name;
        $sur_name = $request->sur_name;
        $document = $request->document;
        $status = $request->status;

        if(!$name){
            return response()->json(['value' => false,'message' => "Ingrese un nombre"]);
        }

        if(!$last_name){
            return response()->json(['value' => false,'message' => "Ingrese un apellido paterno"]);
        }

        if(!$sur_name){
            return response()->json(['value' => false,'message' => "Ingrese un apellido materno"]);
        }

        if(!$document){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        try{
            $person_new = Person::where('id',$person)->update([
                'name' => $name,
                'last_name' => $last_name,
                'sur_name' => $sur_name,
                'document' => $document,
                'status' => $status == 1 ? true : false,
            ]);
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => $e->getMessage()]);
        }
        
        return response()->json(['value' => true,'message' => "Persona Actualizada"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy($person)
    {
        $person_destroyed = Person::where('id',$person)->update(['status' => false]);
        if(!$person_destroyed){
            return response()->json(['value' => false,'message' => "Error"]);
        }
        return response()->json(['value' => true,'message' => "Persona Eliminada"]);
    }

    public function search(Request $request)
    {
        $document = $request->document;
        $people = Person::where(
            'document', 'like', '%'.$document.'%'
        )->get();
        if(count($people) == 0){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }
        return response()->json(['value' => true,'data' => $people,'message' => "Success"]);
    }
}
