<script setup>
import { computed } from 'vue'

const props = defineProps({
  transaksi: {
    type: Array,
    default: () => [],
  },
})

const totalTransaksi = computed(() => {
  return props.transaksi.length
})

const totalNominal = computed(() => {
  return props.transaksi.reduce((total, item) => {
    return total + Number(item.nominal || 0)
  }, 0)
})

const totalBiayaAdmin = computed(() => {
  return props.transaksi.reduce((total, item) => {
    return total + Number(item.biaya_admin || 0)
  }, 0)
})

const totalKeseluruhan = computed(() => {
  return props.transaksi.reduce((total, item) => {
    return total + Number(item.total || 0)
  }, 0)
})

const formatRupiah = (angka) => {
  return Number(angka || 0).toLocaleString('id-ID')
}
</script>

<template>
  <div class="summary-section">

    <div class="summary-card">
      <div class="card-label">
        Total Transaksi
      </div>

      <div class="card-value">
        {{ totalTransaksi }}
      </div>

      <div class="card-description">
        Transaksi tersimpan
      </div>
    </div>

    <div class="summary-card">
      <div class="card-label">
        Total Nominal
      </div>

      <div class="card-value">
        Rp {{ formatRupiah(totalNominal) }}
      </div>

      <div class="card-description">
        Nominal seluruh transaksi
      </div>
    </div>

    <div class="summary-card">
      <div class="card-label">
        Total Biaya Admin
      </div>

      <div class="card-value">
        Rp {{ formatRupiah(totalBiayaAdmin) }}
      </div>

      <div class="card-description">
        Pendapatan biaya admin
      </div>
    </div>

    <div class="summary-card">
      <div class="card-label">
        Total Keseluruhan
      </div>

      <div class="card-value">
        Rp {{ formatRupiah(totalKeseluruhan) }}
      </div>

      <div class="card-description">
        Nominal + biaya admin
      </div>
    </div>

  </div>
</template>

<style scoped>
.summary-section {
  display: grid;

  grid-template-columns:
    repeat(4, minmax(0, 1fr));

  gap: 18px;

  margin-top: 25px;
}

.summary-card {
  padding: 22px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);

  border:
    1px solid #f1f5f9;
}

.card-label {
  margin-bottom: 12px;

  color: #6b7280;

  font-size: 13px;

  font-weight: 600;
}

.card-value {
  color: #1f2937;

  font-size: 23px;

  font-weight: 700;

  line-height: 1.3;
}

.card-description {
  margin-top: 9px;

  color: #9ca3af;

  font-size: 12px;
}

@media (max-width: 1100px) {
  .summary-section {
    grid-template-columns:
      repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 600px) {
  .summary-section {
    grid-template-columns: 1fr;
  }
}
</style>