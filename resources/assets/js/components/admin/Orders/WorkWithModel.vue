<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <div class="ds-block" v-on:click="alerts = []">
      <el-form label-position="right" class="ds-source"
               ref="formWorkWithModel"
               :rules="rules"
               @keydown.enter="onSubmit"
               :model="form"
               label-width="200px">

        <input type="hidden" :value="form.user_id">
        <el-form-item label="Пользователь" v-if="user !== null">
          <router-link :to="{ name: 'users-update', params: { id: user.id }}">
            {{user.user_surname}} {{user.user_name}} (ID {{user.id}})
          </router-link>
          <el-button type="text"
                     icon="el-icon-delete"
                     @click="deleteUser"></el-button>
        </el-form-item>

        <template v-if="user !== null && user.discount > 0">
          <el-form-item>
            <el-alert :closable="false"
                      title="Прикрепленный пользователь имеет персональную скилку. Она в приоритете выше скидки на группу."
                      type="info">
            </el-alert>
          </el-form-item>
        </template>

        <template v-if="user !== null && user.group !== undefined && user.group !== null">
          <el-form-item>
            <el-alert :closable="false"
                      title="Прикреплённый пользователь состоит в Группе пользователей. Её скидка может повлиять на общую сумму заказа"
                      type="info">
            </el-alert>
          </el-form-item>
        </template>

        <template v-if="user !== null && user.promotional_codes.length">
          <el-form-item label="Промо-коды пользователя" prop="promotional_code_id">
            <el-select v-model="form.promotional_code_id" placeholder="Промо-коды пользователя">
              <el-option
                  v-for="item in this.selectUserPromotionalCodes()"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-alert :closable="false"
                      title="Чтобы открепить промо-код от заказа выберите 'Не выбран'"
                      type="info">
            </el-alert>
          </el-form-item>
        </template>
        <template v-else>
          <el-form-item>
            <el-alert :closable="false"
                      title="Чтобы открепить промо-код от заказа удалите данные в поле Промо-код"
                      type="info">
            </el-alert>
          </el-form-item>
        </template>

        <el-form-item v-if="form.promotional_code != null"
                      label="Промо-код который прикреплен к заказу">
          {{this.form.promotional_code.code}}
          <template v-if="this.form.promotional_code.type === 0">
            ({{this.form.promotional_code.discount}} %)
          </template>
          <template v-else>
            ({{this.form.promotional_code.cash_value}} грн)
          </template>
        </el-form-item>

        <el-form-item label="Промо-код" prop="promotionalCode">
          <el-input type="text" v-model="promotionalCode" placeholder="Введите Промо-код"></el-input>
        </el-form-item>

        <el-form-item label="Имя" prop="user_name">
          <el-input type="text" v-model="form.user_name" placeholder="Введите Имя"></el-input>
        </el-form-item>

        <el-form-item label="Фамилия" prop="user_surname">
          <el-input type="text" v-model="form.user_surname" placeholder="Введите Фамилию"></el-input>
        </el-form-item>

        <el-form-item label="E-Mail" prop="email">
          <el-input type="text" v-model="form.email" placeholder="Введите E-Mail"></el-input>
        </el-form-item>

        <el-form-item label="Телефон" prop="phone">
          <el-input type="text" v-model="form.phone" placeholder="Введите Телефон"></el-input>
        </el-form-item>

        <el-form-item label="Комментарий" prop="note">
          <el-input type="textarea" v-model="form.note" placeholder="Введите комментарий"></el-input>
        </el-form-item>

        <el-form-item label="Общая сумма заказа" prop="total_price">
          <el-input type="text"
                    v-model="form.total_price"
                    :disabled="true"
                    placeholder="Общая сумма заказа"></el-input>
        </el-form-item>

        <el-form-item label="Общая сумма заказа (с учетом всех скидок)" prop="total_discount_price">
          <el-input type="text"
                    v-model="form.total_discount_price"
                    :disabled="true"
                    placeholder="Общая сумма заказа (с учетом всех скидок)"></el-input>
        </el-form-item>

        <el-form-item label="Статус заказа" prop="order_status_id">
          <el-select v-model="form.order_status_id" placeholder="">
            <el-option
                v-for="item in this.orderStatuses"
                :key="item.id"
                :label="item.name"
                :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Метод оплаты" prop="order_payment_method_id">
          <el-select v-model="form.order_payment_method_id" placeholder="">
            <el-option
                v-for="item in this.orderPaymentMethods"
                :key="item.id"
                :label="item.name"
                :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Метод доставки"
                      prop="delivery_method">
          <el-select v-model="form.delivery_method"
                     filterable
                     @change="changeDeliveryMethodSelect"
                     placeholder="Выберите метод доставки">
            <el-option
                v-for="item in deliveryMethods"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="areas !== null"
                      label="Выберите область"
                      prop="area_id">
          <el-select v-model="form.area_id"
                     style="min-width: 400px;"
                     filterable
                     @change="changeAreaSelect"
                     placeholder="Выберите область">
            <el-option
                v-for="item in areas"
                :key="item.ref"
                :label="item.description"
                :value="item.ref">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="cities !== null"
                      label="Выберите населенный пункт"
                      prop="city_id">
          <el-select v-model="form.city_id"
                     style="min-width: 400px;"
                     filterable
                     @change="changeCitySelect"
                     placeholder="Выберите населенный пункт">
            <el-option
                v-for="item in cities"
                :key="item.ref"
                :label="item.description"
                :value="item.ref">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="warehouses !== null"
                      label="Выберите отделение"
                      prop="warehouse_id">
          <el-select v-model="form.warehouse_id"
                     style="min-width: 400px;"
                     filterable
                     placeholder="Выберите отделение">
            <el-option
                v-for="item in warehouses"
                :key="item.ref"
                :label="item.description"
                :value="item.ref">
            </el-option>
          </el-select>
        </el-form-item>

        <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

        <el-form-item>
          <el-button-group>
            <el-button type="default"
                       v-if="this.currentRoute.name === 'orders-update'"
                       @click="modalSelectProducts">
              Добавить продукцию
            </el-button>
            <el-button type="default"
                       v-if="this.currentRoute.name === 'orders-update'"
                       @click="dialogUserTableVisible = true">
              Выбрать пользователя
            </el-button>
            <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
          </el-button-group>
        </el-form-item>
      </el-form>
    </div>

    <div class="ds-block" v-if="this.form.products !== undefined && this.form.products.length">
      <h4 class="text-center">Список продукции</h4>
      <el-table
          :data="form.products"
          style="width: 100%">
        <el-table-column
            fixed
            prop="id"
            label="ID"
            min-width="30">
        </el-table-column>
        <el-table-column
            fixed
            label="Изображение"
            width="120">
          <template slot-scope="props" v-if="props.row.product.main_image !== null">
            <img width="70px" height="auto" :src="'/app/public/images/products/'+ props.row.product.main_image.preview">
          </template>
        </el-table-column>
        <el-table-column
            prop="product.article"
            label="Артикул"
            min-width="50">
        </el-table-column>
        <el-table-column
            label="Наименование"
            min-width="100">
          <template slot-scope="props">
            <template v-if="props.row.discount_price !== null && props.row.discount_price > 0">
              {{props.row.product.name}}
              <el-tooltip class="item" effect="dark" content="Акционный товар" placement="top-start">
                <v-icon name="tag"></v-icon>
              </el-tooltip>
            </template>
            <template v-else>
              {{props.row.product.name}}
            </template>
          </template>
        </el-table-column>
        <el-table-column
            label="Опции"
            min-width="150">
          <template slot-scope="props">
            <template v-for="(filter, index) in props.row.available.filters">
              {{ getFilter(filter.filter_id) }}
              <template v-if="index !== props.row.available.filters.length - 1">
                <v-icon name="arrow-right"></v-icon>
              </template>
            </template>
          </template>
        </el-table-column>
        <el-table-column
            label="Кол."
            prop="quantity"
            min-width="45">
        </el-table-column>
        <el-table-column
            label="Цена"
            min-width="55">
          <template slot-scope="props">
            <template v-if="props.row.product_discount_price !== null && props.row.product_discount_price > 0">
              <p>{{props.row.product_discount_price}} <strike style="color: red">{{props.row.product_price}}</strike></p>
            </template>
            <template v-else>
              <p>{{props.row.product_price}}</p>
            </template>
          </template>
        </el-table-column>
        <el-table-column
            label="Сумма"
            min-width="55">
          <template slot-scope="props">
            <template v-if="props.row.discount_price !== null && props.row.discount_price > 0">
              <p>{{props.row.discount_price}} <strike style="color: red">{{props.row.price}}</strike></p>
            </template>
            <template v-else>
              <p>{{props.row.price}}</p>
            </template>
          </template>
        </el-table-column>
        <el-table-column
            fixed="right"
            min-width="70">
          <template slot-scope="props">
            <el-button-group>
              <el-button
                  @click.native.prevent="openProduct(props.row)"
                  size="mini">
                <i class="el-icon-view"></i>
              </el-button>
              <el-button
                  @click.native.prevent="deleteProductOrder(props.row.id)"
                  type="danger"
                  size="mini">
                <i class="el-icon-delete"></i>
              </el-button>
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="ds-block" v-if="this.form.history_statuses !== undefined && this.form.history_statuses.length">
      <h4 class="text-center">История изменения статуса заказа</h4>
      <el-table style="width: 100%"
                :data="this.form.history_statuses">
        <el-table-column
            fixed
            prop="id"
            label="ID"
            width="70">
        </el-table-column>

        <el-table-column width="250"
                         label="Статус">
          <template slot-scope="props">
                        <span :style="'color: '+getOrderStatus(props.row.order_status_id).color">
                            {{getOrderStatus(props.row.order_status_id).name}}
                        </span>
          </template>
        </el-table-column>

        <el-table-column
            prop="created_at"
            label="Добавлен"
            width="200">
        </el-table-column>

        <el-table-column
            fixed="right"
            min-width="70">
          <template slot-scope="props">
            <el-button-group>
              <el-button
                  v-if="props.row.send_status == 0"
                  size="mini"
                  @click.native.prevent="sendStatus(props.row.id)">
                <i class="el-icon-message"></i>
              </el-button>
              <el-button
                  size="mini"
                  type="danger"
                  @click.native.prevent="deleteStatus(props.row.id)">
                <i class="el-icon-delete"></i>
              </el-button>
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <el-dialog width="95%" title="Управление продукцией" :visible.sync="dialogTableVisible">
      <ProductsTableSelect :model="form"
                           v-on:selectProduct="selectProduct"/>
    </el-dialog>

    <el-dialog width="80%" title="Управление пользователями" :visible.sync="dialogUserTableVisible">
      <UsersTableSelect :relationKey="null"
                        :relationForAction="null"
                        v-on:selectUser="selectUser"/>
    </el-dialog>

    <el-dialog width="45%" v-if="operationsOnProduct !== null"
               :title="operationsOnProduct.name + ' | Выберите опции и введите количество'"
               :visible.sync="dialogSelectAvailableVisible">
      <el-form label-position="right" label-width="200px">
        <el-form-item label="Опция">
          <el-select v-model="selectedAvailableId"
                     placeholder="Выберите опцию">
            <el-option
                v-for="item in generationSelectAvailableProduct(this.operationsOnProduct.available)"
                :key="item.id"
                :label="item.label"
                :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <template v-if="selectedAvailableId !== null">
            <p>В наличии: {{this.getQuantityAvailable().quantity}}</p>
          </template>
        </el-form-item>
        <el-form-item label="Количество">
          <el-input v-model="inputQuantity"
                    placeholder="Введите количество">
          </el-input>
        </el-form-item>
      </el-form>

      <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

      <span slot="footer" class="dialog-footer">
                <el-button type="primary"
                           v-if="showBtnAddProductToOrder"
                           @click="addProductToOrder">
                    Добавить к заказу
                </el-button>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as ApiUsers from '../../../app/admin/api/Users';
  import * as ApiOrders from '../../../app/admin/api/Orders';
  import * as ApiNovaPoshta from '../../../app/public/api/NovaPoshta';
  import * as ApiFilters from '../../../app/admin/api/Filters';
  import * as ApiOrderStatuses from '../../../app/admin/api/OrderStatuses';
  import * as ApiOrderPaymentMethods from '../../../app/admin/api/OrderPaymentMethods';
  import * as helperRouter from '../../../app/helpers/router';
  import * as helperArray from '../../../app/admin/helpers/Array';

  import {PageElementsAlerts, PageElementsBreadcrumb} from '../page/elements';
  import {ProductsTableSelect} from '../Products';
  import {UsersTableSelect} from '../Users';
  import {generatingValidationMessage} from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'orders-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;
      if (this.currentRoute.name === 'orders-update') {
        if (this.ordersInStore.data !== undefined && this.ordersInStore.data.length) {
          let order = this.ordersInStore.data.find((item) => item.id === this.$route.params.id);
          this.setDataWhenCreating(order);
        }
        else {
          ApiOrders.show(this.$route.params.id).then((response) => {
            this.setDataWhenCreating(response.data.order);
          });
        }
        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'orders-update').meta.title;
      }
      else {
        ApiOrders.create({}).then((response) => {
          if (response.data.status === 'success') {
            this.form = response.data.order;

            let orders = this.ordersInStore;
            if (orders.data !== undefined && orders.data.length) {
              orders.data.unshift(response.data.order);

              this.$store.commit('updateOrders', orders);
            }
            this.$notify.success({
              offset: 50,
              title: 'Запрос успешно выполнен',
              message: response.data.message
            });

            this.$router.push({name: 'orders-list', query: {update_model: this.form.id}});
          }
        }).catch((error) => {});
      }

      this.setBreadcrumbElements();
    },
    computed: {
      ordersInStore() {
        return this.$store.getters.orders;
      },
      orderStatuses() {
        if (this.$store.getters.orderStatuses.length === 0) {
          ApiOrderStatuses.get().then((response) => {
            this.$store.commit('updateOrderStatuses', helperArray.sort(response.data.order_statuses));
            this.$store.commit('updateNewOrders', this.$store.getters.newOrders - 1);
          });
        }

        return this.$store.getters.orderStatuses;
      },
      orderPaymentMethods() {
        if (this.$store.getters.orderPaymentMethods.length === 0) {
          ApiOrderPaymentMethods.get().then((response) => {
            this.$store.commit('updateOrderPaymentMethods', helperArray.sort(response.data.order_payment_methods));
          });
        }

        return this.$store.getters.orderPaymentMethods;
      },
      productsStore: function () {
        return this.$store.getters.products;
      },
      filtersInStore: function () {
        if (this.$store.getters.filters.length === 0) {
          ApiFilters.get().then((response) => {
            this.$store.commit('updateFilters', helperArray.sort(response.data.filters));
          });
        }

        return this.$store.getters.filters;
      }
    },
    data() {
      return {
        pageTitle: '',
        form: this.defaultFormData(),
        oldForm: null,
        rules: {
          user_name: [
            {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
          ],
          user_surname: [
            {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
          ],
          email: [
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
            {type: 'email', message: generatingValidationMessage('email'), trigger: ['blur', 'change']}
          ],
          phone: [
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
          ],
          note: [
            {max: 50000, message: generatingValidationMessage('length', 2500), trigger: ['blur', 'change']}
          ],
        },
        currentRoute: null,
        breadcrumbElements: [],
        submitName: null,
        alerts: [],
        typeAlerts: 'success',
        dialogTableVisible: false,
        operationsOnProduct: null,
        selectedAvailableId: null,
        dialogSelectAvailableVisible: false,
        inputQuantity: 0,
        showBtnAddProductToOrder: false,
        dialogUserTableVisible: false,
        user: null,
        promotionalCode: null,
        deliveryMethods: [
          {value: null, label: 'Не выбран'},
          {value: 1, label: 'Новая почта'}
        ],
        areas: null,
        cities: null,
        warehouses: null
      }
    },
    methods: {
      updateReadStatus: function (data) {
        if (!data.read_status) {
          ApiOrders.updateReadStatus({
            id: data.id
          }).then((response) => {
            if (response.data.status === 'success') {
              this.form = response.data.order;
              this.oldForm = this.form;
            }
          });
        }
      },
      selectUserPromotionalCodes: function () {
        if (this.user !== null) {
          let items = this.user.promotional_codes.map(item => {
            if (item.promotional_code.status === 1
              || item.promotional_code.id === this.form.promotional_code_id) {
              return {
                id: item.promotional_code_id,
                label: `${item.promotional_code.code} (${item.promotional_code.discount}%)`
              }
            }
          }).filter(item => item);

          if (items.findIndex(item => item.id === null) === -1) {
            items.unshift({
              id: null,
              label: 'Не выбран'
            })
          }

          return items;
        }
      },
      sendStatus: function (id) {
        ApiOrders.sendStatus({
          order_id: this.form.id,
          status_id: id
        }).then((response) => {
          if (response.data.status === 'success') {
            let orderStatus = response.data.order_status;
            let indexStatus = this.form.history_statuses.findIndex(item => item.id === id);
            this.form.history_statuses[indexStatus] = orderStatus;
            this.setDataToStore();

            this.$notify.success({
              offset: 50,
              title: 'Запрос успешно выполнен',
              message: response.data.message
            });
          }
        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      deleteUser: function () {
        this.user = null;
        this.form.user_id = null;
        this.onSubmit();
      },
      setUser: function (id) {
        ApiUsers.show(id).then((response) => {
          this.user = response.data.user;
        })
      },
      selectUser: function (user) {
        this.onSubmit();
        this.user = user;

        if (this.form.user_name === null) {
          this.form.user_name = user.user_name;
        }

        if (this.form.user_surname === null) {
          this.form.user_surname = user.user_surname;
        }

        if (this.form.email === null) {
          this.form.email = user.email;
        }

        if (this.form.phone === null) {
          this.form.phone = user.phone;
        }

        this.form.user_id = user.id;
        this.dialogUserTableVisible = false;
      },
      openProduct: function (product) {
        window.open(`${window.location.origin}/product/${product.product.slug}`, '_blank');
      },
      getQuantityAvailable: function () {
        if (this.operationsOnProduct !== null) {
          return this.operationsOnProduct.available.find(item => item.id === this.selectedAvailableId);
        } else {
          return {
            quantity: 0
          }
        }
      },
      getOrderStatus: function (id){
        return this.orderStatuses.find(item => item.id === id) || {
          name: '',
          color: '#000'
        }
      },
      getFilter: function (id) {
        let filter = this.filtersInStore.find(item => item.id === id);
        if (filter !== undefined) {
          let parent = this.filtersInStore.find(item => item.id === filter.parent_id);
          return `${parent.name}: ${filter.name}`;
        }
      },
      generationSelectAvailableProduct: function (available) {
        available = available.filter(item => item.filters.length).map(availableItem => {
          let name = '';
          availableItem.filters.forEach((filter, index) => {
            name += this.getFilter(filter.filter_id);

            if (index !== availableItem.filters.length - 1) {
              name += ' -> ';
            }
          });

          return {
            id: availableItem.id,
            label: name,
          }
        });

        return available;
      },
      modalSelectProducts: function () {
        this.dialogTableVisible = true;
      },
      selectProduct: function (product) {
        this.operationsOnProduct = product;
        this.selectedAvailableId = null;
        this.inputQuantity = 0;
        this.showBtnAddProductToOrder = false;
        this.dialogSelectAvailableVisible = true;
      },
      deleteStatus: function (id) {
        ApiOrders.deleteStatus({
          order_id: this.form.id,
          order_status_id: id
        }).then((response) => {
          if (response.data.status === 'success') {
            this.form = response.data.order;
            this.setDataToStore(response.data.order);

            this.$notify.success({
              offset: 50,
              title: 'Запрос успешно выполнен',
              message: response.data.message
            });
          }
        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      deleteProductOrder: function (id) {
        ApiOrders.deleteProduct({
          order_id: this.form.id,
          order_product_id: id
        }).then((response) => {
          if (response.data.status === 'success') {
            this.form = response.data.order;
            this.oldForm = response.data.order;
            this.setDataToStore(response.data.order);

            let products = this.productsStore;
            if (products.data !== undefined && products.data.length) {
              let orderProduct = response.data.order_product;
              let indexProduct = products.data.findIndex(item => item.id === orderProduct.product_id);
              if (indexProduct !== -1) {
                let product = products.data[indexProduct];
                let indexAvailable = product.available.findIndex(item => item.id === orderProduct.product_available_id);
                let available = product.available[indexAvailable];
                available.quantity = available.quantity + orderProduct.quantity;

                product.available[indexAvailable] = available;

                products.data[indexProduct] = product;

                this.$store.commit('updateProducts', products);
              }
            }

            this.setPromotionalCodeToStore();

            this.$notify.success({
              offset: 50,
              title: 'Запрос успешно выполнен',
              message: response.data.message
            });
          }
        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      addProductToOrder: function () {
        ApiOrders.addProduct({
          order_id: this.form.id,
          product_id: this.operationsOnProduct.id,
          product_available_id: this.selectedAvailableId,
          quantity: this.inputQuantity
        }).then((response) => {
          if (response.data.status === 'success') {
            this.form = response.data.order;
            this.oldForm = response.data.order;
            this.setDataToStore(response.data.order);

            let products = this.productsStore;
            if (products.data !== undefined && products.data.length) {
              let indexAvailable = this.operationsOnProduct.available.findIndex(item => item.id === this.selectedAvailableId);
              let available = this.operationsOnProduct.available[indexAvailable];
              available.quantity = available.quantity - this.inputQuantity;
              this.operationsOnProduct.available[indexAvailable] = available;

              let indexProduct = products.data.findIndex(item => item.id === this.operationsOnProduct.id);
              products.data[indexProduct] = this.operationsOnProduct;

              this.$store.commit('updateProducts', products);
            }

            this.setPromotionalCodeToStore();

            this.$notify.success({
              offset: 50,
              title: 'Запрос успешно выполнен',
              message: response.data.message
            });
          }
        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      setDataWhenCreating: function(data) {
        if (data.user_id !== null && this.user === null) {
          this.setUser(data.user_id);
        }
        this.form = data;
        this.updateReadStatus(data);
        this.oldForm = _.cloneDeep(this.form);
      },
      setDataToStore: function (data = null) {
        let currentData = (data === null) ? this.oldForm : data;
        let orders = this.ordersInStore;
        if (orders.data) {
          let index = orders.data.findIndex((item) => item.id === this.currentRoute.params.id);
          orders.data[index] = currentData;

          if (currentData.promotional_code_id !== null) {
            let promotionalCodes = this.$store.getters.promotionalCodes;
            if (promotionalCodes.data !== undefined && promotionalCodes.data.length) {
              let index = promotionalCodes.data.findIndex(item => item.id === currentData.promotional_code_id);
              promotionalCodes.data[index].status = 0;
              this.$store.commit('updatePromotionalCodes', promotionalCodes);
            }
          }

          this.$store.commit('updateOrders', orders);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          user_name: '',
          user_surname: '',
          phone: '',
          email: '',
          note: '',
          total_price: 0,
          total_discount_price: 0,
          delivery_method: null,
          area_id: null,
          city_id: null,
          warehouse_id: null
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'orders-list'}).href, title: helperRouter.getRouteByName(this.$router, 'orders-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      setPromotionalCodeToStore: function() {
        if (this.form.promotional_code !== undefined && this.form.promotional_code !== null) {
          let promotionalCodes = this.$store.getters.promotionalCodes;
          if (promotionalCodes.data !== undefined) {
            let index = promotionalCodes.data.findIndex((item) => item.id === this.form.promotional_code.id);
            if (index !== -1) {
              promotionalCodes.data[index] = this.form.promotional_code;

              this.$store.commit('updatePromotionalCodes', promotionalCodes);
            }
          }
        }
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'orders-update') {
              if (this.promotionalCode !== null) {
                this.form.input_promotional_code = this.promotionalCode;
              }
              ApiOrders.update(this.$route.params.id, this.form).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });

                this.form = response.data.order;
                this.oldForm = response.data.order;
                this.setDataToStore(response.data.order);

                this.setPromotionalCodeToStore();
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.typeAlerts = 'error';
              });
            }
          } else {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'При проверке данных произошла ошибка. Проверте форму ввода данных.'
            });
            return false;
          }
        });
      },
      checkQuantity: function () {
        if (this.inputQuantity > 0 && this.selectedAvailableId !== null && this.operationsOnProduct.available.length) {
          let index = this.operationsOnProduct.available.findIndex(item => item.id === this.selectedAvailableId);
          if (this.operationsOnProduct.available[index].quantity >= this.inputQuantity) {
            this.showBtnAddProductToOrder = true;
          }
          else {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'По этому параметру нет достаточного количества товара в наличии'
            });
            this.showBtnAddProductToOrder = false;
          }
        }
        else {
          this.showBtnAddProductToOrder = false;
        }
      },
      changeDeliveryMethodSelect: function () {
        this.form.area_id = null;
        this.form.city_id = null;
        this.form.warehouse_id = null;
      },
      changeAreaSelect: function () {
        this.form.city_id = null;
        this.form.warehouse_id = null;
      },
      changeCitySelect: function () {
        this.form.warehouse_id = null;
      }
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts,
      ProductsTableSelect,
      UsersTableSelect
    },
    watch: {
      '$route' (to, from) {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'orders-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
      'inputQuantity': function () {
        this.checkQuantity();
      },
      'selectedAvailableId': function () {
        this.checkQuantity();
      },
      'user': function () {
        this.selectUserPromotionalCodes();
      },
      'form': function () {
        if (this.form.promotional_code !== null) {
          this.promotionalCode = this.form.promotional_code.code;
        }
      },
      'promotionalCode': function (value) {
        if (value === null) {
          this.form.promotional_code_id = null;
        }
        else if (value.toString().length === 0) {
          this.form.promotional_code_id = null;
        }
      },
      'form.promotional_code_id': function (promotionalCodeId) {
        if (promotionalCodeId === null) {
          this.promotionalCode = null;
        }
      },
      'form.delivery_method': function (value) {
        this.areas = null;
        if (value !== null) {
          ApiNovaPoshta.areas().then((res) => {
            if (res.data.status === 'success') {
              this.areas = res.data.areas;
            }
          });
        }
      },
      'form.area_id': function (value) {
        this.cities = null;
        if (value !== null) {
          ApiNovaPoshta.cities({
            area_ref: value
          }).then((res) => {
            if (res.data.status === 'success') {
              this.cities = res.data.cities;
            }
          });
        }
      },
      'form.city_id': function (value) {
        this.warehouses = null;
        if (value !== null) {
          ApiNovaPoshta.warehouses({
            city_ref: value
          }).then((res) => {
            if (res.data.status === 'success') {
              this.warehouses = res.data.warehouses;
            }
          });
        }
      }
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'orders-update') {
        this.setDataToStore();
      }
    }
  }
</script>