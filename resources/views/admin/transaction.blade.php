@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="mt-3">
            <h3>Riwayat Transaksi Peserta</h3>
            <div class="d-flex justify-content-end">
                <form action="{{ route('admin.history-transaction') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3" style="max-width: 400px;">
                        <input type="text" class="form-control" id="hkid" name="hkid" placeholder="Masukkan HKID"
                            value="{{ old('hkid') }}">
                        <button class="btn btn-danger" type="submit">Lihat</button>
                        <a href="{{ route('admin.history-transaction') }}" class="btn btn-info">Reset</a>
                    </div>
                </form>
            </div>
            <div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-nowrap">No. Transaksi</th>
                                <th class="text-nowrap">Tanggal Transaksi</th>
                                <th class="text-nowrap">Nama Pengirim</th>
                                <th class="text-nowrap">Nama Penerima</th>
                                <th class="text-nowrap">Nama Bank</th>
                                <th class="text-nowrap">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($transactions) && count($transactions) > 0)
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="text-nowrap">{{ $transaction['TransNum'] }}</td>
                                        <td class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($transaction['TransDate'])->format('d-m-Y H:i:s') }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ str_replace('+', ' ', $transaction['SenderName']) }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ str_replace('+', ' ', $transaction['ReceiverName']) }}
                                        </td>
                                        <td class="text-nowrap">{{ $transaction['BankName'] }}</td>
                                        <td class="text-nowrap">
                                            {{ number_format($transaction['Amount'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    @if (!request()->input('hkid'))
                        <div class="alert alert-info text-center">
                            Masukkan HKID terlebih dahulu
                        </div>
                    @else
                        <div class="container mb-3">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    {{ $transactions->links('vendor.pagination.custom') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
@endsection
