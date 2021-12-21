<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user
        ];
        $role = Auth::user()->user_role;
        return view("dashboard", $data);
    }

    public function admin()
    {
        $user = Auth::user();
        $total_user = User::select(DB::raw('COUNT(*) as total'))
            ->where('user_role','!=','admin')
            ->first();
        $total_items = Item::select(DB::raw('COUNT(*) as total'))->first();
        $total_open = Inquiry::select(DB::raw('COUNT(inquiries.id) as total'))
            ->leftJoin('quotations','quotations.inquiry_id','inquiries.id')
            ->whereNull('quotations.id')
            ->first();

        $total_quotation= Quotation::select(DB::raw('COUNT(*) as total'))->first();

        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
            'total_items'   => $total_items,
            'total_open'    => $total_open,
            'total_quotation' => $total_quotation
        ];
        return view("admin.dashboard", $data);
    }
    public function manager()
    {
        $user = Auth::user();
        $total_items = User::select(DB::raw('COUNT(*) as total'))->first();
        $total_inquiries = Inquiry::select(DB::raw('COUNT(*) as total'))->first();
        $total_quotations = Quotation::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_items'   => $total_items,
            'total_quotations' => $total_quotations,
            'total_inquiries' => $total_inquiries
        ];
        return view("manager.dashboard", $data);
    }
    public function sale()
    {
        $user = Auth::user();
        $total_inquiries = Inquiry::select(DB::raw('COUNT(*) as total'))->where('user_id',$user->id)->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_inquiries'    => $total_inquiries,
        ];
        return view("sale.dashboard", $data);
    }
    public function team()
    {
        $user = Auth::user();
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
        ];
        return view("team.dashboard", $data);
    }
}
