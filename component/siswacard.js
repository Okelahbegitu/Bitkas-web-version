class siswacard extends HTMLElement {
    static get observedAttributes() {
        return ['nama', 'saldo']
    }

    connectedCallback() {
        this.render()
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue !== newValue) {
            this.render();
        }
    }

    render() {

        const nama = this.getAttribute("nama");
        const saldo = this.getAttribute("saldo");
        this.innerHTML = `
        <div class="${saldo >= 0 ? 'bg-green-400' : 'bg-red-400'} rounded-2xl py-5 px-2 my-5">
          <div class="flex items-start">
            <h3 class="text-2xl font-bold">${nama}</h3>
          </div>
          <div class="flex justify-end">
            <p>${saldo > 0 ? '+'  : '-'}${Math.abs(saldo)}</p>
          </div>
        </div>
    `
    }
}
customElements.define("siswa-card", siswacard)