@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="upload-section" id="uploadSection" style="display: none;">
            <h3>Validasi Peserta</h3>
            <form action="" method="POST" enctype="multipart/form-data" class="mb-3 mt-3">
                @csrf
                <div>
                    <label class="form-label mt-3">Mutasi Rekening Akhir Program</label>
                </div>
                <a href="#" id="viewMutasiAkhir" target="_blank">Lihat Mutasi</a>
            </form>

            <div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="mb-1 mt-3">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" readonly>
                    </div>

                    <div class="col-md-3 position-relative">
                        <label for="hkid" class="mb-1 mt-3">HKID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="hkid" name="hkid" readonly>
                            <button class="btn btn-outline-info" type="button" onclick="copyToClipboard()">Salin</button>
                        </div>
                    </div>

                    <script>
                        function copyToClipboard() {
                            var copyText = document.getElementById("hkid");
                            copyText.select();
                            copyText.setSelectionRange(0, 99999);
                            document.execCommand("copy");
                            alert("HKID telah disalin: " + copyText.value);
                        }
                    </script>


                    <div class="col-md-3">
                        <label for="total_nominal" class="mb-1 mt-3">Total Nominal</label>
                        <input type="text" class="form-control" id="total_nominal" name="total_nominal" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="failure_reason" class="mb-1 mt-3">Alasan Gagal</label>
                        <input type="text" class="form-control" id="failure_reason" name="failure_reason" required>
                        <span id="failure_reason_error" class="text-danger" style="display:none;">Alasan Gagal harus
                            diisi!</span>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-1">
                            <label for="file_akhir" class="form-label mt-3">Berhak mendapatkan hadiah?</label>
                        </div>
                        <div class="d-flex justify-content-start">
                            <form id="updateStatusFailForm" action="{{ route('update.status.fail', ':id') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" id="participantIdFail" name="id">
                                <button type="submit" class="btn btn-sm btn-danger me-2">Gagal</button>
                            </form>

                            <form id="updateStatusSuccessForm" action="{{ route('update.status', ':id') }}" method="POST">
                                @csrf
                                <input type="hidden" id="participantIdSuccess" name="id">
                                <button type="submit" class="btn btn-sm btn-info">Berhasil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.detailButton').click(function(e) {
                    e.preventDefault();

                    function formatRupiah(angka) {
                        return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    var $uploadSection = $('#uploadSection');
                    $uploadSection.show();

                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var status = $(this).data('status');
                    var hkid = $(this).data('hkid');
                    var fileBankStatement = $(this).data('file-bank-statement');
                    var totalNominal = $(this).data('total-nominal');
                    var failureReason = $('#failure_reason');
                    var failureReasonData = $(this).data('failure-reason');

                    $('#name').val(name);
                    $('#status').val(status);
                    $('#hkid').val(hkid);
                    $('#file_bank_statement').val(fileBankStatement);
                    $('#total_nominal').val(formatRupiah(totalNominal));

                    $('#participantIdSuccess').val(id);
                    $('#participantIdFail').val(id);

                    //menampilkan mutasi
                    if (fileBankStatement) {
                        var baseUrl = "http://localhost/web-service/apikko/reward/";
                        var fullUrl = baseUrl + fileBankStatement;

                        $('#viewMutasiAkhir').attr('href', fullUrl);
                        $('#viewMutasiAkhir').text('Lihat Mutasi');
                    } else {
                        $('#viewMutasiAkhir').removeAttr('href').text('Tidak ada file');
                    }

                    // pengecekan data validasi alasan gagal
                    if (failureReasonData == 'Tidak tersedia') {
                        $('#failure_reason').val('');
                    } else {
                        $('#failure_reason').val(failureReasonData);
                    }

                    //pengecekan button berhasil atau gagal
                    if (status == 'Berhasil') {
                        $('#updateStatusSuccessForm button[type="submit"]').prop('disabled', true);
                        $('#updateStatusFailForm button[type="submit"]').prop('disabled', false);
                    } else if (status == 'Gagal') {
                        $('#updateStatusSuccessForm button[type="submit"]').prop('disabled', false);
                        $('#updateStatusFailForm button[type="submit"]').prop('disabled', true);
                    }

                    var actionUrlSuccess = '{{ route('update.status', ':id') }}';
                    actionUrlSuccess = actionUrlSuccess.replace(':id', id);
                    $('#updateStatusSuccessForm').attr('action', actionUrlSuccess);

                    $('#updateStatusFailForm button[type="submit"]').click(function(e) {
                        var failureReasonValue = $('#failure_reason').val();

                        //pengecekan isian alasan gagal
                        if (failureReasonValue.trim() === '') {
                            e.preventDefault();
                            $('#failure_reason_error').show()
                            $('#failure_reason').focus();
                            return false;
                        } else {
                            $('#failure_reason_error').hide();
                        }

                        $('#updateStatusFailForm').append(
                            '<input type="hidden" name="failure_reason" value="' +
                            failureReasonValue + '">'
                        );

                        var actionUrlFail = '{{ route('update.status.fail', ':id') }}';
                        actionUrlFail = actionUrlFail.replace(':id', id);
                        $('#updateStatusFailForm').attr('action', actionUrlFail);
                    });
                });
            });
        </script>

        <div class="mt-3">
            <h3>Peserta</h3>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <select class="form-control" id="periods_id" name="periods_id" required>
                        <option value="">Pilih Periode</option>
                        @foreach ($period as $data)
                            <option value="{{ $data->id }}" @if (request('periods_id') == $data->id) selected @endif>
                                {{ $data->name }} ({{ $data->start_date }} s/d {{ $data->end_date }})
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger" type="submit">Tampilkan</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-info">Reset</a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">Aksi</th>
                            <th class="text-nowrap text-center">ID</th>
                            <th class="text-nowrap text-center">Nama</th>
                            <th class="text-nowrap text-center">Status</th>
                            <th class="text-nowrap text-center">Nomor HP</th>
                            <th class="text-nowrap text-center">HKID</th>
                            <th class="text-nowrap text-center">Nama Bank dan Buku Tabungan</th>
                            <th class="text-nowrap text-center">Rekening Bank</th>
                            <th class="text-nowrap text-center">Nama Pemilik Bank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participants as $participant)
                            <tr>
                                <td class="text-nowrap text-center"><a href="#"
                                        class="btn btn-sm btn-warning detailButton" data-id="{{ $participant->user->id }}"
                                        data-name="{{ $participant->user->name }}"
                                        data-hkid="{{ $participant->user->hkid }}"
                                        data-file-bank-statement="{{ $participant->file_bank_statement }}"
                                        data-total-nominal="{{ $participant->total_nominal }}"
                                        data-failure-reason="{{ $participant->failure_reason }}"
                                        data-status="{{ $participant->status }}">Validasi</a>
                                </td>
                                <td class="text-nowrap text-center">{{ $participant->user->id }}</td>
                                <td class="text-nowrap text-center">{{ $participant->user->name }}</td>
                                <td class="text-nowrap text-center">{{ $participant->status }}</td>
                                <td class="text-nowrap text-center">{{ $participant->user->phone_number }}</td>
                                <td class="text-nowrap text-center">{{ $participant->user->hkid }}&nbsp;&nbsp;&nbsp;<a
                                        href="http://localhost/web-service/apikko/reward/{{ $participant->user->file_hkid }}"
                                        target="_blank">Lihat</a></td>
                                <td class="text-nowrap text-center">
                                    {{ $participant->user->bank->name }}&nbsp;&nbsp;&nbsp;<a
                                        href="http://localhost/web-service/apikko/reward/{{ $participant->user->file_bank_book }}"
                                        target="_blank">Lihat</a></td>
                                <td class="text-nowrap text-center">{{ $participant->user->bank_account_number }}</td>
                                <td class="text-nowrap text-center">{{ $participant->user->bank_holder_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container mb-3">
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            @if ($participants instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $participants->links('vendor.pagination.custom') }}
                            @endif
                        </div>
                    </div>
                </div>

                @if (!request()->input('periods_id'))
                    <div class="alert alert-info text-center">
                        Pilih periode terlebih dahulu
                    </div>
                @elseif ($participants->isEmpty())
                    <div class="alert alert-info text-center">
                        Tidak ada peserta untuk periode yang dipilih
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
