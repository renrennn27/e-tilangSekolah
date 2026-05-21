<template>
    <div class="min-h-screen bg-[#0a0a0a] text-zinc-300 font-sans selection:bg-red-500/30">
        
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 bg-[#0a0a0a]/80 backdrop-blur-md border-b border-white/5 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="font-bold text-xl tracking-tight text-white">E-Tilang</h1>
            </div>
            <a href="/login" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors flex items-center gap-1">
                Akses Guru <span aria-hidden="true">&rarr;</span>
            </a>
        </nav>

        <main class="max-w-5xl mx-auto px-4 py-12 md:py-20">
            <div v-if="!selectedSiswa" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                
                <!-- Hero Section -->
                <div class="text-center mb-12 relative">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-red-500/10 blur-3xl rounded-full pointer-events-none"></div>
                    
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">
                        Cek Poin <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Kedisiplinan.</span>
                    </h2>
                    <p class="text-zinc-400 mb-8 max-w-2xl mx-auto text-lg leading-relaxed">
                        Transparansi kedisiplinan siswa. Masukkan <b>Nama Lengkap</b> atau <b>NISN</b> untuk melihat riwayat pelanggaran.
                    </p>
                    
                    <!-- Form Pencarian -->
                    <form @submit.prevent="cariSiswa" class="max-w-xl mx-auto relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-zinc-500 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input 
                            v-model="keyword" 
                            type="text" 
                            placeholder="Ketik Nama atau NISN..."
                            class="w-full bg-zinc-900/50 border border-white/10 rounded-2xl py-4 pl-12 pr-32 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all shadow-xl"
                        >
                        <button 
                            type="submit" 
                            :disabled="isLoading"
                            class="absolute right-2 top-2 bottom-2 bg-white text-black hover:bg-zinc-200 font-semibold px-6 rounded-xl transition-all disabled:opacity-50 flex items-center"
                        >
                            <span v-if="isLoading" class="animate-spin w-4 h-4 border-2 border-black border-t-transparent rounded-full mr-2"></span>
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Hasil Pencarian (List Siswa) -->
                <div v-if="siswaList.length > 0" class="mt-12">
                    <p class="text-sm text-zinc-500 mb-4 font-medium uppercase tracking-wider">Hasil Pencarian ({{ siswaList.length }} ditemukan)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kartu Siswa -->
                        <div v-for="s in siswaList" :key="s.id" class="bg-zinc-900/40 border border-white/5 hover:border-white/10 rounded-2xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-all hover:bg-zinc-900/80 group">
                            <div>
                                <h3 class="text-lg font-bold text-white group-hover:text-red-400 transition-colors">{{ s.nama }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-sm text-zinc-400">
                                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg> {{ s.nisn }}</span>
                                    <span class="w-1 h-1 rounded-full bg-zinc-600"></span>
                                    <span>Kelas {{ s.kelas }}</span>
                                </div>
                            </div>
                            <button @click="lihatDetail(s.id)" class="w-full sm:w-auto bg-zinc-800 hover:bg-red-600 hover:text-white text-zinc-300 font-medium py-2 px-4 rounded-lg transition-colors text-sm border border-white/5 hover:border-red-500">
                                Lihat Riwayat
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else-if="hasSearched" class="mt-12 text-center py-12 bg-zinc-900/30 rounded-2xl border border-white/5 border-dashed">
                    <p class="text-zinc-500">Tidak ada siswa yang cocok dengan kata kunci tersebut.</p>
                </div>
            </div>

            <div v-else class="animate-in fade-in slide-in-from-right-8 duration-500">
                
                <button @click="selectedSiswa = null" class="mb-6 text-zinc-400 hover:text-white flex items-center gap-2 transition-colors text-sm font-medium group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Pencarian
                </button>

                <!-- Header Profil Detail -->
                <div class="bg-zinc-900 border border-white/10 rounded-3xl overflow-hidden relative mb-8 shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-1" :class="selectedSiswa.total_poin_pelanggaran > 50 ? 'bg-red-500' : (selectedSiswa.total_poin_pelanggaran > 20 ? 'bg-yellow-500' : 'bg-emerald-500')"></div>
                    
                    <div class="p-6 md:p-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-black text-white mb-2">{{ selectedSiswa.nama }}</h2>
                            <div class="flex flex-wrap items-center gap-3 text-zinc-400 font-medium">
                                <span class="bg-zinc-800 text-zinc-300 px-3 py-1 rounded-md text-sm border border-white/5">Kelas {{ selectedSiswa.kelas }}</span>
                                <span>NISN: {{ selectedSiswa.nisn }}</span>
                            </div>
                        </div>
                        
                        <!-- Lingkaran Poin -->
                        <div class="flex flex-col items-center bg-black/50 p-6 rounded-2xl border border-white/5 w-full md:w-auto">
                            <span class="text-xs text-zinc-500 uppercase tracking-widest font-bold mb-2">Total Poin</span>
                            <span class="text-5xl font-black" :class="selectedSiswa.total_poin_pelanggaran > 50 ? 'text-red-500' : (selectedSiswa.total_poin_pelanggaran > 20 ? 'text-yellow-500' : 'text-emerald-500')">
                                {{ selectedSiswa.total_poin_pelanggaran }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat (Dark Mode Style) -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Pelanggaran
                    </h3>

                    <div v-if="selectedSiswa.riwayat_pelanggarans.length === 0" class="text-center py-16 bg-zinc-900/50 rounded-2xl border border-white/5">
                        <div class="w-16 h-16 bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Bersih Tanpa Catatan!</h4>
                        <p class="text-zinc-500">Siswa ini belum memiliki catatan pelanggaran kedisiplinan.</p>
                    </div>

                    <div v-else class="bg-zinc-900 border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-black/40 text-zinc-400 text-xs uppercase tracking-wider">
                                        <th class="py-4 px-6 font-semibold w-1/4">Tanggal</th>
                                        <th class="py-4 px-6 font-semibold w-1/2">Jenis Pelanggaran</th>
                                        <th class="py-4 px-6 font-semibold text-center w-1/4">Poin & Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    <tr v-for="riwayat in selectedSiswa.riwayat_pelanggarans" :key="riwayat.id" class="hover:bg-white/[0.02] transition-colors">
                                        <td class="py-4 px-6 text-sm text-zinc-400">
                                            <div class="font-medium text-zinc-300">
                                                {{ new Date(riwayat.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                            </div>
                                            <div class="text-xs mt-1">
                                                {{ new Date(riwayat.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }} WIB
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-white font-medium mb-1">{{ riwayat.pelanggaran.nama_pelanggaran }}</div>
                                            <div v-if="riwayat.catatan" class="text-sm text-zinc-500 flex items-start gap-2">
                                                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                                <span class="italic">{{ riwayat.catatan }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 font-bold px-3 py-1.5 rounded-lg text-sm inline-block mb-2 shadow-inner">
                                                +{{ riwayat.pelanggaran.poin_pelanggaran }}
                                            </div>
                                            <div class="text-xs text-zinc-500">
                                                Oleh: {{ riwayat.user.name }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const keyword = ref('');
const siswaList = ref([]);
const hasSearched = ref(false);
const isLoading = ref(false);

const selectedSiswa = ref(null);


const cariSiswa = async () => {
    if (!keyword.value) return;
    
    isLoading.value = true;
    hasSearched.value = false;
    
    try {
        const response = await axios.post('/portal/search', { keyword: keyword.value });
        siswaList.value = response.data;
        hasSearched.value = true;
    } catch (error) {
        console.error("Gagal mengambil data pencarian:", error);
    } finally {
        isLoading.value = false;
    }
};

const lihatDetail = async (id) => {
    try {
        const response = await axios.get(`/portal/siswa/${id}`);
        selectedSiswa.value = response.data;
    } catch (error) {
        console.error("Gagal mengambil detail siswa:", error);
        alert("Terjadi kesalahan saat memuat data.");
    }
};
</script>