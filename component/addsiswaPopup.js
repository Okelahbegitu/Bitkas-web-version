import axios from "axios";
import { API_BASE_URL } from "../config/config.js";

class AddSiswaPopup extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div id="popup-container" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
            
            <div class="bg-white dark:bg-purple-900 dark:text-gray-300 shadow-2xl w-fit px-15 pt-5 pb-15 rounded-2xl relative">
                <div class="relative flex items-center justify-center mb-10">
                    <h1 class="text-lg font-semibold">Tambah Siswa</h1>

                    <button class="absolute right-0" id="closeBtn">
                        <i data-lucide="circle-x"></i>
                    </button>
                </div>

                <div class="flex justify-center gap-4 mt-5">
                    <form id="addSiswaForm" class="w-full">
                        <div class="mb-5">
                            <label for="nisn">NISN</label> <br>
                            <input id="nisn" class="rounded-2xl w-full border border-purple-500 px-2" type="number" name="nisn">
                        </div>
                        <div class="mb-5">
                            <label for="nama">Nama</label> <br>
                            <input id="nama" class="rounded-2xl w-full border border-purple-500 px-2" type="text" name="nama">
                        </div>
                        <div class="mb-5">
                            <label for="gender">Gender</label> <br>
                            <select id="gender" class="rounded-2xl w-full border border-purple-500 px-2" name="gender">
                                <option value="">Pilih Gender</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="tanggallahir">Tanggal Lahir</label> <br>
                            <input id="tanggallahir" class="rounded-2xl w-full border border-purple-500 px-2" type="date" name="tanggallahir">
                        </div>
                        <div class="flex justify-center">
                            <input type="submit" class="bg-primary w-full py-5 rounded-2xl" value="Tambah">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        `;

        this.container = this.querySelector("#popup-container");
        
        // Klik tombol close untuk sembunyikan
            this.querySelector("#closeBtn").onclick = () => this.remove();
        // Klik di area luar box (overlay) untuk sembunyikan
        this.container.onclick = (e) => {
            if (e.target === this.container) this.remove();
        };

        const form = this.querySelector("#addSiswaForm");


        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            // Ambil data dari form
            const nisn = this.querySelector("#nisn").value;
            const nama = this.querySelector("#nama").value;
            const gender = this.querySelector("#gender").value;
            const tanggallahir = this.querySelector("#tanggallahir").value;

            axios.post(`${API_BASE_URL}POST_NEW_SISWA.php`, {
                nisn: nisn,
                nama_siswa: nama,
                gender: gender,
                tanggal_lahir: tanggallahir
            })
                .then(response => {
                    console.log("Siswa berhasil ditambahkan:", response.data);
                    this.querySelector("#addSiswaForm").reset();
                    this.remove();
                })
                .catch(error => {
                    console.error("Error menambahkan siswa:", error);
                });
        });

    }
}
customElements.define("add-siswa-popup", AddSiswaPopup);