<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Menu;
use App\Student;
use Illuminate\Http\Request;
use App\Utils\WithUtils;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.student');
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
        $code = $request->code;
        $cycle = $request->cycle;
        $career = $request->career;

        if(!$person_id){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        if(!$code){
            return response()->json(['value' => false,'message' => "Ingrese un codigo"]);
        }

        if(strlen($code) != 10){
            return response()->json(['value' => false,'message' => "El código debe tener 10 dígitos"]);
        }

        if(!$cycle){
            return response()->json(['value' => false,'message' => "Ingrese un ciclo"]);
        }

        if(!$career){
            return response()->json(['value' => false,'message' => "Ingrese una carrera"]);
        }

        try{
            $student = Student::create([
                'person_id' => $person_id,
                'code' => $code,
                'cycle' => $cycle,
                'career' => $career,
            ]);
            if(!$student){
                return response()->json(['value' => false,'message' => "Error"]);
            }
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => "El codigo de alumno ya existe"]);
        }
        return response()->json(['value' => true,'message' => "Estudiante Creado"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $student)
    {
        $person_id = $request->person_id;
        $code = $request->code;
        $cycle = $request->cycle;
        $career = $request->career;

        if(!$person_id){
            return response()->json(['value' => false,'message' => "Ingrese un documento"]);
        }

        if(!$code){
            return response()->json(['value' => false,'message' => "Ingrese un codigo"]);
        }

        if(strlen($code) != 10){
            return response()->json(['value' => false,'message' => "El código debe tener 10 dígitos"]);
        }

        if(!$cycle){
            return response()->json(['value' => false,'message' => "Ingrese un ciclo"]);
        }

        if(!$career){
            return response()->json(['value' => false,'message' => "Ingrese una carrera"]);
        }

        try{
            $student = Student::where('id',$student)->update([
                'person_id' => $person_id,
                'code' => $code,
                'cycle' => $cycle,
                'career' => $career,
            ]);
        }catch(\Exception $e){
            return response()->json(['value' => false,'message' => "El codigo de alumno ya existe"]);
        }
        return response()->json(['value' => true,'message' => "Estudiante Creado"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy( $student)
    {
        $student_destroyed = Student::where('id',$student)->update(['status' => false]);
        if(!$student_destroyed){
            return response()->json(['value' => false,'message' => "Error"]);
        }
        return response()->json(['value' => true,'message' => "Studiante Eliminado"]);
    }

    public function search(Request $request)
    {
        $code = $request->document;
        $students = Student::with(WithUtils::withStudent())->where(
            'code', 'like', "%$code%"
        )->get();
        if(count($students) == 0){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }
        return response()->json(['value' => true,'data' => $students,'message' => "Success"]);
    }

    public function search2(Request $request)
    {
        $code = $request->document;
        if(strlen($code)!=10){
            return response()->json(['value' => false,'message' => "Debe ingresar 10 caracteres"]);
        }
        $students = Student::with(WithUtils::withStudent())->where(
            'code', '=', trim($code)
        )->get();
        if(count($students) == 0){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }
        return response()->json(['value' => true,'data' => $students,'message' => "Success"]);
    }

    public function searchReception(Request $request){

        $code = $request->document;
        if(strlen($code)!=10){
            return response()->json(['value' => false,'message' => "Debe ingresar 10 caracteres"]);
        }
        $student = Student::with(WithUtils::withStudent())->where(
            'code', '=', trim($code)
        )->first();
        if(!$student){
            return response()->json(['value' => false,'message' => "No se encontraron resultados"]);
        }

        date_default_timezone_set('America/Lima');
        $data = [
            "name" => $student->person->name." ".$student->person->last_name,
            "file" => "",
            "quantity" => 0,
            "status" => 0,
            "date" => date("Y-m-d"),
        ];

        $menus = Menu::with(WithUtils::withMenu())->where([
            ["reservation_date", "=", date("Y-m-d")],
            ['status','=',1],
        ])->get();
        $tickets = [];
        foreach($menus as $key => $menu){
            $ticket = Ticket::with(WithUtils::withTicket())->where([
                ["student_id", "=", $student->id],
                ['menu_id','=',$menu->id],
                ["status","=",1],
            ])->first();
            if($ticket){
                $tickets[] = $ticket;
            }
        }

        if(count($tickets) == 0){
            return response()->json(['value' => true,'data' => $data,'message' => "No se encontró ticket"]);
        }

        $tickets_end = [];
        foreach($tickets as $key => $value){
            $menu = json_decode(json_encode($value->menu),true);
            $tickets_end[] = [
                "status" => $value->consumed?2:1,
                "quantity" => $value->id,
                "type_menu_id" => $value->menu->type_menu_id,
                "type_menu" => $menu["type_menu"]["name"],
                "ticket_id" => $value->id,
            ];
        }

        $data["tickets"] = $tickets_end;

        return response()->json(['value' => true,'data' => $data,'message' => "Success"]);
    }
}
