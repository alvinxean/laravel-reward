<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\PeriodParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ParticipantController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        $lastPeriodParticipant = PeriodParticipant::where('users_id', $userId)
            ->orderBy('periods_id', 'desc')
            ->first();

        $currentPeriod = Period::find($lastPeriodParticipant->periods_id);

        if ($currentPeriod) {
            $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
            $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
            $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;
        }

        $participant = PeriodParticipant::where('users_id', $user->id)
            ->where('periods_id', $lastPeriodParticipant->periods_id)
            ->first();

        $status = $participant->status;
        // dd($status);
        $failureReason = $participant->failure_reason;
        $fileBankStatement = $participant->file_bank_statement;
        $totalNominal = $participant->total_nominal;
        $starDatePeriodUser = $participant->period->start_date;
        $endDatePeriodUser = $participant->period->end_date;

        $response = Http::withOptions(['verify' => false])->get('https://remittance.apikko.co.id/remittance/getLuckyDrawTransactionDataWithDateRange.php', [
            'user' => 'alvin',
            'password' => 'Apikk0*_4l1N',
            'startdate' => $starDatePeriodUser,
            'enddate' => $endDatePeriodUser,
            'custID' => $user->hkid,
        ]);

        $data = $response->json();
        $transactions = $data['status'] ? $data['transaction'] : [];

        usort($transactions, function ($a, $b) {
            return strtotime($b['TransDate']) - strtotime($a['TransDate']);
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 3;
        $currentItems = array_slice($transactions, ($currentPage - 1) * $perPage, $perPage);

        $transactions = new LengthAwarePaginator(
            $currentItems,
            count($transactions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('participant.dashboard', compact('transactions', 'currentPeriod', 'status', 'failureReason', 'fileBankStatement', 'totalNominal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_bank_statements' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $userId = Auth::id();
        $totalNominal = str_replace('.', '', $request->total_nominal);
        $hkid = Auth::user()->hkid;

        $data = [
            'users_id' => $userId,
            'total_nominal' => $totalNominal,
            'hkid' => $hkid
        ];

        if ($request->hasFile('file_bank_statements')) {
            $fileBankStatement = $request->file('file_bank_statements');
            $filenameBankStatement = now()->format('Ymd_His') . '_bank_statement.' . $fileBankStatement->getClientOriginalExtension();
            $fileBankStatement->storeAs('file_bank_statements', $filenameBankStatement);
            $data['file_bank_statements'] = Storage::path('file_bank_statements/' . $filenameBankStatement);
        }

        try {
            $response = Http::attach(
                'file_bank_statements',
                file_get_contents($data['file_bank_statements']),
                basename($data['file_bank_statements'])
            )->post('http://localhost/web-service/apikko/reward/uploadBankStatement.php', $data);

            if ($response->successful()) {
                return redirect()->route('participant.dashboard')->with('message', 'Upload berhasil');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim data transaksi',
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengirim data: ' . $e->getMessage(),
            ]);
        }
    }
}
