<template>
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            Ticket
          </div>
        </div>
        <div class="card-body">
          <form class="row" @submit.prevent="preForm">
            <div class="col-md-12 mb-4">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="date_start">Fecha de compra</label>
                    <div class="input-group mb-3">
                      <input type="date" class="form-control" id="date_start" v-model="ticket.reservation_date">
                      <button class="btn btn-outline-secondary" type="button"
                              @click.prevent="searchMenu" id="button-addon3">Buscar
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th scope="col">Tipo menu</th>
                      <th scope="col">Stock disponible</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in listTickets" :key="`person-${index}`">
                      <th scope="row">{{ item.type_menu.name }}</th>
                      <td>{{ item.tickets_stock }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="student" value="student" v-model="ticket.type_person">
                <label class="form-check-label" for="student">Estudiante</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="professor" value="professor" v-model="ticket.type_person">
                <label class="form-check-label" for="professor">Personal Administrativo</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="document" v-if="ticket.type_person==='professor'">Documento</label>
                <label for="document" v-else>Carnet universitario</label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" id="document" v-model="ticket.document">
                  <button class="btn btn-outline-secondary" type="button"
                          @click.prevent="searchPerson" id="button-addon2">Buscar
                  </button>
                </div>
                <div class="h6">{{ ticket_person.name }}</div>
              </div>
            </div>
            <div class="col-md-6" v-if="ticket.type_person==='professor'">
              <div class="form-group">
                <label for="name">Cantidad</label>
                <input type="number" min="1" max="2" class="form-control" id="name" v-model="ticket.quantity">
              </div>
            </div>
            <div class="col-md-12 mb-4">
              <div class="form-group">
                <label for="date_start">Tipo</label>
                <select id="cycle" class="form-select" v-model="ticket.type_menu_id">
                  <option :value="1" selected>Desayuno</option>
                  <option :value="2" selected>Almuerzo</option>
                  <option :value="3" selected>Cena</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-success text-white">{{ update ? "Actualizar" : "Guardar" }}</button>
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
      ticket: {
        type_person: 'student',
        document: '',
        student_id: '',
        professor_id: '',
        type_menu_id: 2,
        quantity: '',
        reservation_date: '2022-06-15',
        status: 1,
      },
      ticket_person: {
        name: ''
      },
      listTickets:[],
      update: false,
    }
  },
  methods: {
    async searchPerson(){
      if(this.ticket.type_person === 'student'){
        await this.searchStudent();
      }else{
        await this.searchProfessor();
      }
    },
    async searchProfessor() {
      const result = await axios.post('/search-professor2', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket.professor_id = data.data[0].id;
          this.ticket.student_id = '';
          this.ticket_person.name = data.data[0].person.name+" "+data.data[0].person.last_name;
        }else{
          this.ticket_person.name=''
          alert(data.message);
        }
      }).catch(error => {
        console.log(error);
      });
    },
    async searchStudent() {
      const result = await axios.post('/search-student2', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket.student_id = data.data[0].id;
          this.ticket.professor_id = '';
          this.ticket_person.name = data.data[0].person.name+" "+data.data[0].person.last_name;
        }else{
          this.ticket_person.name=''
          alert(data.message);
        }
      }).catch(error => {
        console.log(error);
      });
    },
    setItem(item) {
      this.ticket = {
        id: item.id,
        type_person: item.student?"student":"professor",
        document:  item.student?item.student.code:item.professor.person.document,
        student_id: item.student?item.student.id:'',
        professor_id: item.professor?item.professor.id:'',
        type_menu_id: item.menu.type_menu_id,
        quantity: item.quantity,
        reservation_date: item.menu.reservation_date,
        status: 1,
      };
      const person = item.student?item.student.person:item.professor.person;
      this.ticket_person.name = person.name+" "+person.last_name;
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
      const result = await axios.post('/ticket', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket = {
            type_person: 'student',
            document: '',
            student_id: '',
            professor_id: '',
            type_menu_id: '',
            quantity: '',
            reservation_date: '2022-06-15',
            status: 1,
          }
        }
        this.searchMenu()
        alert(data.message);
      })
    },
    async updateItem() {
      const result = await axios.put(`/ticket/${this.ticket.id}`, {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket = {
            type_person: 'student',
            document: '',
            student_id: '',
            professor_id: '',
            type_menu_id: '',
            quantity: '',
            reservation_date: '2022-06-15',
            status: 1,
          }
        }
        this.$emit('updated');
        alert(data.message);
      })
    },
    async searchMenu() {
      const body = {
        date_start: this.ticket.reservation_date,
        date_end: this.ticket.reservation_date,
      }
      const result = await axios.post('/search-menu', {...body}).then(response => {
        const data = response.data;
        if (data.value) {
          let dataList = data.data;

          dataList.sort(function (x, y){return x.type_menu_id - y.type_menu_id;})

          this.listTickets = dataList;
        }else{
          alert(data.message);
        }
      }).catch(error => {
        console.log(error);
      });
    },
  },
  mounted(){
    this.searchMenu()
  },
}
</script>