<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Http\Resources\AuthResource;
use App\Http\Requests\AuthRequest;
use App\Enums\UserRoleEnum;

use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN) {
            return AuthResource::collection(
                User::where('id','=', Auth::id())
                ->get()
            );
        }

        return AuthResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // logout
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
    public function show(User $user)
    {
        if (($user->id !== Auth::id()) and (Auth::user()->role !== UserRoleEnum::ADMIN)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return new AuthResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthRequest $request, User $user)
    {
        if (($user->id !== Auth::id()) and (Auth::user()->role !== UserRoleEnum::ADMIN)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $user->update($request->validated());

        return new AuthResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN){
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $user->delete();

        return response(null, Response::HTTP_NOT_FOUND);
    }
}
