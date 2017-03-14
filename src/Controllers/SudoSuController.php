<?php

namespace VIACreative\SudoSu\Controllers;

use Illuminate\Http\Request;
use VIACreative\SudoSu\SudoSu;
use Illuminate\Routing\Controller;

class SudoSuController extends Controller
{
    protected $sudoSu;

    public function __construct(SudoSu $sudoSu)
    {
        $this->sudoSu = $sudoSu;
    }

    public function loginAsUser(Request $request)
    {
        $this->sudoSu->loginAsUser($request->userId, $request->originalUserId);

        return redirect()->back();
    }

    public function return(Request $request)
    {
        $this->sudoSu->return();

        return redirect()->back();
    }
}