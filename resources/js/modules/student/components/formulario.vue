<template>
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Estudiante
                    </div>
                </div>
                <div class="card-body">
                    <form class="row" @submit.prevent="preForm">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document">Documento</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="document" v-model="student.document">
                                    <button class="btn btn-outline-secondary" type="button"
                                        @click.prevent="searchPerson" id="button-addon2">Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Codigo de alumno</label>
                                <input type="text" class="form-control" id="code" v-model="student.code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cycle">Ciclo</label>
                                <select id="cycle" class="form-select" v-model="student.cycle">
                                    <option :value="1" selected>1</option>
                                    <option :value="2" selected>2</option>
                                    <option :value="3" selected>3</option>
                                    <option :value="4" selected>4</option>
                                    <option :value="5" selected>5</option>
                                    <option :value="6" selected>6</option>
                                    <option :value="7" selected>7</option>
                                    <option :value="8" selected>8</option>
                                    <option :value="9" selected>9</option>
                                    <option :value="10" selected>10</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="career">Carreras</label>
                                <select id="career" class="form-select" v-model="student.career">
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
                            <button type="submit" class="btn btn-success text-white">{{ update ? "Actualizar" :
                                    "Guardar"
                            }}</button>
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
            student: {
                person_id: '',
                document: '',
                code: '',
                cycle: '',
                career: '',
            },
            update: false,
        }
    },
    methods: {
        setItem(item) {
            this.student = { ...item };
            this.update = true;
        },
        async preForm() {
            if (!this.student.person_id) {
                alert("Debe buscar una persona");
                return;
            }
            if (this.update) {
                await this.updateItem();
            } else {
                await this.save();
            }
        },
        async save() {
            const result = await axios.post('/student', { ...this.student }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.student = {
                        person_id: '',
                        document: '',
                        code: '',
                        cycle: '',
                        career: '',
                    }
                }
                alert(data.message);
            })
        },
        async updateItem() {
            const result = await axios.put(`/student/${this.student.id}`, { ...this.student }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.student = {
                        person_id: '',
                        document: '',
                        code: '',
                        cycle: '',
                        career: '',
                    }
                }
                this.$emit('updated');
                alert(data.message);
            })
        },
        async searchPerson() {
            const result = await axios.post('/search-person', { ...this.student }).then(response => {
                const data = response.data;
                if (data.value) {
                    if (data.data.length === 0) {
                        alert("No se encontro ninguna persona");
                        return;
                    }
                    const person = data.data[0];
                    this.student.person_id = person.id;
                    alert("Persona encontrada");
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