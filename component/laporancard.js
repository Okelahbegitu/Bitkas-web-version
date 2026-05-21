class ReaportCard extends HTMLElement {
    connectedCallback() {
        const month = this.getAttribute("month");
        const totalPemasukan = this.getAttribute("total-pemasukan");
        const totalPengeluaran = this.getAttribute("total-pengeluaran");
        this.innerHTML = `
        <div class="flex-shrink-0 w-75 bg-purple-100 hover:scale-115 hover:z-10 hover:mx-6 transition-all duration-250 dark:bg-purple-800 dark:text-white p-7 rounded-lg shadow-md flex flex-col justify-between h-full gap-3">
            <div class="flex-1">
                <h2 class="text-xl font-bold">${month}</h2>
                <p class="text-gray-600 dark:text-gray-300">Total Pemasukan: Rp ${totalPemasukan}</p>
                <p class="text-gray-600 dark:text-gray-300">Total Pengeluaran: Rp ${totalPengeluaran}</p>
            </div>
        </div>
        
        `
    }
}

customElements.define("laporan-card", ReaportCard)