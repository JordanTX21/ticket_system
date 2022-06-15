<template>
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Profesor
                    </div>
                </div>
                <div class="card-body">
                    <form class="row" @submit.prevent="preForm">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document">Documento</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="document" v-model="professor.document">
                                    <button class="btn btn-outline-secondary" type="button"
                                        @click.prevent="searchPerson" id="button-addon2">Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="career">Carreras</label>
                                <select id="career" class="form-select" v-model="professor.career">
                                    <option value="Ing. Sistemas" selected>Ing. Sistemas</option>
                                    <option value="Ing. Mecanica" selected>Ing. Mecanica</option>
                                    <option value="Ing. Electronica" selected>Ing. Electrónica</option>
                                    <option value="Ing. Ambiental" selected>Ing. Ambiental</option>
                                    <option value="Administracion" selected>Administracion</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-16">
                            <label class="form-label text-white d-block">Guardar</label>
                            <button type="submit"
                                class="btn btn-success text-white">{{ update ? "Actualizar" : "Guardar" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            professor: {
                person_id: '',
                document: '',
                career: '',
            },
            update: false,
        }
    },
    methods: {
        setItem(item) {
            this.professor = { ...item };
            this.update = true;
        },
        async preForm() {
            if (this.update) {
                await this.updateItem();
            } else {
                await this.save();
            }
        },
        async save() {
            const result = await axios.post('/professor', { ...this.professor }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.professor = {
                        person_id: '',
                        document: '',
                        career: '',
                    }
                }
                alert(data.message);
            })
        },
        async updateItem() {
            const result = await axios.put(`/professor/${this.professor.id}`, { ...this.professor }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.professor = {
                        person_id: '',
                        document: '',
                        career: '',
                    }
                }
                this.$emit('updated');
                alert(data.message);
            })
        },
        async searchPerson() {
            const result = await axios.post('/search-person', { ...this.professor }).then(response => {
                const data = response.data;
                if (data.value) {
                    if (data.data.length === 0) {
                        alert("No se encontro ningun profesor");
                        return;
                    }
                    const person = data.data[0];
                    this.professor.person_id = person.id;
                    alert("profesor encontrada");
                } else {
                    alert(data.message);
                }
            }).catch(error => {
                console.log(error);
            });
        },
    }
}
</script>