<template>
    <div class="min-h-screen bg-[#0a0a0a] text-zinc-300 font-sans selection:bg-red-500/30 pb-12 relative">
        
        <!-- Navbar Dashboard -->
        <nav class="bg-zinc-900/50 backdrop-blur-md border-b border-white/5 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                            <span class="text-white font-bold text-xs">ET</span>
                        </div>
                        <span class="font-bold text-white text-lg tracking-tight">E-Tilang Admin</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="showModalSiswa = true" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Siswa
                        </button>

                        <a href="/kiosk" class="bg-red-600/10 hover:bg-red-600/20 text-red-400 border border-red-500/20 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Buka Kiosk
                        </a>

                        <Link href="/logout" method="post" as="button" class="bg-zinc-800 hover:bg-red-600 text-zinc-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-white/5 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <header class="mb-8">
                <h1 class="text-3xl font-black text-white">Dashboard Statistik</h1>
                <p class="text-zinc-500 mt-1">Ringkasan data kedisiplinan sekolah.</p>
            </header>

            <!-- Grid Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-zinc-900/40 border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl"></div>
                    <p class="text-zinc-500 text-sm font-medium uppercase tracking-wider mb-2">Total Siswa Terdaftar</p>
                    <h3 class="text-4xl font-black text-white">{{ stats.total_siswa }}</h3>
                </div>
                <div class="bg-zinc-900/40 border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
                    <p class="text-zinc-500 text-sm font-medium uppercase tracking-wider mb-2">Total Kasus Tercatat</p>
                    <h3 class="text-4xl font-black text-white">{{ stats.total_pelanggaran }}</h3>
                </div>
            </div>

            <!-- Tabel Data Pelanggaran -->
            <div class="bg-zinc-900/40 border border-white/5 rounded-2xl overflow-hidden shadow-xl">
                <div class="px-6 py-5 border-b border-white/5 bg-black/20">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Riwayat Pelanggaran
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-black/40 text-zinc-400 text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 font-semibold">Siswa & Waktu</th>
                                <th class="py-4 px-6 font-semibold">Pelanggaran</th>
                                <th class="py-4 px-6 font-semibold text-center">Poin</th>
                                <th class="py-4 px-6 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="item in riwayatTerbaru" :key="item.id" class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-white mb-1" v-if="item.siswa">{{ item.siswa.nama }}</div>
                                    <div class="text-xs text-zinc-500">{{ new Date(item.created_at).toLocaleString('id-ID') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-zinc-300 font-medium" v-if="item.pelanggaran">{{ item.pelanggaran.nama_pelanggaran }}</div>
                                    <div class="text-xs text-zinc-500 mt-1 line-clamp-1">{{ item.catatan || '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span v-if="item.pelanggaran" class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-1 rounded text-xs font-bold">
                                        +{{ item.pelanggaran.poin_pelanggaran }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button @click="hapusData(item.id)" class="opacity-0 group-hover:opacity-100 bg-zinc-800 hover:bg-red-600 text-zinc-400 hover:text-white p-2 rounded-lg transition-all border border-white/5 hover:border-red-500">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="riwayatTerbaru.length === 0">
                                <td colspan="4" class="py-8 text-center text-zinc-500">Belum ada data pelanggaran.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- MODAL TAMBAH SISWA -->
        <div v-if="showModalSiswa" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-zinc-900 border border-white/10 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5 flex justify-between items-center bg-black/20">
                    <h3 class="text-lg font-bold text-white">Tambah Data Siswa</h3>
                    <button @click="showModalSiswa = false" class="text-zinc-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form @submit.prevent="submitSiswa" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Nama Lengkap</label>
                        <input v-model="formSiswa.nama" type="text" required class="w-full bg-black/50 border border-white/10 rounded-xl py-2.5 px-4 text-white focus:ring-2 focus:ring-red-500/50 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">NISN</label>
                            <input v-model="formSiswa.nisn" type="text" required class="w-full bg-black/50 border border-white/10 rounded-xl py-2.5 px-4 text-white focus:ring-2 focus:ring-red-500/50 outline-none">
                            <p v-if="formSiswa.errors.nisn" class="text-red-400 text-xs mt-1">{{ formSiswa.errors.nisn }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Kelas</label>
                            <input v-model="formSiswa.kelas" type="text" placeholder="Misal: X RPL 1" required class="w-full bg-black/50 border border-white/10 rounded-xl py-2.5 px-4 text-white focus:ring-2 focus:ring-red-500/50 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">UID Kartu RFID</label>
                        <input v-model="formSiswa.rfid_uid" type="text" required placeholder="Scan kartu di sini..." class="w-full bg-black/50 border border-white/10 rounded-xl py-2.5 px-4 text-white focus:ring-2 focus:ring-red-500/50 outline-none">
                        <p v-if="formSiswa.errors.rfid_uid" class="text-red-400 text-xs mt-1">{{ formSiswa.errors.rfid_uid }}</p>
                    </div>

                    <div class="mt-6 flex gap-3 pt-4 border-t border-white/5">
                        <button type="button" @click="showModalSiswa = false" class="flex-1 bg-zinc-800 hover:bg-zinc-700 text-white py-2.5 rounded-xl transition-colors font-medium">Batal</button>
                        <button type="submit" :disabled="formSiswa.processing" class="flex-1 bg-white hover:bg-zinc-200 text-black font-bold py-2.5 rounded-xl transition-colors disabled:opacity-50">
                            {{ formSiswa.processing ? 'Menyimpan...' : 'Simpan Siswa' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    stats: { type: Object, default: () => ({ total_siswa: 0, total_pelanggaran: 0 }) },
    riwayatTerbaru: { type: Array, default: () => [] }
});

const showModalSiswa = ref(false);

const formSiswa = useForm({
    nama: '',
    nisn: '',
    kelas: '',
    rfid_uid: ''
});

const submitSiswa = () => {
    formSiswa.post('/siswa', {
        preserveScroll: true,
        onSuccess: () => {
            showModalSiswa.value = false;
            formSiswa.reset();
            alert('Siswa baru berhasil ditambahkan!');
        }
    });
};

const hapusData = (id) => {
    if (confirm('Hapus riwayat pelanggaran ini?')) {
        router.delete(`/pelanggaran/${id}`, { preserveScroll: true });
    }
};
</script>