<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\Bank;
use App\Models\User;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\PeriodParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id'); 
        Carbon::now('Asia/Jakarta');

        $currentPeriod = DB::table('periods as p')
            ->whereDate(DB::raw('CURRENT_DATE'), '>=', DB::raw('p.start_date'))
            ->whereDate(DB::raw('CURRENT_DATE'), '<=', DB::raw('p.end_regist_date'))
            ->first();

        $currentPeriodId = $currentPeriod ? $currentPeriod->id : null;

        $aktif = 'Tidak';
        $canRegister = false;

        if ($currentPeriod) {
            $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
            $endMonthYear = Carbon::parse($currentPeriod->end_regist_date)->isoFormat('MMMM YYYY');
            $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;

            $aktif = 'Ya';

            $totalParticipant = PeriodParticipant::where('periods_id', $currentPeriodId)->count();

            if ($totalParticipant < 100) {
                $canRegister = true;
            }

            $bank = Bank::all();

            return view('auth.register', compact('bank', 'currentPeriod', 'aktif', 'totalParticipant', 'canRegister'));
        }

        return view('auth.register', compact('aktif'));
    }


    public function store(Request $request)
    {
        $phone_number = $request->country_code . $request->phone_number;


        $userHkid = User::where('hkid', $request->hkid)->first();
        $userPhone = User::where('phone_number', $phone_number)->first();

        if ($userHkid && $userPhone) {
            $currentPeriod = DB::table('periods as p')
                ->whereDate(DB::raw('CURRENT_DATE'), '>=', DB::raw('p.start_date'))
                ->whereDate(DB::raw('CURRENT_DATE'), '<=', DB::raw('p.end_regist_date'))
                ->first();
            $currentPeriodId = $currentPeriod ? $currentPeriod->id : null;

            if ($currentPeriod) {
                $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
                $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
                $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;
            }

            $periodParticipant = PeriodParticipant::where('users_id', $userHkid->id)
                ->orderBy('periods_id', 'desc')
                ->first();

            $userPeriodId  = $periodParticipant->periods_id;
            $period = Period::find($userPeriodId);

            $currentDate = Carbon::now();

            if ($currentDate->gt($period->end_date)) {
                PeriodParticipant::create([
                    'users_id' => $userHkid->id,
                    'periods_id' => $currentPeriodId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return redirect()->route('login')->with('message', 'Pendaftaran berhasil');
            } else {
                $periods = 'Masa periode';
                return view('auth.login', compact('periods', 'currentPeriod'));
            }
        } elseif ($userHkid && !$userPhone) {
            $currentPeriod = DB::table('periods as p')
                ->whereDate(DB::raw('CURRENT_DATE'), '>=', DB::raw('p.start_date'))
                ->whereDate(DB::raw('CURRENT_DATE'), '<=', DB::raw('p.end_regist_date'))
                ->first();
            $currentPeriodId = $currentPeriod ? $currentPeriod->id : null;

            if ($currentPeriod) {
                $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
                $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
                $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;
            }

            $HKIDorPhoneReady = 'Ya';
            return view('auth.login', compact('HKIDorPhoneReady', 'currentPeriod'));
        } elseif (!$userHkid && $userPhone) {
            $currentPeriod = DB::table('periods as p')
                ->whereDate(DB::raw('CURRENT_DATE'), '>=', DB::raw('p.start_date'))
                ->whereDate(DB::raw('CURRENT_DATE'), '<=', DB::raw('p.end_regist_date'))
                ->first();
            $currentPeriodId = $currentPeriod ? $currentPeriod->id : null;

            if ($currentPeriod) {
                $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
                $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
                $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;
            }

            $HKIDorPhoneReady = 'Ya';
            return view('auth.login', compact('HKIDorPhoneReady', 'currentPeriod'));
        } else {
            $request->validate([
                'hkid' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'file_bank_book' => 'required|nullable|mimes:jpg,jpeg,png,pdf|max:2048',
                'bank_account_number' => 'required|nullable|string|max:255',
                'password' => 'required|string',
                'file_hkid' => 'required|nullable|mimes:jpg,jpeg,png,pdf|max:2048',
                'country_code' => 'required|string',
                'phone_number' => 'required|numeric',
                'banks_id' => 'required',
                'bank_holder_name' => 'required|nullable|string|max:255',
            ]);

            $phone_number = $request->country_code . $request->phone_number;

            $data = [
                'hkid' => strtoupper($request->hkid),
                'name' => strtoupper($request->name),
                'bank_account_number' => $request->bank_account_number,
                'password' => $request->password,
                'phone_number' => $phone_number,
                'banks_id' => $request->banks_id,
                'bank_holder_name' => strtoupper($request->bank_holder_name),
            ];

            session(['register_data' => $data]);

            if ($request->hasFile('file_bank_book')) {
                $fileBankBook = $request->file('file_bank_book');
                $filenameBankBook = now()->format('Ymd_His') . '_bank_book.' . $fileBankBook->getClientOriginalExtension();
                $fileBankBook->storeAs('file_bank_book', $filenameBankBook);
                session(['file_bank_book_path' => Storage::path('file_bank_book/' . $filenameBankBook)]);
            }

            if ($request->hasFile('file_hkid')) {
                $fileHkid = $request->file('file_hkid');
                $filenameHkid = now()->format('Ymd_His') . '_hkid.' . $fileHkid->getClientOriginalExtension();
                $fileHkid->storeAs('file_hkid', $filenameHkid);
                session(['file_hkid_path' => Storage::path('file_hkid/' . $filenameHkid)]);
            }

            try {
                $response = Http::asForm()->post('http://localhost/web-service/apikko/reward/otp.php', $data);

                if ($response->successful()) {
                    session(['phone_number' => $phone_number]);
                    return redirect()->route('register.otp');
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP',
                        'error_details' => $response->body()
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while sending OTP',
                    'error_details' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function otp()
    {
        $otp = session('otp');
        if ($otp == 'otpwrong') {
            session()->forget('otp');
            $otp = 'OTP salah';
            return view('auth.otp', compact('otp'));
        } else {
            return view('auth.otp');
        }
    }

    public function forgotPasswod()
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordCheck(Request $request)
    {
        $phone_number = $request->country_code . $request->phone_number;

        $response = Http::asForm()->post('http://localhost/web-service/apikko/reward/forgot_password.php', [
            'phone_number' => $phone_number
        ]);

        if ($response->successful()) {
            return redirect()->route('login');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP',
                'error_details' => $response->body()
            ], 500);
        }
    }

    public function otpCheck(Request $request)
    {
        $phoneNumber = session('phone_number');
        $fileHkidPath = session('file_hkid_path');
        $fileBankBookPath = session('file_bank_book_path');
        $data = session('register_data');

        if (!$phoneNumber || !$fileHkidPath || !$fileBankBookPath || !$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data yang diperlukan tidak ditemukan di session.',
            ], 400);
        }

        $otp = Otp::where('nomor', $phoneNumber)->first();

        if (!$otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor telepon tidak ditemukan di OTP.',
            ], 404);
        }

        if ($request->otp != $otp->otp) {
            session(['otp' => 'otpwrong']);
            return redirect()->route('register.otp');
        }

        try {
            $response = Http::attach(
                'file_hkid',
                file_get_contents($fileHkidPath),
                basename($fileHkidPath)
            )->attach(
                'file_bank_book',
                file_get_contents($fileBankBookPath),
                basename($fileBankBookPath)
            )->post('http://localhost/web-service/apikko/reward/register.php', $data);

            if ($response->successful()) {
                session()->forget('phone_number');
                session()->forget('file_hkid_path');
                session()->forget('file_bank_book_path');
                session()->forget('register_data');
                return redirect()->route('login')->with('message', 'Pendaftaran berhasil');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim data transaksi',
                    'response' => $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengirim data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
