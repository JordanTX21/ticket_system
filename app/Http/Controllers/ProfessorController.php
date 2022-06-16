<?php

namespace App\Http\Controllers;

use App\Professor;
use App\Menu;
use App\Person;
use App\Ticket;
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

    public function search2(Request $request)
    {
        $document = $request->document;
        if(strlen($document)!=8){
            return response()->json(['value' => false,'message' => "Debe ingresar 8 caracteres"]);
        }
        if($document){
            $people = Person::where(
                'document', '=', $document
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

    public function searchReception(Request $request){

        $document = $request->document;
        if(strlen($document)!=8){
            return response()->json(['value' => false,'message' => "Debe ingresar 8 caracteres"]);
        }
        $people = Person::where(
            'document', '=', $document
        )->first();
        if(!$people){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }
        $professor = Professor::with(WithUtils::withProfessor())->where(
            'person_id', '=', $people->id
        )->first();

        if(!$professor){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }

        date_default_timezone_set('America/Lima');
        $data = [
            "name" => $professor->person->name." ".$professor->person->last_name,
            "file" => "",
            "quantity" => 0,
            "status" => 0,
        ];

        $menus = Menu::with(WithUtils::withMenu())->where([
            ["reservation_date", "=", date("Y-m-d")],
            ['status','=',1],
        ])->get();
        $tickets = [];
        foreach($menus as $key => $menu){
            $ticket = Ticket::with(WithUtils::withTicket())->where([
                ["professor_id", "=", $professor->id],
                ['menu_id','=',$menu->id],
                ["status","=",1],
            ])->first();
            $tickets[] = $ticket;
        }

        if(count($tickets) == 0){
            return response()->json(['value' => true,'data' => $data,'message' => "No se encontró ticket"]);
        }
        $tickets_end = [];
        foreach($tickets as $key => $value){
            $tickets_end[] = [
                "status" => $value->consumed?2:1,
                "quantity" => $value->id,
                "type_menu_id" => $value->menu->type_menu_id,
                "type_menu" => $value->menu->type_menu->name,
                "ticket_id" => $value->id,
            ];
        }

        $data["tickets"] = $tickets_end;

        return response()->json(['value' => true,'data' => $data,'message' => "Success"]);
    }
}
