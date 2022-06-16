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
            <div class="col-md-4">
              <div class="form-group">
                <label for="date_start">Fecha Inicio</label>
                <input type="date" class="form-control" id="date_start" v-model="menu.date_start">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="date_end">Fecha Fin</label>
                <input type="date" class="form-control" id="date_end" v-model="menu.date_end">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="date_end" class="text-white">calendario</label>
                <button type="button" @click.prevent="updateCalendar" class="btn btn-info d-block text-white">Actualizar
                  calendario
                </button>
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
              <button type="submit" class="btn btn-success text-white">{{
                  update ? "Actualizar" :
                      "Guardar"
                }}
              </button>
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
      list: {}
    }
  },
  methods: {
    async searchMenu() {
      this.$emit('search', {...this.menu});
      const result = await axios.post('/search-menu', {...this.menu}).then(response => {
        const data = response.data;
        if (data.value) {
          const dataList = data.data;
          for (const item of dataList) {
            this.list[item.reservation_date+"_"+item.type_menu_id] = item;
          }
        }
      }).catch(error => {
        console.log(error);
      });
    },
    difference(date1, date2) {
      const date1utc = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate());
      const date2utc = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate());
      const day = 1000 * 60 * 60 * 24;
      return (date2utc - date1utc) / day
    },
    updateCalendar() {
      const dias = [
        'lunes',
        'martes',
        'miércoles',
        'jueves',
        'viernes',
        'sábado',
        'domingo',
      ];
      const numeroDia = new Date(this.menu.date_start).getDay();
      const numeroDia2 = new Date(this.menu.date_end).getDay();
      const day1 = new Date(this.menu.date_start);
      const day2 = new Date(this.menu.date_end);

      const time_difference = this.difference(day1,day2)

      if (time_difference !== 4) {
        alert("La fecha no puede diferir de 1 semana");
        return;
      }

      if (dias[numeroDia] !== "lunes") {
        alert("La fecha inicial debe ser un lunes");
        return;
      }
      if (dias[numeroDia2] !== "viernes") {
        alert("La fecha final debe ser un viernes");
        return;
      }
      this.fillData()
    },
    getTypeMenu(type_menu_id) {
      if (type_menu_id === 1) {
        return "Desayuno";
      } else if (type_menu_id === 2) {
        return "Almuerzo";
      } else if (type_menu_id === 3) {
        return "Lonche";
      }
    },
    async fillData() {
      await this.searchMenu()

      let data = [];
      for (let i = 0; i < 3; i++) {
        let week = [];
        for (let j = 0; j < 5; j++) {
          let date = this.menu.date_start
          let year = date.split('-')[0]
          let month = date.split('-')[1]
          let day = (parseInt(date.split('-')[2]) + j).toString()

          date = year + "-" + month + "-" + day
          let tickets_stock = ''
          if(this.list[date+"_"+(i + 1)]){
            tickets_stock = this.list[date+"_"+(i + 1)].tickets_stock
          }

          week.push(
              {
                type_menu_id: i + 1,
                reservation_date: date,
                tickets_stock: tickets_stock
              }
          );
        }
        data.push(week)
      }
      this.menu.data = data;
    },
    setItem(item) {
      this.menu = {...item};
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
      const result = await axios.post('/create-menu-by-week', {...this.menu}).then(response => {
        const data = response.data;
        if (data.value) {
          this.menu = {
            date_start: '2022-06-13',
            date_end: '2022-06-17',
            data: [],
          }
          this.$emit('updated', {...this.menu});
          this.fillData();
        }
        alert(data.message);
      })
    },
    async updateItem() {
      const result = await axios.put(`/menu/${this.menu.id}`, {...this.menu}).then(response => {
        const data = response.data;
        if (data.value) {
          this.menu = {
            date_start: '2022-06-13',
            date_end: '2022-06-17',
            data: [],
          }
        }
        this.$emit('updated', {...this.menu});
        alert(data.message);
      })
    }
  },
  mounted() {
    this.fillData();
  }
}
</script>