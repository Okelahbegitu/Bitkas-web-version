class AppNavbar extends HTMLElement {
  connectedCallback() {
    this.innerHTML= `
    <nav class="print:hidden flex sticky top-0 shadow justify-between gap-6 py-5 rounded-b- px-10 text-white" style="background-color: #7C3AED;">
    <button class="cursor-pointer text-2xl">💰 BitKas</button>
    <a  id="dashboard-link" class="text-2xl px-7 rounded-3xl"  href="home.html">Dashboard</a>
    <a  id="siswa-link"     class="text-2xl px-7 rounded-3xl"  href="siswalist.html">Murid</a>
    <a  id="laporan-link"   class="text-2xl px-7 rounded-3xl"  href="laporanlist.html">Laporan</a>
  </nav>
    `

    if (window.location.pathname.endsWith("home.html")) {
      document.getElementById("dashboard-link").classList.add("bg-white", "text-purple-700");
    } else if (window.location.pathname.endsWith("siswalist.html")) {
      document.getElementById("siswa-link").classList.add("bg-white", "text-purple-700");
    } else if (window.location.pathname.endsWith("laporanlist.html") || window.location.pathname.endsWith("laporandetail.html")) {
      document.getElementById("laporan-link").classList.add("bg-white", "text-purple-700");
    }
    
  }
}
customElements.define("app-navbar", AppNavbar);