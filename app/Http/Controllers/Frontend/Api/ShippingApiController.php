<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Services\GHTK;
use Illuminate\Http\Request;
use Validator;

class ShippingApiController extends Controller
{
    private $ghtk;

    public function __construct()
    {
        $this->ghtk = new GHTK();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShipmentFee(Request $request)
    {
        $rules = [
            'pick_province' => 'required',
            'pick_district' => 'required',
            'province' => 'required',
            'district' => 'required',
            'weight' => 'required|int',
            'price' => 'int',
            'transport' => 'required',
            'xfast' => 'required|boolean',
        ];
        $validator = Validator::make($request->all(), $rules);
        $errors = $validator->errors();
        if (count($errors) > 0) {
            return response($errors, 400);
        }
        $pickProvince = $request->input('pick_province');
        $pickDistrict = $request->input('pick_district');
        $province = $request->input('province');
        $district = $request->input('district');
        $weight = $request->input('weight');
        $price = $request->input('price');
        $transport = $request->input('transport');
        $xfast = $request->input('xfast');
        return $this->ghtk->getShipmentFee(
            $pickProvince,
            $pickDistrict,
            $province,
            $district,
            $weight,
            $price,
            $transport,
            $xfast
        );
    }
}
