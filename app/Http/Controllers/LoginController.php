<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        Carbon::now('Asia/Jakarta');

        $currentPeriod = DB::table('periods as p')
            ->whereDate(DB::raw('CURRENT_DATE'), '>=', DB::raw('p.start_date'))
            ->whereDate(DB::raw('CURRENT_DATE'), '<=', DB::raw('LAST_DAY(p.start_date)'))
            ->first();
        $currentPeriodId = $currentPeriod ? $currentPeriod->id : null;

        if ($currentPeriod) {
            $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
            $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
            $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;
        }
        return view('auth.login', compact('currentPeriod'));
    }

    public function decryptPassword($data, $key)
    {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'country_code' => 'required|string',
            'phone_number' => 'required|numeric', 
            'password' => 'required',  
        ]);

        $phone_number = $credentials['country_code'] . $credentials['phone_number'];

        $user = User::where('phone_number', $phone_number)->first();

        if ($user) {
            $key = env('ENCRYPTION_KEY', '256');
            $decrypted_password = $this->decryptPassword($user->password, $key);

            if ($decrypted_password == $credentials['password']) {
                if ($user->roles_id == 1) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return redirect()->route('admin.dashboard');
                } elseif ($user->roles_id == 2) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return redirect()->route('participant.dashboard');
                } else {
                    Auth::logout();
                    return back()->with('loginError', 'Anda tidak memiliki akses untuk login.');
                }
            } else {
                return back()->with('loginError', 'Password salah.');
            }
        } else {
            return back()->with('loginError', 'Nomor HP tidak ditemukan.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
