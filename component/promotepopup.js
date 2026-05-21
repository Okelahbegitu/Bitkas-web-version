import axios, { AxiosHeaders } from "axios";
import { API_BASE_URL } from "../config/config";
class PromotePopup extends HTMLElement {
    connectedCallback() {
        const nisn = this.getAttribute("nisn");
        const id = this.getAttribute("id_siswa");
        const role = this.getAttribute("role") || "siswa"; // Default role is "siswa" if not provided
        this.innerHTML = `
        <div id="popup-container" class=" fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center">
            
            <div class="bg-white dark:bg-purple-900 dark:text-gray-300 shadow-2xl w-fit px-15 pt-5 pb-15 rounded-2xl">
                <div class="flex flex-col items-stretch gap-3 mb-10">
                    <h2 class="text-xl font-bold mb-4">De/Promote Siswa</h2>
                    <select id="role-select" class="w-full mb-4 p-2 border rounded">
                        <option value="siswa"       ${role === "siswa" ? "selected" : ""}>Siswa</option>
                        <option value="ketkel"      ${role === "Ketua Kelas" ? "selected" : ""}>Ketua Kelas</option>
                        <option value="bendahara"   ${role === "Bendahara" ? "selected" : ""}>Bendahara</option>
                    </select>
                    <label>Password:</label>
                    <input type="password" id="password-input" required class="w-full mb-4 p-2 border rounded" placeholder="Masukkan password untuk konfirmasi">
                </div>
                    <div class="flex justify-end gap-4">
                        <button id="cancel" class="px-4 py-2 text-black bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                        <button id="confirm" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Promote</button>
                    </div>
                </div>
            </div>
        </div>
        `
        this.container = this.querySelector("#popup-container");

        // Klik tombol cancel untuk sembunyikan
        this.querySelector("#cancel").onclick = () => this.remove();
        // Klik di area luar box (overlay) untuk sembunyikan
        this.container.onclick = (e) => {
            if (e.target === this.container) this.remove()
        };

        axios.get(`${API_BASE_URL}GET_AKUN.php?id_siswa=${id}`, {
            headers: {
                "Content-Type": "application/json",
                "ngrok-skip-browser-warning": "true"
            }
        }).then(response => {
            const data = response.data;
            if (data && data.length > 0) {
                const akun = data[0];
                const password = this.querySelector("#password-input").value;
                password.value = akun.password;
                role = akun.role;
            } 
        })

        const confirmButton = this.querySelector("#confirm");
        confirmButton.onclick = async () => {
            const selectedRole = this.querySelector("#role-select").value;
            const password = this.querySelector("#password-input").value;
            axios.post(`${API_BASE_URL}POST_MOD_AKUN.php`,{
                id_siswa: id,
                nisn: nisn,
                role: selectedRole,
                password: password
            }, {
                headers: {
                    "Content-Type": "application/json",
                    "ngrok-skip-browser-warning": "true"
                }
            })
                .then(response => {
                    console.log("Siswa berhasil dipromosikan:", response.data);
                    this.remove();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        };
    }
}
customElements.define("promote-popup", PromotePopup)