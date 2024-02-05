@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"> </ion-icon>
            </a>
            <div class="pagetitle">Report Penjualan</div>
            <div class="right"></div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top:70px;">
        <div class="col">
            <div class="row m-0">
                <div class="col-12">
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"{{ date('m') == $i ? 'selected' : '' }}>
                                    {{ $namabulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>

                </div>

            </div>

            <div class="row m-0">
                <div class="col-12">
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">Tahun</option>
                            @php
                                $tahunmulai = 2022;
                                $tahunskrng = date('Y');
                            @endphp
                            @for ($tahun = $tahunmulai; $tahun <= $tahunskrng; $tahun++)
                                <option value="{{ $tahun }}" {{ date('Y') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}</option>
                            @endfor

                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" id="getdata"> <ion-icon
                                name="search-outline"></ion-icon>
                            Search</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="col" id="showhistori">
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(function() {
            $("#getdata").click(function(e) { // Corrected spelling of click and function
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                $.ajax({
                    type: 'POST',
                    url: '/gethistori',
                    data: {
                        _token: "{{ csrf_token() }}", // Corrected csrf_token syntax
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    success: function(response) { // Corrected spelling of function
                        $("#showhistori").html(response);
                    }
                });
            });
        });
    </script>
@endpush
