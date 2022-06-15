<template>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">Filtros</div>

                <div class="card-body">
                    <form class="row" @submit.prevent="searchMenu">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_start">Fecha Inicio</label>
                                <input type="date" class="form-control" id="date_start" v-model="menu.date_start">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_end">Fecha Fin</label>
                                <input type="date" class="form-control" id="date_end" v-model="menu.date_end">
                            </div>
                        </div>
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
                                <th scope="col">Nombre</th>
                                <th scope="col">Documento</th>
                                <th scope="col">Habilitado</th>
                                <th scope="col" class="th-options"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in list" :key="`menu-${index}`">
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.name }}</td>
                                <td>{{ item.document }}</td>
                                <td>{{ item.status ? "SI" : "NO" }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuMenu">
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
            menu: {
                date_start: '2022-06-13',
                date_end: '2022-06-17',
            },
            list: []
        }
    },
    methods: {
        async searchMenu() {
            const result = await axios.post('/search-menu', {...this.menu}).then(response => {
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
                    this.searchMenu();
                }
                alert(data.message);
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        console.log('Componente menu mounted.');
    }
}
</script>