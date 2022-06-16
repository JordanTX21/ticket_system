<template>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">Filtros</div>

                <div class="card-body">
                    <form class="row" @submit.prevent="searchTicket">
                        <div class="col-md-4">
                            <label for="input_name" class="form-label text-white">Buscar</label>
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
                                <th scope="col">#</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col" class="th-options"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in list" :key="`ticket-${index}`">
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.menu.reservation_date }}</td>
                                <td>{{ item.menu.type_menu.name }}</td>
                                <td>{{ item.professor? (item.professor.person.document +" " + item.professor.person.name + item.student.person.last_name):(item.student.code +" "+ item.student.person.name + item.student.person.last_name) }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuTicket" data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuTicket">
                                            <li><a class="dropdown-item" @click.prevent="updateItem(item)"
                                                    href="#">Editar</a></li>
                                            <li><a class="dropdown-item" @click.prevent="deleteItem(item)"
                                                    href="#">Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
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
          ticket: {

            },
            list: []
        }
    },
    methods: {
        async searchTicket() {
            const result = await axios.post('/search-ticket', {...this.ticket}).then(response => {
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
            const result = await axios.delete(`/ticket/${item.id}`).then(response => {
                const data = response.data;
                if (data.value) {
                    this.searchTicket();
                }
                alert(data.message);
            }).catch(error => {
                console.log(error);
            });
        }
    }
}
</script>