<script setup>
import {
  onMounted,
  ref,
  reactive,
  computed,
} from 'vue'

import api from '../services/api'

import SummaryCards from '../components/SummaryCards.vue'
import TransaksiForm from '../components/TransaksiForm.vue'
import TransaksiTable from '../components/TransaksiTable.vue'


// =====================================
// STATE DATA
// =====================================

const transaksi = ref([])

const loading = ref(true)
const processing = ref(false)

const dataError = ref('')
const formError = ref('')
const message = ref('')


// =====================================
// SEARCH & FILTER
// =====================================

const pencarian = ref('')
const filterJenis = ref('')
const filterTanggal = ref('')


// =====================================
// DATA YANG DITAMPILKAN
// =====================================

const transaksiTampil = computed(() => {
  const keyword =
    pencarian.value
      .trim()
      .toLowerCase()

  return transaksi.value.filter((item) => {

    // ---------------------------------
    // SEARCH
    // ---------------------------------

    const nama =
      String(
        item.nama_pelanggan || ''
      ).toLowerCase()

    const nomor =
      String(
        item.nomor_tujuan || ''
      ).toLowerCase()

    const cocokPencarian =
      !keyword ||
      nama.includes(keyword) ||
      nomor.includes(keyword)


    // ---------------------------------
    // FILTER JENIS
    // ---------------------------------

    const cocokJenis =
      !filterJenis.value ||
      item.jenis_transaksi ===
        filterJenis.value


    // ---------------------------------
    // FILTER TANGGAL
    // ---------------------------------

    const cocokTanggal =
      !filterTanggal.value ||
      item.tanggal_transaksi ===
        filterTanggal.value


    // ---------------------------------
    // HASIL
    // ---------------------------------

    return (
      cocokPencarian &&
      cocokJenis &&
      cocokTanggal
    )
  })
})


// =====================================
// APAKAH FILTER AKTIF?
// =====================================

const filterAktif = computed(() => {
  return (
    pencarian.value ||
    filterJenis.value ||
    filterTanggal.value
  )
})


// =====================================
// STATE EDIT
// =====================================

const sedangEdit = ref(false)
const editId = ref(null)


// =====================================
// FORM
// =====================================

const form = reactive({
  jenis_transaksi: '',
  nama_pelanggan: '',
  nomor_tujuan: '',
  nominal: '',
  biaya_admin: '',
  tanggal_transaksi: '',
})


// =====================================
// AMBIL DATA
// =====================================

const ambilTransaksi = async () => {
  try {
    loading.value = true
    dataError.value = ''

    const response =
      await api.get('/transaksi')

    transaksi.value =
      Array.isArray(response.data.data)
        ? response.data.data
        : []

  } catch (err) {
    console.error(
      'Gagal mengambil transaksi:',
      err
    )

    transaksi.value = []

    dataError.value =
      'Gagal mengambil data transaksi.'

  } finally {
    loading.value = false
  }
}


// =====================================
// RESET FORM
// =====================================

const resetForm = () => {
  form.jenis_transaksi = ''
  form.nama_pelanggan = ''
  form.nomor_tujuan = ''
  form.nominal = ''
  form.biaya_admin = ''
  form.tanggal_transaksi = ''

  sedangEdit.value = false
  editId.value = null

  formError.value = ''
}


// =====================================
// RESET FILTER
// =====================================

const resetFilter = () => {
  pencarian.value = ''
  filterJenis.value = ''
  filterTanggal.value = ''
}


// =====================================
// VALIDASI FORM
// =====================================

const validasiForm = () => {
  if (!form.jenis_transaksi) {
    return 'Jenis transaksi wajib dipilih.'
  }

  if (
    !String(
      form.nama_pelanggan
    ).trim()
  ) {
    return 'Nama pelanggan wajib diisi.'
  }

  if (
    !String(
      form.nomor_tujuan
    ).trim()
  ) {
    return 'Nomor rekening / pembayaran wajib diisi.'
  }

  if (
    !/^\d+$/.test(
      String(form.nomor_tujuan)
    )
  ) {
    return 'Nomor rekening / pembayaran hanya boleh berisi angka.'
  }

  if (
    !form.nominal ||
    Number(form.nominal) <= 0
  ) {
    return 'Nominal harus lebih dari Rp 0.'
  }

  if (
    form.biaya_admin === '' ||
    Number(form.biaya_admin) < 0
  ) {
    return 'Biaya admin tidak boleh negatif.'
  }

  if (!form.tanggal_transaksi) {
    return 'Tanggal transaksi wajib dipilih.'
  }

  return null
}


// =====================================
// PAYLOAD
// =====================================

const buatPayload = () => {
  return {
    jenis_transaksi:
      form.jenis_transaksi,

    nama_pelanggan:
      String(
        form.nama_pelanggan
      ).trim(),

    nomor_tujuan:
      String(
        form.nomor_tujuan
      ).trim(),

    nominal:
      Number(form.nominal),

    biaya_admin:
      Number(form.biaya_admin),

    tanggal_transaksi:
      form.tanggal_transaksi,
  }
}


// =====================================
// SIMPAN / UPDATE
// =====================================

const simpanTransaksi = async () => {
  if (processing.value) {
    return
  }

  formError.value = ''
  message.value = ''

  const hasilValidasi =
    validasiForm()

  if (hasilValidasi) {
    formError.value =
      hasilValidasi

    return
  }

  try {
    processing.value = true

    const data =
      buatPayload()

    if (
      sedangEdit.value &&
      editId.value
    ) {
      await api.put(
        `/transaksi/${editId.value}`,
        data
      )

      resetForm()

      message.value =
        'Transaksi berhasil diperbarui.'

    } else {
      await api.post(
        '/transaksi',
        data
      )

      resetForm()

      message.value =
        'Transaksi berhasil disimpan.'
    }

    await ambilTransaksi()

  } catch (err) {
    console.error(
      'Gagal memproses transaksi:',
      err
    )

    console.error(
      'Response Laravel:',
      err.response?.data
    )

    if (
      err.response?.status === 422 &&
      err.response?.data?.errors
    ) {
      formError.value =
        Object.values(
          err.response.data.errors
        )
          .flat()
          .join(' ')

    } else if (!err.response) {
      formError.value =
        'Tidak dapat terhubung ke server Laravel.'

    } else {
      formError.value =
        err.response?.data?.message ||
        'Gagal memproses transaksi.'
    }

  } finally {
    processing.value = false
  }
}


// =====================================
// EDIT
// =====================================

const editTransaksi = (item) => {
  message.value = ''
  formError.value = ''

  sedangEdit.value = true
  editId.value = item.id

  form.jenis_transaksi =
    item.jenis_transaksi || ''

  form.nama_pelanggan =
    item.nama_pelanggan || ''

  form.nomor_tujuan =
    item.nomor_tujuan || ''

  form.nominal =
    String(item.nominal ?? '')

  form.biaya_admin =
    String(item.biaya_admin ?? '')

  form.tanggal_transaksi =
    item.tanggal_transaksi || ''

  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}


// =====================================
// HAPUS
// =====================================

const hapusTransaksi = async (item) => {
  if (processing.value) {
    return
  }

  const yakin =
    window.confirm(
      `Apakah Anda yakin ingin menghapus transaksi milik ${item.nama_pelanggan}?`
    )

  if (!yakin) {
    return
  }

  try {
    processing.value = true

    message.value = ''
    formError.value = ''

    await api.delete(
      `/transaksi/${item.id}`
    )

    if (
      sedangEdit.value &&
      editId.value === item.id
    ) {
      resetForm()
    }

    message.value =
      'Transaksi berhasil dihapus.'

    await ambilTransaksi()

  } catch (err) {
    console.error(
      'Gagal menghapus transaksi:',
      err
    )

    formError.value =
      err.response?.data?.message ||
      'Gagal menghapus transaksi.'

  } finally {
    processing.value = false
  }
}


// =====================================
// MOUNT
// =====================================

onMounted(() => {
  ambilTransaksi()
})
</script>


<template>
  <div class="dashboard">

    <!-- =================================
         HEADER
    ================================== -->

    <div class="page-header">

      <div>
        <h2>
          Dashboard Transaksi
        </h2>

        <p>
          Kelola dan pantau seluruh transaksi agen.
        </p>
      </div>

      <div
        v-if="!dataError"
        class="backend-status"
      >
        <span class="status-dot"></span>

        Backend Terhubung
      </div>

      <div
        v-else
        class="backend-status backend-error"
      >
        <span
          class="status-dot status-dot-error"
        ></span>

        Backend Bermasalah
      </div>

    </div>


    <!-- =================================
         FORM
    ================================== -->

    <TransaksiForm
      :form="form"
      :sedang-edit="sedangEdit"
      :processing="processing"
      :message="message"
      :error="formError"
      @submit="simpanTransaksi"
      @reset="resetForm"
    />


    <!-- =================================
         LOADING
    ================================== -->

    <div
      v-if="loading"
      class="info-card"
    >
      Memuat data transaksi...
    </div>


    <!-- =================================
         ERROR
    ================================== -->

    <div
      v-else-if="dataError"
      class="error-card"
    >
      {{ dataError }}
    </div>


    <!-- =================================
         DATA
    ================================== -->

    <div v-else>

      <!-- SUMMARY -->

      <SummaryCards
        :transaksi="transaksi"
      />


      <!-- =================================
           SEARCH & FILTER
      ================================== -->

      <div class="filter-card">

        <div class="filter-header">

          <div>
            <h3>
              Cari & Filter Transaksi
            </h3>

            <p>
              Cari pelanggan dan filter berdasarkan
              jenis serta tanggal transaksi.
            </p>
          </div>

          <div class="hasil-filter">
            {{ transaksiTampil.length }}
            dari
            {{ transaksi.length }}
            transaksi
          </div>

        </div>


        <!-- FILTER GRID -->

        <div class="filter-grid">

          <!-- SEARCH -->

          <div class="filter-group search-group">

            <label>
              Pencarian
            </label>

            <div class="search-input-wrapper">

              <span class="search-icon">
                🔍
              </span>

              <input
                v-model="pencarian"
                type="text"
                placeholder="Nama pelanggan atau nomor tujuan..."
              />

            </div>

          </div>


          <!-- JENIS TRANSAKSI -->

          <div class="filter-group">

            <label>
              Jenis Transaksi
            </label>

            <select
              v-model="filterJenis"
            >

              <option value="">
                Semua Jenis
              </option>

              <option value="Transfer">
                Transfer
              </option>

              <option value="Tarik Tunai">
                Tarik Tunai
              </option>

              <option value="Setor Tunai">
                Setor Tunai
              </option>

              <option value="Pembayaran">
                Pembayaran
              </option>

            </select>

          </div>


          <!-- TANGGAL -->

          <div class="filter-group">

            <label>
              Tanggal Transaksi
            </label>

            <input
              v-model="filterTanggal"
              type="date"
            />

          </div>

        </div>


        <!-- FILTER AKTIF -->

        <div
          v-if="filterAktif"
          class="filter-actions"
        >

          <div class="filter-status">

            <span v-if="pencarian">
              Pencarian:
              <strong>
                "{{ pencarian }}"
              </strong>
            </span>

            <span v-if="filterJenis">
              Jenis:
              <strong>
                {{ filterJenis }}
              </strong>
            </span>

            <span v-if="filterTanggal">
              Tanggal:
              <strong>
                {{ filterTanggal }}
              </strong>
            </span>

          </div>


          <button
            type="button"
            class="btn-reset-filter"
            @click="resetFilter"
          >
            Reset Filter
          </button>

        </div>


        <!-- DATA TIDAK DITEMUKAN -->

        <div
          v-if="
            transaksiTampil.length === 0 &&
            filterAktif
          "
          class="filter-empty"
        >
          Tidak ada transaksi yang sesuai
          dengan pencarian atau filter yang dipilih.
        </div>

      </div>


      <!-- =================================
           TABLE
      ================================== -->

      <TransaksiTable
        :transaksi="transaksiTampil"
        @edit="editTransaksi"
        @hapus="hapusTransaksi"
      />

    </div>

  </div>
</template>


<style scoped>
.dashboard {
  max-width: 1400px;

  margin: 0 auto;

  padding: 30px;
}


/* ================================
   HEADER
================================ */

.page-header {
  display: flex;

  justify-content: space-between;
  align-items: center;

  gap: 20px;

  padding: 22px 25px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);
}

.page-header h2 {
  margin: 0;

  color: #111827;

  font-size: 24px;
}

.page-header p {
  margin: 7px 0 0;

  color: #6b7280;

  font-size: 14px;
}


/* ================================
   BACKEND STATUS
================================ */

.backend-status {
  display: flex;

  align-items: center;

  gap: 8px;

  padding: 8px 13px;

  background-color: #ecfdf5;

  color: #047857;

  border-radius: 20px;

  font-size: 13px;

  font-weight: 600;

  white-space: nowrap;
}

.status-dot {
  width: 8px;

  height: 8px;

  background-color: #10b981;

  border-radius: 50%;
}

.backend-error {
  background-color: #fef2f2;

  color: #b91c1c;
}

.status-dot-error {
  background-color: #ef4444;
}


/* ================================
   INFO
================================ */

.info-card,
.error-card {
  margin-top: 25px;

  padding: 20px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);
}

.info-card {
  color: #2563eb;
}

.error-card {
  color: #dc2626;
}


/* ================================
   FILTER CARD
================================ */

.filter-card {
  margin-top: 25px;

  padding: 22px 25px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);
}

.filter-header {
  display: flex;

  justify-content: space-between;
  align-items: center;

  gap: 20px;

  margin-bottom: 20px;
}

.filter-header h3 {
  margin: 0;

  color: #1f2937;

  font-size: 17px;
}

.filter-header p {
  margin: 5px 0 0;

  color: #6b7280;

  font-size: 13px;
}

.hasil-filter {
  padding: 6px 11px;

  background-color: #eff6ff;

  color: #2563eb;

  border-radius: 20px;

  font-size: 12px;

  font-weight: 600;

  white-space: nowrap;
}


/* ================================
   FILTER GRID
================================ */

.filter-grid {
  display: grid;

  grid-template-columns:
    2fr 1fr 1fr;

  gap: 15px;

  align-items: end;
}

.filter-group {
  display: flex;

  flex-direction: column;

  gap: 7px;
}

.filter-group label {
  color: #374151;

  font-size: 12px;

  font-weight: 600;
}

.filter-group select,
.filter-group > input {
  width: 100%;

  height: 42px;

  padding: 10px 12px;

  border:
    1px solid #d1d5db;

  border-radius: 8px;

  background-color: white;

  color: #1f2937;

  outline: none;

  font-size: 14px;
}

.filter-group select:focus,
.filter-group > input:focus {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37, 99, 235, 0.1);
}


/* ================================
   SEARCH
================================ */

.search-input-wrapper {
  display: flex;

  align-items: center;

  height: 42px;

  border:
    1px solid #d1d5db;

  border-radius: 8px;

  overflow: hidden;

  background-color: white;
}

.search-input-wrapper:focus-within {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37, 99, 235, 0.1);
}

.search-icon {
  padding-left: 13px;

  font-size: 15px;
}

.search-input-wrapper input {
  flex: 1;

  min-width: 0;

  height: 100%;

  padding: 10px 12px;

  border: none;

  outline: none;

  color: #1f2937;

  font-size: 14px;
}


/* ================================
   FILTER STATUS
================================ */

.filter-actions {
  display: flex;

  justify-content: space-between;
  align-items: center;

  gap: 15px;

  margin-top: 16px;

  padding-top: 16px;

  border-top:
    1px solid #e5e7eb;
}

.filter-status {
  display: flex;

  flex-wrap: wrap;

  gap: 10px;

  color: #6b7280;

  font-size: 12px;
}

.filter-status span {
  padding: 5px 8px;

  background-color: #f8fafc;

  border-radius: 5px;
}

.filter-status strong {
  color: #2563eb;
}

.btn-reset-filter {
  padding: 8px 12px;

  border: none;

  border-radius: 7px;

  background-color: #f3f4f6;

  color: #374151;

  cursor: pointer;

  font-size: 12px;

  font-weight: 600;

  white-space: nowrap;
}

.btn-reset-filter:hover {
  background-color: #e5e7eb;
}

.filter-empty {
  margin-top: 15px;

  padding: 12px 14px;

  background-color: #fffbeb;

  color: #92400e;

  border-radius: 7px;

  font-size: 13px;
}


/* ================================
   RESPONSIVE
================================ */

@media (max-width: 900px) {
  .filter-grid {
    grid-template-columns:
      1fr 1fr;
  }

  .search-group {
    grid-column:
      1 / -1;
  }
}

@media (max-width: 700px) {
  .dashboard {
    padding: 15px;
  }

  .page-header,
  .filter-header,
  .filter-actions {
    flex-direction: column;

    align-items: flex-start;
  }

  .page-header h2 {
    font-size: 20px;
  }

  .filter-card {
    padding: 18px;
  }

  .filter-grid {
    grid-template-columns: 1fr;
  }

  .search-group {
    grid-column: auto;
  }

  .btn-reset-filter {
    width: 100%;
  }
}
</style>