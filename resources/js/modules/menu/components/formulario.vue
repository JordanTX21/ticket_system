<template>
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Menu
                    </div>
                </div>
                <div class="card-body">
                    <form class="row" @submit.prevent="preForm">
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
                        <div class="col-12 mt-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Lunes</th>
                                        <th scope="col" class="text-center">Martes</th>
                                        <th scope="col" class="text-center">Miercoles</th>
                                        <th scope="col" class="text-center">Jueves</th>
                                        <th scope="col" class="text-center">Viernes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in menu.data" :key="`menu1-${index}`">
                                        <td v-for="(itemm, indexx) in item" :key="`menu2-${index}-${indexx}`"
                                            class="text-center py-3">
                                            <div>{{ getTypeMenu(itemm.type_menu_id) }}</div>
                                            <br>
                                            <div>{{ itemm.reservation_date }}</div>
                                            <br>
                                            <div class="text-center">
                                                <input :id="`inputTicketStock-${index}-${indexx}`" type="number"
                                                    class="form-control d-inline-block" v-model="itemm.tickets_stock"
                                                    style="width: 70px;">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
            menu: {
                date_start: '2022-06-13',
                date_end: '2022-06-17',
                data: [],
            },
            update: false,
        }
    },
    methods: {
        getTypeMenu(type_menu_id) {
            if (type_menu_id === 1) {
                return "Desayuno";
            } else if (type_menu_id === 2) {
                return "Almuerzo";
            } else if (type_menu_id === 3) {
                return "Lonche";
            }
        },
        fillData() {
            let data = [];
            for (var i = 0; i < 3; i++) {
                let week = [];
                for (var j = 0; j < 5; j++) {
                    let date = this.menu.date_start
                    let year = date.split('-')[0]
                    let month = date.split('-')[1]
                    let day = (parseInt(date.split('-')[2]) + j).toString()

                    date = year + "-" + month + "-" + day
                    week.push(
                        {
                            type_menu_id: i + 1,
                            reservation_date: date,
                            tickets_stock: ''
                        }
                    );
                }
                data.push(week)
            }
            this.menu.data = data;
        },
        setItem(item) {
            this.menu = { ...item };
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
            const result = await axios.post('/create-menu-by-week', { ...this.menu }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.menu = {
                        date_start: '',
                        date_end: '',
                        data: [],
                    }
                }
                this.fillData();
                alert(data.message);
            })
        },
        async updateItem() {
            const result = await axios.put(`/menu/${this.menu.id}`, { ...this.menu }).then(response => {
                const data = response.data;
                if (data.value) {
                    this.menu = {
                        date_start: '',
                        date_end: '',
                        data: [],
                    }
                }
                this.$emit('updated');
                alert(data.message);
            })
        }
    },
    mounted() {
        this.fillData();
    }
}
</script>