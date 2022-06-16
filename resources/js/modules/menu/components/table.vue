<template>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4" v-if="false">
            <div class="card">
                <div class="card-header">Filtros</div>

                <div class="card-body">
                    <form class="row" @submit.prevent="searchMenu">
                        <div class="col-md-4">
                            <label class="form-label text-white">Buscar</label>
                            <button type="submit" class="btn btn-primary text-white d-block">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">Busqueda</div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Tickets</th>
                                <th scope="col">Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in list" :key="`menu-${index}`">
                                <td>{{ item.reservation_date }}</td>
                                <td>{{ item.tickets_stock }}</td>
                                <td>{{ item.type_menu.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            menu: {
                date_start: '2022-06-13',
                date_end: '2022-06-17',
            },
            list: []
        }
    },
    methods: {
        async searchMenu(menu) {
            const result = await axios.post('/search-menu', {...menu}).then(response => {
                const data = response.data;
                if (data.value) {
                    this.list = data.data;
                }else{
                    alert(data.message);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        updateItem(item) {
            this.$emit("update-item", item);
        },
        async deleteItem(item) {
            const result = await axios.delete(`/menu/${item.id}`).then(response => {
                const data = response.data;
                if (data.value) {
                  this.$emit('deleted')
                    this.searchMenu();
                }
                alert(data.message);
            }).catch(error => {
                console.log(error);
            });
        }
    }
}
</script>