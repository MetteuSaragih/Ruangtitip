<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrelovedSubmission;
use App\Models\TitipanOrder;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AccountManagementController extends Controller
{
    private function customers()
    {
        return User::query()->where(function ($q) {
            $q->where('role', '!=', 'admin')->orWhereNull('role');
        });
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab');
        if (! in_array($tab, ['semua', 'aktif', 'diblokir'], true)) {
            $tab = 'semua';
        }
        $search = trim((string) $request->query('search', ''));

        $query = $this->customers()
            ->when($tab === 'aktif', fn ($q) => $q->where('is_active', true))
            ->when($tab === 'diblokir', fn ($q) => $q->where('is_active', false))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $customers = $query->paginate(15)->withQueryString();

        $activeOrders = TitipanOrder::query()
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereIn('user_id', $customers->pluck('id'))
            ->where('status', '!=', 'selesai')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        $counts = [
            'semua' => $this->customers()->count(),
            'aktif' => $this->customers()->where('is_active', true)->count(),
            'diblokir' => $this->customers()->where('is_active', false)->count(),
        ];

        return view('admin.accounts', [
            'tab' => $tab,
            'search' => $search,
            'customers' => $customers,
            'activeOrders' => $activeOrders,
            'counts' => $counts,
        ]);
    }

    public function show(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $titipan = TitipanOrder::with('storage')->where('user_id', $user->id)->latest()->get();

        $hasUserId = Schema::hasColumn('orders', 'user_id');
        $purchases = Order::query()
            ->where(function ($q) use ($user, $hasUserId) {
                $q->where('customer_email', $user->email);
                if ($hasUserId) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->latest()
            ->limit(20)
            ->get();

        $submissions = collect();
        if (Schema::hasTable('preloved_submissions')) {
            try {
                $submissions = PrelovedSubmission::where('user_id', $user->id)->latest()->limit(20)->get();
            } catch (\Throwable $e) {
                $submissions = collect();
            }
        }

        $addresses = collect();
        if (Schema::hasTable('user_addresses')) {
            $addresses = UserAddress::where('user_id', $user->id)->orderByDesc('is_primary')->get();
        }

        return view('admin.accounts-show', [
            'customer' => $user,
            'titipan' => $titipan,
            'purchases' => $purchases,
            'submissions' => $submissions,
            'addresses' => $addresses,
        ]);
    }

    public function toggleActive(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dinonaktifkan.');
        }
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin dikelola dari halaman Tim admin.');
        }

        $user->forceFill([
            'is_active' => ! $user->is_active,
        ])->save();

        return back()->with('success', $user->is_active ? 'Blokir akun dibuka.' : 'Akun diblokir.');
    }
}
