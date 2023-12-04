<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const create = useForm({
    apartment_id: '',
    title: '',
    content: '',
    user_id: '',
    is_owner: '1',   // cause role have not existed yet -> auto 1
})

export default {
  components: { AuthenticatedLayout },
  props: {
    messages: Array,
    user: Object,
    },
    data() {
        return {
        create: create,
        detailInfo: {},
        };
    },

  methods: {
    viewDetails(message, user) {
      this.detailInfo = {
        fullName: user.name,
        phoneNumber: user.phone,
        email: user.email,
        title: message.title,
        content: message.content,
      };

      // Mở modal chi tiết
      $('#detailModal').modal('show');
    },

    confirmDelete(id) {
      this.idToDelete = id;
      $('#confirmDeleteModal').modal('show');
    },

    deleteConfirmed() {
      // Thực hiện xóa với id đã được lưu
      const id = this.idToDelete;
      const url = route("messages.destroy", { id: id });
        Inertia.delete(url);
        window.location.reload();
      console.log(`Deleting item with id ${id}`);

      // Sau khi xóa, đóng modal xác nhận
      $('#confirmDeleteModal').modal('hide');
    },

    openDetailModal(message, user) {
      this.detailInfo = {
        fullName: user.name,
        phoneNumber: user.phone,
        email: user.email,
        title: message.title,
        content: message.content,
      };
      $('#detailModal').modal('show');
    },

    closeDetailModal() {
      $('#detailModal').modal('hide');
    },

    openCreateModal() {
      // Mở modal tạo mới
      $('#createModal').modal('show');
    },

    closeCreateModal() {
      // Đóng modal tạo mới
      $('#createModal').modal('hide');
    },

    sendCreate(user) {
        this.create.user_id = user.id;
        create.post(route("messages.store"), {
                onFinish: () => {
                    this.create = {
                        'apartment_id' : '',
                        'title' : '',
                        'content' : '',
                    };
                    this.closeCreateModal();
                }
            });
      console.log('Sending create data:', this.create);
    },
  },
  components: {AuthenticatedLayout}
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="content-wrapper">
      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">List of send messages</h1>
            </div>
                <!-- button create -->
                                    <div class="col-sm-6">
                <div class="d-flex justify-content-end">
                     <!-- Button to trigger modal -->
                     <button type="button" class="btn btn-primary" @click="openCreateModal" style="margin-right: 200px;background-color: green;">
                      Create
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
          <!--  -->
          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title"></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>Number</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Dữ liệu -->
                    <tr v-for="(message, index) in messages" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ message.title }}</td>
                      <td>{{ message.content }}</td>
                      <td>
                        <!-- Các action -->
                        <button @click="viewDetails(message, user)" class="btn btn-primary btn-sm">Detail</button>
                        <button @click="confirmDelete(message.id)" class="btn btn-danger btn-sm">Delete</button>
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

      <!-- Modal create -->
      <div class="modal" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title ">Create Form</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeCreateModal">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Form cretae -->
              <form @submit.prevent="sendCreate(user)">
                <div class="form-group">
                  <label for="apartment_id">Apartment:</label>
                  <input type="text" class="form-control" id="apartment_id" v-model="create.apartment_id" >
                </div>
                <div class="form-group">
                  <label for="title">Title:</label>
                  <input type="text" class="form-control" id="title" v-model="create.title">

                </div>
                <div class="form-group">
                  <label for="content">Content:</label>
                  <textarea class="form-control" id="content" v-model="create.content"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeCreateModal">Close</button>
              </form>
              <!-- End Form Reply -->
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Reply -->

      <!-- Modal Detail -->

  <div class="modal" id="detailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Message Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeDetailModal">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <dl>
                <dt>Full Name:</dt>
                <dd>{{ detailInfo.fullName }}</dd>

                <dt>Phone Number:</dt>
                <dd>{{ detailInfo.phoneNumber }}</dd>

                <dt>Email:</dt>
                <dd>{{ detailInfo.email }}</dd>

                <dt>Title:</dt>
                <dd>{{ detailInfo.title }}</dd>

                <dt>Content:</dt>
                <dd>{{ detailInfo.content }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      <!--End modal detail-->


        <!-- Modal Confirm Delete -->
<div class="modal" id="confirmDeleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" @click="deleteConfirmed" style="background-color: brown;">Yes, delete</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Confirm Delete -->

    </div>
    <!-- /.content-wrapper -->
  </AuthenticatedLayout>
</template>

<style scoped>

.table th,
.table td {
  text-align: center;
}

.table th {
  background-color: #f8f9fa;
}

.btn-group {
  margin-right: 5px;
}

/* CSS cho nút "close" và "send" */
#createModal .btn-primary{
  background-color: #007bff; /* Màu nền */
  border-color: #007bff; /* Màu viền */
  color: #fff; /* Màu chữ */
}
#createModal .btn-secondary {
  background-color: #cbcbcb; /* Màu nền */
  border-color: #cbcbcb; /* Màu viền */
  color: #fff; /* Màu chữ */
}


/* CSS cho khung bo tròn */
#createModal .form-group input[type="text"],
#createModal .form-group input[type="text"]:focus,
#createModal .form-group textarea,
#createModal .form-group textarea:focus {
  border-radius: 10px; /* Độ bo tròn */
  padding: 10px; /* Khoảng cách bên trong ô nhập liệu */
  border: 1px solid #ced4da; /* Viền */
}

#replyModal .modal-header {
  background-color: #198754; /* Màu chữ */
}

/* CSS cho modal chi tiết */
#detailModal .modal-content {
  /* Tùy chỉnh kích thước, màu nền, hoặc bất kỳ thuộc tính CSS nào khác cho nội dung của modal */
  width: 80%; /* Đặt chiều rộng của modal */
  background-color: #f8f9fa; /* Màu nền của modal */
}

#detailModal .modal-header {
  /* Tùy chỉnh header của modal */
  background-color: #007bff; /* Màu nền header */
  color: #fff; /* Màu chữ của header */
}

#detailModal .modal-title {
  /* Tùy chỉnh tiêu đề của modal */
  font-size: 1.5rem; /* Đặt kích thước chữ cho tiêu đề */

}

#detailModal .modal-body {
  /* Tùy chỉnh phần thân của modal */
  padding: 20px; /* Thêm padding cho phần thân */
}

#detailModal .btn-primary {
  /* Tùy chỉnh nút "Close" trong modal */
  background-color: #007bff; /* Màu nền nút */
  border-color: #007bff; /* Màu viền nút */
  color: #fff; /* Màu chữ của nút */
}
</style>
