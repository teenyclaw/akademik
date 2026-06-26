<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Rapor {{ $reportCard->student->name }}</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #333;padding:6px}</style></head>
<body>
<h2 style="text-align:center">RAPOR SISWA</h2>
<p><strong>Nama:</strong> {{ $reportCard->student->name }} | <strong>NIS:</strong> {{ $reportCard->student->nis }}</p>
<p><strong>Kelas:</strong> {{ $reportCard->schoolClass?->name }} | <strong>Semester:</strong> {{ $reportCard->semester?->name }}</p>
<table><thead><tr><th>Mata Pelajaran</th><th>Nilai</th><th>Huruf</th></tr></thead>
<tbody>@foreach($reportCard->details as $d)<tr><td>{{ $d->subject?->name }}</td><td>{{ $d->score }}</td><td>{{ $d->grade_letter }}</td></tr>@endforeach</tbody></table>
@if($reportCard->homeroom_notes)<p><strong>Catatan Wali Kelas:</strong> {{ $reportCard->homeroom_notes }}</p>@endif
</body></html>
