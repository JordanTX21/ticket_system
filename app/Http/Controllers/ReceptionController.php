<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Ticket;
use Illuminate\Http\Request;
use App\Utils\WithUtils;

class ReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.reception');
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
        $ticket_id = $request->ticket_id;

        if(!$ticket_id){
            return response()->json(['value' => false,'message' => "El cliente no tiene un ticket registrado o ticket pendiente"]);
        }

        $ticket = Ticket::where('id',$ticket_id)->first();

        if(!$ticket){
            return response()->json(['value' => false,'message' => "Ocurrió un error al validar el ticket"]);
        }

        if($ticket->consumed == 1){
            return response()->json(['value' => false,'message' => "El cliente no tiene un ticket registrado o ticket pendiente"]);
        }

        if($ticket->status == 0){
            return response()->json(['value' => false,'message' => "Este ticket fue eliminado"]);
        }

        $ticket_new = Ticket::where('id',$ticket_id)
            ->update([
                "consumed" => 1
            ]);

        if(!$ticket_new){
            return response()->json(['value' => false,'message' => "Ocurrió un error al entregar el ticket"]);
        }


        return response()->json(['value' => true,'message' => "Ticket entregado con éxito"]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
