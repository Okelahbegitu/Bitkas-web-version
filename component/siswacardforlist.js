import axios from "axios";
import { API_BASE_URL } from "../config/config.js";

class siswacardforlist extends HTMLElement {
    connectedCallback() {
        const id_siswa = this.getAttribute("id_siswa");
        const nisn = this.getAttribute("nisn");
        const nama = this.getAttribute("nama");
        const gender = this.getAttribute("gender");
        const tanggallahir = this.getAttribute("tanggallahir");

        this.innerHTML = `
                        <div class="bg-gradient-to-br from-purple-600 to-purple-800 
                            hover:scale-105 hover:-translate-y-1 
                            transition-all duration-300 
                            text-white p-5 rounded-2xl shadow-lg flex flex-col justify-between h-full gap-4">

                            <!-- Header -->
                            <div class="flex flex-col gap-2">
                                <h1 class="text-lg font-bold">${nama}</h1>
                                <span class="text-xs bg-white/20 px-2 py-1 rounded w-fit">SISWA</span>
                            </div>

                            <!-- Info -->
                            <div class="flex flex-col gap-1 text-sm text-white/80">
                                <p><span class="font-semibold text-white">NISN:</span> ${nisn}</p>
                                <p><span class="font-semibold text-white">Gender:</span> ${gender === "L" ? "Laki-laki" : "Perempuan"}</p>
                                <p><span class="font-semibold text-white">Lahir:</span> ${tanggallahir}</p>
                            </div>

                            <!-- Action -->
                            <div class="flex gap-2 mt-3">
                                <button id="edit" class="flex-1 bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded-lg text-sm">
                                    Edit
                                </button>
                                <button id="promosi" class="flex-1 bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded-lg text-sm">
                                    Role
                                </button>
                                <button id="delete" class="flex-1 bg-red-500 hover:bg-red-600 px-3 py-2 rounded-lg text-sm">
                                    Hapus
                                </button>
                            </div>

                        </div>
        `

        if (window.lucide) window.lucide.createIcons();

        const editButton = this.querySelector("#edit");
        editButton.addEventListener("click", () => {
            const editPopup = document.createElement("edit-siswa-popup");
            editPopup.setAttribute("nisn", nisn);
            editPopup.setAttribute("nama", nama);
            editPopup.setAttribute("gender", gender);
            editPopup.setAttribute("tanggallahir", tanggallahir);
            document.body.appendChild(editPopup);
        });

        const deleteButton = this.querySelector("#delete");
        deleteButton.addEventListener("click", () => {
            // Logic for deleting student
            if (confirm("Apakah Anda yakin ingin menghapus siswa ini?")) {
                axios.delete(`${API_BASE_URL}/DELETE_SISWA.php`, {
                    headers: {
                        'ngrok-skip-browser-warning': 'true'
                    },
                    data: {
                        nisn: nisn
                    },
                }).then((response) => {
                    alert(response.data.message);
                    location.reload();
                }).catch((error) => {
                    console.error(error);
                });
            }
        });

        const promoteButton = this.querySelector("#promosi");
        promoteButton.addEventListener("click", () => {
            const promotePopup = document.createElement("promote-popup");
            promotePopup.setAttribute("id_siswa", id_siswa);
            promotePopup.setAttribute("nisn", nisn);
            document.body.appendChild(promotePopup);
        });
    }
}

customElements.define("siswacard-for-list", siswacardforlist);