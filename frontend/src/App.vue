<script setup>
import { onMounted, ref } from 'vue'

import api from './services/api'

import LoginView from './views/LoginView.vue'
import DashboardView from './views/DashboardView.vue'
import OwnerDashboardView from './views/OwnerDashboardView.vue'


// ========================================
// STATE AUTHENTICATION
// ========================================

const token = ref(
  localStorage.getItem('token')
)

const user = ref(null)

const checkingAuth = ref(
  Boolean(token.value)
)

const logoutProcessing = ref(false)


// ========================================
// HAPUS DATA LOGIN
// ========================================

const bersihkanLogin = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')

  token.value = null
  user.value = null
}


// ========================================
// LOGIN BERHASIL
// ========================================

const handleLoginSuccess = (dataUser) => {
  token.value =
    localStorage.getItem('token')

  user.value = dataUser
}


// ========================================
// CEK SESSION
// ========================================

const cekSession = async () => {
  if (!token.value) {
    checkingAuth.value = false
    return
  }

  try {
    const response =
      await api.get('/user')

    user.value =
      response.data.data

    // Perbarui data user termasuk role
    localStorage.setItem(
      'user',
      JSON.stringify(response.data.data)
    )
  } catch (error) {
    console.error(
      'Session tidak valid:',
      error
    )

    bersihkanLogin()
  } finally {
    checkingAuth.value = false
  }
}


// ========================================
// LOGOUT
// ========================================

const logout = async () => {
  if (logoutProcessing.value) {
    return
  }

  logoutProcessing.value = true

  try {
    await api.post('/logout')
  } catch (error) {
    console.error(
      'Logout error:',
      error
    )
  } finally {
    bersihkanLogin()

    logoutProcessing.value = false
  }
}


// ========================================
// SAAT APLIKASI DIBUKA
// ========================================

onMounted(() => {
  cekSession()
})
</script>


<template>

  <!-- ================================== -->
  <!-- CEK SESSION -->
  <!-- ================================== -->

  <div
    v-if="checkingAuth"
    class="auth-loading"
  >
    <div class="auth-loading-box">
      <div class="spinner"></div>

      <p>
        Memeriksa sesi login...
      </p>
    </div>
  </div>


  <!-- ================================== -->
  <!-- BELUM LOGIN -->
  <!-- ================================== -->

  <LoginView
    v-else-if="!token"
    @login-success="handleLoginSuccess"
  />


  <!-- ================================== -->
  <!-- SUDAH LOGIN -->
  <!-- ================================== -->

  <div v-else>

    <!-- NAVBAR -->
    <nav class="app-navbar">

      <div class="navbar-container">

        <div class="navbar-brand">
          <strong>
            Sistem Transaksi Agen
          </strong>

          <span>
            Mini Project UTS
          </span>
        </div>


        <div class="navbar-user">

          <div class="user-info">

            <span class="user-label">
              Login sebagai
            </span>

            <strong>
              {{
                user?.name ||
                'Pengguna'
              }}
            </strong>

            <span class="role-label">
              {{
                user?.role === 'admin'
                  ? 'Admin'
                  : 'Owner'
              }}
            </span>

          </div>


          <button
            type="button"
            class="logout-button"
            :disabled="logoutProcessing"
            @click="logout"
          >
            {{
              logoutProcessing
                ? 'Keluar...'
                : 'Logout'
            }}
          </button>

        </div>

      </div>

    </nav>


    <!-- ================================== -->
    <!-- ADMIN -->
    <!-- ================================== -->

    <DashboardView
      v-if="user?.role === 'admin'"
    />


    <!-- ================================== -->
    <!-- OWNER -->
    <!-- ================================== -->

    <OwnerDashboardView
      v-else-if="user?.role === 'owner'"
    />


    <!-- ================================== -->
    <!-- ROLE TIDAK DIKENAL -->
    <!-- ================================== -->

    <div
      v-else
      class="role-error"
    >
      Role pengguna tidak dikenali.
    </div>

  </div>

</template>


<style scoped>
.auth-loading {
  min-height: 100vh;

  display: flex;
  justify-content: center;
  align-items: center;

  background: #f3f4f6;
}

.auth-loading-box {
  text-align: center;
  color: #4b5563;
}

.spinner {
  width: 36px;
  height: 36px;

  margin: 0 auto 14px;

  border: 4px solid #d1d5db;
  border-top-color: #2563eb;
  border-radius: 50%;

  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.app-navbar {
  background: #111827;
  color: white;

  padding: 14px 24px;
}

.navbar-container {
  width: 100%;
  max-width: 1200px;

  margin: 0 auto;

  display: flex;
  justify-content: space-between;
  align-items: center;

  gap: 20px;
}

.navbar-brand {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.navbar-brand strong {
  font-size: 18px;
}

.navbar-brand span {
  color: #9ca3af;
  font-size: 12px;
}

.navbar-user {
  display: flex;
  align-items: center;
  gap: 16px;
}

.user-info {
  display: flex;
  flex-direction: column;

  text-align: right;
}

.user-label {
  color: #9ca3af;
  font-size: 10px;
}

.user-info strong {
  font-size: 14px;
}

.role-label {
  margin-top: 2px;

  color: #93c5fd;
  font-size: 11px;

  text-transform: uppercase;
}

.logout-button {
  border: none;
  border-radius: 7px;

  padding: 9px 14px;

  background: #dc2626;
  color: white;

  font-size: 13px;
  font-weight: 600;

  cursor: pointer;
}

.logout-button:hover:not(:disabled) {
  background: #b91c1c;
}

.logout-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.role-error {
  max-width: 1200px;

  margin: 30px auto;
  padding: 20px;

  background: #fee2e2;
  color: #991b1b;

  border-radius: 10px;
}

@media (max-width: 640px) {

  .app-navbar {
    padding: 12px 16px;
  }

  .navbar-user {
    gap: 8px;
  }

  .user-info {
    display: none;
  }
}
</style>