<template>
  <div class="row justify-content-center">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header">Filtros</div>

        <div class="card-body">
          <form class="row" @submit.prevent="searchTicket">
            <div class="col-md-4">
              <div class="form-group">
                <label for="date_start">Fecha Inicio</label>
                <input type="date" class="form-control" id="date_start" v-model="ticket.date_start">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="date_end">Fecha Fin</label>
                <input type="date" class="form-control" id="date_end" v-model="ticket.date_end">
              </div>
            </div>
            <div class="col-md-4">
              <label for="submit" class="form-label text-white">Buscar</label>
              <button type="submit" id="submit" class="btn btn-primary text-white d-block">Buscar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <div>Busqueda</div>
          <div>
            <button class="btn btn-success text-white" type="button" @click.prevent="exportarExcel">Exportar</button>
          </div>
        </div>

        <div class="card-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Fecha</th>
              <th scope="col">Menu</th>
              <th scope="col">Documento</th>
              <th scope="col">Cliente</th>
              <th scope="col">Cantidad</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in list" :key="`ticket-${index}`">
              <th scope="row">{{ item.id }}</th>
              <td>{{ item.menu.reservation_date }}</td>
              <td>{{ item.menu.type_menu.name }}</td>
              <td>{{ item.professor ? (item.professor.person.document):(item.student.code) }}</td>
              <td>{{
                  item.professor ? ( item.professor.person.name + " " + item.professor.person.last_name) : (item.student.person.name + " " + item.student.person.last_name)
                }}
              </td>
              <td>{{ item.quantity }}</td>
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
        date_start: '2022-06-13',
        date_end: '2022-06-17',
      },
      list: []
    }
  },
  methods: {
    exportarExcel() {
      if(this.ticket.date_start>this.ticket.date_end){
        alert("La fecha fin debe ser mayor o igual que la fecha inicial");
        return;
      }
      window.open(`/export-excel/${this.ticket.date_start}/${this.ticket.date_end}`)
      alert("Se exportó correctamente");
    },
    async searchTicket() {
      const result = await axios.post('/search-ticket2', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.list = data.data;
        } else {
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