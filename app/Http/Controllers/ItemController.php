<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = DB::table('items')->get(); //Get All Data From Database

        return view('index', ['items'  =>  $items]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate (
            [
                'sku'           => 'required|size:5|unique:items,sku',
                'name'          => 'required|min:1|max:70',
                'description'   => 'required|min:1|max:100',
                'price'         => 'required|integer',
            ]
        );

        $result = DB::table('items')->insert(
            [
            'sku'           => $request->sku,
            'name'          => $request->name,
            'description'   => $request->description,
            'price'         => $request->price,
            'created_at'    => now(),
            'updated_at'    => now(),
            ]
        );

        return redirect()->route('index')->with('message', "{$validateData['name']}");
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($item)
    {
        // get database with id search
        $result = DB::table('items')->where('id', $item)->get();

        return view('show', ['item'  =>  $result[0]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('edit', ['item' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $validateData = $request->validate (
            [
                'sku'           => 'required|size:5|unique:items,sku,'.$item->id,
                'name'          => 'required|min:1|max:70',
                'description'   => 'required|min:1|max:100',
                'price'         => 'required|integer',
            ]
        );

        $result = DB::table('items')->where('id', $item->id)->update(
                    [
                        'sku'           => $request->sku,
                        'name'          => $request->name,
                        'description'   => $request->description,
                        'price'         => $request->price,
                        'updated_at' => now(),
                    ]
                );

        return redirect()->route('show', ['item' => $item->id])->with('message', "{$validateData['name']}");
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(item $item)
    {
        $result = DB::table('items')->where('id', $item->id)->delete();

        return redirect()->route('index')->with('message', "Delete data $item->name Success");
    }
}
