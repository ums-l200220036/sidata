export function waktuIndonesia() {
    return {
        tanggalWaktu: "",
        init() {
            this.update();
            setInterval(() => this.update(), 1000); // update setiap detik
        },
        update() {
            const tanggalOptions = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            const jamOptions = {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: false,
            };
            const now = new Date();
            const tanggal = new Intl.DateTimeFormat(
                "id-ID",
                tanggalOptions
            ).format(now);
            const jam = new Intl.DateTimeFormat("id-ID", jamOptions).format(
                now
            );

            this.tanggalWaktu = `
                <i class="fa-solid fa-calendar-days text-[#FE482B] mr-1"></i> ${tanggal}
                &nbsp;
                <i class="fa-solid fa-clock text-[#FE482B] mr-1"></i> ${jam}
            `;
        },
    };
}
