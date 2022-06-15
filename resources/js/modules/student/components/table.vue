<template>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">Filtros</div>

                <div class="card-body">
                    <form class="row" @submit.prevent="searchStudent">
                        <div class="col-md-4">
                            <label for="input_document" class="form-label">Código de Alumno</label>
                            <input type="text" class="form-control" id="input_document" v-model="student.code">
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
                                <th scope="col">Codigo</th>
                                <th scope="col">Carrera</th>
                                <th scope="col">Ciclo</th>
                                <th scope="col" class="th-options"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in list" :key="`student-${index}`">
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.person.name }}</td>
                                <td>{{ item.code }}</td>
                                <td>{{ item.career }}</td>
                                <td>{{ item.cycle }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuStudent" data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuStudent">
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
            student: {
                code: '',
            },
            list: []
        }
    },
    methods: {
        async searchStudent() {
            const result = await axios.post('/search-student', {...this.student}).then(response => {
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
            const result = await axios.delete(`/student/${item.id}`).then(response => {
                const data = response.data;
                if (data.value) {
                    this.searchStudent();
                }
                alert(data.message);
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        console.log('Componente student mounted.');
    }
}
</script>