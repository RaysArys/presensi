@extends('layouts.app')
@section('title','Dashboard Admin') @section('page-title','Dashboard') @section('page-subtitle','Ringkasan aktivitas presensi hari ini')
@section('content')
<div class="stats-grid">
@foreach([['Total Karyawan',$ringkasan['karyawan'],'sage'],['Hadir',$ringkasan['hadir'],'green'],['Terlambat',$ringkasan['terlambat'],'amber'],['Izin',$ringkasan['izin'],'blue'],['Sakit',$ringkasan['sakit'],'purple'],['Cuti',$ringkasan['cuti'],'teal'],['Alpa',$ringkasan['alpa'],'red']] as $s)
<div class="stat-card"><div class="stat-icon {{ $s[2] }}">●</div><div><span>{{ $s[0] }}</span><strong>{{ $s[1] }}</strong></div></div>@endforeach
</div>
<div class="dashboard-grid">
<div class="card"><div class="card-head"><div><h3>Tren 7 Hari Terakhir</h3><p>Perbandingan kehadiran karyawan</p></div></div><div class="chart-wrap"><canvas id="attendanceChart"></canvas></div></div>
<div class="card"><div class="card-head"><div><h3>Pengajuan Terbaru</h3><p>Menunggu keputusan Admin</p></div><a href="{{ route('admin.ketidakhadiran.index') }}" class="text-link">Lihat semua</a></div>
<div class="request-list">@forelse($pengajuan as $p)<div class="request-item"><div class="avatar small">{{ strtoupper(substr($p->pegawai->nama,0,1)) }}</div><div class="grow"><strong>{{ $p->pegawai->nama }}</strong><small>{{ ucfirst($p->jenis) }} • {{ $p->tanggal_mulai->format('d M') }}–{{ $p->tanggal_selesai->format('d M') }}</small></div><span class="badge pending">Menunggu</span></div>@empty<div class="empty">Tidak ada pengajuan baru.</div>@endforelse</div></div>
</div>
<div class="card mt"><div class="card-head"><div><h3>Presensi Hari Ini</h3><p>{{ now()->translatedFormat('l, d F Y') }}</p></div><a href="{{ route('admin.rekap.index') }}" class="text-link">Buka rekap</a></div>
<div class="table-wrap"><table><thead><tr><th>Nama Karyawan</th><th>Status</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Tanggal</th></tr></thead><tbody>@foreach($data as $r)<tr><td><strong>{{ $r->pegawai->nama }}</strong><small class="cell-sub">{{ $r->pegawai->jabatan?->nama_jabatan ?? '–' }}</small></td><td><span class="badge {{ str_replace(' ','-',$r->status) }}">{{ ucfirst($r->status) }}</span></td><td>{{ $r->presensi?->jam_masuk ? substr($r->presensi->jam_masuk,0,5) : '–' }}</td><td>{{ $r->presensi?->jam_pulang ? substr($r->presensi->jam_pulang,0,5) : '–' }}</td><td>{{ $r->tanggal->format('d/m/Y') }}</td></tr>@endforeach</tbody></table></div></div>
@endsection
@push('scripts')<script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script>new Chart(document.getElementById('attendanceChart'),{type:'bar',data:{labels:@json($chartLabels),datasets:[{label:'Hadir',data:@json($chartHadir),backgroundColor:'#78977a',borderRadius:6},{label:'Terlambat',data:@json($chartTerlambat),backgroundColor:'#e8b45c',borderRadius:6},{label:'Alpa',data:@json($chartAlpa),backgroundColor:'#d7786f',borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{precision:0}}}}});</script>@endpush
