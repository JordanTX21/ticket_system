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
                                        <li class="breadcrumb-item active" aria-current="page">Ticket</li>
                                    </ol>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <Form ref="my-form" @updated="updatedFinish" v-if="false"></Form>
        <Table ref="my-table" @update-item="updateItem"></Table>
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
        updatedFinish() {
            this.$refs["my-table"].searchTicket();
        }
    },
}
</script>