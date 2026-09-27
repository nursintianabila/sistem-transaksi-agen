<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

import SummaryCards from '../components/SummaryCards.vue'

const transaksi = ref([])
const loading = ref(true)
const error = ref('')

const ambilTransaksi = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/transaksi')

    transaksi.value = Array.isArray(response.data.data)
      ? response.data.data
      : []
  } catch (err) {
    console.error('Gagal mengambil transaksi:', err)

    error.value = 'Gagal mengambil data transaksi.'
  } finally {
    loading.value = false
  }
}

const formatRupiah = (angka) => {
  return Number(angka || 0).toLocaleString('id-ID')
}

const formatTanggal = (tanggal) => {
  if (!tanggal) {
    return '-'
  }

  const bagian = tanggal.split('-')

  if (bagian.length !== 3) {
    return tanggal
  }

  return `${bagian[2]}/${bagian[1]}/${bagian[0]}`
}

onMounted(() => {
  ambilTransaksi()
})
</script>

<template>
  <main class="owner-dashboard">
    <div class="owner-container">

      <!-- HEADER -->
      <div class="owner-header">
        <div>
          <h1>Dashboard Owner</h1>

          <p>
            Ringkasan dan daftar seluruh transaksi agen.
          </p>
        </div>

        <span class="read-only-badge">
          Read Only
        </span>
      </div>


      <!-- LOADING -->
      <div
        v-if="loading"
        class="status-box"
      >
        Memuat data transaksi...
      </div>


      <!-- ERROR -->
      <div
        v-else-if="error"
        class="error-box"
      >
        {{ error }}
      </div>


      <template v-else>

        <!-- ========================= -->
        <!-- RINGKASAN -->
        <!-- ========================= -->

        <section class="section">
          <div class="section-header">
            <div>
              <h2>Ringkasan Transaksi</h2>

              <p>
                Informasi keseluruhan transaksi agen.
              </p>
            </div>
          </div>

          <SummaryCards
            :transaksi="transaksi"
          />
        </section>


        <!-- ========================= -->
        <!-- DAFTAR TRANSAKSI -->
        <!-- ========================= -->

        <section class="section">
          <div class="section-header">
            <div>
              <h2>Daftar Transaksi</h2>

              <p>
                Seluruh transaksi yang telah tercatat.
              </p>
            </div>
          </div>


          <!-- JIKA DATA KOSONG -->
          <div
            v-if="transaksi.length === 0"
            class="empty-box"
          >
            Belum ada data transaksi.
          </div>


          <!-- TABLE -->
          <div
            v-else
            class="table-wrapper"
          >
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Jenis Transaksi</th>
                  <th>Nama Pelanggan</th>
                  <th>No. Rekening / Pembayaran</th>
                  <th>Nominal</th>
                  <th>Biaya Admin</th>
                  <th>Total</th>
                  <th>Tanggal</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in transaksi"
                  :key="item.id"
                >
                  <td>
                    {{ index + 1 }}
                  </td>

                  <td>
                    {{ item.jenis_transaksi }}
                  </td>

                  <td>
                    {{ item.nama_pelanggan }}
                  </td>

                  <td>
                    {{ item.nomor_tujuan || '-' }}
                  </td>

                  <td>
                    Rp
                    {{ formatRupiah(item.nominal) }}
                  </td>

                  <td>
                    Rp
                    {{ formatRupiah(item.biaya_admin) }}
                  </td>

                  <td class="total-column">
                    Rp
                    {{ formatRupiah(item.total) }}
                  </td>

                  <td>
                    {{
                      formatTanggal(
                        item.tanggal_transaksi
                      )
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </section>

      </template>

    </div>
  </main>
</template>

<style scoped>
.owner-dashboard {
  min-height: calc(100vh - 70px);
  background: #f3f4f6;
  padding: 30px 20px;
}

.owner-container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.owner-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;

  margin-bottom: 28px;
}

.owner-header h1 {
  margin: 0 0 6px;
  color: #111827;
  font-size: 28px;
}

.owner-header p {
  margin: 0;
  color: #6b7280;
}

.read-only-badge {
  display: inline-block;

  padding: 7px 12px;

  border-radius: 999px;

  background: #e0e7ff;
  color: #3730a3;

  font-size: 12px;
  font-weight: 700;
}

.section {
  margin-bottom: 28px;
}

.section-header {
  margin-bottom: 16px;
}

.section-header h2 {
  margin: 0 0 5px;
  color: #111827;
  font-size: 20px;
}

.section-header p {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}

.status-box,
.error-box,
.empty-box {
  padding: 18px;
  border-radius: 10px;
}

.status-box,
.empty-box {
  background: white;
  color: #6b7280;
}

.error-box {
  background: #fee2e2;
  color: #991b1b;
}

.table-wrapper {
  overflow-x: auto;

  background: white;
  border-radius: 12px;

  box-shadow:
    0 2px 8px rgba(0, 0, 0, 0.06);
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 950px;
}

thead {
  background: #f9fafb;
}

th,
td {
  padding: 13px 14px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
  font-size: 13px;
}

th {
  color: #374151;
  font-weight: 700;
}

td {
  color: #4b5563;
}

tbody tr:hover {
  background: #f9fafb;
}

.total-column {
  font-weight: 700;
  color: #111827;
}

@media (max-width: 640px) {
  .owner-dashboard {
    padding: 20px 12px;
  }

  .owner-header {
    align-items: flex-start;
  }

  .owner-header h1 {
    font-size: 23px;
  }
}
</style>