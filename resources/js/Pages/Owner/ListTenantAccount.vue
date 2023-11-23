<template>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List of Tenants</h1>
          </div>
          <!-- button create -->
          <div class="col-sm-6">
          <div class="d-flex justify-content-end">
            <!-- Button to trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrationModal" style="margin-right: 140px">
              Add Tenant
            </button>
          </div>
          </div>
          <!--end button-->
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- TABLE: LATEST ORDERS -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">List</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th>Number</th>
                    <th>Full name</th>
                    <th>Phone number</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dữ liệu sẽ được hiển thị ở đây -->
                  <tr v-for="(tenant, index) in tenants" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ tenant.name }}</td>
                    <td>{{ tenant.phone }}</td>
                    <td>{{ tenant.email }}</td>
                    <td>
                      <!-- Các action (ví dụ: xem chi tiết, xoá) sẽ được đặt ở đây -->
                      <button @click="viewDetails(tenant)" class="btn btn-primary btn-sm">Detail</button>
                      <button @click="deleteTenant(tenant.id)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
      <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
    <!-- Begin Detail Modal -->

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="detailModalLabel">Details of the tenant's account</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody>
          <tr>
            <th scope="row">Full name</th>
            <td>{{ selectedTenant.name }}</td>
          </tr>
          <tr>
            <th scope="row">Phone number</th>
            <td>{{ selectedTenant.phone }}</td>
          </tr>
          <tr>
            <th scope="row">Email</th>
            <td>{{ selectedTenant.email }}</td>
          </tr>
          <tr>
            <th scope="row">Verified at</th>
            <td>{{ selectedTenant.email_verified_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

  <!-- End  Modal Deatail -->

  <!-- Add Tenant Modal -->
  <div>
  <!-- Button to trigger modal -->


  <!-- Registration Modal -->
  <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registrationModalLabel">Registration Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form for registration -->
          <form @submit.prevent="createAccountTenant">
            <div class="mb-3">
              <label for="fullName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullName" v-model="newAccount.fullName" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" v-model="newAccount.email" required>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="phone" v-model="newAccount.phone" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" v-model="newAccount.password" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" v-model="newAccount.confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- End Add Tenant Modal -->


  </div>
  <!-- /.content-wrapper -->

</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

export default {
  props: {
  tenants: Array,
},
data() {
  return {
    selectedTenant: {},
    newAccount: {
      fullName: '',
      email: '',
      phone: '',
      password: '',
      confirmPassword: '',
    },
  };
},
methods: {
  viewDetails(tenant) {
    this.selectedTenant = tenant;
    $('#detailModal').modal('show');
  },
  deleteTenant(tenantId) {
    // Xử lý khi người dùng nhấn nút xoá
    console.log('Xoá người thuê có ID:', tenantId);
  },
  createAccountTenant() {
    this.newAccount._token = document.querySelector('meta[name="csrf-token"]').content;
    // Logic to create account
    axios.post('/tenant-accounts/web/submit-form', this.newAccount)
      .then(response => {
        console.log(response);
      })
      .catch(error => {
        console.log(error);
      });

    // Clear form after account creation
    this.newAccount = {
      fullName: '',
      email: '',
      phone: '',
      password: '',
      confirmPassword: '',
    };

    // Close the modal
    $('#registrationModal').modal('hide');
  },
},
};
</script>

<style>
@import url('https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css');
</style>