<ul class="listview image-listview">
    <li>
        <div class="item">
            <div class="in">
                <div>
                    <small>Produk</small>
                </div>
                <small>Harga Jual</small>
                <small>Keuntungan</small>

                {{-- <span
                    class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                <span class="badge bg-primary">{{ $d->jam_out }}</span> --}}
            </div>
        </div>
    </li>
</ul>
@if ($histori->isEmpty())
    <div class="alert alert-outline-warning">
        <p> Belum Ada Data Tersimpan</p>
    </div>
@endif
@foreach ($histori as $d)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        <b>{{ date('d-m-Y', strtotime($d->tanggal)) }}</b><br>

                        <strong>{{ $d->produk }}</strong><br>
                        <span>{{ $d->qty }} {{ $d->satuan }}</span><br>
                        {{-- Use date() instead of $d->date() and remove the extra $d-> --}}
                    </div>
                    <strong>Rp.{{ number_format($d->harga, 2, ',', '.') }}</strong>
                    <strong>Rp.{{ number_format($d->laba, 2, ',', '.') }}</strong>

                    {{-- <span
                        class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                    <span class="badge bg-primary">{{ $d->jam_out }}</span> --}}
                </div>
            </div>
        </li>
    </ul>
@endforeach
