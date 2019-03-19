<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <div class="ds-block" v-on:click="alerts = []">
            <el-form label-position="top" class="ds-source"
                     ref="formWorkWithModel"
                     :rules="rules"
                     @keydown.enter="onSubmit"
                     :model="form"
                     label-width="120px">
                <el-form-item label="Фамилия" prop="user_surname">
                    <el-input type="text" v-model="form.user_surname" placeholder="Введите Фамилию"></el-input>
                </el-form-item>

                <el-form-item label="Имя" prop="user_name">
                    <el-input type="text" v-model="form.user_name" placeholder="Введите Имя"></el-input>
                </el-form-item>

                <el-form-item label="Отчество" prop="user_patronymic">
                    <el-input type="text" v-model="form.user_patronymic" placeholder="Введите Отчество"></el-input>
                </el-form-item>

                <el-form-item label="E-mail" prop="email">
                    <el-input type="email" v-model="form.email" placeholder="Введите E-mail"></el-input>
                </el-form-item>

                <el-form-item label="Телефон" prop="phone">
                    <el-input type="text" v-model="form.phone" placeholder="Введите Телефон"></el-input>
                </el-form-item>

                <el-form-item label="Пароль" prop="password">
                    <el-input type="password" autocomplete="off" v-model="form.password" placeholder="Введите Пароль"></el-input>
                </el-form-item>

                <el-form-item label="Повторите Пароль" prop="password_confirmation">
                    <el-input type="password" v-model="form.password_confirmation" placeholder="Повторите Пароль"></el-input>
                </el-form-item>

                <el-alert style="padding-bottom: 20px;"
                          :closable="false"
                          v-if="currentRoute.name === 'users-update'"
                          title="Изменение пароля"
                          description="Если вы хотите изменить пароль введите данные в соответствующие поля иначе пароль останется прежним"
                          type="warning">
                </el-alert>

                <el-form-item label="Статус пользователя" prop="status">
                    <el-select v-model="form.status" placeholder="Выберите статус пользователя">
                        <el-option
                                v-for="item in this.selectListStatuses"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Примеание к пользователю" prop="description">
                    <el-input type="textarea" v-model="form.description" placeholder="Введите примечание"></el-input>
                </el-form-item>

                <el-form-item label="Статус надежности" prop="reliability">
                    <el-select v-model="form.reliability" placeholder="Выберите статус надежноти">
                        <el-option
                                v-for="item in this.selectListReliability"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Группа пользователей" prop="groups">
                    <el-select v-model="form.group_id" placeholder="Выберите группу пользователей">
                        <el-option
                                v-for="item in userGroups"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Персональный процент скидки" prop="discount">
                    <el-input type="text" v-model="form.discount" placeholder="Персональный процент скидки"></el-input>
                </el-form-item>

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button-group>
                        <el-button type="info"
                                   v-if="currentRoute.name === 'users-update'"
                                   @click="dialogTableVisible = true">Управление промокод</el-button>
                        <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                    </el-button-group>
                </el-form-item>
            </el-form>
        </div>

        <el-dialog width="80%" title="Управление промокодами" :visible.sync="dialogTableVisible">
            <PromotionalCodesTableSelect :currentModel="currentModel"
                                         :model="form"
                                         v-on:changeOldForm="changeOldForm"
                                         v-on:selectPromotionalCode="promotionalCodeActionHandler"/>
        </el-dialog>

    </div>
</template>

<script>
    import * as ApiUsers from '../../../app/admin/api/Users';
    import * as helperRouter from '../../../app/helpers/router';
    import * as ApiUserGroups from '../../../app/admin/api/UserGroups';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';
    import { PromotionalCodesTableSelect } from '../PromotionalCodes';

    export default {
        name: 'users-work-with-model',
        created() {
            this.rules.password = [
                {type: 'string', message: generatingValidationMessage('string'), trigger: ['blur', 'change']},
                {min: 6, max: 32, message: generatingValidationMessage('length', [32, 6]), trigger: ['blur', 'change']},
                {pattern: /^.*(?=.{3,})(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/, message: 'Пароль должен иметь латинские символы, цифры от 0 до 9. В пароле должно быть минимум три символа латинского алфивита и минимум три цифры.', trigger: ['blur', 'change']}
            ];
            this.rules.password_confirmation = this.rules.password;

            this.currentRoute = this.$router.currentRoute;

            this.userGroupsStore();

            if (this.currentRoute.name === 'users-update') {
                this.showPromotionalCodeTableSelect = true;
                if (this.users.data !== undefined && this.users.data.length) {
                    let user = this.users.data.find((user) => user.id === this.$route.params.id);
                    this.setDataWhenCreating(user);
                }
                else {
                    ApiUsers.show(this.$route.params.id).then((response) => {
                        let user = response.data.user;
                        this.setDataWhenCreating(user);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'users-update').meta.title;
            }
            else {
                this.setRequiredPassword();
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'users-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    user_name: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    user_surname: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    user_patronymic: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    phone: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    email: [
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {type: 'email', message: generatingValidationMessage('email'), trigger: ['blur', 'change']}
                    ],
                    status: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {
                            type: 'enum',
                            enum: ['user', 'administration'],
                            message: generatingValidationMessage('enum', ['user', 'administration'])
                        }
                    ],
                    description: [
                        {max: 2500, message: generatingValidationMessage('length', 2500), trigger: ['blur', 'change']}
                    ],
                    reliability: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {type: 'enum', enum: [1, 0], message: generatingValidationMessage('enum', [1, 0])}
                    ],
                    group_id: [
                        {type: 'integer', message: generatingValidationMessage('integer')}
                    ],
                    discount: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^(?:100|[1-9]?[0-9])$/, message: 'Значение в этом поле должно быть от 0 до 100', trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
                showPromotionalCodeTableSelect: false,
                dialogTableVisible: false,
                currentModel: 'users',
                userGroups: []
            }
        },
        computed: {
            users: function() {
                return this.$store.getters.users;
            },
            searchPromotionalCodes: function () {
                return this.$store.getters.searchPromotionalCodes;
            },
            selectListStatuses: function () {
                return this.$store.getters.selectDataListStatuses;
            },
            selectListReliability: function () {
                return this.$store.getters.selectDataListReliability;
            }
        },
        methods: {
            changeOldForm: function (data) {
                this.oldForm = data;
            },
            userGroupsStore: function() {
                if (this.$store.getters.userGroups.length === 0) {
                    return ApiUserGroups.get().then((response) => {
                        this.userGroups = response.data.user_groups;
                        this.$store.commit('updateUserGroups', this.userGroups);
                    });
                }
                else {
                    this.userGroups = this.$store.getters.userGroups;
                }
            },
            getPromotionalCodes: function (page = 1) {
                this.loading = true;
                return axios.get('/api/admin/promotional_codes?page=' + page, {
                    params: this.searchPromotionalCodes
                }).then((response) => {
                    this.$store.commit('updatePromotionalCodes', response.data.promotional_codes);
                });
            },
            promotionalCodeActionHandler: function(promotionalCode) {
                ApiUsers.handlePromotionalCode({
                    'user_id': this.form.id,
                    'promotional_code_id': promotionalCode.id
                }).then((response) => {
                    response = response.data;
                    if (response.status === 'success') {
                        if (response.operation === 'add') {
                            this.$notify.success({
                                offset: 50,
                                title: 'Запрос успешно выполнен',
                                message: response.message
                            });
                            if (this.users.data.length) {
                                let userStore = this.users;
                                let users = userStore.data.map(user => {
                                    user.promotional_codes = user.promotional_codes.filter(promotional_code => promotional_code.promotional_code_id !== promotionalCode.id);

                                    return user;
                                });
                                userStore.data = users;

                                this.$store.commit('updateUsers', userStore);
                            }

                            this.form.promotional_codes.unshift(response.create_model);
                        }
                        else {
                            let index = this.form.promotional_codes.findIndex((item) => item.promotional_code_id === promotionalCode.id);
                            this.form.promotional_codes.splice(index, 1);
                            this.$notify.error({
                                offset: 50,
                                title: 'Ошибка при выполнении запроса',
                                message: response.message
                            });
                        }
                        this.changeOldForm(this.form);
                    }

                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.typeAlerts = 'error';
                });
            },
            setDataWhenCreating: function(user) {
                if (typeof user.password_confirmation === 'undefined') {
                    user.password_confirmation = '';
                }
                if (typeof user.password === 'undefined') {
                    user.password = '';
                }
                this.form = user;
                this.form.group_id = (user.group !== null) ? user.group.user_group_id : null;
                this.oldForm = _.cloneDeep(this.form);
            },
            setRequiredPassword: function() {
                this.rules.password.push({required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']});
                this.rules.password_confirmation.push({required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']});
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;

                let users = this.users;
                if (users.data) {
                    let index = users.data.findIndex((user) => user.id === this.currentRoute.params.id);
                    users.data[index] = currentData;
                    this.$store.commit('updateUsers', users);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    user_name: '',
                    user_surname: '',
                    user_patronymic: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    status: 'user',
                    description: '',
                    reliability: 1,
                    discount: 0,
                    group_id: null
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'users-list'}).href, title: helperRouter.getRouteByName(this.$router, 'users-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.form.password_confirmation.length || this.form.password.length) {
                            if (this.form.password !== this.form.password_confirmation) {
                                this.$notify.error({
                                    offset: 50,
                                    title: 'Пароли не совпадают',
                                    message: 'Проверте правильность ввода и повторите попытку'
                                });
                                return false;
                            }
                        }
                        if (this.currentRoute.name === 'users-update') {
                            ApiUsers.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.oldForm = response.data.user;
                                this.setDataToStore(response.data.user);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiUsers.create(this.form).then((response) => {
                                let users = this.$store.getters.users;
                                if (users.data !== undefined && users.data.length) {
                                    users.data.unshift(response.data.user);
                                    this.$store.commit('updateUsers', users);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'users-list'});
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
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts,
            PromotionalCodesTableSelect
        },
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'users-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
                this.setRequiredPassword();
            },
        },
        beforeDestroy() {
            let searchPromotionCodes = this.searchPromotionalCodes;
            if (searchPromotionCodes.user_id !== null) {
                searchPromotionCodes.user_id = null;
                this.$store.commit('updateSearchPromotionalCodes', searchPromotionCodes);
                this.getPromotionalCodes();
            }
            if (this.currentRoute.name === 'users-update') {
                this.setDataToStore();
            }
        }
    }
</script>