// Приложение (получение информации с сервера пр загрузке: axios)
const myAppII = {
	data: () => ({
		title: 'Получаем данные с сервера через AXIOS',
		resp: [],
		name: '',
		err: false,
		button1Title: 'Заказы',
		button2Title: 'Контакты',
		dataTable: false,
		contactTable: false
	}),
	mounted() {
			axios({
				method: 'get',
				url: '/0/i.php',
				params: {
					name: 'Data',
					pwd: "PWD"
				}
			})
				.then(response => { 
					this.resp = response.data;
					this.dataTable = true;
				})
			.catch(error => { 
				console.log(error)
				this.err = true
			})		
	},
	methods: {
		dataLoad(name) {
			this.name = name,
			axios({
				method: 'get',
				url: '/0/i.php',
				params: {
					name: name,
					pwd: "PWD"
				}
			})
				// .then(response => this.resp = response.data)
			.then(response => {
				this.resp = response.data;
				if (this.name === 'Data') {
					this.dataTable = true;
					this.contactTable = false;
				} else if (this.name === 'Contacts') {
					this.dataTable = false;
					this.contactTable = true;
				} else {
					this.dataTable = false;
					this.contactTable = false
				}
			})
			.catch(error => { 
				console.log(error)
				this.err = true
			})
		}
	}	
}
Vue.createApp(myAppII).mount('#app')

const myApp = {
	data: () => ({
		dateInput: '',
		formattedDate: ''
	}),
	methods: {
		getCorrectDate(e) { 
      let inputDate = this.dateInput.replace(/\D/g, '');
      if (inputDate.length > 0) {
        // inputDate = inputDate.match(/.{1,2}/g).join("-");
		  inputDate = inputDate.match(/.{1,4}/g).join("-");
		  this.dateInput = inputDate;
        // this.formattedDate = inputDate;
      } else {
        this.formattedDate = '';
      }
    }
		
	}
}

Vue.createApp(myApp).mount('#appIII')
