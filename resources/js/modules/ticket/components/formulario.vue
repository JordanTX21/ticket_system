<template>
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Persona
                    </div>
                </div>
                <div class="card-body">
                    <form class="row" @submit.prevent="preForm">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document">Documento</label>
                                <input type="text" class="form-control" id="document" v-model="person.document">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" v-model="person.name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Apellido Paterno</label>
                                <input type="text" class="form-control" id="last_name" v-model="person.last_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sur_name">Apellido Materno</label>
                                <input type="text" class="form-control" id="sur_name" v-model="person.sur_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Habilitado</label>
                                <select id="status" class="form-select" v-model="person.status">
                                    <option :value="1" selected>Si</option>
                                    <option :value="0" selected>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-16">
                            <label class="form-label text-white d-block">Guardar</label>
                            <button type="submit" class="btn btn-success text-white">{{update?"Actualizar":"Guardar"}}</button>
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
            person: {
                name: '',
                document: '',
                last_name: '',
                sur_name: '',
                status: 1,
            },
            update: false,
        }
    },
    methods: {
        setItem(item) {
            this.person = {...item};
            this.update = true;
        },
        async preForm(){
            if(this.update){
                await this.updateItem();
            }else{
                await this.save();
            }
        },
        async save() {
            const result = await axios.post('/person', {...this.person}).then(response => {
                const data = response.data; 
                if (data.value) {
                    this.person = {
                        name: '',
                        document: '',
                        last_name: '',
                        sur_name: '',
                        status: 1,
                    }
                }
                alert(data.message);
            })
        },
        async updateItem() {
            const result = await axios.put(`/person/${this.person.id}`, {...this.person}).then(response => {
                const data = response.data; 
                if (data.value) {
                    this.person = {
                        name: '',
                        document: '',
                        last_name: '',
                        sur_name: '',
                        status: 1,
                    }
                }
                this.$emit('updated');
                alert(data.message);
            })
        }
    }
}
</script>