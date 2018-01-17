<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Address $address)
    {
        $addresses = $address->where('user_id', session('user_id'))->get();

        return view('index.address', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('index.address_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $address Address
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Address $address)
    {
        $address->fill($request->all());
        $address->user_id = session('user_id');
        $address->save();

        if($address->id) {
            session(['address' => $address->find($address->id)->toarray()]);
            //记录为默认地址
            User::where('id', session('user_id'))->update(['address_id' => $address->id]);

            //判断添加的地址是否属于偏远地区
            $province = strstr($request->province, ' ', true);
            if(in_array($province, config('system.postage_area'))){
                session(['extra_postage' => 10]);
            } else {
                session(['extra_postage' => 0]);
            }
            return response()->json(['state' => 0, 'error' => '添加地址完成', 'url' => route('index.order_ready_pay', session('order_id'))]);
        }
        return response()->json(['state' => 500, 'error' => '添加地址失败']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        return view('index.address_edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        if($address->update($request->all())) {
            return response()->json(['state' => 0, 'error' => '修改完成', 'url' => route('address.index')]);
        }
        return response()->json(['state' => 500, 'error' => '修改失败']);
    }

    /**
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Address $address)
    {
        if($address->delete()) {
            return response()->json(['state' => 0, 'error' => '删除完成', 'url' => route('address.index')]);
        }
        return response()->json(['state' => 500, 'error' => '删除失败']);
    }

    /**
     * 选择收货地址
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectAddress( Address $address )
    {
        session(['address' => $address->toarray()]);
        //记录为默认地址
        User::where('id', session('user_id'))->update(['address_id' => $address->id]);

        //判断添加的地址是否属于偏远地区
        $province = strstr($address->province, ' ', true);
        if(in_array($province, config('system.postage_area'))){
            session(['extra_postage' => 10]);
        } else {
            session(['extra_postage' => 0]);
        }

        return response()->json(['state' => 0, 'url' => route('index.order_ready_pay', session('order_id'))]);
    }
}
