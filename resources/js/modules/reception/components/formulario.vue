<template>
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            Despacho de menu
          </div>
        </div>
        <div class="card-body">
          <form class="row" @submit.prevent="preForm">
            <div class="col-md-12">
              <div class="form-group">
                <form @submit.prevent="searchPerson">
                  <label for="document">Documento</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" id="document" v-model="ticket.document">
                    <button class="btn btn-outline-secondary" type="submit"
                             id="button-addon2">Buscar
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-md-12 mb-4" v-if="ticket_person">
              <div class="form-group">
                <label for="type_menu">Tipo</label>
                <select id="type_menu" class="form-select" v-model="ticket_person_selected">
                  <option v-for="(item,index) in listMenu" :value="item.type_menu_id" selected>{{ item.type_menu }}</option>
                </select>
              </div>
            </div>
            <div class="col-md-12 d-flex justify-content-center" v-if="ticket_person">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="card-header">
                  <h5 class="card-title">{{ ticket_person.name }}</h5>
                </div>
                <div class="row g-0">
                  <div class="col-md-4 d-flex align-items-center">
                    <img src="https://img.freepik.com/foto-gratis/retrato-joven-sonriente-gafas_171337-4842.jpg?w=2000" class="img-fluid rounded-start" alt="client_img">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <p class="card-text">
                        <span class="badge bg-secondary d-block p-2" v-if="ticket_person.status === 1">Pendiente</span>
                        <span class="badge bg-success d-block" v-if="ticket_person.status === 2">Entregado</span>
                        <span class="badge bg-danger d-block"  v-if="ticket_person.status === 0">Sin Ticket</span>
                      </p>
                      <p class="card-text text-center">
                        <button class="btn btn-success text-white" type="submit">Entregar</button>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
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
      ticket_person: null,
      ticket_person_selected: null,
      listTickets: [],
      listMenu: [],
      update: false,
    }
  },
  methods: {
    async searchPerson() {
      if (this.ticket.document.length === 10) {
        await this.searchStudent();
      } else if (this.ticket.document.length === 8) {
        await this.searchProfessor();
      } else {
        alert("Solo se aceptan 8 y 10 dígitos");
      }
    },
    async searchProfessor() {
      const result = await axios.post('/search-professor-reception', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket_person = data.data;
          this.ticket_person_selected = data.data.tickets[0].type_menu_id
          this.listMenu = data.data.tickets
        } else {
          this.ticket_person = null
          this.ticket_person_selected = null
          this.listMenu = []
          alert(data.message);
        }
      }).catch(error => {
        console.log(error);
      });
    },
    async searchStudent() {
      const result = await axios.post('/search-student-reception', {...this.ticket}).then(response => {
        const data = response.data;
        if (data.value) {
          this.ticket_person = data.data;
          this.ticket_person_selected = data.data.tickets[0].type_menu_id
          this.listMenu = data.data.tickets
        } else {
          this.ticket_person = null
          this.ticket_person_selected = null
          this.listMenu = []
          alert(data.message);
        }
      }).catch(error => {
        console.log(error);
      });
    },
    setItem(item) {
      this.ticket = {
        id: item.id,
        type_person: item.student ? "student" : "professor",
        document: item.student ? item.student.code : item.professor.person.document,
        student_id: item.student ? item.student.id : '',
        professor_id: item.professor ? item.professor.id : '',
        type_menu_id: item.menu.type_menu_id,
        quantity: item.quantity,
        reservation_date: item.menu.reservation_date,
        status: 1,
      };
      const person = item.student ? item.student.person : item.professor.person;
      this.ticket_person.name = person.name + " " + person.last_name;
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
      let ticket_id = 0;
      for (const key in this.listMenu) {
        if(this.ticket_person_selected === this.listMenu[key].type_menu_id){
          ticket_id = this.listMenu[key].ticket_id
        }
      }
      const body = {
        ticket_id: ticket_id
      }
      const result = await axios.post('/reception', {...body}).then(response => {
        const data = response.data;
        if (data.value) {
          if (this.ticket.document.length === 10) {
            this.searchStudent();
          } else if (this.ticket.document.length === 8) {
            this.searchProfessor();
          }
        }
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
  },
  mounted() {

  },
  watch:{
    'ticket_person_selected': function (ticket_person_selected) {
      let ticket = null;
      for (const key in this.listMenu) {
        if(this.ticket_person_selected === this.listMenu[key].type_menu_id){
          ticket = {...this.listMenu[key]}
        }
      }
      console.log(ticket)
      if(ticket){
        this.ticket_person.quantity = ticket.quantity
        this.ticket_person.status = ticket.status
      }
    }
  }
}
</script>