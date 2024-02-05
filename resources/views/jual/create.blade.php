@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Tambah Penjualan</div>
            <div class="right"></div>
        </div>
    </div>
@endsection

@section('content')
    <div style="margin-top: 5rem">
        <div class="col">
            @php
                $massagesuccess = Session::get('success');
                $massageerror = Session::get('error');
            @endphp
            @if ($massagesuccess)
                <div class="alert alert-success">
                    {{ $massagesuccess }}
                </div>
            @elseif ($massageerror)
                <div class="alert alert-danger">
                    {{ $massageerror }}
                </div>
            @endif
        </div>
    </div>
    <form action="/jual.store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                            <path d="M16 3l0 4" />
                            <path d="M8 3l0 4" />
                            <path d="M4 11l16 0" />
                            <path d="M8 15h2v2h-2z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <div class="form-group">
                    <input type="text" id="tanggal" name="tanggal" class="form-control datepicker"
                        placeholder="   Tanggal">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M15 8l2 0" />
                            <path d="M15 12l2 0" />
                            <path d="M7 16l10 0" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <select type="text" name="produk" id="produk" class="form-select select2" required>
                    <option value="">Pilih Produk</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id }}">{{ $item->produk }}/{{ $item->satuan }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row">
            <div class="col-1">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                            <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="col-11">
                <input type="text" value="" class="form-control" name="stok" placeholder="Jumlah Penjualan"
                    required>
            </div>


        </div>
        <div class="modal-footer">
            {{-- <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button> --}}
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </div>

    </form>
@endsection
@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy/mm/dd"
            });

            $("#tanggal").change(function(e) {
                var tanggal = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/kas.store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == 1) {
                            Swal.fire({
                                title: 'Ooops!',
                                text: 'Anda Sudah Melakukan Input kas Pada Tanggal tersebut',
                                icon: 'warning',
                            }).then((result) => {
                                $("#tanggal").val("");
                            })
                        }
                    }
                });
            });

            $("#frmkas").submit(function() {
                var tanggal = $("#tanggal").val();
                var status = $("#status").val();
                var keterangan = $("#keterangan").val();

                if (tanggal == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Tanggal Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                } else if (status == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Status Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                } else if (keterangan == "") {
                    Swal.fire({
                        title: 'Ooops!',
                        text: 'Keterangan Harus Di isi',
                        icon: 'warning',
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
