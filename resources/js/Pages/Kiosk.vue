// ...existing code...
<template>
  <div class="min-h-screen bg-black text-white antialiased">
    <!-- Nav / top bar -->
    <nav class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-[#FF2D20] flex items-center justify-center font-extrabold text-black">ET</div>
        <div>
          <div class="text-lg font-semibold text-white">E-Tilang</div>
          <div class="text-xs text-white/80 -mt-1">Kiosk · Scanner · Bukti Visual</div>
        </div>
      </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-6 pb-20">
      <div id="kiosk-main" ref="mainRef" class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-slate-900/80 rounded-2xl p-6 shadow-xl text-slate-100 border border-slate-800">
        <!-- Camera / Live feed -->
        <section id="camera" class="p-6 rounded-2xl border border-slate-700 bg-slate-800/50">
          <div class="flex items-start justify-between mb-4">
            <div>
              <h2 class="text-xl font-semibold text-white">Live Webcam Feed</h2>
              <p class="text-sm text-slate-400 mt-1">Jepret bukti visual yang akan tersimpan sementara sebelum diproses.</p>
            </div>
            <div class="text-xs text-slate-400">Mode Guru</div>
          </div>

          <div class="relative rounded-xl overflow-hidden bg-black border border-slate-700 aspect-video flex items-center justify-center">
            <video ref="videoElement" autoplay playsinline muted class="w-full h-full object-cover"></video>
            <canvas ref="canvasElement" class="hidden"></canvas>
          </div>

          <div class="mt-4 flex gap-3">
            <button @click="ambilFoto" class="flex-1 bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-orange-500 transition-colors">
              📸 Ambil Foto Bukti
            </button>
            <button @click="togglePreview" class="px-4 py-2 rounded-lg border border-slate-700 text-sm text-slate-300 hidden">Pratinjau</button>
          </div>

          <div v-if="fotoBase64" class="mt-4 p-3 bg-slate-800 rounded-lg border border-orange-700">
            <p class="text-xs text-orange-400 mb-2">✓ Bukti Visual Tersimpan Sementara</p>
            <img :src="fotoBase64" class="w-40 rounded border border-slate-700" />
          </div>
        </section>

        <!-- Form / RFID -->
        <section id="form" class="p-6 rounded-2xl border border-slate-700 bg-slate-800/50">
          <div class="mb-4">
            <h2 class="text-xl font-semibold text-white">Data Pelanggaran</h2>
            <p class="text-sm text-slate-400 mt-1">Scan kartu RFID atau ketik NISN/nama, pilih pelanggaran, lalu proses tilang.</p>
          </div>

          <div class="mb-5">
            <label class="block text-sm font-medium text-slate-300 mb-1">Identifikasi Siswa (RFID / Manual)</label>
            <div class="flex gap-2">
              <input 
                v-model="inputPencarian" 
                @keyup.enter="cariSiswa"
                type="text" 
                placeholder="Tempelkan kartu atau ketik nama/NISN lalu Enter..."
                class="w-full bg-slate-900/70 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
              />
              <button @click="cariSiswa" class="bg-orange-600 hover:bg-orange-500 px-4 py-2 rounded-lg transition-colors text-white font-semibold">Cari</button>
            </div>
          </div>

          <div v-if="siswaTerpilih" class="mb-5 p-4 bg-slate-800/60 rounded-lg border-l-4 border-orange-500">
            <p class="text-sm text-slate-300">Siswa Teridentifikasi:</p>
            <h3 class="text-lg font-bold text-white">{{ siswaTerpilih.nama }}</h3>
            <p class="text-sm text-slate-300">Kelas: {{ siswaTerpilih.kelas }} · NISN: {{ siswaTerpilih.nisn }}</p>
            <div class="mt-2 inline-block px-3 py-1 bg-orange-900/20 text-orange-300 rounded-full text-xs font-semibold">
              Total Poin Saat Ini: {{ siswaTerpilih.total_poin_pelanggaran }}
            </div>
          </div>

          <div v-if="siswaTerpilih" class="mb-5">
            <label class="block text-sm font-medium text-slate-300 mb-1">Jenis Pelanggaran</label>
            <select v-model="form.pelanggaran_id" class="w-full bg-slate-900/70 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
              <option disabled value="">-- Pilih Pelanggaran --</option>
              <option v-for="p in pelanggarans" :key="p.id" :value="p.id">
                {{ p.nama_pelanggaran }} (+{{ p.poin_pelanggaran }} Poin)
              </option>
            </select>
          </div>

          <div v-if="siswaTerpilih" class="mb-5">
            <label class="block text-sm font-medium text-slate-300 mb-1">Catatan Tambahan (Opsional)</label>
            <textarea v-model="form.catatan" rows="3" class="w-full bg-slate-900/70 border border-slate-700 rounded-lg px-4 py-2 text-slate-100 focus:outline-none focus:border-orange-500" placeholder="Misal: Siswa tidak bawa kartu..."></textarea>
          </div>

          <button 
            v-if="siswaTerpilih && fotoBase64 && form.pelanggaran_id"
            @click="submitTilang" 
            class="w-full bg-orange-700 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-transform transform hover:scale-105"
          >
            🚨 PROSES TILANG & CETAK STRUK
          </button>
        </section>
      </div>
    </main>

    <footer class="border-t border-slate-800 py-6 text-center text-sm text-slate-400">© 2026 E‑Tilang — Dikelola Sekolah</footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

// ...existing code...
const props = defineProps({
    pelanggarans: Array
});

// State Management
const videoElement = ref(null);
const canvasElement = ref(null);
const fotoBase64 = ref(null);

const inputPencarian = ref('');
const siswaTerpilih = ref(null);

const form = ref({
    pelanggaran_id: '',
    catatan: ''
});

const mainRef = ref(null);

// === LOGIKA WEBCAM ===
let stream = null;

onMounted(async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        if (videoElement.value) {
            videoElement.value.srcObject = stream;
        }
    } catch (err) {
        alert("Webcam tidak terdeteksi atau izin ditolak browser!");
        console.error(err);
    }
});

onUnmounted(() => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});

const ambilFoto = () => {
    if (!videoElement.value || !canvasElement.value) return;
    
    const canvas = canvasElement.value;
    const video = videoElement.value;
    
    canvas.width = 640;
    canvas.height = 480;
    
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    fotoBase64.value = canvas.toDataURL('image/jpeg', 0.8);
};

// === NAV / SCROLL HELPERS ===
function bukaKiosk() {
  mainRef.value?.scrollIntoView({ behavior: 'smooth' });
}
function scrollTo(id) {
  const el = document.getElementById(id);
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// === LOGIKA PENCARIAN (RFID / MANUAL) ===
const cariSiswa = async () => {
    if (!inputPencarian.value) return;
    
    try {
        const response = await axios.post('/kiosk/search', { keyword: inputPencarian.value });
        
        if (response.data.length > 0) {
            siswaTerpilih.value = response.data[0];
            inputPencarian.value = '';
        } else {
            alert("Data Siswa Tidak Ditemukan!");
            siswaTerpilih.value = null;
        }
    } catch (error) {
        console.error(error);
        alert("Gagal menghubungi server.");
    }
};

// === LOGIKA SUBMIT DATA ===
const submitTilang = async () => {
    try {
        const response = await axios.post('/kiosk/store', {
            siswa_id: siswaTerpilih.value.id,
            pelanggaran_id: form.value.pelanggaran_id,
            foto_base64: fotoBase64.value,
            catatan: form.value.catatan
        });

        if (response.data.status === 'success') {
            alert("Sukses! " + response.data.message);
            siswaTerpilih.value = null;
            fotoBase64.value = null;
            form.value.pelanggaran_id = '';
            form.value.catatan = '';
        }
    } catch (error) {
        console.error(error);
        alert("Terjadi kesalahan saat memproses data.");
    }
};
</script>