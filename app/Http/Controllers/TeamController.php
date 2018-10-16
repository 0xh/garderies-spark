<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function letter(User $user)
    {
        $pdf = \PDF::loadView('team.letter', ['user' => $user]);
        return $pdf->stream();
    }
}
