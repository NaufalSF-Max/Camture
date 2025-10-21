<x-app-layout>
    @section('title', 'Syarat & Ketentuan')

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card container --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                {{-- Styling prose untuk formatting teks, dengan warna custom Camture --}}
                <div class="p-8 prose prose-lg max-w-none prose-headings:text-camture-rose prose-strong:text-camture-green-dark prose-ul:list-disc prose-ul:ml-6 prose-li:text-gray-700 prose-a:text-camture-rose hover:prose-a:text-camture-rose-hover">

                    {{-- Judul Utama --}}
                    <h1 class="text-3xl font-extrabold text-camture-rose mb-6 text-center">
                        Syarat dan Ketentuan (Terms and Conditions)
                    </h1>

                    <p class="text-center text-sm text-gray-500 mb-8">
                        Selamat datang di <strong class="text-camture-green-dark">CAMTURE</strong>!<br>
                        Dengan mengakses dan menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui Syarat dan Ketentuan berikut.
                    </p>

                    {{-- Bagian Definisi --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4">
                        1. Definisi
                    </h2>
                    <p class="text-gray-700 leading-relaxed">Dalam Syarat dan Ketentuan ini:</p>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li><strong class="text-camture-green-dark">Layanan</strong> berarti seluruh fitur yang disediakan oleh website photobooth online ini, termasuk pengambilan foto melalui kamera, penerapan filter, pemilihan template, serta pengunduhan hasil foto.</li>
                        <li><strong class="text-camture-green-dark">Pengguna</strong> adalah setiap individu yang mengakses dan menggunakan layanan photobooth ini.</li>
                    </ul>

                    {{-- Bagian Penggunaan Layanan --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4 mt-8">
                        2. Penggunaan Layanan
                    </h2>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li>Pengguna dapat menggunakan kamera perangkat untuk mengambil foto secara langsung melalui website.</li>
                        <li>Foto yang diambil dapat dimodifikasi menggunakan template dan filter yang tersedia di dalam sistem.</li>
                        <li>Setelah selesai, pengguna dapat mengunduh hasil foto secara gratis untuk keperluan pribadi.</li>
                        <li>Pengguna tidak diperbolehkan menggunakan layanan ini untuk tujuan komersial, ilegal, atau yang melanggar norma sosial dan hukum yang berlaku.</li>
                        <li>Dilarang mengunggah, mengedit, atau membagikan foto yang mengandung unsur kekerasan, pornografi, diskriminasi, atau pelanggaran hak cipta.</li>
                    </ul>

                    {{-- Bagian Hak Cipta --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4 mt-8">
                        3. Hak Cipta dan Kepemilikan Konten
                    </h2>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li>Seluruh foto yang diambil melalui website ini tetap menjadi milik pengguna.</li>
                        <li>Template, filter, dan desain grafis yang disediakan merupakan hak cipta milik pengembang website dan tidak boleh digunakan kembali tanpa izin.</li>
                        <li>Dengan menggunakan layanan ini, pengguna memberikan izin kepada sistem kami untuk memproses foto secara otomatis (misalnya untuk menambahkan filter atau template).</li>
                        <li>Kami tidak akan menyimpan atau membagikan hasil foto pengguna ke pihak lain tanpa persetujuan eksplisit.</li>
                    </ul>

                    {{-- Bagian Privasi --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4 mt-8">
                        4. Privasi dan Keamanan Data
                    </h2>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li>Website ini dapat meminta izin untuk mengakses kamera perangkat demi menjalankan fungsi photobooth. Akses tersebut hanya digunakan selama sesi berlangsung dan tidak disimpan setelah pengguna keluar dari halaman.</li>
                        <li>Data pribadi atau foto pengguna tidak akan disimpan di server kecuali dinyatakan lain secara eksplisit.</li>
                        <li>Kami tidak bertanggung jawab atas penyalahgunaan data oleh pihak ketiga di luar sistem kami.</li>
                    </ul>

                    {{-- Bagian Tanggung Jawab --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4 mt-8">
                        5. Tanggung Jawab dan Batasan
                    </h2>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li>Pengguna bertanggung jawab penuh atas konten foto yang dihasilkan melalui layanan ini.</li>
                        <li>Kami tidak bertanggung jawab atas kehilangan data, kerusakan perangkat, atau gangguan teknis akibat penggunaan layanan ini.</li>
                        <li>Layanan dapat dihentikan sementara atau permanen untuk pemeliharaan, pembaruan, atau alasan teknis lainnya tanpa pemberitahuan sebelumnya.</li>
                    </ul>

                    {{-- Bagian Perubahan S&K --}}
                    <h2 class="text-2xl font-bold text-camture-rose border-b border-camture-peach pb-2 mb-4 mt-8">
                        6. Perubahan Syarat dan Ketentuan
                    </h2>
                    <ul class="list-disc ml-6 space-y-2 text-gray-700">
                        <li>Syarat dan Ketentuan ini dapat diperbarui sewaktu-waktu sesuai kebutuhan pengembang.</li>
                        <li>Versi terbaru akan ditampilkan di halaman ini, dan pengguna dianggap menyetujui perubahan setelah tetap menggunakan layanan.</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>