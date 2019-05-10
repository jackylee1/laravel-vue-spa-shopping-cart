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
                <el-form-item label="Наименование" prop="name">
                    <el-input type="text" v-model="form.name" placeholder="Введите Имя"></el-input>
                </el-form-item>

                <el-form-item label="Процент скидки на группу" prop="discount">
                    <el-input type="text" v-model="form.discount" placeholder="Введите процент скидки"></el-input>
                </el-form-item>

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button type="info"
                               v-if="currentRoute.name === 'user-groups-update'"
                               @click="dialogTableVisible = true">Добавить пользователя в группу</el-button>
                    <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                </el-form-item>
            </el-form>
        </div>

        <h3 class="text-center" v-if="showTableUsers">Список пользователей в группе</h3>
        <div class="ds-block" v-if="showTableUsers">
            <el-table
                    v-loading="loading"
                    :data="usersInGroup"
                    style="width: 100%">
                <el-table-column
                        fixed
                        prop="user.id"
                        label="ID"
                        min-width="50">
                </el-table-column>
                <el-table-column
                        prop="user.name"
                        label="Имя"
                        min-width="150">
                </el-table-column>
                <el-table-column
                        prop="user.email"
                        label="E-mail"
                        min-width="150">
                </el-table-column>
                <el-table-column
                        fixed="right"
                        min-width="150">
                    <template slot-scope="props">
                        <el-button
                                type="danger"
                                icon="el-icon-close" circle
                                @click="deleteModel(props.row.user)">
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <PageElementsPagination :total="total"
                                    :currentPage="currentPage"
                                    :pageSize="pageSize"
                                    v-on:change="handleCurrentPageChange"/>
        </div>

        <el-dialog width="80%" title="Управление пользователями" :visible.sync="dialogTableVisible">
            <UsersTableSelect :relationKey="relationKey"
                              :relationForAction="relationForAction"
                              v-on:selectUser="userActionHandler"/>
        </el-dialog>

        <el-dialog
                :title="titleDialog"
                :visible.sync="dialogVisible"
                width="30%">
            <el-alert
                    :title="titleAlert"
                    :type="typeAlert"
                    :closable="false">
            </el-alert>
            <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="dialogVisible = false">Отмена</el-button>
                    <el-button type="primary" @click="deleteUser">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiUserGroups from '../../../app/admin/api/UserGroups';
    import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { UsersTableSelect } from '../Users';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';
    import * as helperRouter from '../../../app/helpers/router';

    export default {
        name: 'types-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'user-groups-update') {
                if (this.userGroups !== undefined && this.userGroups.length) {
                    this.form = this.userGroups.find((user_group) => user_group.id === this.$route.params.id);
                    this.oldForm = _.cloneDeep(this.form);

                    this.getUsersInGroup(1, false);
                }
                else {
                    this.getUsersInGroup(1, true);
                }

                this.showTableUsers = true;
                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'user-groups-update').meta.title;
            }
            else {
                this.loading = false;
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'user-groups-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                showTableUsers: false,
                relationForAction: 'group',
                relationKey: 'user_group_id',
                usersInGroup: [],
                currentPage: 0,
                total: 0,
                pageSize: 0,
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                dialogTableVisible: false,
                dialogVisible: false,
                titleDialog: '',
                operationsOnUserGroups: null,
                typeAlert: 'warning',
                loading: true,
                titleAlert: '',
                deleteUserObject: null,
                rules: {
                    name: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    discount: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^(?:100|[1-9]?[0-9])$/, message: 'Значение в этом поле не должно быть от 0 до 100', trigger: ['blur', 'change']}
                    ]
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success'
            }
        },
        computed: {
            userGroups() {
                return this.$store.getters.userGroups;
            },
            users() {
                return this.$store.getters.users;
            }
        },
        methods: {
            deleteModel: function(user) {
                this.deleteUserObject = user;
                this.titleDialog = 'Удаление пользователя с группы';
                this.titleAlert = `Вы дейстительно хотите удалить пользователей ${this.deleteUserObject.name} с текущей группы?`;
                this.dialogVisible = true;
            },
            deleteUser: function() {
                this.userActionHandler(this.deleteUserObject, 'inUserGroupPage');
            },
            handleCurrentPageChange: function (page) {
                this.getUsersInGroup(page);
            },
            getUsersInGroup: function (page = 1, insertInForm = false) {
                this.loading = true;
                ApiUserGroups.show(this.$route.params.id, page).then((response) => {
                    this.usersInGroup = response.data.users_in_group.data;
                    this.total = response.data.users_in_group.total;
                    this.currentPage = response.data.users_in_group.current_page;
                    this.pageSize = response.data.users_in_group.per_page;
                    if (insertInForm) {
                        this.form = response.data.user_group;
                        this.oldForm = _.cloneDeep(this.form)
                    }
                    this.loading = false;
                });
            },
            userActionHandler: function (user, option = null) {
                ApiUserGroups.actionHandler({
                    'user_id': user.id,
                    'user_group_id': this.form.id
                }).then((response) => {
                    if (option !== null) {
                        switch (option) {
                            case 'inUserGroupPage':
                                let index = this.usersInGroup.findIndex((item) => item.user.id === user.id);
                                this.usersInGroup.splice(index, 1);
                                this.dialogVisible = false;
                                break;
                        }
                    }

                    if (this.users.data !== undefined) {
                        let index = this.users.data.findIndex((item) => item.id === user.id);

                        this.users.data.splice(index, 1, response.data.user);

                        this.$store.commit('updateUsers', this.users);
                    }

                    if (response.data.operation === 'added') {
                        let user = {
                            user: {
                                id: response.data.user.id,
                                name: response.data.user.name,
                                email: response.data.user.email,
                            }
                        };
                        this.usersInGroup.unshift(user);

                        this.$notify.success({
                            offset: 50,
                            title: 'Операции над пользователями',
                            message: response.data.message
                        });
                    }
                    else {
                        if (option === null) {
                            let index = this.usersInGroup.findIndex((item) => item.user.id === user.id);
                            this.usersInGroup.splice(index, 1);
                        }
                        this.$notify.error({
                            offset: 50,
                            title: 'Операции над пользователями',
                            message: response.data.message
                        });
                    }
                }).catch((error) => {
                    this.$notify.error({
                        offset: 50,
                        title: 'Операции над пользователями',
                        message: 'Ошибка при выполнении запроса'
                    });
                });
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let userGroups = this.userGroups;
                if (userGroups) {
                    let index = userGroups.findIndex((user_group) => user_group.id === this.currentRoute.params.id);
                    userGroups.splice(index, 1, currentData);
                    this.$store.commit('updateUserGroups', userGroups);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    name: '',
                    discount: 0,
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'user-groups-list'}).href, title: helperRouter.getRouteByName(this.$router, 'user-groups-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'user-groups-update') {
                            let self = this;
                            ApiUserGroups.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                self.oldForm = response.data.user_group;
                                self.setDataToStore(response.data.user_group);
                            }).catch((error) => {
                                self.alerts = error.response.data.errors;
                                self.typeAlerts = 'error';
                            });
                        }
                        else {
                            let self = this;
                            ApiUserGroups.create(self.form).then((response) => {
                                let userGroups = self.userGroups;
                                if (userGroups.length) {
                                    userGroups.unshift(response.data.user_group);
                                    self.$store.commit('updateUserGroups', userGroups);
                                }
                                self.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                self.$router.push({name: 'user-groups-list'});
                            }).catch((error) => {
                                self.alerts = error.response.data.errors;
                                self.typeAlerts = 'error';
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
            UsersTableSelect,
            PageElementsPagination
        },
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'user-groups-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'user-groups-update' && this.userGroups.length) {
                this.setDataToStore();
            }
        }
    }
</script>