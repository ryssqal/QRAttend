@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Date</p>
                    <p class="text-lg text-gray-900">{{ $event->date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Time</p>
                    <p class="text-lg text-gray-900">{{ $event->start_time->format('H:i') }} - {{ $event->end_time ? $event->end_time->format('H:i') : 'TBD' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Location</p>
                    <p class="text-lg text-gray-900">{{ $event->location ?? 'TBD' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Attendance List</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="attendance-table">
                                @foreach($participants as $participant)
                                <tr data-participant-id="{{ $participant->user->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participant->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ isset($attendances[$participant->user->id]) && $attendances[$participant->user->id]->status == 'Present'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800' }}">
                                            {{ isset($attendances[$participant->user->id]) ? $attendances[$participant->user->id]->status : 'Missing' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Participants</span>
                            <span class="text-sm font-medium text-gray-900" id="total-participants">{{ $totalParticipants }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Present</span>
                            <span class="text-sm font-medium text-green-600" id="total-present">{{ $totalPresent }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Missing</span>
                            <span class="text-sm font-medium text-red-600" id="total-missing">{{ $totalMissing }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6" data-event-id="{{ $event->id }}">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Record Attendance</h3>
                    <div id="qr-reader" class="mb-4" style="width: 100%; min-height: 250px; background: #f3f4f6; display: none;"></div>
                    <div id="camera-instructions" class="mt-3 text-center text-sm text-gray-600" style="display: none;">
                        <p>📱 Camera activated. Scan QR code.</p>
                    </div>
                    <div class="space-y-3">
                        <button id="start-scan" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700">
                            Start QR Scan
                        </button>
                        <button id="stop-scan" class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700" style="display: none;">
                            Stop Scanning
                        </button>
                    </div>
                    <div id="scan-result" class="mt-4 text-sm text-center font-bold"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. URL dinamik dari route Laravel
    const url = "{{ route('pengurusMajlis.attendance.record') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let html5QrCode = null;
    let isScanning = false;
    let isProcessing = false;

    // Ambil data event
    const scannerContainer = document.querySelector('[data-event-id]');
    const eventId = parseInt(scannerContainer.getAttribute('data-event-id'));
    
    const startScanBtn = document.getElementById('start-scan');
    const stopScanBtn = document.getElementById('stop-scan');
    const qrReader = document.getElementById('qr-reader');
    const cameraInstructions = document.getElementById('camera-instructions');
    const scanResult = document.getElementById('scan-result');

    startScanBtn.addEventListener('click', startScanning);
    stopScanBtn.addEventListener('click', stopScanning);

    function startScanning() {
        if (isScanning) return;
        isProcessing = false;
        showResult('Initializing camera...', 'info');

        qrReader.style.display = 'block';
        cameraInstructions.style.display = 'block';

        html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start(
            { facingMode: "environment" }, 
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            () => {} 
        ).then(() => {
            isScanning = true;
            startScanBtn.style.display = 'none';
            stopScanBtn.style.display = 'block';
            showResult('Ready to scan!', 'info');
        }).catch(err => {
            showResult('Camera Error: ' + err, 'error');
            qrReader.style.display = 'none';
        });
    }

    function stopScanning() {
        if (!html5QrCode) return;
        html5QrCode.stop().then(() => {
            isScanning = false;
            qrReader.style.display = 'none';
            cameraInstructions.style.display = 'none';
            startScanBtn.style.display = 'block';
            stopScanBtn.style.display = 'none';
            showResult('', '');
        }).catch(err => console.error(err));
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;
        
        if(html5QrCode) html5QrCode.pause();
        showResult('Sedang memproses QR...', 'info');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                event_id: eventId,
                qr_data: decodedText
            })
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                showResult('✅ ' + data.message, 'success');
                updateAttendanceTable(data.participant_id);
                setTimeout(resumeScanning, 2000);
            } else {
                showResult('❌ ' + (data.error || 'Ralat'), 'error');
                setTimeout(resumeScanning, 2000);
            }
        })
        .catch(error => {
            showResult('⚠️ Network Error: Sila periksa sambungan internet.', 'error');
            setTimeout(resumeScanning, 3000);
        });
    }

    function resumeScanning() {
        if (html5QrCode && isScanning) {
            html5QrCode.resume();
            isProcessing = false;
            showResult('Ready for next scan...', 'info');
        }
    }

    function updateAttendanceTable(userId) {
        const row = document.querySelector(`tr[data-participant-id="${userId}"]`);
        if (!row) return;
        
        const badge = row.querySelector('.status-badge');
        // Hanya update jika status belum Present
        if (!badge.classList.contains('bg-green-100')) {
            badge.className = 'status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
            badge.textContent = 'Present';
            
            // Update summary UI
            const pres = document.getElementById('total-present');
            const miss = document.getElementById('total-missing');
            if(pres) pres.textContent = parseInt(pres.textContent) + 1;
            if(miss) miss.textContent = Math.max(0, parseInt(miss.textContent) - 1);
        }
    }

    function showResult(msg, type) {
        scanResult.textContent = msg;
        scanResult.className = 'mt-4 text-sm text-center font-bold ' + 
            (type === 'success' ? 'text-green-600' : type === 'error' ? 'text-red-600' : 'text-blue-600');
    }
});
</script>
@endsection