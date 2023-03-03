<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Code;
use App\Client;
use App\HistoryCodes;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $code = Code::orderBy('id','desc')->first();
        if($code == null)
            $codeId = 1;
        else{
            $codeId = $code->id + 1;
        }
        $histories =  HistoryCodes::whereHas('clinets', function ($query) {
            return $query->orderBy('id','desc');
        })->get();
        // $histories = HistoryCodes::orderBy('id','desc')->get();
        // $histories = $histories->with('clinets');

        return view('backend.codes.create', compact('clients','codeId','histories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            $first = $request->first;
            $qty = $request->qty;
            $last = $first + $qty - 1;
            $client_id = $request->client_id;

            for($id = $first ; $id <= $last; $id++){
                $code = new Code();
                $code->client_id = $client_id;
                $code->save();
            }

            $code = Code::orderBy('id','desc')->first();
            if($code == null)
                $codeId = 1;
            else{
                $codeId = $code->id + 1;
            }

            $history = new HistoryCodes();
            $history->client_id = $client_id;
            $history->first = $first;
            $history->last = $last;
            $history->qty = $qty;
            $history->save();
            $clients = Client::all();
            $histories = HistoryCodes::orderBy('id','desc')->get();
            $histories = $histories->with('clinet');
            return view('backend.codes.create', compact('clients','codeId','histories'));

        }catch (\Exception $e) {
            DB::rollback();
            print_r($e->getMessage());
            exit;

            flash(translate("Error"))->error();
            return back();
        }
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
