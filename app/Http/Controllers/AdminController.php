<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\AdminFee;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $transactions = Transfer::with('sender', 'receiver')->get();
        $admin_fee = AdminFee::first();

        return view('dashboard', compact('transactions', 'admin_fee'));
    }

    public function updateAdminFee(Request $request)
    {
        $request->validate([
            'admin_fee' => 'required|numeric|min:0.01',
        ]);

        $admin_fee = AdminFee::first();
        if ($admin_fee) {
            $admin_fee->update(['fee' => $request->admin_fee]);
        }

        AdminFee::create(['fee' => $request->admin_fee]);

        return redirect()->route('admin.dashboard')->with('success', 'Admin fee updated successfully');
    }
}
