export default function userManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        selectedUser: {
            id: null,
            name: '',
            email: '',
            role: '',
            opd_id: null,
            wilayah_id: null,
            password: '',
        },
        selectedKecamatanId: null,
        opds: [],
        wilayahs: [],

        get filteredWilayahsKecamatan() {
            return this.wilayahs.filter(w => w.tipe === 'kecamatan');
        },
        get filteredWilayahsKelurahan() {
            return this.wilayahs.filter(w => w.tipe === 'kelurahan' && w.parent_id === this.selectedKecamatanId);
        },

        initSelectedKecamatanId() {
            if (this.selectedUser.role === 'kelurahan') {
                const kel = this.wilayahs.find(w => w.id === this.selectedUser.wilayah_id);
                this.selectedKecamatanId = kel ? kel.parent_id : null;
            } else {
                this.selectedKecamatanId = null;
            }
        },

        validateForm() {
            if (!this.selectedUser.name) { alert('Nama wajib diisi'); return false; }
            if (!this.selectedUser.email) { alert('Email wajib diisi'); return false; }
            if (!this.selectedUser.role) { alert('Role wajib dipilih'); return false; }
            if (this.selectedUser.role !== 'admin' && !this.selectedUser.opd_id) {
                alert('OPD wajib dipilih untuk role ini');
                return false;
            }
            if ((this.selectedUser.role === 'kecamatan' || this.selectedUser.role === 'kelurahan') && !this.selectedUser.wilayah_id) {
                alert('Wilayah wajib dipilih');
                return false;
            }
            return true;
        },

        init() {
            this.opds = window.__opds || [];
            this.wilayahs = window.__wilayahs || [];
        }
    }
}