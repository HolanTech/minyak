@extends('layouts.presensi')
@section('content')
    {{-- <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            text-decoration: none;
            right: 5%;
        }

        .logout hover {
            color: white;
        }

        .section#user-section {

            background-color: greenyellow !important;
            background-image: url(assets/img/bg.png) !important;
            background-size: cover;
        }
    </style> --}}
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            text-decoration: none;
            right: 5%;
        }

        .logout hover {
            color: white;
        }

        .section#user-section {
            background-color: rgb(255, 182, 47) !important;
            background-image: url(assets/img/bg.png) !important;
            background-size: cover;
        }

        .list-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        .item-menu {
            flex-basis: calc(25% - 20px);
            /* 20px is the total margin for each item */
            margin: 10px;
            text-align: center;
        }

        .menu-icon a {
            display: block;
        }
    </style>
    <div class="section" id="user-section">
        <a href="/proseslogout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded">
                @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar"
                        class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
                <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
                {{-- <span id="user-role">({{ Auth::guard('karyawan')->user()->kode_cabang }})</span> --}}
            </div>
        </div>
    </div>
    <div class="section" id="bg-section">
    </div>
    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/kas" class="success" style="font-size: 40px;">
                                <ion-icon name="cash-outline" style="color: green !important"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">KAS</span>
                        </div>
                    </div>

                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/karyawan" class="danger" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Karyawan</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/produk" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Produk</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/produk.show" class="primary" style="font-size: 40px;">
                                <ion-icon name="cube-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Stok</span>
                        </div>
                    </div>
                </div>
                <div class="list-menu mt-3">

                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/produk.createstok" class="primary" style="font-size: 40px;">
                                <ion-icon name="bag-add-outline"style="color: rgb(19, 231, 221) !important"></ion-icon> </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Tambah_Stock</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/jual" class="success" style="font-size: 40px;">
                                <ion-icon name="storefront-outline"style="color: green !important"></ion-icon> </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Jual</span>
                        </div>
                    </div>
                </div>
                {{--         <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/expedisi" class="danger" style="font-size: 40px;">
                                <ion-icon name="bus-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Expedisi</span>
                        </div>
                    </div>
                </div>
                <div class="list-menu mt-3">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/beli" class="warning" style="font-size: 40px;">
                                <ion-icon name="cube-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Beli</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/transport" class="danger" style="font-size: 40px;">
                                <ion-icon name="car-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Transport</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/qc" class="" style="font-size: 40px;">
                                <ion-icon name="clipboard-outline"style="color: gray !important"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">QC Palet</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/report" class="danger" style="font-size: 40px;">
                                <ion-icon name="alert-circle-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Report</span>
                        </div>
                    </div>
                </div>
                <div class="list-menu mt-3">

                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/project" class="primary" style="font-size: 40px;">
                                <ion-icon name="newspaper-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Project</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/pks" class="" style="font-size: 40px;">
                                <ion-icon name="aperture-outline"style="color: green !important"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">PKS</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="note" class="warning" style="font-size: 40px;">
                                <ion-icon name="reader-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Note</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="danger" style="font-size: 40px;">
                                {{-- <ion-icon name="alert-circle-outline"></ion-icon> --}}
                {{-- </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center"></span>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
@endsection
