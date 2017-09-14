<?php

namespace App\Repositories;

use DB;
use App\Models\Sale;
use Illuminate\Config;
use Entrust;
use Carbon\Carbon;
use App\Models\Building;

class SalesRepository
{
    protected $saleModel;

    public function __construct(Sale $saleModel)
    {
        $this->saleModel = $saleModel;
    }

    public function getChartData( $month = 0 )
    {
        $sales = Sale::with(['order'])
                        ->where('status_id','invoiced')
                        ->whereMonth('updated_at','=',$month)
                        ->orderBy('updated_at','asc')
                        ->get();
        $data['daily'] = $this->_arrangeSales( $sales ,false);
        $data['cumulative'] = $this->_arrangeSales( $sales, true );
        return $data;
        // $data = [];
        // $limit = (int)$now->diff($last)->format("%a");
        // $sales = Sale::with(['order'])
        //                 ->whereBetween('created_at',[$last,$now])
        //                 ->where('status_id','invoiced')
        //                 ->orderBy('order_id','asc')
        //                 ->get();
        // $last = $last->format('Y-m-d');

        // for ($i=0; $i <= $limit ; $i++) { 
        //     $data[$i] = $this->_getSaleDateRecords( $sales, Carbon::parse($last)->addDays($i) ); 
        // }
        // if( count( $data ) ){
            // $resp = $this->_setDataForChart($data);
        // }
        
    }

    protected function _getSaleDateRecords( $records, $date )
    {
        $response = [];
        if( isset( $records ) && $records->count()){
            foreach ($records as $value) {
                # code...
                if( !$value->created_at->diffInDays($date) ){
                    $response[] = $value;
                }
            }
            
        }
        return $response;
          
    }

    protected function _arrangeSales( $sales, $condition = false )
    {   
        if( $sales->count() ){
                $data= [];
                foreach ($sales as  $value) {
                    # code...
                    $index = (int)date('d',strtotime($value->updated_at));
                    $data[$index][] = $value->order->total_amount ? $value->order->total_amount : 0.00;
                    
                }
                $data = collect($data)->map(function( $q ){
                                return array_sum($q);
                        });
                if( $condition ){
                    $new = [];
                    foreach ($data as $key => $value) {
                        # code...
                        $new[$key] = $this->_cumulativeAdd( $data, $key);
                    }
                    $data = collect($new);
                    
                }
            return $data;
        }
        return false;

    }

    protected function _cumulativeAdd( $parent,$pKey ) 
    {
        $data = 00.00;
        foreach ($parent as $key => $value) {
            $data = $data + (float)$value;
            if( $key == $pKey ){
                break;
            }

        }
        return $data;
    }


    public function getProductionChartData($month)
    {
        $building = Building::with('last_status')
                    ->join('building_history',function($join) use($month){
                        $join->on('buildings.status_id','=','building_history.status_id')
                            ->where('building_history.status_id','=',4)
                            ->whereMonth('building_history.updated_at','=',$month);
                    })
                    ->get();
        
        dd($building);
    }

}