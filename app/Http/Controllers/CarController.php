<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;

class CarController extends Controller
{
    function index()
    {
        $car = Car::all();

        if(!$car)
        {
            return response()->json([
                'code' => 'error',
                'status' => 404,
                'message' => 'El coche que busca no existe'
            ], 404);
        }

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Coches encontrados',
            'cars' => $car
        ], 201);
    }

    public function store(Request $request)
    {
        $car = new Car($request->all());

        if(!$car)
        {
            return response()->json([
                'code' => 'error',
                'status' => 404,
                'message' => 'El coche que busca no existe'
            ], 404);
        }

        $car->user_id = $request->user()->id;
        $car->name = $request->name;
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->image = $request->image;
        $car->description = $request->description;
        $car->price = $request->price;
        $car->status = 'comprado';

        $car->save();

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Coche creado de forma correcta',
            'car' => $car
        ], 201);
    }

    public function show(Request $request)
    {
        $car = Car::query()->find($request->id);

        if(!$car)
        {
            return response()->json([
                'code' => 'error',
                'status' => 404,
                'message' => 'El coche que busca no existe'
            ], 404);
        }

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Coches encontrado correctamente',
            'car' => $car
        ], 201);
    }

    public function update(Request $request)
    {
        $car = Car::query()->find($request->id);

        if(!$car)
        {
            return response()->json([
                'code' => 'error',
                'status' => 404,
                'message' => 'El coche que busca no existe'
            ], 404);
        }

        $car->user_id = $request->user()->id;
        $car->name = $request->name;
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->image = $request->image;
        $car->description = $request->description;
        $car->price = $request->price;
        $car->status = 'comprado';

        $car->save();

        return response()->json([
        'code' => 'success',
        'status' => 201,
        'message' => 'Coches actualizado correctamente',
        'car' => $car
    ], 201);
    }

    public function destroy(Request $request)
    {
        $car = Car::destroy($request->id);

        if(!$car)
        {
            return response()->json([
                'code' => 'error',
                'status' => 404,
                'message' => 'El coche que busca no existe'
            ], 404);
        }

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Coches borrado correctamente',
            'car' => $car
        ], 201);

    }
}
