<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="linkToSocialNetworks"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    width="50">
            </el-table-column>
            <el-table-column
                    fixed
                    label="Изображение"
                    width="150">
                <template slot-scope="props" v-if="props.row.image_preview !== null">
                    <img width="70px" height="auto" :src="'/app/public/images/social_network/'+ props.row.image_preview">
                </template>
            </el-table-column>
            <el-table-column
                    label="Ссылка"
                    width="300">
                <template slot-scope="props">
                    <a target="_blank" :href="props.row.url">{{props.row.url}}</a>
                </template>
            </el-table-column>
            <el-table-column
                    prop="sorting_order"
                    label="Порядок сортировки"
                    min-width="50">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="Управление">
                <template slot-scope="props">
                    <el-button-group>
                        <el-button
                                @click.native.prevent="goToUpdate(props.row.id)"
                                size="mini">
                            <i class="el-icon-edit"></i>
                        </el-button>
                        <el-button
                                size="mini"
                                type="danger"
                                @click.native.prevent="btnDeleteLink(props.$index, linkToSocialNetworks)">
                            <i class="el-icon-delete"></i>
                        </el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

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
                    <el-button type="primary" @click="deleteLink">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as helperRouter from '../../../app/helpers/router';
    import * as ApiLinkToSocialNetworks from '../../../app/admin/api/LinkToSocialNetworks';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'link-to-social-networks-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'link-to-social-networks-list'}).href, title: this.$router.currentRoute.meta.title},
            ];
            if (typeof this.linkToSocialNetworksStore !== 'undefined'
                && this.linkToSocialNetworksStore.length) {
                this.linkToSocialNetworks = this.linkToSocialNetworksStore;
                this.loading = false;
                return true;
            }

            this.getLinkToSocialNetworks();
        },
        data() {
            return {
                linkToSocialNetworks: [],
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnLinkToSocialNetwork: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
            }
        },
        computed: {
            linkToSocialNetworksStore: function () {
                return this.$store.getters.linkToSocialNetworks;
            }
        },
        methods: {
            deleteLink: function () {
                if (this.operationsOnLinkToSocialNetwork) {
                    ApiLinkToSocialNetworks.destroy(this.operationsOnLinkToSocialNetwork.id).then((response) => {
                        let linkToSocialNetworks = this.linkToSocialNetworksStore;
                        let index = linkToSocialNetworks.findIndex((item) => item.id === this.operationsOnLinkToSocialNetwork.id);
                        linkToSocialNetworks.splice(index, 1);
                        this.$store.commit('updateLinkToSocialNetworks', linkToSocialNetworks);
                        this.dialogVisible = false;
                        this.operationsOnLinkToSocialNetwork = null;
                        this.$notify.success({
                            offset: 50,
                            title: 'Удаление ссылки',
                            message: response.data.message
                        });
                    }).catch((error) => {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'при удалении ссылки'
                        });
                        this.typeAlert = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnLinkToSocialNetwork = null;
                    });
                }
            },
            btnDeleteLink: function (index, links) {
                this.operationsOnLinkToSocialNetwork = links[index];
                this.titleDialog = 'Удаление ссылки';
                this.titleAlert = `Вы дейстительно хотите удалить ссылку: ${this.operationsOnLinkToSocialNetwork.url} (ID: ${this.operationsOnLinkToSocialNetwork.id})?`;
                this.dialogVisible = true;
            },
            getLinkToSocialNetworks: function () {
                this.loading = true;
                return ApiLinkToSocialNetworks.get().then((response) => {
                    this.linkToSocialNetworks = response.data.link_to_social_networks;
                    this.$store.commit('updateLinkToSocialNetworks', this.linkToSocialNetworks);
                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'link-to-social-networks-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
    }
</script>