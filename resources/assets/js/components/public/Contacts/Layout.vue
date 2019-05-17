<template>
  <div>
    <Breadcrumbs :items="breadcrumbs"/>

    <section class="wrapper">
      <div class="container">
        <div class="row titles_top">
          <div class="col-12 my-auto">
            <h1 class="category_title">
              Контакты
            </h1>
          </div>
        </div>
        <div class="row our_contacts">
          <div class="col-md-6 col-12 our_contacts_data">
            <h2 v-if="phone1 !== null || phone2 !== null">Звоните нам:</h2>
            <ul v-if="phone1 !== null || phone2 !== null">
              <li v-if="phone1 !== null">
                <i class="fas fa-phone-square"></i> {{phone1}}
              </li>
              <li v-if="phone2 !== null">
                <i class="fas fa-phone-square"></i> {{phone2}}
              </li>
            </ul>
            <h2 v-if="email !== null">E-Mail для связи:</h2>
            <ul v-if="email !== null">
              <li>
                <i class="fas fa-envelope-square"></i> {{email}}
              </li>
            </ul>
            <h2 v-if="address !== null">Наш адрес:</h2>
            <ul v-if="address !== null">
              <li>
                <i class="fas fa-globe"></i> {{address}}
              </li>
            </ul>
          </div>
          <div class="col-md-6 col-12 our_contacts_form">
            <h2>Обратный звонок:</h2>
            <p class="ps">Заполните пожалуйста форму и наши менеджеры свяжутся с Вами в ближайшее время</p>
            <form data-vv-scope="form-contacts"  action="javascript:void(0)">
              <div class="form-row">
                <div class="col-md-6 col-12 form-group">
                  <label for="name">Имя *</label>
                  <input type="text"
                         id="name"
                         name="name"
                         v-model="form.name"
                         data-vv-as="Имя"
                         v-validate="'required|max:191'"
                         class="form-control"
                         placeholder="Имя">
                  <small v-show="errors.has('form-contacts.name')" class="text-danger">
                    {{ errors.first('form-contacts.name') }}
                  </small>
                </div>
                <div class="col-md-6 col-12 form-group">
                  <label for="phone">Телефон</label>
                  <input type="phone"
                         id="phone"
                         name="phone"
                         v-model="form.phone"
                         data-vv-as="Телефон"
                         v-validate="'max:191'"
                         class="form-control"
                         placeholder="Телефон">
                  <small v-show="errors.has('form-contacts.phone')" class="text-danger">
                    {{ errors.first('form-contacts.phone') }}
                  </small>
                </div>
                <div class="col-12 form-group">
                  <label for="email">E-Mail *</label>
                  <input type="email"
                         id="email"
                         name="email"
                         v-model="form.email"
                         data-vv-as="E-Mail"
                         v-validate="'required|email|max:191'"
                         class="form-control"
                         placeholder="E-Mail">
                  <small v-show="errors.has('form-contacts.email')" class="text-danger">
                    {{ errors.first('form-contacts.email') }}
                  </small>
                </div>
                <div class="col-12 form-group">
                  <label for="message">Сообщение *</label>
                  <textarea type="text"
                            id="message"
                            name="message"
                            v-model="form.message"
                            data-vv-as="Сообщение"
                            v-validate="'required|max:50000'"
                            class="form-control"
                            placeholder="Введите Ваше сообщение"
                            rows="4"></textarea>
                  <small v-show="errors.has('form-contacts.message')" class="text-danger">
                    {{ errors.first('form-contacts.message') }}
                  </small>
                </div>
              </div>

              <Errors :type="typeAlerts"
                      v-on:clearAlerts="clearAlerts"
                      :alerts="alerts"/>

              <div class="submit_btn">
                <button @click="onSubmit"
                        style="min-height: 45px"
                        class="btn btn-primary">Отправить запрос</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import * as ApiFeedback from '../../../app/public/api/Feedback';
  import Breadcrumbs from "../Breadcrumbs";
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import Errors from "../Errors";

  export default {
    name: 'TextPageLayout',
    mixins: [mixinAlerts],
    mounted() {
      this.breadcrumbs = [
        {'title': 'Контакты'}
      ];
      this.settings = this.settingsStore;
      if (this.settings.length) {
        this.setData();
      }
    },
    computed: {
      settingsStore: function () {
        return this.$store.getters.settings;
      }
    },
    data() {
      return {
        breadcrumbs: [],
        settings: [],
        phone1: null,
        phone2: null,
        email: null,
        address: null,
        form: {
          name: '',
          email: '',
          phone: '',
          message: ''
        }
      }
    },
    components: {
      Errors,
      Breadcrumbs
    },
    methods: {
      setData: function () {
        this.phone1 = this.settings.find((item) => item.slug === 'phone1').value;
        this.phone2 = this.settings.find((item) => item.slug === 'phone2').value;
        this.email = this.settings.find((item) => item.slug === 'email').value;
        this.address = this.settings.find((item) => item.slug === 'address').value;
      },
      onSubmit: function () {
        this.$validator.validateAll('form-contacts').then((result) => {
          if (result) {
            ApiFeedback.send(this.form).then((res) => {
              if (res.data.status === 'success') {
                this.form = {
                  name: '',
                  email: '',
                  phone: '',
                  message: ''
                };

                this.$notify({
                  type: 'success',
                  title: 'Данные',
                  text: res.data.message
                });
              }
            }).catch((error) => {
              this.alerts = error.response.data.errors;
              this.$notify({
                type: 'error',
                title: 'Ошибка',
                text: 'при обновлении данных'
              });
            });
          }
          else {
            this.$notify({
              type: 'error',
              title: 'Ошибка',
              text: 'При валидации формы. Проверте правильность ввода.'
            });
          }
        });
      }
    },
    watch: {
      'settingsStore': function (settings) {
        this.settings = settings;
        this.setData();
      }
    }
  }
</script>

<style scoped>
  /* Contacts */
  .our_contacts {
    padding-bottom: 25px;
  }
  .our_contacts h2 {
    font-size: 18px;
  }
  .our_contacts .our_contacts_data ul li {
    font-size: 16px;
    list-style-type: none;
  }
  .our_contacts .our_contacts_data ul li .fas {
    color: #6c6c6c;
  }
  .our_contacts .our_contacts_form .ps {
    font-size: 12px;
    color: #6c6c6c;
  }
  .our_contacts .our_contacts_form input {
    border-radius: 10px;
    margin-top: 10px;
    height: 47px;
  }
  .our_contacts .our_contacts_form textarea {
    border-radius: 10px;
    margin-top: 10px;
  }
  .our_contacts .our_contacts_form .submit_btn {
    margin-top: 15px;
    text-align: right;
  }
  .our_contacts .our_contacts_form .submit_btn .btn {
    border-radius: 10px;
  }
</style>