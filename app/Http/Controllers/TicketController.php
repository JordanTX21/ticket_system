<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Ticket;
use Illuminate\Http\Request;
use App\Utils\WithUtils;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.ticket');
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
        $student_id = $request->student_id;
        $professor_id = $request->professor_id;
        $type_menu_id = $request->type_menu_id;
        $reservation_date = $request->reservation_date;
        $quantity = $request->quantity;

        if (!$student_id && !$professor_id) {
            return response()->json(['value' => false, 'message' => "Busque a un estudiante o docente"]);
        }
        if (!$type_menu_id) {
            return response()->json(['value' => false, 'message' => "Ingrese un tipo de menu"]);
        }
        if (!$reservation_date) {
            return response()->json(['value' => false, 'message' => "Seleccione una fecha del menu"]);
        }
        if($professor_id && !$quantity){
            return response()->json(['value' => false, 'message' => "Ingrese una cantidad para del menú"]);
        }
        if($student_id){
            $quantity = 1;
        }
        $menu = Menu::with(WithUtils::withMenu())->where([
            ['status','=',1],
            ['type_menu_id','=',$type_menu_id],
        ])
            ->whereBetween('reservation_date',[$reservation_date." 00:00:00",$reservation_date." 23:59:59"])
            ->first();

        if (!$menu) {
            return response()->json(['value' => false, 'message' => "No hay menu disponible"]);
        }
        if ($menu->tickets_stock == 0) {
            return response()->json(['value' => false, 'message' => "No hay stock de tickets"]);
        }

        $menu_id = $menu->id;

        $ticket = Ticket::where([
            ['student_id', '=',$student_id],
            ['professor_id', '=',$professor_id],
            ['menu_id', '=',$menu_id],
            ['status', '=',1],
        ])->first();
        if($ticket){
            return response()->json(['value' => false, 'message' => "Ya hay un ticket generado para este cliente y dia"]);
        }

        try {
            $ticket_new = Ticket::create([
                'student_id' => $student_id,
                'professor_id' => $professor_id,
                'menu_id' => $menu_id,
                'quantity' => $quantity,
                'consumed' => 0,
            ]);
            if (!$ticket_new) {
                return response()->json(['value' => false, 'message' => "Error"]);
            }
        } catch (\Exception $e) {
            return response()->json(['value' => false, 'message' => $e->getMessage()]);
        }

        $menu_new = Menu::where('id',$menu_id)
            ->update([
                "tickets_stock"=> (intval($menu->tickets_stock) - intval($quantity))
            ]);

        return response()->json(['value' => true, 'message' => "Ticket Creado"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ticket)
    {
        $student_id = $request->student_id;
        $professor_id = $request->professor_id;
        $type_menu_id = $request->type_menu_id;
        $reservation_date = $request->reservation_date;
        $quantity = $request->quantity;

        if (!$student_id && !$professor_id) {
            return response()->json(['value' => false, 'message' => "Busque a un estudiante o docente"]);
        }
        if (!$type_menu_id) {
            return response()->json(['value' => false, 'message' => "Ingrese un tipo de menu"]);
        }
        if (!$reservation_date) {
            return response()->json(['value' => false, 'message' => "Seleccione una fecha del menu"]);
        }
        if($professor_id && !$quantity){
            return response()->json(['value' => false, 'message' => "Ingrese una cantidad para del menú"]);
        }
        if($student_id){
            $quantity = 1;
        }
        $menu = Menu::with(WithUtils::withMenu())->where([
            ['status','=',1],
            ['type_menu_id','=',$type_menu_id],
        ])
            ->whereBetween('reservation_date',[$reservation_date." 00:00:00",$reservation_date." 23:59:59"])
            ->first();

        if (!$menu) {
            return response()->json(['value' => false, 'message' => "No hay menu disponible"]);
        }
        if ($menu->tickets_stock == 0) {
            return response()->json(['value' => false, 'message' => "No hay stock de tickets"]);
        }

        $menu_id = $menu->id;

        try {
            $ticket_new = Ticket::where('id',$ticket)->update([
                'student_id' => $student_id,
                'professor_id' => $professor_id,
                'menu_id' => $menu_id,
                'quantity' => $quantity,
                'consumed' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['value' => false, 'message' => $e->getMessage()]);
        }
        return response()->json(['value' => true, 'message' => "Ticket Creado"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy($ticket)
    {
        $delete = Ticket::where('id',$ticket)->update([
            'status' => 0,
        ]);
        if(!$delete){
            return response()->json(['value' => false, 'message' => "No se pudo eliminar"]);
        }
        return response()->json(['value' => true, 'message' => "Ticket Eliminado"]);
    }


    public function search(Request $request)
    {
        $ticket = Ticket::with(WithUtils::withTicket())->where('status',1)->get();
        if(count($ticket) ==0){
            return response()->json(['value' =>false,"message"=>"No se encontraron resultados"]);
        }

        return response()->json(['value' =>true,"data"=>$ticket,"message"=>"Tickets encontrados"]);
    }
    public function search2(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        if($date_start>$date_end){
            return response()->json(['value' =>false,"message"=>"La fecha fin debe ser mayor o igual que la fecha inicial"]);
        }

        $menu = Menu::with(WithUtils::withMenu())->where('status',1)
            ->whereBetween('reservation_date',[$date_start." 00:00:00",$date_end." 23:59:59"])
            ->get();
        $tickets_all = [];
        foreach($menu as $item){

            $tickets = Ticket::with(WithUtils::withTicket())->where([
                ["menu_id", "=",$item->id]
            ])->get();

            foreach ($tickets as $itemx){
                $tickets_all[] = $itemx;
            }

        }

        if(count($tickets_all) ==0){
            return response()->json(['value' =>false,"message"=>"No se encontraron resultados"]);
        }

        return response()->json(['value' =>true,"data"=>$tickets_all,"message"=>"Tickets encontrados"]);
    }
}
