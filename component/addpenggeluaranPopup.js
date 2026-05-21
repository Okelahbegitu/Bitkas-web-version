import axios from "axios";
import { API_BASE_URL } from "../config/config.js";


class addpenggeluaranPopup extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div id="popup-container" class=" fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
            
            <div class="bg-white dark:bg-purple-900 dark:text-gray-300 shadow-2xl w-fit px-15 pt-5 pb-15 rounded-2xl relative">
                <div class="relative flex items-center justify-center mb-10">
                    <h1 class="text-lg font-semibold">Penggeluaran</h1>

                    <button class="absolute right-0" id="closeBtn">
                        <i data-lucide="circle-x"></i>
                    </button>
                </div>

                <div class="flex justify-center gap-4 mt-5">
                    <form id="penggeluaranForm" class="w-full">
                    <div class="mb-5 ">
                        <label for="keterangan">Keterangan</label> <br>
                        <textarea id="keterangan" class="rounded-2xl w-full h-full border border-purple-500" name="keterangan"></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="nominal">Nominal</label> <br>
                        <input id="nominal" class="rounded-2xl w-full border border-purple-500 px-2" type="number" name="nominal">
                    </div>
                    <div class="flex justify-center">
                        <input type="submit" class="bg-primary w-full py-5 rounded-2xl">
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

        // Handle submit form
        const form = this.querySelector("#penggeluaranForm");
        form.onsubmit = (e) => {
            e.preventDefault();
            const keterangan = this.querySelector("#keterangan").value;
            const nominal = this.querySelector("#nominal").value;
            const data = {
                keterangan: keterangan,
                nominal: nominal
            };
            axios.post(`${API_BASE_URL}POST_NEW_PENGGELUARAN.php`, data, {
                headers: {
                    'ngrok-skip-browser-warning': 'true'
                }
            })
                .then(response => {
                    console.log("Penggeluaran berhasil ditambahkan:", response.data);
                    this.remove(); // Tutup popup setelah submit
                })
                .catch(error => {
                    console.error("Error menambahkan penggeluaran:", error);
                });
        };
    }

}
customElements.define("addpenggeluaran-popup", addpenggeluaranPopup);