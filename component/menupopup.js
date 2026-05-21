import '../component/addsiswaPopup';
class menupopup extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div id="popup-container" class=" fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center">
            
            <div class="bg-white dark:bg-purple-900 dark:text-gray-300 shadow-2xl w-fit px-15 pt-5 pb-15 rounded-2xl relative">
                <div class="relative flex items-center justify-center mb-10">
                    <h1 class="text-lg font-semibold">Tambahkan</h1>

                    <button class="absolute right-0" id="closeBtn">
                        <i data-lucide="circle-x"></i>
                    </button>
                </div>

                <div class="flex justify-center gap-4 mt-5">
                    <button id="tambahpemasukan" class="dark:bg-gray-500 dark:hover:bg-amber-400 p-5 flex flex-col items-center w-40 bg-yellow-200 rounded-2xl hover:bg-yellow-300 transition">
                        <i data-lucide="banknote-arrow-down"></i>
                        <p>Pemasukan</p>
                    </button>
                    <button id="tambahpenggeluaran" class="dark:bg-gray-500 dark:hover:bg-amber-400  p-5 flex flex-col items-center w-40 bg-yellow-200 rounded-2xl hover:bg-yellow-300 transition">
                        <i data-lucide="banknote-arrow-up"></i>
                        <p>Pengeluaran</p>
                    </button>
                    <button id="tambahsiswa" class="dark:bg-gray-500 dark:hover:bg-amber-400 p-5 flex flex-col items-center w-40 bg-yellow-200 rounded-2xl hover:bg-yellow-300 transition">
                        <i data-lucide="book-user"></i>
                        <p>Siswa</p>
                    </button>
                </div>
            </div>
        </div>
        `;

        this.container = this.querySelector("#popup-container");
        
        // Klik tombol close untuk sembunyikan
        this.querySelector("#closeBtn").onclick = () => this.remove();
        // Klik di area luar box (overlay) untuk sembunyikan
        this.container.onclick = (e) => {
            if (e.target === this.container) this.remove()
        };

        // Klik tombol tambah pemasukan untuk tampilkan popup siswa
        this.querySelector("#tambahsiswa").onclick = () => {
            const addSiswaPopup = document.createElement("add-siswa-popup");
            document.body.appendChild(addSiswaPopup);
            this.remove();
        };
        this.querySelector("#tambahpemasukan").onclick = () => {
            const addPemasukanPopup = document.createElement("addpemasukan-popup");
            document.body.appendChild(addPemasukanPopup);
            this.remove();
        }
        this.querySelector("#tambahpenggeluaran").onclick = () => {
            const addPenggeluaranPopup = document.createElement("addpenggeluaran-popup");
            document.body.appendChild(addPenggeluaranPopup);
            this.remove();
        };
    }

}
customElements.define("menu-popup", menupopup);