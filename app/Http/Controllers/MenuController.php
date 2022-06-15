<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.menu');
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
        $type_menu_id = $request->type_menu_id;
        $reservation_date = $request->reservation_date;
        $tickets_stock = $request->tickets_stock;

        if (!$type_menu_id) {
            return response()->json(['value' => false, 'message' => "Ingrese un tipo de menú"]);
        }

        if (!$reservation_date) {
            return response()->json(['value' => false, 'message' => "Ingrese una fecha de reserva"]);
        }

        if (!$tickets_stock) {
            return response()->json(['value' => false, 'message' => "Ingrese un stock de tickets"]);
        }

        try {
            $menu = Menu::create([
                'type_menu_id' => $type_menu_id,
                'reservation_date' => $reservation_date,
                'tickets_stock' => $tickets_stock,
            ]);
            if (!$menu) {
                return response()->json(['value' => false, 'message' => "Error"]);
            }
        } catch (\Exception $e) {
            return response()->json(['value' => false, 'message' => "El menú ya existe"]);
        }
        return response()->json(['value' => true, 'message' => "Menú Creado"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }

    public function createMenuByWeek(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $data = $request->data;

        if (!$date_start) {
            return response()->json(['value' => false, 'message' => "Ingrese una fecha de inicio"]);
        }
        if (!$date_end) {
            return response()->json(['value' => false, 'message' => "Ingrese una fecha de fin"]);
        }

        $data_final = [];

        foreach ($data as $key => $value) {
            foreach ($value as $keyx => $valuex){
                $type_menu_id = $valuex['type_menu_id'];
                $reservation_date = $valuex['reservation_date'];
                $tickets_stock = $valuex['tickets_stock'];
    
                if (!$type_menu_id) {
                    return response()->json(['value' => false, 'message' => "Ingrese un tipo de menú para el día $reservation_date"]);
                }
    
                if (!$reservation_date) {
                    return response()->json(['value' => false, 'message' => "Ingrese una fecha de reserva para el día $reservation_date"]);
                }
    
                if ($tickets_stock) {
                    $data_final[] = $valuex;
                }
            }
        }

        if (count($data_final) == 0) {
            return response()->json(['value' => false, 'message' => "Ingrese al menos un menú"]);
        }

        foreach ($data_final as $key => $value) {
            $type_menu_id = $value['type_menu_id'];
            $reservation_date = $value['reservation_date'];
            $tickets_stock = $value['tickets_stock'];

            try {
                $menu = Menu::create([
                    'type_menu_id' => $type_menu_id,
                    'reservation_date' => $reservation_date,
                    'tickets_stock' => $tickets_stock,
                ]);
                if (!$menu) {
                    return response()->json(['value' => false, 'message' => "Error"]);
                }
            } catch (\Exception $e) {
                return response()->json(['value' => false, 'message' => "Error"]);
            }

        }
        return response()->json(['value' => true, 'message' => "Registrado con éxito"]);
    }
}
