<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Ticket;
use Illuminate\Http\Request;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Utils\WithUtils;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.report');
    }

    public function exportExcel($date_start,$date_end){

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
            return null;
        }

        $data = [
            ["Fecha", "Menu", "Documento", "Cliente", "Cantidad"]
        ];

        foreach($tickets_all as $ticket){
            $ticket = json_decode(json_encode($ticket),true);
            $data[] = [
                $ticket["menu"]["reservation_date"],
                $ticket["menu"]["type_menu"]["name"],
                $ticket["professor"]?($ticket["professor"]["person"]["document"]):
                    ($ticket["student"]["code"]),
                $ticket["professor"]?($ticket["professor"]["person"]["name"]." ".$ticket["professor"]["person"]["last_name"]):
                    ($ticket["student"]["person"]["name"]." ".$ticket["student"]["person"]["last_name"]),
                $ticket["quantity"]
            ];
        }

        return Excel::download(new ArrayExport($data), 'report.xlsx');


    }
}
