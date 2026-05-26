<?php

namespace App\Http\Controllers;
use OwenIt\Auditing\Models\Audit;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
{
    $audits = Audit::latest()->paginate(10);
    return view('audits.index', compact('audits'));
}
}
