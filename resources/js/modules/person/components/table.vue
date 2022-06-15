<template>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">Filtros</div>

                <div class="card-body">
                    <form class="row" @submit.prevent="searchPerson">
                        <div class="col-md-4">
                            <label for="input_document" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="input_document" v-model="person.document">
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
                            <tr v-for="(item, index) in list" :key="`person-${index}`">
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.name }}</td>
                                <td>{{ item.document }}</td>
                                <td>{{ item.status ? "SI" : "NO" }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuPerson" data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuPerson">
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
            person: {
                document: '',
            },
            list: []
        }
    },
    methods: {
        async searchPerson() {
            const result = await axios.post('/search-person', {...this.person}).then(response => {
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
            const result = await axios.delete(`/person/${item.id}`).then(response => {
                const data = response.data;
                if (data.value) {
                    this.searchPerson();
                }
                alert(data.message);
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        console.log('Componente person mounted.');
    }
}
</script>