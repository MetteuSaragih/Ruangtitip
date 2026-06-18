<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountManagementController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'customers');
        $search = trim((string) $request->query('search', ''));

        $usersQuery = User::query()
            ->where(function ($query) {
                $query->where('role', '!=', 'admin')->orWhereNull('role');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $customers = $usersQuery->get();
        $staff = User::query()->where('role', 'admin')->latest()->get();

        $orderCounts = PackingOrder::query()
            ->selectRaw('user_id, COUNT(*) as total_orders')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->pluck('total_orders', 'user_id');

        $customerScope = fn ($query) => $query->where(function ($inner) {
            $inner->where('role', '!=', 'admin')->orWhereNull('role');
        });

        $totalCustomers = User::where($customerScope)->count();
        $activeCustomers = User::where($customerScope)->where('is_active', true)->count();
        $suspendedCustomers = User::where($customerScope)->where('is_active', false)->count();

        return view('admin.accounts', [
            'tab' => $tab,
            'search' => $search,
            'customers' => $customers,
            'staff' => $staff,
            'orderCounts' => $orderCounts,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'suspendedCustomers' => $suspendedCustomers,
        ]);
    }

    public function toggleActive(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dinonaktifkan.');
        }

        $user->forceFill([
            'is_active' => ! $user->is_active,
        ])->save();

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }
}
