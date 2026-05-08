<template>
    <div class="min-h-screen bg-[#0a0a0a] text-zinc-300 font-sans selection:bg-red-500/30">
        <!-- Navbar Kiosk -->
        <nav class="bg-[#0a0a0a]/80 backdrop-blur-md border-b border-white/5 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-600/20 border border-red-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div>
                            <h1 class="font-bold text-white text-lg tracking-tight leading-tight">E-Tilang Kiosk</h1>
                            <p class="text-[10px] text-red-400 font-mono tracking-widest uppercase">System Active</p>
                        </div>
                    </div>
                    <div>
                        <a href="/dashboard" class="text-sm font-medium text-zinc-500 hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- KOLOM KIRI: Kamera & Bukti Visual (Lebar 5/12) -->
                <section class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-zinc-900/40 border border-white/5 rounded-2xl p-6 shadow-xl relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                Modul Kamera
                            </h2>
                            <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-md border border-emerald-400/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                ON
                            </span>
                        </div>
                        
                        <!-- Frame Kamera Viewfinder -->
                        <div class="relative bg-black rounded-xl overflow-hidden border border-white/10 aspect-video flex justify-center items-center group">
                            <video ref="videoElement" autoplay playsinline class="w-full h-full object-cover"></video>
                            <canvas ref="canvasElement" class="hidden"></canvas>
                            
                            <!-- Overlay Grid Ala HUD Kamera -->
                            <div class="absolute inset-0 pointer-events-none border border-white/5 flex flex-col justify-between p-4 opacity-30">
                                <div class="flex justify-between"><div class="w-4 h-4 border-t-2 border-l-2 border-white"></div><div class="w-4 h-4 border-t-2 border-r-2 border-white"></div></div>
                                <div class="flex justify-between"><div class="w-4 h-4 border-b-2 border-l-2 border-white"></div><div class="w-4 h-4 border-b-2 border-r-2 border-white"></div></div>
                            </div>
                        </div>

                        <button @click="ambilFoto" class="mt-4 w-full bg-zinc-800 hover:bg-zinc-700 text-white font-medium py-3 px-4 rounded-xl transition-all border border-white/5 hover:border-white/20 flex justify-center items-center gap-2 group">
                            <svg class="w-5 h-5 text-zinc-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Jepret Bukti
                        </button>
                    </div>

                    <!-- Preview Foto -->
                    <div v-if="fotoBase64" class="bg-zinc-900/40 border border-emerald-500/30 rounded-2xl p-4 shadow-xl flex gap-4 items-center animate-in fade-in slide-in-from-top-4">
                        <img :src="fotoBase64" class="w-24 h-24 object-cover rounded-lg border border-white/10" />
                        <div>
                            <p class="text-emerald-400 font-bold text-sm">✓ Foto Bukti Tersimpan</p>
                            <p class="text-zinc-500 text-xs mt-1">Siap dilampirkan ke laporan.</p>
                            <button @click="fotoBase64 = null" class="mt-2 text-xs text-red-400 hover:text-red-300">Buang Foto</button>
                        </div>
                    </div>
                </section>

                <!-- KOLOM KANAN: Formulir Data & Logika RFID (Lebar 7/12) -->
                <section class="lg:col-span-7 flex flex-col gap-6">
                    <div class="bg-zinc-900/40 border border-white/5 rounded-2xl p-6 shadow-xl lg:p-8">
                        
                        <div class="flex justify-between items-end mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-white">Data Pelanggaran</h2>
                                <p class="text-sm text-zinc-500 mt-1">Tempelkan kartu RFID atau cari manual.</p>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 text-xs font-mono text-zinc-500 bg-black/50 px-3 py-1.5 rounded-lg border border-white/5">
                                <svg class="w-4 h-4 animate-pulse text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                RFID LISTENER ACTIVE
                            </div>
                        </div>
                        
                        <!-- Pencarian -->
                        <div class="mb-8">
                            <form @submit.prevent="cariSiswa" class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input 
                                    v-model="inputPencarian" 
                                    type="text" 
                                    placeholder="Ketik Nama, NISN, atau scan RFID..."
                                    class="w-full bg-black/50 border border-white/10 rounded-xl py-4 pl-12 pr-24 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all shadow-inner"
                                >
                                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-zinc-800 hover:bg-zinc-700 text-white text-sm font-semibold px-4 rounded-lg transition-colors border border-white/5">
                                    Cari
                                </button>
                            </form>
                        </div>

                        <!-- Info Siswa Terpilih (Glassmorphism Card) -->
                        <div v-if="siswaTerpilih" class="mb-8 p-5 bg-gradient-to-br from-zinc-800/80 to-zinc-900/80 rounded-xl border border-white/10 relative overflow-hidden animate-in fade-in slide-in-from-right-4">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-red-500/10 blur-3xl rounded-full pointer-events-none"></div>
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-zinc-400 font-mono mb-1">TARGET IDENTIFIED</p>
                                    <h3 class="text-2xl font-black text-white mb-1">{{ siswaTerpilih.nama }}</h3>
                                    <div class="flex gap-3 text-sm text-zinc-400">
                                        <span>Kelas: {{ siswaTerpilih.kelas }}</span>
                                        <span class="text-zinc-600">|</span>
                                        <span>NISN: {{ siswaTerpilih.nisn }}</span>
                                    </div>
                                </div>
                                <div class="bg-black/50 border border-white/5 px-4 py-2 rounded-lg text-center">
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-500 font-bold mb-1">Total Poin</p>
                                    <p class="text-2xl font-black" :class="siswaTerpilih.total_poin_pelanggaran > 50 ? 'text-red-500' : (siswaTerpilih.total_poin_pelanggaran > 20 ? 'text-yellow-500' : 'text-emerald-500')">
                                        {{ siswaTerpilih.total_poin_pelanggaran }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Eksekusi Tilang -->
                        <div v-if="siswaTerpilih" class="space-y-5 animate-in fade-in slide-in-from-bottom-4 delay-100">
                            <div>
                                <label class="block text-sm font-medium text-zinc-400 mb-2">Pilih Jenis Pelanggaran</label>
                                <select v-model="form.pelanggaran_id" class="w-full bg-black/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-red-500/50 appearance-none">
                                    <option disabled value="">-- Pilih Pelanggaran --</option>
                                    <option v-for="p in pelanggarans" :key="p.id" :value="p.id">
                                        {{ p.nama_pelanggaran }} (+{{ p.bobot_poin }} Poin)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-400 mb-2">Catatan Kejadian (Opsional)</label>
                                <textarea v-model="form.catatan" rows="2" class="w-full bg-black/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-red-500/50 resize-none placeholder-zinc-700" placeholder="Detail kronologi singkat..."></textarea>
                            </div>

                            <button 
                                @click="submitTilang" 
                                :disabled="!fotoBase64 || !form.pelanggaran_id || isSubmitting"
                                class="w-full mt-4 bg-red-600 hover:bg-red-500 disabled:bg-zinc-800 disabled:text-zinc-500 text-white font-bold py-4 px-4 rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.3)] hover:shadow-[0_0_30px_rgba(220,38,38,0.5)] transition-all disabled:shadow-none flex justify-center items-center"
                            >
                                <span v-if="isSubmitting" class="animate-spin w-5 h-5 border-2 border-white border-t-transparent rounded-full mr-2"></span>
                                <span v-if="!fotoBase64">⚠️ Harap Ambil Foto Bukti</span>
                                <span v-else-if="!form.pelanggaran_id">⚠️ Pilih Jenis Pelanggaran</span>
                                <span v-else>🚨 PROSES TILANG</span>
                            </button>
                        </div>

                    </div>
                </section>

            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    pelanggarans: Array
});

// State
const videoElement = ref(null);
const canvasElement = ref(null);
const fotoBase64 = ref(null);

const inputPencarian = ref('');
const siswaTerpilih = ref(null);
const isSubmitting = ref(false);

const form = ref({
    pelanggaran_id: '',
    catatan: ''
});

// === LOGIKA WEBCAM ===
let stream = null;

onMounted(async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        if (videoElement.value) {
            videoElement.value.srcObject = stream;
        }
    } catch (err) {
        console.error("Webcam error:", err);
    }
    
    // Pasang Global Listener RFID saat halaman dimuat
    window.addEventListener('keydown', handleRFIDScan);
});

onUnmounted(() => {
    // Matikan webcam dan lepas listener saat pindah halaman
    if (stream) stream.getTracks().forEach(track => track.stop());
    window.removeEventListener('keydown', handleRFIDScan);
});

const ambilFoto = () => {
    if (!videoElement.value || !canvasElement.value) return;
    const canvas = canvasElement.value;
    const video = videoElement.value;
    
    canvas.width = 640;
    canvas.height = 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    
    fotoBase64.value = canvas.toDataURL('image/jpeg', 0.8);
};

// === LOGIKA RFID GLOBAL LISTENER ===
let rfidBuffer = '';
let lastKeyTime = Date.now();

const handleRFIDScan = (e) => {
    // Abaikan jika sedang mengetik di input/textarea
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

    const currentTime = Date.now();
    // Jika jeda ngetik lebih dari 50ms (manusia lambat), reset buffer
    if (currentTime - lastKeyTime > 50) {
        rfidBuffer = ''; 
    }
    
    if (e.key === 'Enter' && rfidBuffer.length >= 8) {
        // Terdeteksi tembakan scanner RFID!
        e.preventDefault();
        inputPencarian.value = rfidBuffer;
        cariSiswa();
        rfidBuffer = ''; 
    } else if (e.key !== 'Enter' && e.key !== 'Shift') {
        rfidBuffer += e.key;
    }
    
    lastKeyTime = currentTime;
};

// === LOGIKA PENCARIAN & SUBMIT ===
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
        alert("Gagal memuat data siswa.");
    }
};

const submitTilang = async () => {
    isSubmitting.value = true;
    try {
        const response = await axios.post('/kiosk/store', {
            siswa_id: siswaTerpilih.value.id,
            pelanggaran_id: form.value.pelanggaran_id,
            foto_base64: fotoBase64.value,
            catatan: form.value.catatan
        });

        if (response.data.status === 'success') {
            alert("Sukses! " + response.data.message);
            // Reset state
            siswaTerpilih.value = null;
            fotoBase64.value = null;
            form.value.pelanggaran_id = '';
            form.value.catatan = '';
        }
    } catch (error) {
        alert("Terjadi kesalahan saat memproses data.");
    } finally {
        isSubmitting.value = false;
    }
};
</script>