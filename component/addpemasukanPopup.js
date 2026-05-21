import axios from "axios";
import { API_BASE_URL } from "../config/config";
class addpemasukanPopup extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div id="popup-container" class=" fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
            
            <div class="bg-white dark:bg-purple-900 dark:text-gray-300 shadow-2xl w-fit px-15 pt-5 pb-15 rounded-2xl relative">
                <div class="relative flex items-center justify-center mb-10">
                    <h1 class="text-lg font-semibold">Pemasukan</h1>

                    <button class="absolute right-0" id="closeBtn">
                        <i data-lucide="circle-x"></i>
                    </button>
                </div>

                <div class="flex justify-center gap-4 mt-5">
                    <form id="pemasukanForm" class="w-full">
                    <div class="mb-5">
                        <label for="nama">Nama siswa</label> <br>
                        <select name="nama" class="w-full h-fit rounded-2xl border border-purple-500 px-2" id="nama">
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="nominal">Nominal</label> <br>
                        <input class="rounded-2xl w-full border border-purple-500 px-2" type="number" name="nominal">
                    </div>
                    <div class="flex justify-center">
                        <input name="pemasukanForm" type="submit" class="bg-primary w-full py-5 rounded-2xl" >
                    </div>
                    </form>
                </div>
            </div>
        </div>
        `

        // Handle submit form
        const form = this.querySelector("#pemasukanForm");
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            axios.post(`${API_BASE_URL}POST_NEW_PEMASUKAN.php`, {
                headers: {
                    'ngrok-skip-browser-warning': 'true'
                },
                nisn: form.nama.value,
                nominal: form.nominal.value
            })
            .then(response => {
                console.log("Pemasukan berhasil ditambahkan:", response.data);
                this.hide(); // Tutup popup setelah berhasil
            })
            .catch(error => {
                console.error("Error menambahkan pemasukan:", error);
            });
        });

        //load list siswa untuk dropdown'
        axios.get(`${API_BASE_URL}GET_ALL_SISWA.php`, {
            headers: {
                'ngrok-skip-browser-warning': 'true'
            }
        })
            .then(response => {
                if (!response.data || !Array.isArray(response.data.data)) {
                    console.error("Format response tidak sesuai:", response.data);
                    return;
                }

                const siswaList = response.data.data;
                const namaInput = document.querySelector("#nama");
                console.log(response);

                siswaList.forEach(siswa => {
                    const option = document.createElement("option");
                    option.value = siswa.nisn;
                    option.textContent = siswa.nama_siswa;
                    namaInput.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching siswa list:", error);
            });


        this.container = this.querySelector("#popup-container");

        // Klik tombol close untuk sembunyikan
        this.querySelector("#closeBtn").onclick = () => this.hide();
        // Klik di area luar box (overlay) untuk sembunyikan
        this.container.onclick = (e) => {
            if (e.target === this.container) this.hide();
        };
    }

    show() {
        this.container.classList.remove("hidden");
        if (window.lucide) lucide.createIcons(); // Refresh ikon saat muncul
    }

    hide() {
        this.container.classList.add("hidden");
    }
}
customElements.define("addpemasukan-popup", addpemasukanPopup);