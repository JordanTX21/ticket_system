<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <nav class="bd-subnavbar py-2" aria-label="Secondary navigation">
                            <div class="container-xxl d-flex align-items-md-center">
                                <div class="bd-search position-relative me-auto">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">Operaciones</li>
                                        <li class="breadcrumb-item active" aria-current="page">Menu</li>
                                    </ol>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <Form ref="my-form" @updated="updatedFinish" @search="searchMenu"></Form>
        <Table ref="my-table" @update-item="updateItem" @deleted="deleted"></Table>
    </div>
</template>

<script>
import Table from './components/table.vue'
import Form from './components/formulario.vue'

export default {
    components: {
        Table,
        Form
    },
    data() {
        return {
        }
    },
    methods: {
        updateItem(item) {
            this.$refs["my-form"].setItem(item);
        },
        updatedFinish(menu) {
            this.$refs["my-table"].searchMenu(menu);
        },
        deleted() {
            this.$refs["my-form"].fillData();
        },
        searchMenu(menu){
            this.$refs["my-table"].searchMenu(menu);
        }
    },
  mounted() {
    this.$refs["my-table"].searchMenu({
      date_start: '2022-06-13',
      date_end: '2022-06-17',
    });
  }
}
</script>