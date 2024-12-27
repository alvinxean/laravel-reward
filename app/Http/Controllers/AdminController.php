<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\PeriodParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $period = Period::all();

        $periods_id = $request->get('periods_id');
        session(['periods_id' => $periods_id]);

        if ($periods_id) {
            $participants = PeriodParticipant::with('user', 'period')
                ->where('periods_id', $periods_id)
                ->paginate(10) 
                ->appends(['periods_id' => $periods_id]);
        } else {
            $participants = collect();
        }

        return view('admin.dashboard', compact('period', 'participants'));
    }




    public function updateStatus($id)

    {
        $periods_id = session('periods_id');

        $checkParticipant = PeriodParticipant::where('users_id', $id)
            ->where('periods_id', $periods_id)
            ->update(['status' => 'Berhasil', 'failure_reason' => 'Tidak tersedia']);

        if ($checkParticipant) {
            return redirect()->to('/admin/dashboard?periods_id=' . $periods_id);
        }
    }

    public function updateStatusFail($id)
    {
        $periods_id = session('periods_id');

        $failureReason = request('failure_reason');

        $checkParticipant = PeriodParticipant::where('users_id', $id)
            ->where('periods_id', $periods_id)
            ->update(['status' => 'Gagal', 'failure_reason' => $failureReason]);

        if ($checkParticipant) {
            return redirect()->to('/admin/dashboard?periods_id=' . $periods_id);
        }
    }
    public function historyTransaction(Request $request)
    {
        $periods_id = session('periods_id');

        $currentPeriod = Period::find($periods_id);

        if (!$currentPeriod) {
            return view('admin.transaction');
        }

        $startMonthYear = Carbon::parse($currentPeriod->start_date)->isoFormat('MMMM YYYY');
        $endMonthYear = Carbon::parse($currentPeriod->end_date)->isoFormat('MMMM YYYY');
        $currentPeriod->formatted_period = $currentPeriod->name . ': ' . $startMonthYear . ' - ' . $endMonthYear;

        $startDatePeriodUser = $currentPeriod->start_date;
        $endDatePeriodUser = $currentPeriod->end_date;

        $hkid = $request->input('hkid');

        $response = Http::withOptions(['verify' => false])->get('https://remittance.apikko.co.id/remittance/getLuckyDrawTransactionDataWithDateRange.php', [
            'user' => 'alvin',
            'password' => 'Apikk0*_4l1N',
            'startdate' => $startDatePeriodUser,
            'enddate' => $endDatePeriodUser,
            'custID' => $hkid,
        ]);

        $data = $response->json();
        $transactions = $data['status'] ? $data['transaction'] : [];

        usort($transactions, function ($a, $b) {
            return strtotime($b['TransDate']) - strtotime($a['TransDate']);
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 7;
        $currentItems = array_slice($transactions, ($currentPage - 1) * $perPage, $perPage);

        $transactions = new LengthAwarePaginator(
            $currentItems,
            count($transactions),
            $perPage,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath(),
                'query' => ['hkid' => $hkid]
            ]
        );

        return view('admin.transaction', compact('transactions'));
    }
}
