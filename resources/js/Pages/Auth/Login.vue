<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0a0a0a] relative overflow-hidden">
        
        <!-- Background Glow Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-red-600/20 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-zinc-900/60 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-3xl z-10 relative">
            
            <div class="flex justify-center mb-8">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>
            <h2 class="text-2xl font-black text-center text-white mb-2">Portal Guru BK</h2>
            <p class="text-center text-zinc-400 text-sm mb-8">Silakan masuk untuk mengelola data E-Tilang</p>

            <form @submit.prevent="submit">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
                    <input 
                        v-model="form.email" 
                        type="email" 
                        required 
                        autofocus 
                        class="w-full bg-black/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all"
                        placeholder="tatib@sekolah.com"
                    >
                    <p v-if="form.errors.email" class="text-red-400 text-xs mt-1">{{ form.errors.email }}</p>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-zinc-300 mb-1">Password</label>
                    <input 
                        v-model="form.password" 
                        type="password" 
                        required 
                        class="w-full bg-black/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all"
                        placeholder="••••••••"
                    >
                    <p v-if="form.errors.password" class="text-red-400 text-xs mt-1">{{ form.errors.password }}</p>
                </div>

                <div class="mt-8">
                    <button 
                        :class="{ 'opacity-50': form.processing }" 
                        :disabled="form.processing"
                        class="w-full bg-white hover:bg-zinc-200 text-black font-bold py-3 px-4 rounded-xl transition-all flex justify-center items-center"
                    >
                        <span v-if="form.processing" class="animate-spin w-5 h-5 border-2 border-black border-t-transparent rounded-full mr-2"></span>
                        Log in
                    </button>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="/" class="text-sm text-zinc-500 hover:text-white transition-colors">&larr; Kembali ke Portal Publik</a>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>