@extends('layouts.user')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 p-4">
        <h2 class="text-center fw-bold mb-4">
            <i class="bi bi-exclamation-triangle-fill text-success me-2"></i>Report an Incident
        </h2>
        <script>
            const BASE_URL = "{{ config('app.url') }}";
        </script>
        <form id="reportForm" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">📍 Select Location Method:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="location_method" id="locCurrent" value="current" checked>
                    <label class="form-check-label" for="locCurrent">Use Current Location</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="location_method" id="locSearch" value="search">
                    <label class="form-check-label" for="locSearch">Search Location</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Location:</label>
                <input type="text" id="locationInput" name="location" class="form-control" placeholder="Type an address" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Incident Type:</label>
                <select name="incident_type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option>Fire</option>
                    <option>Flood</option>
                    <option>Earthquake</option>
                    <option>Accident</option>
                </select>
            </div>

            <div id="map" style="height: 350px; border-radius: 10px; margin-bottom: 20px;"></div>

            <div class="mb-3">
                <label class="form-label fw-semibold">📸 Landmark Photos <small class="text-warning">(Minimum 2)</small></label>
                <div id="landmarkPreview" class="d-flex flex-wrap gap-2 mb-2"></div>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="openCameraModal('landmark')">
                    <i class="bi bi-camera-fill me-1"></i> Capture Landmark
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">📷 Proof Photos <small class="text-warning">(Minimum 2)</small></label>
                <div id="proofPreview" class="d-flex flex-wrap gap-2 mb-2"></div>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="openCameraModal('proof')">
                    <i class="bi bi-camera-fill me-1"></i> Capture Proof
                </button>
            </div>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-send-fill me-1"></i> Submit
                </button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="cameraModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-0">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-camera-video-fill me-2"></i>Live Camera</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="closeModalBtn"></button>
      </div>
      <div class="modal-body text-center">
        <video id="cameraFeed" autoplay playsinline style="width: 100%; border-radius: 8px;"></video>
        <canvas id="cameraCanvas" style="display:none;"></canvas>
        <div id="modalPreview" class="d-flex flex-wrap gap-2 mt-3 justify-content-center"></div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-outline-light" id="captureBtn">
            <i class="bi bi-camera-fill me-1"></i> Capture
        </button>
        <button type="button" class="btn btn-success" id="doneBtn">Done</button>
      </div>
    </div>
  </div>
</div>

{{-- Libraries --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    #landmarkPreview img, #proofPreview img, #modalPreview img {
        width: 100px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ccc;
        position: relative;
        transition: transform 0.2s ease;
    }
    .preview-container {
        position: relative;
        display: inline-block;
    }
    .remove-btn {
        position: absolute;
        top: -6px;
        right: -6px;
        background: rgba(255,0,0,0.85);
        border: none;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        line-height: 18px;
        cursor: pointer;
    }
    #landmarkPreview img:hover, #proofPreview img:hover {
        transform: scale(1.05);
    }
</style>

<script>
    let map, marker, activeType = null, cameraStream = null;
    let landmarkPhotos = [], proofPhotos = [], modalPhotos = [];

    map = L.map('map').setView([14.5995, 120.9842], 13);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a> | Satellite Imagery © Esri'
    }).addTo(map);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    }).addTo(map);

    function setMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true })
                .addTo(map)
                .bindTooltip("Drag to adjust location", { direction: "top" })
                .openTooltip();
            marker.on('dragend', onMarkerDrag);
        }
        map.setView([lat, lng], 16);
    }

    function onMarkerDrag() {
        const pos = marker.getLatLng();
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.lat}&lon=${pos.lng}&format=json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('locationInput').value = data.display_name || `${pos.lat.toFixed(5)}, ${pos.lng.toFixed(5)}`;
            });
    }

    function setCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const { latitude, longitude } = pos.coords;
                setMarker(latitude, longitude);
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('locationInput').value = data.display_name || '';
                    });
            }, () => Swal.fire('Error', 'Unable to get your location.', 'error'));
        } else {
            Swal.fire('Unsupported', 'Geolocation not supported by your device.', 'warning');
        }
    }

    document.querySelectorAll('input[name="location_method"]').forEach(el => {
        el.addEventListener('change', e => {
            if (e.target.value === 'current') {
                document.getElementById('locationInput').disabled = true;
                setCurrentLocation();
            } else {
                document.getElementById('locationInput').disabled = false;
            }
        });
    });

    document.getElementById('locationInput').addEventListener('input', function() {
        const query = this.value.trim();
        if (document.getElementById('locSearch').checked && query.length > 3) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        setMarker(lat, lon);
                    }
                });
        }
    });

    async function openCameraModal(type) {
        activeType = type;
        modalPhotos = [];
        document.getElementById('modalPreview').innerHTML = '';
        const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
        modal.show();

        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            document.getElementById('cameraFeed').srcObject = cameraStream;
        } catch {
            Swal.fire('Camera Error', 'Please allow camera permissions.', 'error');
        }

        document.getElementById('closeModalBtn').disabled = true;
    }

    document.getElementById('captureBtn').addEventListener('click', () => {
        const video = document.getElementById('cameraFeed');
        const canvas = document.getElementById('cameraCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0);
        const imgData = canvas.toDataURL('image/jpeg');

        modalPhotos.push(imgData);
        const img = document.createElement('img');
        img.src = imgData;
        img.onclick = () => Swal.fire({ imageUrl: imgData, showConfirmButton: false });
        document.getElementById('modalPreview').appendChild(img);

        if (modalPhotos.length >= 2) document.getElementById('closeModalBtn').disabled = false;
    });

    document.getElementById('doneBtn').addEventListener('click', () => {
        if (modalPhotos.length < 2) {
            Swal.fire('Minimum 2 photos required!', '', 'warning');
            return;
        }

        modalPhotos.forEach(imgData => appendPreview(imgData, activeType));
        bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
    });

    function appendPreview(imgData, type) {
        const container = document.createElement('div');
        container.classList.add('preview-container');
        const img = document.createElement('img');
        img.src = imgData;
        img.onclick = () => Swal.fire({ imageUrl: imgData, showConfirmButton: false });
        const removeBtn = document.createElement('button');
        removeBtn.classList.add('remove-btn');
        removeBtn.innerHTML = '✖';
        removeBtn.onclick = () => {
            container.remove();
            if (type === 'landmark') {
                landmarkPhotos = landmarkPhotos.filter(i => i !== imgData);
            } else {
                proofPhotos = proofPhotos.filter(i => i !== imgData);
            }
        };
        container.appendChild(img);
        container.appendChild(removeBtn);

        if (type === 'landmark') {
            landmarkPhotos.push(imgData);
            document.getElementById('landmarkPreview').appendChild(container);
        } else {
            proofPhotos.push(imgData);
            document.getElementById('proofPreview').appendChild(container);
        }
    }

    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', () => {
        if (cameraStream) {
            cameraStream.getTracks().forEach(t => t.stop());
            cameraStream = null;
        }
    });

    document.getElementById('reportForm').addEventListener('submit', async e => {
        e.preventDefault();

        if (landmarkPhotos.length < 2) {
            Swal.fire('Incomplete', 'Please capture at least 2 landmark photos.', 'warning');
            return;
        }
        if (proofPhotos.length < 2) {
            Swal.fire('Incomplete', 'Please capture at least 2 proof photos.', 'warning');
            return;
        }

        const formData = {
            _token: document.querySelector('input[name="_token"]').value,
            incident_type: e.target.incident_type.value,
            location: e.target.location.value,
            landmark_photos: landmarkPhotos,
            proof_photos: proofPhotos
        };

        Swal.fire({
            title: 'Submitting...',
            text: 'Please wait while your report is being submitted.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch(`${BASE_URL}{{ route('incident.store', [], false) }}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            });

            const data = await res.json();
            Swal.close();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message + ` Reference: ${data.reference}`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    e.target.reset();
                    landmarkPhotos = [];
                    proofPhotos = [];
                    document.getElementById('landmarkPreview').innerHTML = '';
                    document.getElementById('proofPreview').innerHTML = '';
                    setCurrentLocation();
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to submit report.', 'error');
            }

        } catch (err) {
            Swal.close();
            Swal.fire('Error', 'Server error or network issue.', 'error');
            console.error(err);
        }
    });

    setCurrentLocation();
</script>
@endsection
