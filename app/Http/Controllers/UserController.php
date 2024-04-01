<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class UserController extends Controller {

    public function register (Request $request) {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validated == false) return response()->json(['status' => false], 200);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' => true, 'user' => $user], 200);
    }

    function update(Request $request, $id) {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validated == false) return response()->json(['status' => false], 200);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' => true, 'user' => $user], 200);
    }
}
