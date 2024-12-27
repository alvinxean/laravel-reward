<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peserta</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('mot/assets/images/logos/chandra-peduli.png') }}" />
    <link rel="stylesheet" href="{{ asset('mot/assets/css/styles.min.css') }}" />

    {{-- Link Icon --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-8">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h4 class="text-center py-1">Dashboard <span style="font-weight: bold">PESERTA</span> -
                                    <span style="font-weight: bold; color: rgb(255, 72, 0);">CHANDRA PEDULI</span></h4>
                                    <p style="font-size: 12px" class="text-center">Syarat dan Ketentuan <a
                                            href="http://localhost/web-service/apikko/reward/file_pdf/s&k.pdf" target="_blank">Klik
                                            disini</a></p>

                                    @if ($currentPeriod)
                                        @if ($status == 'Berhasil')
                                            <div class="alert alert-success text-center">
                                                Selamat, Anda berhak mendapatkan hadiah Rp500.000 pada
                                                {{ $currentPeriod->formatted_period }}🎉
                                            </div>
                                        @elseif ($status == 'Gagal')
                                            <div class="alert alert-danger text-center">
                                                Maaf, Anda gagal mendapatkan hadiah karena <span
                                                    style="text-transform: lowercase;">{{ $failureReason }}</span>
                                            </div>
                                        @endif
                                    @endif

                                    <form action="{{ route('participant.dashboard.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="file_bank_statements" class="form-label  ">Upload Mutasi
                                                    Rekening</label>
                                                <div class="d-flex align-items-center">
                                                    <input style="margin-right: 10px" type="file"
                                                        class="form-control" id="file_bank_statements"
                                                        name="file_bank_statements" accept="image/*, application/pdf" required
                                                        @if ($status === 'Berhasil' || $status === 'Gagal') disabled @endif>
                                                </div>

                                                @if ($fileBankStatement == 'Tidak tersedia')
                                                    @if ($status == 'Tunda')
                                                        <p style="font-size: 12px" class="text-danger mb-0">Peringatan!
                                                            Upload mutasi hanya akhir
                                                            program.<br> </p>
                                                    @endif
                                                @else
                                                    @if ($status == 'Tunda')
                                                        <p style="font-size: 12px" class="text-danger mb-0">Silahkan
                                                            upload ulang apabila terdapat
                                                            kesalahan <br> </p>
                                                        </span>
                                                    @endif

                                                    <p style="font-size: 12px;" class="mb-2">
                                                        File sudah tersimpan ke database. <span><a
                                                                href="http://localhost/web-service/apikko/reward/{{ $fileBankStatement }}"
                                                                target="_blank">Lihat Mutasi</a>
                                                        </span>
                                                    </p>

                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <label for="total_nominal" class="form-label  "> Total Saldo
                                                    Akhir</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="input-group me-2">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control" id="total_nominal"
                                                            name="total_nominal" required oninput="formatRupiah(this)"
                                                            value="{{ number_format($totalNominal, 0, ',', '.') }}"
                                                            @if ($status === 'Berhasil' || $status === 'Gagal') disabled @endif>
                                                    </div>

                                                    <script>
                                                        function formatRupiah(input) {
                                                            let value = input.value.replace(/\D/g, '');
                                                            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                            input.value = value;
                                                        }
                                                    </script>

                                                    <button id="uploadButton" type="submit"
                                                        class="btn btn-outline-primary d-flex align-items-center"
                                                        @if ($status === 'Berhasil' || $status === 'Gagal') disabled @endif>
                                                        Upload&nbsp;&nbsp;&nbsp;<i class="fas fa-upload py-1"></i>
                                                    </button>

                                                </div>
                                                <p style="font-size: 12px" class="text-danger mb-2">Total saldo dari
                                                    awal sampai akhir program</p>
                                            </div>
                                        </div>
                                    </form>

                                    <div>
                                        @if ($currentPeriod)
                                            <label class="form-label">Riwayat Transaksi <br>
                                                <span
                                                    style="font-weight: normal; font-size: 12px">({{ $currentPeriod->formatted_period }})</span></label>
                                        @else
                                            <label class="form-label">Riwayat Transaksi</label>
                                        @endif

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
                                                                {{ number_format($transaction['Amount'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="container mb-2">
                                                <div class="row justify-content-center ">
                                                    <div class="col-auto">
                                                        {{ $transactions->links('vendor.pagination.custom') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center  ">
                                            <a href="" class="d-flex align-items-center"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                style="color: #FF5733;">
                                                <i class="fas fa-sign-out-alt me-2" style="color: #FF5733;"></i>
                                                Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('mot/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('mot/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
